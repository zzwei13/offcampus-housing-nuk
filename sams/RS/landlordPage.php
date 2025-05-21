<?php
session_start(); // 啟動會話

// 檢查會話中是否存在有效的登入信息，檢查username是否存在資料庫中，如果不存在，則導向到登入頁面
if (!isset($_SESSION['username'])) {
  header('Location: ../system/login.html'); // 使用相對路徑進行重定向
    exit;
} 

// 包含資料庫連接文件
include 'db_connect.php';

// 獲取會話中的參數
$permissionLevel = $_SESSION['permissionLevel'];
$username = $_SESSION['username'];

// 判斷 permissionLevel 是否為 "房東"
if ($permissionLevel !== "房東") {
    echo "您沒有管理員權限訪問此頁面。";
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>房東頁面</title>
  <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
  <link rel="manifest" href="static/image/manifest.json">
  <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
  <meta name="theme-color" content="#ffffff">
  <link href="static/css/theme.min.css" rel="stylesheet">
  <link rel="stylesheet" href="profile_management.css">
  <style>
    .menu {
      margin-top: 20px;
    }

    .menu ul {
      list-style: none;
      padding: 0;
    }

    .menu ul li {
      margin-bottom: 10px;
    }

    .menu ul li a {
      text-decoration: none;
      color: #fff;
      background-color: #220088;
      /* 深藍色 */
      padding: 10px;
      display: block;
      border: 1px solid #220088;
      /* 深藍色 */
      border-radius: 5px;
      text-align: center;
    }

    .menu ul li a:hover {
      background-color: #ffc107;
      /* 黃色 */
      border-color: #ffc107;
      /* 黃色 */
      color: #220088;
      /* 深藍色 */
    }
  </style>
</head>

<body>
  <main class="main" id="top">
  <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block"
            data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container"><a class="navbar-brand" href="landlordPage.php"><img src="static/picture/nuk.png" height="45"
                        alt="logo"></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">
                    </span></button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                        <li class="nav-item px-2"><a class="nav-link active" aria-current="page" href="landlordPage.php">房東主頁</a></li>
                        
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($permissionLevel); ?> <?php echo htmlspecialchars($username); ?></a>
                        </li>
                    </ul>
                    <div id="userStatus" class="ms-lg-2">
                        <a class="btn btn-primary" href=" ../system/logout.php" role="button">登出</a>
                    </div>
                    
                    
                </div>
            </div>
    </nav>
    <section c
    <section class="pt-6 bg-600" id="account-management">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-12 text-md-start text-center py-6">

            <div class="menu">
              <ul>
                <li class="item"><a href="rentInformation.php">查看已上傳租屋資訊</a></li>
                <li class="item"><a href="rentInformationUpload.php">租屋資訊上傳</a></li>
                <li class="item"><a href="rental_info.php">瀏覽租屋資訊</a></li>

              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script src="static/js/popper.min.js"></script>
  <script src="static/js/bootstrap.min.js"></script>
  <script src="static/js/is.min.js"></script>
  <script src="static/js/polyfill.min.js"></script>
  <script src="static/js/all.min.js"></script>
  <script src="static/js/theme.js"></script>
  <link href="static/css/css2.css" rel="stylesheet">
</body>

</html>