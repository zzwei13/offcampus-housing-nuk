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

<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>已上傳租屋資訊</title>

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

        <!-- ============================================-->

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
                        <a class="btn btn-primary" href=" ../system/login.html" role="button">登出</a>
                    </div>
                    
                    
                </div>
            </div>
    </nav>

        <section class="pt-6 bg-600" id="home">
            <div class="container">
                <h1 class="fw-bold font-sans-serif">已上傳租屋資訊</h1>
                <br>
                <p>租屋資訊</p>
                <a class="btn btn-primary" href="rentInformationUpload.php">新增</a>
                <table class="table caption-top table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>標題</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'db_connect.php';

                        // Query to select data from rentalinformation table
                        $sql = "SELECT InformationID, RentTitle FROM rentalinformation WHERE Username = '" . $_SESSION['username'] . "'";
                        $result = $conn->query($sql);

                        // Check if there are results and output data for each row
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr><td><a href='rentInformationDetail.php?InformationID=" . $row["InformationID"] . "'>" . $row["InformationID"] . "</a></td><td>" . $row["RentTitle"] . "</td><td><a class=\"btn btn-danger btn-sm\" href='rentInformationDelete.php?InformationID=" . $row["InformationID"] . "'>刪除</a></td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>No records found</td></tr>";
                        }

                        // Close connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ============================================-->
        <!-- <section> begin ============================-->
        <section>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 col-lg-4 mb-5"></div>
                    <div class="col-md-6 col-lg-5">

                    </div>
                </div>
            </div><!-- end of .container-->
        </section><!-- <section> close ============================-->
        <!-- ============================================-->

        <!-- ============================================-->

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