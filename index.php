<?php
session_start();
include("server/require.php");
$_SESSION['csrf'] = createToken(16);
if (isset($_SESSION["user"])) {
    header("location:dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital@1&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/jpg" href="img/icon/favicon.jpg" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script src="plugins/jquery/jquery.min.js"></script>
    <title>Raso & Friend Management System</title>
</head>
<style>
    footer {
        position: fixed;
        height: 50px;
        bottom: 0;
        width: 100%;
    }
</style>
</head>

<body style="background-color: black;">

    <div class="container">

        <div style="height: 61px;"></div>

        <div class="row mb-2">
            <div class="col" align="center">
                <div class="card" style="background-color: black">
                    <div class="card-body" style="height: 250px; background-color: black">
                        <img class="rasao-logo" src="img/rasao.jpg" width="20%" style="display: none;">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-2"></div>
            <div class="col">
                <div class="card flex-md-row mb-4 box-shadow h-md-250">
                    <div class="card-body d-flex flex-column align-items-start">
                        <strong class="d-inline-block mb-2 text-danger">Are you Raso & Friend's staff ?</strong>
                        <h3 class="mb-0">
                            <span class="text-dark">Login</span>
                        </h3>
                        <br>
                        <div class="status_login"></div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control logUsername" placeholder="Enter Username" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control logPassword" placeholder="Enter Password" aria-label="Password" aria-describedby="basic-addon1">
                        </div>
                        <button type="button" class="btn btn-danger submitLogin">Login</button>
                    </div>
                </div>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
    <br>
    <footer class="blog-footer p-3 bg-dark text-white">
        <?php include("pages/footer.php"); ?>
    </footer>
    <script>
        $(document).ready(function() {
            $(".rasao-logo").fadeIn("slow");
            let csrf = '<?= $_SESSION['csrf'] ?>';
            $(document).on('click', '.submitLogin', function() {
                $(".status_login").html("<b>Loading...</b>")
                $.post('server/engine.php', {
                    csrf: csrf,
                    login: 1,
                    password: $(".logPassword").val(),
                    username: $(".logUsername").val()
                }, function(data) {
                    data = JSON.parse(data);
                    console.log(data);
                    if(data['status'] == false){
                        $(".status_login").html("<div class='mb-2 bg-danger text-white'>Login failed </div>");
                    }else if(data['status'] == true){
                        $(".status_login").html("<div class='mb-2 bg-success text-white'>Login success, redirecting to dashboard </div>");
                        setTimeout(function(){
                            window.location='dashboard.php';
                        },500);
                    }
                })
            })
            //$(".head").load("server/lib.html");

        })
    </script>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
</body>

</html>