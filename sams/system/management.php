<?php
session_start();
require 'connect.php'; // 確保資料庫連接在使用任何資料庫操作前就已建立

if (!isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] != '管理員') {
  // 如果不是管理員，則導向到主頁面或登入頁面
  header('Location: login.html');
  exit;
}

$username = $_SESSION['username'] ; // 使用 null 合併運算符來處理未設置情況
$permissionLevel = $_SESSION['permissionLevel'] ; // 同上
?>

<!DOCTYPE html>
<html lang="zh" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>管理員控制台</title>
  <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
  <link rel="manifest" href="static/image/manifest.json">
  <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
  <meta name="theme-color" content="#ffffff">
  <link href="static/css/theme.min.css" rel="stylesheet">
  <link rel="stylesheet" href="main_manage.css">
  <style>
    .btn-primary {
      min-width: 80px; /* 調整這個寬度以適應按鈕的內容 */
      white-space: nowrap; /* 禁止換行 */
    }
    .card-body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .card {
      margin-bottom: 10px; /* 調整卡片之間的距離 */
    }
  </style>
</head>

<body>
  <main class="main" id="top">
  <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block"
            data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container"><a class="navbar-brand" href="management.php"><img src="static/picture/nuk.png" height="45"
                        alt="logo"></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">
                    </span></button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                        <li class="nav-item px-2"><a class="nav-link active" aria-current="page" href="management.php">管理員主頁</a></li>

                        
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($permissionLevel); ?> <?php echo htmlspecialchars($username); ?></a>
                        </li>
                    </ul>
                    <div id="userStatus" class="ms-lg-2">
                        <a class="btn btn-primary" href="logout.php" role="button">登出</a>
                    </div>
                    
                    
                </div>
            </div>
    </nav>
    <section class="pt-6 bg-600" id="admin-console">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-12 text-md-start text-center py-6">
            <div class="container">
              <div class="row">
                <div class="col-md-15 mb-2"> <!-- 調整 mb-2 可以增加或減少距離 -->
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="card-title mb-0">帳號管理</h5>
                      <a href="account_management.php" class="btn btn-primary mt-3">前往</a>
                    </div>
                  </div>
                </div>
                <div class="col-md-15 mb-2"> <!-- 調整 mb-2 可以增加或減少距離 -->
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="card-title mb-0">基本資料管理</h5>
                      <a href="profile_management.php" class="btn btn-primary mt-3">前往</a>
                    </div>
                  </div>
                </div>
                <div class="col-md-15 mb-2"> <!-- 調整 mb-2 可以增加或減少距離 -->
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="card-title mb-0">訪視紀錄查詢</h5>
                      <a href="VisitRecordManage_sys.php" class="btn btn-primary mt-3">前往</a>
                    </div>
                  </div>
                </div>
                <div class="col-md-15 mb-2"> <!-- 調整 mb-2 可以增加或減少距離 -->
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="card-title mb-0">租屋資訊管理</h5>
                      <a href="../RS/a1105543/RIES/ad_management.php" class="btn btn-primary mt-3">前往</a>
                    </div>
                  </div>
                </div>
                <div class="col-md-15 mb-2"> <!-- 調整 mb-2 可以增加或減少距離 -->
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="card-title mb-0">交流平台管理</h5>
                      <a href="../RS/a1105543/RIES/post_delete.php" class="btn btn-primary mt-3">前往</a>
                    </div>
                  </div>
                </div>
                    </div>
                  </div>
                </div>
              </div>
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