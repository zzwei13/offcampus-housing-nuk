<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
$name = $_SESSION['username'];
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
    <title>檢視住宿資料</title>
    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
    <link rel="manifest" href="static/image/manifest.json">
    <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <link href="static/css/theme.min.css" rel="stylesheet">
    <link href="modify.css" rel="stylesheet">
  </head>
<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">

      <!-- ============================================-->
      <!-- <section> begin ============================-->
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

    <section class="pt-6 bg-600" id="home">
    <div class="container2">
        <div class="col-md-8">
            <?php
            require_once 'connect.php';
            // 從 URL 獲取學年和學期參數
            $academicYear = isset($_GET['year']) ? $_GET['year'] : '';
            $semester = isset($_GET['semester']) ? $_GET['semester'] : '';
            
            
            $sql_userid = "SELECT UserID FROM accountmanagement WHERE Username = '$name'";
            $result_userid = $conn->query($sql_userid);

            if ($result_userid === false) {

            } elseif ($result_userid->num_rows > 0) {
                // 獲取 UserID
                $row_userid = $result_userid->fetch_assoc();
                $usr = $row_userid['UserID'];

                $sql_fetch = "SELECT Department, StudentID, Name, Phone, Address, AcademicYear, Semester, LandlordName, LandlordPhoneNumber, MonthlyRent, Deposit, HousingType, RentalType, RecommendOthers, WoodenPartitionsOrIronSheet, HighPowerDevicesOnSingleExtension, FireAlarmOrSmokeDetector, FunctionalFireExtinguisher, SafeWaterHeater, ClearEscapeRoute, GoodSecurity, MoreThan6RoomsOr10Beds, InstalledLighting, InstalledCCTV, FamiliarWithSafetyProcedures, StandardLeaseContract, FamiliarWithEmergencyContacts 
                    FROM studentaccommodation 
                    WHERE StudentID = '$usr' AND AcademicYear = '$academicYear' AND Semester = '$semester'";
                $result_fetch = $conn->query($sql_fetch);
            }

           

            echo "<h1 class='fw-bold font-sans-serif'>學生住宿資料</h1>";
            echo "<br>";

            // 定義欄位名稱的中文對應
            $column_names = array(
                "Department" => "系級",
                "StudentID" => "學號",
                "Name" => "姓名",
                "Phone" => "電話",
                "Address" => "住宿地址",
                "AcademicYear" => "學年",
                "Semester" => "學期",
                "LandlordName" => "房東姓名",
                "LandlordPhoneNumber" => "房東電話",
                "MonthlyRent" => "每月租金",
                "Deposit" => "押金",
                "HousingType" => "租屋型態",
                "RentalType" => "房間類型",
                "RecommendOthers" => "是否推薦其他同學租賃",
                "WoodenPartitionsOrIronSheet" => "木造隔間或鐵皮加蓋",
                "HighPowerDevicesOnSingleExtension" => "使用多種電器(高耗能)是否同時插在同一條延長線",
                "FireAlarmOrSmokeDetector" => "火警警報器或偵測器",
                "FunctionalFireExtinguisher" => "有滅火器且功能正常",
                "SafeWaterHeater" => "熱水器安全良好，無一氧化碳中毒",
                "ClearEscapeRoute" => "逃生通道順暢",
                "GoodSecurity" => "門禁及鎖具良好管理",
                "MoreThan6RoomsOr10Beds" => "分間6個以上房間或10個以上床位",
                "InstalledLighting" => "有安裝照明設備(停車場及周邊)",
                "InstalledCCTV" => "有安裝監視器",
                "FamiliarWithSafetyProcedures" => "了解熟悉電路安全及逃生要領",
                "StandardLeaseContract" => "使用內政部定型化租賃契約",
                "FamiliarWithEmergencyContacts" => "熟悉派出所、醫療、消防隊、學校校安專線電話"
            );

            if ($result_fetch->num_rows > 0) {
                while($row = $result_fetch->fetch_assoc()) {
                    echo "<div class='row'>";
                    foreach($row as $key => $value) {
                        // 若存在中文對應，使用中文名稱，否則保留原本的欄位名稱
                        $column_name = isset($column_names[$key]) ? $column_names[$key] : $key;
                        echo "<div class='row align-items-center'>";
                        echo "<label'>$column_name : $value</label> <br>";
                        echo "<br>";
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "<br>";
                }
            } else {
                echo "No results found for Academic Year: $academicYear and Semester: $semester";
            }
            echo "<button class='btn btn-primary' onclick=\"location.href='house_modify.php?year=" . $academicYear . "&semester=" . $semester  . "'\">修改住宿資料</button>";
            $conn->close();
            ?>
        </div>
    </div>
</section>
<script src="static/js/popper.min.js"></script>
  <script src="static/js/bootstrap.min.js"></script>
  <script src="static/js/is.min.js"></script>
  <script src="static/js/polyfill.min.js"></script>
  <script src="static/js/all.min.js"></script>
  <script src="static/js/theme.js"></script>
  <link href="static/css/css2.css" rel="stylesheet">
</body>
</html>
