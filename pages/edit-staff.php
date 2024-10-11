<?php
admin_access();
?>
<button class="btn btn-info back">Back</button>
<br><br>
<div class="row">
    <div class="col-sm-4">
        <div class="shadow-sm p-3 mb-5 bg-white rounded">
            <div class="row">
                <div class="col-sm-2">

                </div>
                <div class="col">
                    <div>
                        <b>Username: <font color="#042b7a"><?php echo $fetch_user['username'] ?></font></b>
                    </div>
                    <div>
                        <b>Current Rank: <font color="#042b7a"><?php echo $user_rank['rank_name'] ?> [<?php echo $user_rank['rank_id'] ?>]</font></b>
                    </div>
                    <div>
                        <b>
                            Portal Admin:
                            <?php if ($fetch_user['admin'] == '1') { ?>
                                <font color="lime">True</font>
                            <?php } else { ?>
                                <font color="red">False</font>
                            <?php } ?>
                        </b>
                    </div>
                    <div>
                        <b>
                            Ban Status:
                            <?php if ($fetch_user['ban_status'] == '1') { ?>
                                <font color="lime">Banned</font>
                            <?php } else { ?>
                                <font color="red">False</font>
                            <?php } ?>
                        </b>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-5">
        <div class="shadow-sm p-3 mb-5 bg-white rounded manage_user">
            <div class="mb-3 row">
                <label for="adminbtn" class="col-sm-5 col-form-label">Admin</label>
                <div class="col-sm-5">
                    <?php if ($fetch_user['admin'] == '1') { ?>
                        <button class="btn btn-danger form-control-plaintext removeAdmin" id="adminbtn" data-userid="<?php echo $fetch_user['staff_id'] ?>">Remove Admin</button>
                    <?php } else { ?>
                        <button class="btn btn-success form-control-plaintext giveAdmin" id="adminbtn" data-userid="<?php echo $fetch_user['staff_id'] ?>">Give Admin</button>
                    <?php } ?>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="banbtn" class="col-sm-5 col-form-label">Ban</label>
                <div class="col-sm-5">
                    <?php if ($fetch_user['ban_status'] == '1') { ?>
                        <button class="btn btn-danger form-control-plaintext removeBan" id="banbtn" data-userid="<?php echo $fetch_user['staff_id'] ?>">Remove Ban</button>
                    <?php } else { ?>
                        <button class="btn btn-success form-control-plaintext giveBan" id="banbtn" data-userid="<?php echo $fetch_user['staff_id'] ?>">Give Ban</button>
                    <?php } ?>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="resetpass" class="col-sm-5 col-form-label">Reset Password</label>
                <div class="col-sm-5">
                    <button class="btn btn-danger form-control-plaintext resetpass" data-toggle="modal" data-target="#exampleModal" data-userid="<?php echo $fetch_user['staff_id'] ?>">Reset Password</button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Change Password</h1>
                            </div>
                            <div class="modal-body">
                                <span class="status-bar"></span>
                                <input type='text' class="form-control newPass" placeholder="Enter New Password">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary do_resetPass" data-userid="<?php echo $fetch_user['staff_id'] ?>">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        <div class="card-header bg-danger">
            Set User Rank
        </div>
        <div class="card-body p-3 mb-5 bg-white rounded-bottom">
            <div class="row">
                <input class="form-control search-rank" placeholder="Search Rank">
            </div>
            <br>
            <div class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Rank ID</th>
                            <th scope="col">Rank Name</th>
                            <th scope="col">Department</th>
                            <th scope="col">Option</th>
                        </tr>
                    </thead>
                    <tbody class="td-content"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        function reload_page(userID, page) {
            $.post(engine, {
                csrf: csrf,
                click_editUser: 1,
                user_id: userID
            }, function(data) {
                $(".pages").html(data)
            })
        }

        function includePage(page) {
            $.post(engine, {
                csrf: csrf,
                includePage: page
            }, function(data) {
                $(".pages").html(data)
            })
        }
        $(".manage_user").on("click", ".giveAdmin", function() {
            let user_id = $(this).data("userid")
            $.post(engine, {
                csrf: csrf,
                giveAdmin: 1,
                user_id: $(this).data("userid")
            }, function(data) {
                if (data == "OK") {
                    reload_page(user_id)
                }
                console.log(data)
            })
        }).on("click", ".removeAdmin", function() {
            let user_id = $(this).data("userid")
            $.post(engine, {
                csrf: csrf,
                removeAdmin: 1,
                user_id: $(this).data("userid")
            }, function(data) {
                if (data == "OK") {
                    reload_page(user_id)
                }
                console.log(data)
            })
        }).on("click", ".giveBan", function() {
            let user_id = $(this).data("userid")
            $.post(engine, {
                csrf: csrf,
                giveBan: 1,
                user_id: $(this).data("userid")
            }, function(data) {
                if (data == "OK") {
                    reload_page(user_id)
                }
                console.log(data)
            })
        }).on("click", ".removeBan", function() {
            let user_id = $(this).data("userid")
            $.post(engine, {
                csrf: csrf,
                removeBan: 1,
                user_id: $(this).data("userid")
            }, function(data) {
                if (data == "OK") {
                    reload_page(user_id)
                }
                console.log(data)
            })
        }).on("click", ".do_resetPass", function() {
            let user_id = $(this).data("userid")
            $.post(engine, {
                csrf: csrf,
                resetpass: 1,
                user_id: $(this).data("userid"),
                password: $(".newPass").val()
            }, function(data) {
                if (data == "OK") {
                    $(".newPass").val("");
                    $(".status-bar").html("<div class='mb-2 bg-success text-white rounded card-header' style='width:100%'>Password Changed !</div>")
                    $(".status-bar").fadeIn('slow');
                    setTimeout(function() {
                        $(".status-bar").fadeOut('slow');
                    }, 2000)
                }
                console.log(data)
            })
        });

        $(".search-rank").keydown(function() {
            $.post(engine, {
                csrf: csrf,
                search_rank: $(".search-rank").val()
            }, function(data) {
                if (data == 'no_rank') {
                    $(".td-content").html("no data")
                } else {
                    $(".td-content").html(data)
                }
            })
        });

        $(".search-rank").keyup(function() {
            $.post(engine, {
                csrf: csrf,
                search_rank: $(".search-rank").val()
            }, function(data) {
                if (data == 'no_rank') {
                    $(".td-content").html("no data")
                } else {
                    $(".td-content").html(data)
                }
            })
        });

        $(".td-content").on("click", ".give-rank", function() {
            console.log("helo")
            let userid = <?php echo $fetch_user['staff_id'] ?>;
            $.post(engine, {
                csrf: csrf,
                give_rank: 1,
                userid: userid,
                rankid: $(this).data("rankid")
            }, function(data) {
                console.log(data)
                if (data == "OK") {
                    reload_page(userid)
                }
            })
        });

        $(".back").click(function() {
            includePage("manage-staff")
        })
    })
</script>