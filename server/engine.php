<?php
session_start();
require_once('require.php');
if (isset($_POST['csrf'])) {
    if ($_POST['csrf'] != $_SESSION['csrf']) {
        echo json_encode(array("status" => "invalid_token"));
    } else {
        //User Login
        if (isset($_POST['login'])) {
            $username = x($_POST['username']);
            $password = x($_POST['password']);
            $status = false;

            $check = mysqli_query($conn, "SELECT * FROM staff WHERE username = '$username'");
            if (mysqli_num_rows($check) > 0) {
                $fetch = mysqli_fetch_assoc($check);
                if (password_verify($password, $fetch['password'])) {
                    $_SESSION['user'] = $fetch['staff_id'];
                    $_SESSION["page"] = "home";
                    $status = true;
                }
            }
            echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
        }

        // ################ User Features ##################
        if (isset($_SESSION['user'])) {

            //LOGOUT 
            if (isset($_POST['do_logout'])) {
                echo "logout";
                session_destroy();
            }

            // Add sales
            if (isset($_POST['add_sales'])) {
                $product_id = x($_POST['product_id']);
                $total_sales = x($_POST['total_sales']);
                $status = false;

                if ($product_id != '' && $total_sales != '' && is_numeric($product_id) && is_numeric($total_sales) && $total_sales > 0) {
                    try {
                        $query = mysqli_query($conn, "INSERT INTO sales (product_id, sale_total, sale_date, staff_id) VALUES ('$product_id', '$total_sales', '$current_date', '$_SESSION[user]')");
                        if ($query) {
                            $status = true;
                        }
                    } catch (mysqli_sql_exception $e) {
                        // Error Handling
                    }
                }
                echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
            }

            //Change password
            if (isset($_POST['change_pass'])) {
                $oldpass = x($_POST['oldpass']);
                $newpass = x($_POST['newpass']);
                $newpass2 = x($_POST['newpass2']);

                if ($newpass != $newpass2) {
                    echo "no_match";
                } else if ($newpass == '' && $newpass2 == '') {
                    echo "empty";
                } else {
                    $checkUser = mysqli_query($conn, "SELECT * FROM staff WHERE staff_id = '$_SESSION[user]'");
                    if (mysqli_num_rows($checkUser) > 0) {
                        $checkUser = mysqli_fetch_assoc($checkUser);
                        $passVer = password_verify($oldpass, $checkUser['password']);
                        if ($passVer == TRUE) {
                            $newpass_hash = password_hash($newpass, PASSWORD_DEFAULT);
                            $udpate_pass = mysqli_query($conn, "UPDATE staff SET password = '$newpass_hash' WHERE staff_id = '$_SESSION[user]'");
                            if ($udpate_pass) {
                                echo "true";
                            } else {
                                echo "error";
                            }
                        } else {
                            echo "wrong_pass";
                        }
                    }
                }
            }

            // ################# Approve or Reject Sale permission ########################

            if (userDetails($_SESSION['user'], 'admin') == 1 || rankPermission('sale_approval') == 1) {
                if (isset($_POST['checked_sales'])) {
                    $sales_id = x($_POST['sales_id']);
                    $checked = x($_POST['checked_sales']);
                    $remark = x($_POST['remarks']);
                    $status = false;
                    $split_date = explode('-', $current_date);
                    $month = $split_date[1];
                    $day = $split_date[0];
                    $year = $split_date[2];

                    if ($day > 25) {
                        if ($month >= 12) {
                            $year++;
                            $month = 1;
                        } else {
                            $month++;
                        }
                    }

                    $check_approval = mysqli_query($conn, "SELECT * FROM sales WHERE sale_id = '$sales_id'");
                    if (mysqli_num_rows($check_approval) > 0) {
                        if (($checked == '1' || $checked == '2') && $remark != '') {
                            try {
                                $query = mysqli_query($conn, "UPDATE sales SET approval = '$checked', checked_date = '$current_date', approved_by = '$_SESSION[user]', remark = '$remark', month = '$month', year = '$year' WHERE sale_id = '$sales_id'");
                                if ($query) {
                                    $status = true;
                                }
                            } catch (mysqli_sql_exception $e) {
                                //err handling
                            }
                        }
                    }
                    echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
                }
            }

            // ################ END Approve or Reject Sale permission ##########################

            // ################# ADMIN SIDE ######################
            if (userDetails($_SESSION['user'], 'admin') == 1) {

                //Add Product
                if (isset($_POST['add_product'])) {
                    $product_id = x($_POST['product_id']);
                    $product_name = x($_POST['product_name']);
                    $enable = x($_POST['enable_status']);
                    $status = false;

                    try {
                        if ($product_id != "" && $product_name != '' && is_numeric($product_id) && ($enable == '1' || $enable == '0')) {
                            $query = mysqli_query($conn, "INSERT INTO product (product_id, product_name, enable) VALUES ('$product_id', '$product_name', '$enable')");
                            if ($query) {
                                $status = true;
                            }
                        } else {
                        }
                    } catch (mysqli_sql_exception $e) {
                        // Error handle
                    }
                    echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
                }

                //Enable or Disable product
                if (isset($_POST['enable_product'])) {
                    $status = false;
                    $enable = x($_POST['enable_product']);
                    $product_id = x($_POST['id']);
                    if ($enable != '' && ($enable == '1' || $enable == '0')) {
                        try {
                            $query = mysqli_query($conn, "UPDATE product SET enable = '$enable' WHERE product_id = '$product_id'");
                            if ($query) {
                                $status = true;
                            }
                        } catch (mysqli_sql_exception $e) {
                            // Error handle
                        }
                    }
                    echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
                }

                //Add Staff
                if (isset($_POST['add_staff'])) {
                    $username = x($_POST['username']);
                    $email = x($_POST['email']);
                    $status = false;

                    $generate_pass = createRandomStr(8);
                    $pass_hash = password_hash($generate_pass, PASSWORD_DEFAULT);

                    $check_staff = mysqli_query($conn, "SELECT * FROM staff WHERE username = '$username' OR email = '$email'");
                    if (mysqli_num_rows($check_staff) == 0) {
                        $register_staff = mysqli_query($conn, "INSERT INTO staff (username, email, password) VALUES ('$username', '$email', '$pass_hash')");
                        if ($register_staff) {
                            $to = $email;
                            $subject = "Your account detail";
                            $txt = "Username: " . $username . "\nPassword: " . $generate_pass;
                            $headers = "From: Admin";
                            if (mail($to, $subject, $txt, $headers)) {
                                $status = true;
                            }
                        }
                    }

                    echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
                }


                //Click Edit User
                if (isset($_POST['click_editUser'])) {
                    $userid = x($_POST['user_id']);
                    $check_user = get_staff($userid);
                    $fetch_user = mysqli_fetch_assoc($check_user);
                    $user_rank = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rank INNER JOIN department ON rank.dpt_id = department.dpt_id WHERE rank.rank_id = '$fetch_user[rank_id]'"));
                    include("../pages/edit-staff.php");
                }

                //Search Rank
                if (isset($_POST['search_rank'])) {
                    $rankSearch = x($_POST['search_rank']);
                    if ($rankSearch == '') {
                        echo "";
                    } else {
                        $rank = mysqli_query($conn, "SELECT * FROM rank INNER JOIN department ON rank.dpt_id = department.dpt_id WHERE rank_name LIKE '%$rankSearch%' OR rank_id LIKE '$rankSearch%' OR dpt_name LIKE '%$rankSearch%'");
                        if (mysqli_num_rows($rank) > 0) {
                            while ($fetch_rank = mysqli_fetch_assoc($rank)) {
                                echo '<tr>
                        <th scope="row">' . $fetch_rank['rank_id'] . '</th>
                        <td>' . $fetch_rank['rank_name'] . '</td>
                        <td>' . $fetch_rank['dpt_name'] . '</td>
                        <td><button class="btn btn-success give-rank" data-rankid="' . $fetch_rank['rank_id'] . '">Give Rank</button></td>
                     </tr>';
                            }
                        } else {
                            echo "no_rank";
                        }
                    }
                }

                //Set user Rank
                if (isset($_POST["give_rank"])) {
                    $rankid = x($_POST['rankid']);
                    $userid = x($_POST['userid']);
                    $rank = mysqli_query($conn, "SELECT * FROM rank WHERE rank_id = '$rankid'");
                    if (mysqli_num_rows($rank) > 0) {
                        $giverank = mysqli_query($conn, "UPDATE staff set rank_id = '$rankid' WHERE staff_id = '$userid'");
                        if ($giverank) {
                            echo "OK";
                        } else {
                            echo "err";
                        }
                    } else {
                        echo "no_rankid";
                    }
                }

                //Give Admin
                if (isset($_POST['giveAdmin'])) {
                    $user_id = x($_POST['user_id']);
                    $check_user = get_staff($user_id);
                    if (mysqli_num_rows($check_user) > 0) {
                        $update_user = mysqli_query($conn, "UPDATE staff SET admin = '1' WHERE staff_id = '$user_id'");
                        if ($update_user) {
                            echo "OK";
                        }
                    } else {
                        echo "no_user";
                    }
                }

                //Remove Admin
                if (isset($_POST['removeAdmin'])) {
                    $user_id = x($_POST['user_id']);
                    $check_user = get_staff($user_id);
                    if (mysqli_num_rows($check_user) > 0) {
                        $update_user = mysqli_query($conn, "UPDATE staff SET admin = '0' WHERE staff_id = '$user_id'");
                        if ($update_user) {
                            echo "OK";
                        }
                    } else {
                        echo "no_user";
                    }
                }

                //Give Ban
                if (isset($_POST['giveBan'])) {
                    $user_id = x($_POST['user_id']);
                    $check_user = get_staff($user_id);
                    if (mysqli_num_rows($check_user) > 0) {
                        $update_user = mysqli_query($conn, "UPDATE staff SET ban_status = '1' WHERE staff_id = '$user_id'");
                        if ($update_user) {
                            echo "OK";
                        }
                    } else {
                        echo "no_user";
                    }
                }

                //Remove Ban
                if (isset($_POST['removeBan'])) {
                    $user_id = x($_POST['user_id']);
                    $check_user = get_staff($user_id);
                    if (mysqli_num_rows($check_user) > 0) {
                        $update_user = mysqli_query($conn, "UPDATE staff SET ban_status = '0' WHERE staff_id = '$user_id'");
                        if ($update_user) {
                            echo "OK";
                        }
                    } else {
                        echo "no_user";
                    }
                }

                //Reset user pass
                if (isset($_POST['resetpass'])) {
                    $user_id = x($_POST['user_id']);
                    $check_user = get_staff($user_id);
                    $password = $_POST['password'];
                    $hash_newpass = password_hash($password, PASSWORD_DEFAULT);
                    if (mysqli_num_rows($check_user) > 0) {
                        $resetpass = mysqli_query($conn, "UPDATE staff SET password = '$hash_newpass' WHERE staff_id = '$user_id'");
                        if ($resetpass) {
                            echo "OK";
                        }
                    } else {
                        echo "no_user";
                    }
                }

                //Add department
                if (isset($_POST['add_department'])) {
                    $dpt_id = x($_POST['dpt_id']);
                    $dpt_name = x($_POST['dpt_name']);
                    $status = false;

                    if ($dpt_id != '' && $dpt_name != '') {
                        if (is_numeric($dpt_id)) {
                            try {
                                $query = mysqli_query($conn, "INSERT INTO department (dpt_id, dpt_name) VALUES ('$dpt_id', '$dpt_name')");
                                if ($query) {
                                    $status = true;
                                }
                            } catch (mysqli_sql_exception $e) {
                                // Error handle
                            }
                        }
                    }
                    echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
                }

                //Delete department
                if (isset($_POST['delete_department'])) {
                    $dpt_id = x($_POST['dpt_id']);
                    $status = false;

                    if ($dpt_id != '') {
                        if (is_numeric($dpt_id)) {
                            $check_dpt = mysqli_query($conn, "SELECT * FROM department WHERE dpt_id = '$dpt_id'");
                            if (mysqli_num_rows($check_dpt) > 0) {
                                $delete = mysqli_query($conn, "DELETE FROM department WHERE dpt_id = '$dpt_id'");
                                if ($delete) {
                                    $status = true;
                                }
                            }
                        }
                    }
                    echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
                }

                //Edit department
                if (isset($_POST['edit_department'])) {
                    $dpt_id = x($_POST['dpt_id']);
                    $dpt_name = x($_POST['dpt_name']);
                    $status = false;

                    if ($dpt_id != '' && $dpt_name != '') {
                        if (is_numeric($dpt_id)) {
                            try {
                                $query = mysqli_query($conn, "UPDATE department SET dpt_id = $dpt_id, dpt_name = '$dpt_name'");
                                if ($query) {
                                    $status = true;
                                }
                            } catch (mysqli_sql_exception $e) {
                                // Error handle
                            }
                        }
                    }
                    echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
                }

                //Add Rank
                if (isset($_POST['add_rank'])) {
                    $dpt_id = x($_POST['dpt_id']);
                    $rank_id = x($_POST['rank_id']);
                    $rank_name = x($_POST['rank_name']);
                    $status = false;

                    if ($dpt_id != '' && $rank_name != '' && $rank_id != '') {
                        if (is_numeric($dpt_id) && is_numeric($rank_id)) {
                            try {
                                $check_dpt = mysqli_query($conn, "SELECT * FROM department WHERE dpt_id = '$dpt_id'");
                                if (mysqli_num_rows($check_dpt) > 0) {
                                    $query = mysqli_query($conn, "INSERT INTO rank (rank_id, rank_name, dpt_id) VALUES ('$rank_id', '$rank_name', '$dpt_id')");
                                    $check_permission = mysqli_query($conn, "SELECT * FROM rank_permission WHERE rank_id = '$rank_id'");
                                    if ($query) {
                                        if (mysqli_num_rows($check_permission) == 0) {
                                            $add_permission = mysqli_query($conn, "INSERT INTO rank_permission (rank_id) VALUES ('$rank_id')");
                                            if ($query && $add_permission) {
                                                $status = true;
                                            }
                                        }
                                    }
                                }
                            } catch (mysqli_sql_exception $e) {
                                // Error handle
                            }
                        }
                    }

                    echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
                }

                //Delete rank
                if (isset($_POST['delete_rank'])) {
                    $rank_id = x($_POST['rank_id']);
                    $status = false;

                    if ($rank_id != '') {
                        if (is_numeric($rank_id)) {
                            $check_rank = mysqli_query($conn, "SELECT * FROM rank WHERE rank_id = '$rank_id'");
                            if (mysqli_num_rows($check_rank) > 0) {
                                $delete = mysqli_query($conn, "DELETE FROM rank WHERE rank_id = '$rank_id'");
                                $delete_permission = mysqli_query($conn, "DELETE FROM rank_permission WHERE rank_id = '$rank_id'");
                                if ($delete) {
                                    $status = true;
                                }
                            }
                        }
                    }
                    echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
                }

                //Click Edit RANK
                if (isset($_POST['click_editRank'])) {
                    $rank_id = x($_POST['rank_id']);
                    include("../pages/edit-rank.php");
                }

                //Update Rank Permission
                if (isset($_POST['rank_permission'])) {
                    $permission = x($_POST['rank_permission']);
                    $rank_id = x($_POST['rank_id']);
                    $column = x($_POST['col']);
                    $status = false;

                    $query = mysqli_query($conn, "SELECT * FROM rank_permission WHERE rank_id = '$rank_id'");
                    if (mysqli_num_rows($query) > 0) {
                        $update = mysqli_query($conn, "UPDATE rank_permission SET $column = '$permission'");
                        if ($update) {
                            $status = true;
                        }
                    }
                    echo json_encode(array("status" => $status,), JSON_PRETTY_PRINT);
                }
            }

            // ##################### END ADMIN #################################

            //set session pages
            if (isset($_POST["page"])) {
                unset($_SESSION["page"]);
                $_SESSION["page"] = $_POST['page'];
                if (file_exists("../pages/" . $_POST['page'] . ".php")) {
                    include("../pages/" . $_POST['page'] . ".php");
                } else {
                    echo "page_error";
                }
            }

            //check pages session
            if (isset($_POST["checkPage"])) {
                if (isset($_SESSION["page"])) {
                    if (file_exists("../pages/" . $_SESSION["page"] . ".php")) {
                        include("../pages/" . $_SESSION["page"] . ".php");
                    } else {
                        echo "page_error";
                    }
                }
            }

            //included page
            if (isset($_POST["includePage"])) {
                if (file_exists("../pages/" . $_POST['includePage'] . ".php")) {
                    include("../pages/" . $_POST['includePage'] . ".php");
                } else {
                    echo "page_error";
                }
            }
        }
    }
    exit();
}
