<?php
session_start();
// 檢查是否已登入且用戶為導師
if (!isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] != '管理員') {
    // 如果不是導師，則導向到主頁面或登入頁面
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username']; // 從會話中獲取用戶名
$permissionLevel = $_SESSION['permissionLevel']; 

?>

<!DOCTYPE html>
<style>
    .form-container {
        border: 2px solid #ccc; /* 邊框顏色和寬度 */
        border-radius: 15px;    /* 圓角半徑 */
        padding: 15px;          /* 內邊距 */
        margin: 10px 0;         /* 外邊距 */
    }
    .form-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .form-group label {
        flex: 1;
        font-weight: bold;
    }
    .form-group span {
        flex: 2;
        text-align: right;
    }
    .center-section {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 0px; /* 設置高度為視窗高度的 80% */
    }
    .custom-search {
        width: 600px; /* 自定義搜索框寬度 */
        height: 40px;
    }
    .btn-outline-primary {
        width: 100px; /* 自定義按鈕寬度 */
    }   
    /**/
    .card-container {
        margin-bottom: 20px;
    }
    .card-label {
        font-weight: bold;
        color: #333;
    }                  
</style>

<html lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title> NUK學生校外住宿管理系統 | 訪視管理 </title>

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

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link href="static/css/theme.min.css" rel="stylesheet">
</head>

<body>
    <main class="main" id="top">
        <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container">
              <a class="navbar-brand" href="RecordList_sys.php"><img src="static/picture/nuk.png" height="45" alt="logo"></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                      
                    <?php if ($_SESSION['permissionLevel'] != '管理員') : ?>
                            <li class="nav-item px-2">
                                <a class="nav-link" aria-current="page" href="view_user_pro.php">
                                    基本資料
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item px-2">
                            <a class="nav-link" aria-current="page" href="management.php">
                                管理員主頁
                            </a>
                        </li>
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
        

        <section class="pt-2 bg-600" id="home">
            <div class="d-flex justify-content-center align-items-center min-vh-100">
                <div class="container">
                    <div class="row center-section">
                        <!--div class="col-md-5 col-lg-6 order-0 order-md-1 text-end"--></div>
                        <div class="col-md-9 col-lg-12 text-md-start text-center py-4">           

                            <h2 class="fw-bold font-sans-serif" style="text-align: center;">校外賃居訪視紀錄</h2>
                            <form id="toUpdateForm" action="QueryViewRecord.php" method="get">
                                <input type="hidden" name="VisitID" value="<?php echo htmlspecialchars($_GET['VisitID']); ?>">
                                <button type="submit" formaction="RecordList_sys.php" formmethod="get" class="btn btn-secondary"><</button>
                                
                            </form>

                            <?php
                            // 包含資料庫連接文件
                            include_once 'connect.php';

                            // 獲取 URL 中的 VisitID, StudentID 和 VisitDate
                            $VisitID = isset($_GET['VisitID']) ? $_GET['VisitID'] : '';
                            $StudentID = isset($_GET['StudentID']) ? $_GET['StudentID'] : '';
                            $VisitDate = isset($_GET['VisitDate']) ? $_GET['VisitDate'] : '';
                            // 檢查 VisitID 是否為空
                            if (empty($VisitID)) {
                                echo "Visit ID is required.";
                                exit;
                            }

                            // 查詢
                            $sql = "
                            SELECT 
                                s.Department,
                                t.Name AS TeacherName,
                                sa.Name AS StudentName,
                                sa.phone AS StudentContactPhone,
                                sa.*, 
                                vr.*
                            FROM VisitRecord vr
                            JOIN Students s ON vr.StudentID = s.StudentID
                            JOIN Teachers t ON vr.TeacherID = t.TeacherID
                            JOIN StudentAccommodation sa ON vr.AccommodationID = sa.AccommodationID
                            
                            WHERE vr.VisitID = ?
                            ";
                            
                            //用StudentID外鍵關聯至學生 (Students)獲取系級 (Department)
                            //用TeacherID外鍵關聯至導師表(Teachers)獲取導師姓名(Name)
                            //用AccommodationID外鍵關聯至學生住宿表(StudentAccommodation)獲取所有欄位
        

                            // 準備和執行 SQL 語句
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $VisitID);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // 檢查查詢是否成功
                            if ($result) {
                                // 檢查是否有資料
                                if (mysqli_num_rows($result) > 0) {
                                    // 遍歷每一行資料
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // 顯示每一行的資料
                                        echo '<div class="card" style="margin: 20px;">';
                                        echo '<div class="card-body">';
                                        
                                        echo "<div class='form-group'><label class='card-label'>系級：</label><span>" . htmlspecialchars($row['Department']) . "</span></div>";
                                        echo "<div class='form-group'><label class='card-label'>學號：</label><span>" . htmlspecialchars($row['StudentID']) . "</span></div>";
                                        echo "<div class='form-group'><label class='card-label'>姓名：</label><span>" . htmlspecialchars($row['StudentName']) . "</span></div>";
                                        echo "<div class='form-group'><label class='card-label'>連絡電話：</label><span>" . htmlspecialchars($row['StudentContactPhone']) . "</span></div>";
                                        echo "<div class='form-group'><label class='card-label'>導師：</label><span>" . htmlspecialchars($row['TeacherName']) . "</span></div>";
                                        echo "<div class='form-group'><label class='card-label'>訪視日期：</label><span>" . htmlspecialchars($row['VisitDate']) . "</span></div>";
                                        echo "</div>";
                                        echo "</div>";

                                      
                                        echo "<div class='accordion' id='accordionExample'>";
                                        echo "<div class='card' style='margin: 20px'>";
                                        echo "<div class='card-header' id='headingOne'>";
                                        echo "<h2 class='mb-0'>";
                                        echo "<button class='btn  btn-block text-center' type='button' data-toggle='collapse' data-target='#collapseOne' aria-expanded='true' aria-controls='collapseOne'>";
                                        echo "<h4 class='fw-bold font-sans-serif ' >學生住宿資料</h4>";
                                        echo "</button>";
                                        echo "</h2>";
                                        echo "</div>";
        
                                        echo "<div id='collapseOne' class='collapse show' aria-labelledby='headingOne' data-parent='#accordionExample'>";
                                        echo "<div class='card-body'>";
                                        echo "<form>";

                                        echo "<div class='card-container'>";
                                        
                                        echo "</div>";

                                        echo "<div style='margin-bottom: 20px;'></div>";

                                        echo "<h4 class='fw-bold font-sans-serif' style='text-align: center'>校外賃居資料</h4>";
                                        echo "<div class='form-container'>";
                                        echo "<div class='form-group'><label for='landlordName'>房東姓名：</label><span>" . htmlspecialchars($row['LandlordName']) . "</span></div>";
                                        echo "<div class='form-group'><label for='landlordPhoneNumber'>房東電話：</label><span>" . htmlspecialchars($row['LandlordPhoneNumber']) . "</span></div>";
                                        echo "<div class='form-group'><label for='address'>住宿地址：</label><span>" . htmlspecialchars($row['Address']) . "</span></div>";
                                        echo "<div class='form-group'><label for='rentalType'>租屋型態：</label><span>" . htmlspecialchars($row['RentalType']) . "</span></div>";
                                        echo "<div class='form-group'><label for='housingType'>房屋類型：</label><span>" . htmlspecialchars($row['HousingType']) . "</span></div>";
                                        echo "<div class='form-group'><label for='monthlyRent'>月租：</label><span>" . htmlspecialchars($row['MonthlyRent']) . "</span></div>";
                                        echo "<div class='form-group'><label for='deposit'>押金：</label><span>" . htmlspecialchars($row['Deposit']) . "</span></div>";
                                        echo "<div class='form-group'><label for='recommend'>是否推薦其他同學租賃：</label><span>" . htmlspecialchars($row['RecommendOthers']) . "</span></div>";
                                        echo "</div>";

       
                                        echo "<div style='margin-bottom: 20px;'></div>";

                                        echo "<h4 class='fw-bold font-sans-serif' style='text-align: center'>賃居安全自主管理檢視資料</h4>";
                                        echo "<div class='form-container'>";
                                        echo "<div class='form-group'><label for='woodenPartition'>是否有木造隔間或鐵皮加蓋：</label><span>" . htmlspecialchars($row['WoodenPartitionsOrIronSheet']) . "</span></div>";
                                        echo "<div class='form-group'><label for='highEnergyUsage'>是否使用多種高耗能的設備並同時插在同一條延長線：</label><span>" . htmlspecialchars($row['HighPowerDevicesOnSingleExtension']) . "</span></div>";
                                        echo "<div class='form-group'><label for='fireAlarm'>是否有火警警報器或偵煙器：</label><span>" . htmlspecialchars($row['FireAlarmOrSmokeDetector']) . "</span></div>";
                                        echo "<div class='form-group'><label for='fireExtinguisher'>是否有功能正常的滅火器：</label><span>" . htmlspecialchars($row['FunctionalFireExtinguisher']) . "</span></div>";
                                        echo "<div class='form-group'><label for='escapeRoute'>逃生通道是否暢通且標示清楚：</label><span>" . htmlspecialchars($row['ClearEscapeRoute']) . "</span></div>";
                                        echo "<div class='form-group'><label for='heaterSafety'>熱水器是否安全良好，無一氧化碳中毒疑慮：</label><span>" . htmlspecialchars($row['SafeWaterHeater']) . "</span></div>";
                                        echo "<div class='form-group'><label for='lockManagement'>門禁及鎖具是否良好管理：</label><span>" . htmlspecialchars($row['GoodSecurity']) . "</span></div>";
                                        echo "<div class='form-group'><label for='highDensityRental'>是否分間6個以上房間或10個以上床位：</label><span>" . htmlspecialchars($row['MoreThan6RoomsOr10Beds']) . "</span></div>";
                                        echo "<div class='form-group'><label for='cctvInstallation'>是否有安裝監視器設備：</label><span>" . htmlspecialchars($row['InstalledCCTV']) . "</span></div>";
                                        echo "<div class='form-group'><label for='lightingInstallation'>是否有安裝照明設備：</label><span>" . htmlspecialchars($row['InstalledLighting']) . "</span></div>";
                                        echo "<div class='form-group'><label for='safetyKnowledge'>是否熟悉電路安全及逃生要領：</label><span>" . htmlspecialchars($row['FamiliarWithSafetyProcedures']) . "</span></div>";
                                        echo "<div class='form-group'><label for='rentalContract'>是否使用內政部定型化租賃契約：</label><span>" . htmlspecialchars($row['StandardLeaseContract']) . "</span></div>";
                                        echo "<div class='form-group'><label for='contactInfo'>是否熟悉派出所、醫療、消防隊、學校校安專線電話：</label><span>" . htmlspecialchars($row['FamiliarWithEmergencyContacts']) . "</span></div>";
                                        echo "</div>";
                                    
                                        echo "</form>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                        
                                        /////////////////////////////////////////////////////////////////////
                                        /////////////////////////////////////////////////////////////////////

                                        echo "<hr>";
                                        echo "<div style='margin-bottom: 20px;'></div>";

                                        echo '<div class="card" style="margin: 20px;">';
                                        echo '<div class="card-body">';
                                        echo '<h4 class="fw-bold font-sans-serif" style="text-align: center;">環境及作息評估</h4>';

                                        echo '<form>';
                                        echo '<div class="form-container">';
                                        echo '<div class="form-group">';
                                        echo '<label>押金要求： </label>';
                                        echo htmlspecialchars($row['DepositRequirement']);  // 顯示押金欄位
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                        echo '<label>水電資要求： </label>';
                                        echo htmlspecialchars($row['UtilityRequirement']);  // 顯示水電費欄位
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                        echo '<label>居家環境： </label>';
                                        echo htmlspecialchars($row['LivingEnvironment']);  // 顯示居家環境欄位
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                        echo '<label>生活設施： </label>';
                                        echo htmlspecialchars($row['LivingFacilities']);  // 顯示生活設施欄位
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                        echo '<label>訪視現況： </label>';
                                        echo htmlspecialchars($row['VisitStatus']);  // 顯示訪視現況欄位
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                        echo '<label>主客相處： </label>';
                                        echo htmlspecialchars($row['HostGuestInteraction']);  // 顯示主客相處欄位
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</form>';

                                        echo "<hr>";
                                        echo "<div style='margin-bottom: 20px;'></div>";
                                        
                                        // 顯示訪視結果
                                        echo '<h4 class="fw-bold font-sans-serif" style="text-align: center;">訪視結果</h4>';
                                        echo '<form>';
                                        echo '<div class="form-container">';
                                        echo '<div class="form-group">';
                                        echo '<label class="form-check-label" for="GoodCondition">整體賃居狀況良好:</label><br>';
                                        echo '<span>' . htmlspecialchars($row['GoodCondition']) . '</span>';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                        echo '<label class="form-check-label" for="ContactParents">聯繫家長關注:</label><br>';
                                        echo '<span>'  . htmlspecialchars($row['ContactParents']) .  '</span>';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                        echo '<label class="form-check-label" for="AssistanceNeeded">安全堪四慮請協助</label><br>';
                                        echo '<span>'  . htmlspecialchars($row['AssistanceNeeded']) .   '</span>';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                        echo '<label class="form-check-label" for="AdditionalNotes">其他紀載或建議事項:</label>';
                                        echo '<span>' . htmlspecialchars($row['AdditionalNotes']) .'</span>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</form>';
                            
                                        echo "<hr>";
                                        echo "<div style='margin-bottom: 20px;'></div>";
                                        echo '<h4 class="fw-bold font-sans-serif" style="text-align: center;">關懷宣導項目</h4>';
                            
                                        echo "<form>";
                                        echo "<div class='form-container'>";
                                        // 關懷宣導項目
                                        echo '<div class="form-group">';
                                        echo "<label class='form-check-label' for='TrafficSafety'>交通安全: </label>";
                                        echo "<span>" . htmlspecialchars($row['TrafficSafety']) . "</span><br>";
                                        echo "</div>";
                                        echo '<div class="form-group">';
                                        echo "<label class='form-check-label' for='NoSmoking'>拒絕菸害: </label>";
                                        echo "<span>" . htmlspecialchars($row['NoSmoking']) . "</span><br>";
                                        echo "</div>";
                                        echo '<div class="form-group">';
                                        echo "<label class='form-check-label' for='NoDrugs'>拒絕毒品: </label>";
                                        echo "<span>" . htmlspecialchars($row['NoDrugs']) . "</span><br>";
                                        echo "</div>";
                                        echo '<div class="form-group">';
                                        echo "<label class='form-check-label' for='DenguePrevention'>登革熱防治: </label>";
                                        echo "<span>" . htmlspecialchars($row['DenguePrevention']) . "</span><br>";
                                        echo "</div>";
                                        echo '<div class="form-group">';
                                        echo "<label class='form-check-label' for='Other'>其他說明: </label>";
                                        echo "<span>" . htmlspecialchars($row['Other']) . "</span><br>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</form>";


                                        echo "</div>";
                                        echo "</div>";


                                    }


                                    
                                } else {
                                    // 若沒有資料，顯示沒有找到資料的訊息
                                    echo "沒有找到相關資料";
                                }
                                
                               
                                // 釋放結果集
                                mysqli_free_result($result);
                            } else {
                                // 查詢失敗，顯示錯誤訊息
                                echo "查詢失敗：" . mysqli_error($conn);
                            }

                            // 關閉資料庫連接
                            mysqli_close($conn);
                            ?>

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