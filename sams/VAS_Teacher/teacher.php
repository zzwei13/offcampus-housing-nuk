<?php
session_start();
// 檢查是否已登入且用戶為導師
if (!isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] != '導師') {
    // 如果不是導師，則導向到主頁面或登入頁面
    header('Location: login.php');
    exit;
}

// 從會話中獲取用戶名、權限等信息
$Username = $_SESSION['username'];
$PermissionLevel = $_SESSION['permissionLevel'];

include 'connect.php';

// 準備並綁定
$stmt = $conn->prepare("SELECT UserID FROM accountmanagement WHERE Username = ? AND PermissionLevel = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ss", $Username,$PermissionLevel);

// 執行查詢
$stmt->execute();
if ($stmt->errno) {
    die("Execute failed: " . $stmt->error);
}
// Bind the result
$stmt->bind_result($UserID);

// 獲取結果
if ($stmt->fetch()) {
  // 將 UserID 存儲在會話中
  $_SESSION['userid'] = $UserID;
  //echo "UserID: $UserID";
} else {
  // 處理未找到匹配用戶的情況
  echo "No matching user found.";
}

// Close the statement
$stmt->close();

// 用來自 session 的 UserID 關聯 teachers 的 TeacherID，以獲取 teachers 的 Name
$stmt = $conn->prepare("SELECT Name FROM teachers WHERE TeacherID = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $UserID);

// 執行TeacherName查詢 //
$stmt->execute();
if ($stmt->errno) {
    die("Execute failed: " . $stmt->error);
}

// Bind the result
$stmt->bind_result($TeacherName);

// 獲取結果
if ($stmt->fetch()) {
    // 顯示教師的名字
    $_SESSION['teacherName'] = $TeacherName;
    //echo "Teacher Name: $TeacherName";
} else {
    // 處理未找到匹配的教師的情況
    echo "No matching teacher found.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> 導師控制台 </title>
  
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
    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container">
              <a class="navbar-brand" href="teacher.php"><img src="static/picture/nuk.png" height="45" alt="logo"></a>
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
                            <a class="nav-link" aria-current="page" href="TeacherProfile.php?">
                                基本資料
                            </a>
                        </li>

                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($PermissionLevel); ?> <?php echo htmlspecialchars($TeacherName); ?></a>
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

    <section class="pt-11 pb-11 bg-600" id="admin-console">
      <div class="container">
        <div class="row align-items-center ">
          <div class="col-md-12 text-md-start text-center py-6">
            <div class="container">
              <div class="row">

                <div class="col-md-15 mb-2"> <!-- 調整 mb-2 可以增加或減少距離 -->
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="card-title mb-0">訪視管理系統</h5>
                      <a href="VisitRecordManage.php" class="btn btn-primary mt-3">前往</a>
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