<?php
session_start();
// 檢查是否已登入且用戶為導師
if (!isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] != '導師') {
    // 如果不是導師，則導向到主頁面或登入頁面
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username']; // 從會話中獲取用戶名
$PermissionLevel = $_SESSION['permissionLevel']; 
$TeacherName = $_SESSION['teacherName'];
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
</style>

<html lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title> 訪視管理-修改</title>

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

        <section class="pt-2 bg-600" id="home">
            <div class="d-flex justify-content-center align-items-center min-vh-100">
                <div class="container">
                    <div class="row center-section">
                        <!--div class="col-md-5 col-lg-6 order-0 order-md-1 text-end"--></div>
                        <div class="col-md-9 col-lg-12 text-md-start text-center py-4">
                        
                            <h2 class="fw-bold font-sans-serif text-center">訪視紀錄</h2>
                            <br>
                            
                            <?php
                            // 包含資料庫連接文件
                            include_once 'connect.php';

                            // 獲取 URL 中的 VisitID, StudentID 和 VisitDate
                            $VisitID = isset($_GET['VisitID']) ? $_GET['VisitID'] : '';
                            
                            if (empty($VisitID)) {
                                die("訪問ID缺失");
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

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                            ?>
                            <script>
                            var originalData = {
                                DepositRequirement: '<?php echo $row['DepositRequirement']; ?>',
                                UtilityRequirement: '<?php echo $row['UtilityRequirement']; ?>',
                                LivingEnvironment: '<?php echo $row['LivingEnvironment']; ?>',
                                LivingFacilities: '<?php echo $row['LivingFacilities']; ?>',
                                VisitStatus: '<?php echo $row['VisitStatus']; ?>',
                                HostGuestInteraction: '<?php echo $row['HostGuestInteraction']; ?>',
                                GoodCondition: '<?php echo $row['GoodCondition']; ?>',
                                ContactParents: '<?php echo $row['ContactParents']; ?>',
                                AssistanceNeeded: '<?php echo $row['AssistanceNeeded']; ?>',
                                AdditionalNotes: '<?php echo htmlspecialchars($row['AdditionalNotes']); ?>',
                                TrafficSafety: '<?php echo $row['TrafficSafety']; ?>',
                                NoSmoking: '<?php echo $row['NoSmoking']; ?>',
                                NoDrugs: '<?php echo $row['NoDrugs']; ?>',
                                DenguePrevention: '<?php echo $row['DenguePrevention']; ?>',
                                Other: '<?php echo htmlspecialchars($row['Other']); ?>'
                            };
                            </script>

                           <!--顯示每一行的資料-->
                            <div class="card" style="margin: 20px;">
                                <div class="card-body">
                                    <div class='form-group'><label>系級：</label> <?php echo $row['Department']; ?></div>
                                    <div class='form-group'><label>學號：</label> <?php echo $row['StudentID']; ?></div>
                                    <div class='form-group'><label>姓名：</label> <?php echo $row['StudentName']; ?></div>
                                    <div class='form-group'><label>連絡電話：</label> <?php echo $row['StudentContactPhone']; ?></div>
                                    <div class='form-group'><label>導師：</label> <?php echo $row['TeacherName']; ?></div>
                                    <div class='form-group'><label>訪視日期：</label> <?php echo $row['VisitDate']; ?></div>
                                </div>
                            </div>
                            <!------------------------------可以修改的內容---------------------------------------------->
                            <!--學生住宿資料可永表單方式顯示-->
                            <div class="card" style="margin: 20px;">
                                <div class="card-body">
                                    <form id="updateForm" method="post" action="update_data.php">
                                        <h4 class="fw-bold font-sans-serif" style="text-align: center;">環境及作息評估</h4>
                                        <div class='form-container'>
                                        <div class="form-group">
                                            <label>押金要求：</label>
                                            <span>
                                            <label><input type="radio" name="DepositRequirement" value="合理" <?php if ($row['DepositRequirement'] == '合理') echo 'checked'; ?>> 合理</label>
                                            <label><input type="radio" name="DepositRequirement" value="不合理" <?php if ($row['DepositRequirement'] == '不合理') echo 'checked'; ?>> 不合理</label>
                                            <span>
                                        </div>
                                        <div class="form-group">
                                            <label>水電費要求：</label>
                                            <span>
                                            <label><input type="radio" name="UtilityRequirement" value="合理" <?php if ($row['UtilityRequirement'] == '合理') echo 'checked'; ?>> 合理</label>
                                            <label><input type="radio" name="UtilityRequirement" value="不合理" <?php if ($row['UtilityRequirement'] == '不合理') echo 'checked'; ?>> 不合理</label>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>居家環境：</label>
                                            <span>
                                            <label><input type="radio" name="LivingEnvironment" value="佳" <?php if ($row['LivingEnvironment'] == '佳') echo 'checked'; ?>> 佳</label>
                                            <label><input type="radio" name="LivingEnvironment" value="適中" <?php if ($row['LivingEnvironment'] == '適中') echo 'checked'; ?>> 適中</label>
                                            <label><input type="radio" name="LivingEnvironment" value="欠佳" <?php if ($row['LivingEnvironment'] == '欠佳') echo 'checked'; ?>> 欠佳</label>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>生活設施：</label>
                                            <span>
                                            <label><input type="radio" name="LivingFacilities" value="佳" <?php if ($row['LivingFacilities'] == '佳') echo 'checked'; ?>> 佳</label>
                                            <label><input type="radio" name="LivingFacilities" value="適中" <?php if ($row['LivingFacilities'] == '適中') echo 'checked'; ?>> 適中</label>
                                            <label><input type="radio" name="LivingFacilities" value="欠佳" <?php if ($row['LivingFacilities'] == '欠佳') echo 'checked'; ?>> 欠佳</label>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>訪視現況：</label>
                                            <span>
                                            <label><input type="radio" name="VisitStatus" value="生活規律" <?php if ($row['VisitStatus'] == '生活規律') echo 'checked'; ?>> 生活規律</label>
                                            <label><input type="radio" name="VisitStatus" value="適中" <?php if ($row['VisitStatus'] == '適中') echo 'checked'; ?>> 適中</label>
                                            <label><input type="radio" name="VisitStatus" value="待加強" <?php if ($row['VisitStatus'] == '待加強') echo 'checked'; ?>> 待加強</label>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label>主客相處：</label>
                                            <span>
                                            <label><input type="radio" name="HostGuestInteraction" value="和睦" <?php if ($row['HostGuestInteraction'] == '和睦') echo 'checked'; ?>> 和睦</label>
                                            <label><input type="radio" name="HostGuestInteraction" value="欠佳" <?php if ($row['HostGuestInteraction'] == '欠佳') echo 'checked'; ?>> 欠佳</label>
                                            </span>
                                        </div>

                                        </div>

                                        <br><hr>
                                        <h4 class="fw-bold font-sans-serif" style="text-align: center;">訪視結果</h4>
                                        <div class='form-container'>
                                        <div>
                                            <label><input type="checkbox" id="GoodCondition" name="GoodCondition" <?php if ($row['GoodCondition'] == '是') echo 'checked'; ?>> 整體賃居狀況良好</label><br>
                                            <label><input type="checkbox" id="ContactParents" name="ContactParents" <?php if ($row['ContactParents'] == '是') echo 'checked'; ?>> 聯繫家長關注</label><br>
                                            <label><input type="checkbox" id="AssistanceNeeded" name="AssistanceNeeded" <?php if ($row['AssistanceNeeded'] == '是') echo 'checked'; ?>> 安全堪四慮請協助</label><br>
                                            <label>其他記載或建議事項：<br><textarea class="form-control" id="AdditionalNotes" name="AdditionalNotes"><?php echo htmlspecialchars($row['AdditionalNotes']); ?></textarea></label>
                                        </div>
                                        </div>
                                        <br><hr>
                                        <h4 class="fw-bold font-sans-serif" style="text-align: center;">關懷宣導項目</h4>
                                        <div class='form-container'>
                                        <div>
                                            <label><input type="checkbox" id="TrafficSafety" name="TrafficSafety" <?php if ($row['TrafficSafety'] == '是') echo 'checked'; ?>> 交通安全</label><br>
                                            <label><input type="checkbox" id="NoSmoking" name="NoSmoking" <?php if ($row['NoSmoking'] == '是') echo 'checked'; ?>> 拒絕菸害</label><br>
                                            <label><input type="checkbox" id="NoDrugs" name="NoDrugs" <?php if ($row['NoDrugs'] == '是') echo 'checked'; ?>> 拒絕毒品</label><br>
                                            <label><input type="checkbox" id="DenguePrevention" name="DenguePrevention" <?php if ($row['DenguePrevention'] == '是') echo 'checked'; ?>> 登革熱防治</label><br>
                                            <label>其他:<br><textarea class="form-control" id="Other" name="Other"><?php echo htmlspecialchars($row['Other']); ?></textarea></label>
                                        </div>
                                        </div>
                                        
                                        <input type="hidden" name="VisitID" value="<?php echo $VisitID; ?>">
                                        <button type="button" onclick="checkForChanges()" class="btn btn-primary mt-3">提交</button>
                                        <button type="button" id="cancelButton" class="btn btn-primary mt-3">取消修改</button>
                                        <script>
                                            // JavaScript to handle the cancel button click event
                                            document.getElementById('cancelButton').addEventListener('click', function() {
                                                // Redirect to QueryViewRecord.php with VisitID parameter
                                                window.location.href = 'QueryViewRecord.php?VisitID=<?php echo $VisitID; ?>';
                                            });
                                        </script>

                                    </form>
                                    </div>
                            </div>
                            
                            <script>
                
                            function checkForChanges() {
                                    var currentData = {
                                        DepositRequirement: document.querySelector('input[name="DepositRequirement"]:checked') ? document.querySelector('input[name="DepositRequirement"]:checked').value : '',
                                        UtilityRequirement: document.querySelector('input[name="UtilityRequirement"]:checked') ? document.querySelector('input[name="UtilityRequirement"]:checked').value : '',
                                        LivingEnvironment: document.querySelector('input[name="LivingEnvironment"]:checked') ? document.querySelector('input[name="LivingEnvironment"]:checked').value : '',
                                        LivingFacilities: document.querySelector('input[name="LivingFacilities"]:checked') ? document.querySelector('input[name="LivingFacilities"]:checked').value : '',
                                        VisitStatus: document.querySelector('input[name="VisitStatus"]:checked') ? document.querySelector('input[name="VisitStatus"]:checked').value : '',
                                        HostGuestInteraction: document.querySelector('input[name="HostGuestInteraction"]:checked') ? document.querySelector('input[name="HostGuestInteraction"]:checked').value : '',
                                        GoodCondition: document.getElementById('GoodCondition').checked ? '是' : '否',
                                        ContactParents: document.getElementById('ContactParents').checked ? '是' : '否',
                                        AssistanceNeeded: document.getElementById('AssistanceNeeded').checked ? '是' : '否',
                                        AdditionalNotes: document.getElementById('AdditionalNotes').value,
                                        TrafficSafety: document.getElementById('TrafficSafety').checked ? '是' : '否',
                                        NoSmoking: document.getElementById('NoSmoking').checked ? '是' : '否',
                                        NoDrugs: document.getElementById('NoDrugs').checked ? '是' : '否',
                                        DenguePrevention: document.getElementById('DenguePrevention').checked ? '是' : '否',
                                        Other: document.getElementById('Other').value
                                    };

                                    var requiredFields = ['VisitID', 'DepositRequirement', 'UtilityRequirement', 'LivingEnvironment', 'LivingFacilities', 'VisitStatus', 'HostGuestInteraction'];
                                    var changesDetected = false;
                                    var form = document.getElementById('updateForm');
                                    //
                                    var allRequiredFieldsFilled = true;

                                    requiredFields.forEach(function (field) {
                                        if (currentData[field] === '') {
                                            allRequiredFieldsFilled = false;
                                        }
                                    });

                                    if (!allRequiredFieldsFilled) {
                                        alert('必填字段必須填寫！');
                                        return;
                                    }

                                    for (var key in currentData) {
                                        if (originalData[key] !== currentData[key]) {
                                            changesDetected = true;
                                            var hiddenInput = document.createElement('input');
                                            hiddenInput.type = 'hidden';
                                            hiddenInput.name = key;
                                            hiddenInput.value = currentData[key];
                                            form.appendChild(hiddenInput);
                                        }
                                    }

                                    /*for (var key in originalData) {
                                        if (originalData[key] !== currentData[key]) {
                                            changesDetected = true;
                                            var hiddenInput = document.createElement('input');
                                            hiddenInput.type = 'hidden';
                                            hiddenInput.name = key;
                                            hiddenInput.value = currentData[key];
                                            form.appendChild(hiddenInput);
                                        }
                                    }*/

                                    // Explicitly set values for checkboxes to ensure they are always submitted
                                    ['GoodCondition', 'ContactParents', 'AssistanceNeeded', 'TrafficSafety', 'NoSmoking', 'NoDrugs', 'DenguePrevention'].forEach(function (id) {
                                        var hiddenInput = document.createElement('input');
                                        hiddenInput.type = 'hidden';
                                        hiddenInput.name = id;
                                        hiddenInput.value = document.getElementById(id).checked ? '是' : '否';
                                        form.appendChild(hiddenInput);
                                    })

                                    if (changesDetected) {
                                        form.submit();
                                    } else {
                                        alert('沒有檢測到修改！');
                                    }
                            }
                             
                            </script>

                            <?php
                            } else {
                                echo '未找到記錄。';
                            }
                             // 關閉資料庫連接
                            $stmt->close();
                            $conn->close();
                            ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
       
    </main><!-- ===============================================-->

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