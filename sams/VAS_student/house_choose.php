<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
$name = $_SESSION['username'];
$permissionLevel = $_SESSION['permissionLevel']; 
?>

<!-- ==================選擇學年與學期查看住宿資料==================-->
<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>檢視學生住宿資料</title>
    <!-- ===============================================-->
    <!--    Stylesheets (不能刪)-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
    <link rel="manifest" href="static/image/manifest.json">
    <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <link href="static/css/theme.min.css" rel="stylesheet">
    <link href="house_css.css" rel="stylesheet"> 
</head>

<body>
    <!-- ===============================================-->
    <!-- Main Content-->
    <main class="main" id="top">
        <!-- ============================================-->
        <!-- =============== 上面黃色的那一欄=============-->
        <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block"
            data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container">
              <a class="navbar-brand" href="HouseInfoManage.php"><img src="static/picture/nuk.png" height="45" alt="logo"></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page"
                                href="rental_info.php">租屋資訊</a></li>
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page"
                                href="forum.php">交流平台</a></li>
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page" href="stuProfile.php">
                                基本資料</a></li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($permissionLevel); ?> <?php echo htmlspecialchars($name); ?></a>
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
            <!-- ============================================-->
            <section class="d-flex justify-content-center align-items-start vh-100 bg-600" id="home">
                <div class="text-center">
                <?php
                require_once 'connect.php';

                // 安全地處理輸入的 $username
                //$name = htmlspecialchars($name);
                $sql_userid = "SELECT UserID FROM accountmanagement WHERE Username = '$name'";

                // 調試輸出SQL查詢語句
                //echo "SQL Query for UserID: " . $sql_userid . "<br>";
                $result_userid = $conn->query($sql_userid);

                if ($result_userid === false) {
                    //echo "SQL Error: " . $conn->error;  // 調試輸出
                } elseif ($result_userid->num_rows > 0) {
                    // 獲取 UserID
                    $row_userid = $result_userid->fetch_assoc();
                    $usr = $row_userid['UserID'];
                    //echo "Found UserID: " . htmlspecialchars($usr) . "<br>";  // 調試輸出

                    // 使用查詢到的 UserID 進行下一步查詢
                    $sql = "SELECT AccommodationID FROM studentaccommodation WHERE StudentID = '$usr'";
                    //echo "SQL Query for Accommodation: " . $sql . "<br>";  // 調試輸出

                    $result = $conn->query($sql);

                    // 顯示 AccommodationID 的前四個字符、完整的 AccommodationID、學年和學期
                    if ($result === false) {
                        //echo "SQL Error: " . $conn->error;  // 調試輸出
                    } elseif ($result->num_rows > 0) {
                        echo "<div class='message-box'>";
                        while ($row = $result->fetch_assoc()) {
                            $accommodationID = $row["AccommodationID"]; //AccommodationID : 學號+學年+學期 Ex:s0011082 -> 學號:s001,學年108,學期2
                            $firstFourChars = substr($accommodationID, 0, 4); //學號
                            $academicYear = substr($accommodationID, 4, 3); //學年
                            $semester = substr($accommodationID, 7, 1); //學期
                            $link = "house_result.php?year=$academicYear&semester=$semester";
                            echo "<a href='$link'>學年: $academicYear - 學期: $semester</a>";
                            echo " - (表單編號: $accommodationID )<br>";
                        }
                        echo "</div>";
                    } else {
                        echo "No accommodations found for User ID: $usr";
                    }
                } else {
                    echo "<p>用戶不存在</p>";
                }

                // 關閉連線
                $conn->close();
                ?>
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

<style>
.message-box {
    background:white;
    border: grey;
    padding: 15px;
    margin: 20px auto; /* 水平置中 */
    border-radius: 5px;
    text-align: center;
    font-size: 1.2em;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 600px; 
    width: 100%; 
}

#home {
    text-align: center; /* 父元素居中对齐 */
}
</style>
