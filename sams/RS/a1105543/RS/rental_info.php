<?php
session_start();
// 檢查是否已登入且用戶為學生
if (!isset($_SESSION['permissionLevel'])) {
    // 如果不是學生或導師，則導向到主頁面或登入頁面
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>

<html lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>租屋資訊</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
    <link rel="manifest" href="static/image/manifest.json">
    <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="static/css/theme.min.css" rel="stylesheet">
</head>

<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">

        <!-- ============================================-->
        <!-- <section> begin ============================-->
        <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container">
              <a class="navbar-brand" href="../../../VAS_student/HouseInfoManage.php"><img src="static/picture/nuk.png" height="45" alt="logo"></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                      
                        <li class="nav-item px-2">
                          <a class="nav-link" aria-current="page" href="rental_info.php">租屋資訊</a>
                        </li>
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page"
                                href="forum.php">交流平台</a></li>
                        <li class="nav-item px-2">
                            <a class="nav-link" aria-current="page" href="view_user_pro.php?">
                                基本資料
                            </a>
                        </li>

                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($_SESSION['permissionLevel']); ?> <?php echo htmlspecialchars($_SESSION['name']); ?></a>
                        </li>
                    </ul>
                    <div id="userStatus" class="ms-lg-2">
                        <a class="btn btn-primary" href="logout.php" role="button">登出</a>
                    </div>
                    
                    <div class="dropdown d-none d-lg-block">
                        <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="dropdownMenuButton1"
                            style="top:55px">
                            <form>
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
    </nav>



        <section class="pb-0" style="margin-top:0">
        <div class="container">
          <div class="row">
<?php

require 'connect.php';

$query = "SELECT ri.*, (SELECT Image FROM rentalimages WHERE rentalimages.InformationID = ri.InformationID ORDER BY ImageID LIMIT 1) AS first_image
FROM rentalinformation ri ORDER BY InformationID DESC";
$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)) {
if($row['Review'] == 'valid'){

  echo '<div class="col-md-4 mb-4">
  <div class="card h-100"><img class="card-img-top w-100" style="height: 200px; object-fit: cover" src="data:image/jpeg;base64,' . base64_encode($row['first_image']) . '">
    <div class="card-body">
      <h5 class="font-sans-serif fw-bold fs-md-0 fs-lg-1">' . $row['RentTitle'] . '</h5><a class="text-muted fs--1 stretched-link text-decoration-none" href="rental_detail.php?info_id=' . $row['InformationID'] . '">' . $row['Address'] . '</a>
    </div>
  </div>
</div>';
}
}
?>

<!--<div id="pagination" style="text-align:center">
<a href="#!">上一頁</a>
<a href="#!">下一頁</a>
</div>-->
           
          </div>
        </div><!-- end of .container-->
        </section><!-- <section> close ============================-->
        <!-- ============================================-->
        
    </main><!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->



    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="static/js/popper.min.js"></script>
    <script src="static/js/bootstrap.min.js"></script>
    <script src="static/js/is.min.js"></script>
    <script src="static/js/polyfill.min.js"></script>
    <script src="static/js/all.min.js"></script>
    <script src="static/js/theme.js"></script>
    <link href="static/css/css2.css" rel="stylesheet">
</body>

</html>