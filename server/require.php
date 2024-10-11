<?php

date_default_timezone_set("Asia/Kuala_Lumpur");
$current_date = date("d-m-Y");
$current_time = date("H:i:s");

$months = array(
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December"
);

require_once('connect.php');

$path = "/rasao"; // Web directory .. if on main dir.. just make it empty
$engine = $path . "/server/engine.php"; // Backend url

function x($string)
{
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

function checkSession($session)
{
    global $path;
    if (!isset($_SESSION[$session])) {
        header("location:" . $path . "/index.php");
    }
}

function userDetails($id, $column)
{
    global $conn;
    $new_id = x($id);
    $query = mysqli_query($conn, "SELECT * FROM staff WHERE staff_id = '$new_id'");
    if (mysqli_num_rows($query) > 0) {
        $fetch = mysqli_fetch_assoc($query);
        return $fetch[$column];
    }
}

function rankPermission($permisson)
{
    global $conn;
    $new_perm = x($permisson);
    // $sql = "SELECT * FROM rank_permission INNER JOIN staff ON rank_permission.rank_id = staff.rank_id WHERE rank_permission." . $new_perm . " = '1' AND staff.staff_id = '$_SESSION[user]'";
    // echo $sql;
    $query = mysqli_query($conn, "SELECT * FROM rank_permission INNER JOIN staff ON rank_permission.rank_id = staff.rank_id WHERE rank_permission." . $new_perm . " = '1' AND staff.staff_id = '$_SESSION[user]'");
    if (mysqli_num_rows($query) > 0 || userDetails($_SESSION['user'], 'admin') > 0) {
        return 1;
    }
    return 0;
}

function getRank($id, $col){
    global $conn;
    $id = x($id);
    $fetch = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rank WHERE rank_id = '$id'"));
    return $fetch[$col];
}

function createToken($length)
{
    $token = createRandomStr($length);
    return trim(password_hash($token, PASSWORD_DEFAULT), "$2y$10$");
}

function createRandomStr($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function admin_access()
{
    if (userDetails($_SESSION['user'], 'admin') != '1') {
        session_destroy();
        header("location:index.php");
    }
}

function permission_access($perm){
    if (userDetails($_SESSION['user'], 'admin') != '1' || rankPermission($perm) != '1') {
        session_destroy();
        header("location:index.php");
    }
}

function get_all_staff()
{
    global $conn;
    return mysqli_query($conn, "SELECT * FROM staff");
}

//Get user by ID
function get_staff($user_id)
{
    global $conn;
    return mysqli_query($conn, "SELECT * FROM staff WHERE staff_id = '$user_id'");
}

// CSRF protection
function csrf($id)
{
    $_SESSION['csrf'] = password_hash($id, PASSWORD_DEFAULT);
}
