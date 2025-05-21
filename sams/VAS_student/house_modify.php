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
    <title>修改住宿資料</title>
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
    <link href="modify.css" rel="stylesheet">
</head>

<body>
    <main class="main" id="top">
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

                    $sql_userid = "SELECT UserID FROM accountmanagement WHERE Username = '$name'";
                    $result_userid = $conn->query($sql_userid);
                    $row_userid = $result_userid->fetch_assoc();
                    $usr = $row_userid['UserID'];

                    $academicYear = isset($_GET['year']) ? $_GET['year'] : '';
                    $semester = isset($_GET['semester']) ? $_GET['semester'] : '';

                    // 更新資料
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $updatedData = array(
                            'LandlordName' => $_POST['LandlordName'],
                            'LandlordPhoneNumber' => $_POST['LandlordPhoneNumber'],
                            'MonthlyRent' => $_POST['MonthlyRent'],
                            'Deposit' => $_POST['Deposit'],
                            'HousingType' => $_POST['HousingType'],
                            'RentalType' => $_POST['RentalType'],
                            'RecommendOthers' => $_POST['RecommendOthers'],
                            'WoodenPartitionsOrIronSheet' => $_POST['WoodenPartitionsOrIronSheet'],
                            'HighPowerDevicesOnSingleExtension' => $_POST['HighPowerDevicesOnSingleExtension'],
                            'FireAlarmOrSmokeDetector' => $_POST['FireAlarmOrSmokeDetector'],
                            'FunctionalFireExtinguisher' => $_POST['FunctionalFireExtinguisher'],
                            'SafeWaterHeater' => $_POST['SafeWaterHeater'],
                            'ClearEscapeRoute' => $_POST['ClearEscapeRoute'],
                            'GoodSecurity' => $_POST['GoodSecurity'],
                            'MoreThan6RoomsOr10Beds' => $_POST['MoreThan6RoomsOr10Beds'],
                            'InstalledLighting' => $_POST['InstalledLighting'],
                            'InstalledCCTV' => $_POST['InstalledCCTV'],
                            'FamiliarWithSafetyProcedures' => $_POST['FamiliarWithSafetyProcedures'],
                            'StandardLeaseContract' => $_POST['StandardLeaseContract'],
                            'FamiliarWithEmergencyContacts' => $_POST['FamiliarWithEmergencyContacts']
                        );

                        $updateSql = "UPDATE studentaccommodation SET 
                                        LandlordName = '{$updatedData['LandlordName']}', 
                                        LandlordPhoneNumber = '{$updatedData['LandlordPhoneNumber']}', 
                                        MonthlyRent = '{$updatedData['MonthlyRent']}', 
                                        Deposit = '{$updatedData['Deposit']}', 
                                        HousingType = '{$updatedData['HousingType']}', 
                                        RentalType = '{$updatedData['RentalType']}', 
                                        RecommendOthers = '{$updatedData['RecommendOthers']}', 
                                        WoodenPartitionsOrIronSheet = '{$updatedData['WoodenPartitionsOrIronSheet']}', 
                                        HighPowerDevicesOnSingleExtension = '{$updatedData['HighPowerDevicesOnSingleExtension']}', 
                                        FireAlarmOrSmokeDetector = '{$updatedData['FireAlarmOrSmokeDetector']}', 
                                        FunctionalFireExtinguisher = '{$updatedData['FunctionalFireExtinguisher']}', 
                                        SafeWaterHeater = '{$updatedData['SafeWaterHeater']}', 
                                        ClearEscapeRoute = '{$updatedData['ClearEscapeRoute']}', 
                                        GoodSecurity = '{$updatedData['GoodSecurity']}', 
                                        MoreThan6RoomsOr10Beds = '{$updatedData['MoreThan6RoomsOr10Beds']}', 
                                        InstalledLighting = '{$updatedData['InstalledLighting']}', 
                                        InstalledCCTV = '{$updatedData['InstalledCCTV']}', 
                                        FamiliarWithSafetyProcedures = '{$updatedData['FamiliarWithSafetyProcedures']}', 
                                        StandardLeaseContract = '{$updatedData['StandardLeaseContract']}', 
                                        FamiliarWithEmergencyContacts = '{$updatedData['FamiliarWithEmergencyContacts']}' 
                                      WHERE StudentID = '$usr' AND AcademicYear = '$academicYear' AND Semester = '$semester'";

                            //判斷是否成功更新
                            if ($conn->query($updateSql) === TRUE) {
                                echo "<script>alert('修改成功');</script>";
                            } else {
                                echo "修改失敗，錯誤訊息: " . $conn->error;
                            }
                        }

                    // SQL 查詢
                    $sql = "SELECT Department, StudentID, Name, Phone, Address, AcademicYear, Semester, LandlordName, LandlordPhoneNumber, MonthlyRent, Deposit, HousingType, RentalType, RecommendOthers, WoodenPartitionsOrIronSheet, HighPowerDevicesOnSingleExtension, FireAlarmOrSmokeDetector, FunctionalFireExtinguisher, SafeWaterHeater, ClearEscapeRoute, GoodSecurity, MoreThan6RoomsOr10Beds, InstalledLighting, InstalledCCTV, FamiliarWithSafetyProcedures, StandardLeaseContract, FamiliarWithEmergencyContacts 
                            FROM studentaccommodation 
                            WHERE StudentID = '$usr' AND AcademicYear = '$academicYear' AND Semester = '$semester'";
                    $result = $conn->query($sql);

                    echo "<h1 class='fw-bold font-sans-serif'>修改學生住宿資料</h1>";
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
                        "RecommendOthers" => "是否推薦其他同學租賃 (是/否)",
                        "WoodenPartitionsOrIronSheet" => "木造隔間或鐵皮加蓋 (是/否)",
                        "HighPowerDevicesOnSingleExtension" => "使用多種電器(高耗能)是否同時插在同一條延長線 (是/否)",
                        "FireAlarmOrSmokeDetector" => "火警警報器或偵測器 (是/否)",
                        "FunctionalFireExtinguisher" => "有滅火器且功能正常 (是/否)",
                        "SafeWaterHeater" => "熱水器安全良好，無一氧化碳中毒 (是/否)",
                        "ClearEscapeRoute" => "逃生通道順暢 (是/否)",
                        "GoodSecurity" => "門禁及鎖具良好管理 (是/否)",
                        "MoreThan6RoomsOr10Beds" => "分間6個以上房間或10個以上床位 (是/否)",
                        "InstalledLighting" => "有安裝照明設備(停車場及周邊) (是/否)",
                        "InstalledCCTV" => "有安裝監視器 (是/否)",
                        "FamiliarWithSafetyProcedures" => "了解熟悉電路安全及逃生要領 (是/否)",
                        "StandardLeaseContract" => "使用內政部定型化租賃契約 (是/否)",
                        "FamiliarWithEmergencyContacts" => "熟悉派出所、醫療、消防隊、學校校安專線電話 (是/否)"
                    );

                    if ($result->num_rows > 0) {
                        echo "<form method='POST'>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='row'>";
                            foreach ($row as $key => $value) {
                                $column_name = isset($column_names[$key]) ? $column_names[$key] : $key;
                                echo "<div class='row align-items-center'>";
                                if (in_array($key, ["Department", "StudentID", "Name", "Phone", "Address", "AcademicYear", "Semester"])) {
                                    echo "<label>$column_name : $value</label><br>";
                                } else {
                                    echo "<label>$column_name: <input type='text' name='$key' value='$value'></label><br>";
                                }
                                echo "</div>";
                            }
                            echo "</div><br>";
                        }
                        echo "<button type='submit' class='btn btn-primary'>儲存</button>";
                        echo "</form>";
                        echo "<br> <a href='house_choose.php' class='btn btn-primary'>查看資料</a><br>";

                    } else {
                        echo "No results found for Academic Year: $academicYear and Semester: $semester";
                    }
                    // 關閉連線
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