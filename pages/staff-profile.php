<div class="fadeIn" style="display:none">
    <div class="row justify-content-left">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-white" style="background:blueviolet">
                    <h3 class="card-title">Staff Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <img src="img/logo_temp.png" width="30%">
                    </div>
                    <div class="form-group">
                        <b>Staff Name:</b> <?= htmlentities(strtoupper(userDetails($_SESSION['user'], 'fullname'))) ?>
                    </div>
                    <div class="form-group">
                        <b>Identification Number:</b> <?= htmlentities(userDetails($_SESSION['user'], 'ic')) ?>
                    </div>
                    <div class="form-group">
                        <b>Phone Number:</b> <?= htmlentities(userDetails($_SESSION['user'], 'phone')) ?>
                    </div>
                    <div class="form-group">
                        <b>Email:</b> <?= htmlentities(userDetails($_SESSION['user'], 'email')) ?>
                    </div>
                    <div class="form-group">
                        <b>Bank Information:</b> <?= htmlentities(userDetails($_SESSION['user'], 'bank_number') . " (" . strtoupper(userDetails($_SESSION['user'], 'bank_name') . ")")) ?>
                    </div>
                    <hr>
                    <div class="form-group" align="right">
                        <button class="btn btn-outline-danger" data-toggle="modal" data-target="#exampleModal">Change Password</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <b class="modal-title fs-5" id="exampleModalLabel">Change Password</b>
                </div>

                <div class="modal-body">
                    <span class="status-bar" style="display:none;"></span>
                    <div class="form-group">
                        <input type="password" class="form-control oldpass" placeholder="Current Password">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control newpass" placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control newpass2" placeholder="Confirm New Password">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_pass">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(".fadeIn").fadeIn("slow")
    $(".save_pass").click(function() {
        $.post(engine, {
            change_pass: 1,
            csrf: csrf,
            oldpass: $(".oldpass").val(),
            newpass: $(".newpass").val(),
            newpass2: $(".newpass2").val(),
        }, function(data) {
            console.log(data);
            if (data == "true") {
                $(".status-bar").html("<div class='mb-2 bg-success text-white rounded card-header' style='width:100%'>Password Updated</div>")
                $(".status-bar").fadeIn('slow');
                $(".oldpass").val("");
                $(".newpass").val("");
                $(".newpass2").val("");
                setTimeout(function() {
                    $(".status-bar").fadeOut('slow');
                }, 2000)
            } else if (data == 'wrong_pass') {
                $(".status-bar").html("<div class='mb-2 bg-danger text-white rounded card-header' style='width:100%'>Wrong old password</div>")
                $(".status-bar").fadeIn('slow');
                setTimeout(function() {
                    $(".status-bar").fadeOut('slow');
                }, 2000)
            } else if (data == 'no_match') {
                $(".status-bar").html("<div class='mb-2 bg-danger text-white rounded card-header' style='width:100%'>New password not match</div>")
                $(".status-bar").fadeIn('slow');
                setTimeout(function() {
                    $(".status-bar").fadeOut('slow');
                }, 2000)
            } else if (data == 'empty') {
                $(".status-bar").html("<div class='mb-2 bg-danger text-white rounded card-header' style='width:100%'>New password cannot be empty</div>")
                $(".status-bar").fadeIn('slow');
                setTimeout(function() {
                    $(".status-bar").fadeOut('slow');
                }, 2000)
            }
        })
    })
</script>