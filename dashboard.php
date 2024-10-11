<?php
session_start();
require_once("server/require.php");
$_SESSION['csrf'] = createToken(16);
checkSession('user');
?>
<!-- THIS WEBSITE IS USING AdminLTE TEMPLATE https://adminlte.io/ -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/jpg" href="img/icon/favicon.jpg" />
  <!-- Press Start 2P Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- <script src="http://localhost:3000/socket.io/socket.io.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    let engine = 'server/engine.php'; //Serverside file
    var csrf = '<?= $_SESSION['csrf'] ?>';
  </script>

  <title>Dashboard | <?php echo userDetails($_SESSION['user'], 'username') ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed" style="background-color:black;">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake rounded-circle" src="img/preloader.png" alt="AdminLTELogo" height="100" width="100">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" rolFe="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <div class="nav-link">Welcome <?php echo userDetails($_SESSION['user'], 'username') ?>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <!-- Later here-->
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Sidebar -->
      <a href="#Home" class="brand-link pagehomepage">
        <img src='img/logo_temp.png' class="brand-image">
        <span class="brand-text font-weight-light"><?php echo userDetails($_SESSION['user'], "username") ?></span>
      </a>
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <ul class="nav nav-pills nav-sidebar flex-column">
            <li class="nav-item">
              <a href="#" class="nav-link logout_btn">
                <i class="nav-icon far fa-circle text-danger"></i>
                <p class="text">Logout</p>
              </a>
            </li>
          </ul>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item menu-open">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview dashboardContent"></ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon ion ion-cash"></i>
                <p>
                  Sales
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview salesContent"></ul>
            </li>
            <?php if (userDetails($_SESSION['user'], 'admin') == 1) { ?>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon ion ion-qr-scanner"></i>
                  <p>
                    Admin Access
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview adminContent"></ul>
              </li>
            <?php } ?>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">

      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-lg">
            <div class="container-fluid pages"></div>
          </div>

        </div>
    </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Made with ❤ by <a href="https://github.com/muchub/" target="_blink">Muchub</a>.</strong>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery UI 1.11.4 -->
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- daterangepicker -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>
  <!-- JS -->

  <!-- DataTables  & Plugins -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="plugins/jszip/jszip.min.js"></script>
  <script src="plugins/pdfmake/pdfmake.min.js"></script>
  <script src="plugins/pdfmake/vfs_fonts.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

  <script>
    $(document).ready(function() {

      //sidebar content
      let navClass = ['dashboard', 'sales' <?php if (userDetails($_SESSION['user'], 'admin') == 1) { ?>, 'admin'<?php } ?>]
      let navTitle = [
        ['Home', 'Profile Settings', 'Payslip'],
        ['Sales Leaderboard', 'View Sales' <?php if(rankPermission('sale_approval') > 0){ ?>, 'View All Sales'<?php } ?>],
        <?php if (userDetails($_SESSION['user'], 'admin') == 1) { ?>['Manage Staff', 'Manage Department', 'Manage Rank', 'Manage Product'], <?php } ?>
      ]

      //icon name
      let navIcon = [
        ['fas fa-home', 'fa fa-bars', 'nav-icon ion ion-document'],
        ['fas fa-chart-bar', 'ion ion-compose' <?php if(rankPermission('sale_approval') > 0){ ?>, 'ion ion-compose'<?php } ?>],
        <?php if (userDetails($_SESSION['user'], 'admin') == 1) { ?>['ion ion-compose nav-icon', 'ion ion-compose nav-icon', 'ion ion-compose nav-icon', 'ion ion-compose nav-icon'], <?php } ?>
      ]

      //page name
      let arrayPages = [
        ["home", "staff-profile", "payslip"],
        ['sale-leaderboard', 'sales' <?php if(rankPermission('sale_approval') > 0){ ?>, 'all-sales'<?php } ?>],
        <?php if (userDetails($_SESSION['user'], 'admin') == 1) { ?>['manage-staff', 'manage-department', 'manage-rank', 'manage-product'], <?php } ?>
      ]

      //Side bar mobile/desktop
      for (let i = 1; i <= navClass.length; i++) {
        for (let j = 1; j <= navTitle[i - 1].length; j++) {
          $("." + navClass[i - 1] + "Content").append("<li class='nav-item page" + arrayPages[i - 1][j - 1] + "'><a href='#" + navTitle[i - 1][j - 1] + "' class='nav-link'><i class='" + navIcon[i - 1][j - 1] + " nav-icon'></i><p>" + navTitle[i - 1][j - 1] + "</p></a></li>")
        }
      }

      $(".logout_btn").click(function() {
        $.post(engine, {
          csrf: '<?= $_SESSION['csrf'] ?>',
          do_logout: 1
        }, function(data) {
          //console.log(data)
          if (data == "logout") {
            window.location = 'index';
          }
        })
      })

      // check page
      $.post(engine, {
        csrf: '<?= $_SESSION['csrf'] ?>',
        checkPage: 1
      }, function(data) {
        $(".pages").html(data);
        if (data == '') {
          console.log("empty");
          window.location = 'index';
        }
      });

      //set page
      for (let i = 1; i <= arrayPages.length; i++) {
        for (let j = 1; j <= arrayPages[i - 1].length; j++) {
          $(".page" + arrayPages[i - 1][j - 1]).click(function() {
            $(".pages").html("Loading..")
            setTimeout(function() {
              //$(".pages").load("pages/" + arrayPages[i - 1][j - 1] + ".php");
              //console.log(navTitle[i - 1][j - 1])
              $.post(engine, {
                csrf: '<?= $_SESSION['csrf'] ?>',
                page: arrayPages[i - 1][j - 1]
              }, function(data, status) {
                $(".pages").html(data)
                console.log(status)
                if (data == '') {
                  console.log("empty")
                  window.location = 'index'
                }
                //console.log(data)
              })
            }, 100)
          })
        }
      }

      function includePage(page) {
        $.post(engine, {
          csrf: '<?= $_SESSION['csrf'] ?>',
          includePage: page
        }, function(data) {
          $(".pages").html(data)
        })
      }

      function timerIncrement() {
        idleTime = idleTime + 1;
        if (idleTime > 1) { // 20 minutes
          console.log("idle mouse");
          $.post(engine, {
            csrf: '<?= $_SESSION['csrf'] ?>',
            do_logout: 1
          }, function(data) {
            //console.log(data)
            if (data == "logout") {
              window.location = 'index';
            }
          })
        }
      }
    });
  </script>
</body>

</html>