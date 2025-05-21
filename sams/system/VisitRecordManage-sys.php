<?php
session_start();
// 檢查是否已登入且用戶為導師
if (!isset($_SESSION['permissionLevel']) || ($_SESSION['permissionLevel'] != '導師' && $_SESSION['permissionLevel'] != '管理員')) {
    // 如果不是導師或管理員，則導向到登入頁面
    header('Location: login.html');
    exit;
}

$username = $_SESSION['username']; // 從會話中獲取用戶名
$permissionLevel = $_SESSION['permissionLevel']; 
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
    <title> NUK學生校外住宿管理系統 | 訪視管理</title>

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
    <link href="static/css/theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main_manage.css">
    <style>

        .center-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 80vh; /* 设置高度为视窗高度的80% */
            text-align: center;
        }
        .center-section > div {
            width: 100%; /* 或根据需要设置具体宽度 */
            text-align: center;
            margin-bottom: 0.5rem; /* 控制上下间距 */
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            
            border: none;
            border-radius: 0.25rem;
            text-align: center;
        }
        .mt-3 {
            margin-top:1.5rem; /* 控制顶部间距 */
        }
        .btn-outline-primary {
            width: 160px; /* 自定義按鈕寬度 */
        }
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
        
        <section class="pt-6 bg-600">
            <div>
                <h2 class="fw-bold font-sans-serif" style="text-align: center;">訪視管理系統</h2> 
            </div>
        </section>

        <!-- ============================================-->
        <section class="center-section pt-6 bg-600">
            
            <div class="link-container">
                <a href="RecordList-sys.php" class="btn btn-primary mt-3">查詢訪視紀錄</a>
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