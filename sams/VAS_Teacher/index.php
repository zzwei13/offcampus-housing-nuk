<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>租屋-登入頁面</title>
  <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
  <link rel="manifest" href="static/image/manifest.json">
  <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
  <meta name="theme-color" content="#ffffff">
  <link href="static/css/theme.min.css" rel="stylesheet">
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
    .navbar-brand {
      font-family: 'Microsoft JhengHei', sans-serif; /* 設置字體為微軟正黑體 */
    }
  </style>
</head>
</head>

<body>
  <?php
    session_start();
    $isLoggedIn = isset($_SESSION['username']);
  ?>

  <main class="main" id="top">
  <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block"
            data-navbar-on-scroll="data-navbar-on-scroll">
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
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page" href="TeacherProfile.php">
                                基本資料</a></li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($permissionLevel); ?> <?php echo htmlspecialchars($username); ?></a>
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
      
    <section class="pt-6 bg-600" id="home">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-5 col-lg-6 order-0 order-md-1 text-end"><img class="pt-7 pt-md-0 w-100" src="static/picture/hero-header.png" width="470" alt="hero-header"></div>
          <div class="col-md-7 col-lg-6 text-md-start text-center py-6">
            <h4 class="fw-bold font-sans-serif">歡迎登入租屋管理系統</h4>
            <h1 class="fs-6 fs-xl-7 mb-5">登入以開始使用</h1>
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
