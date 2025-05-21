
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
    /***************visitrecordlist-table*************/
    .records-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 20px;
    }

    .record-item {
        display: block;
        padding: 1rem;
        border: 1.5px solid #ccc;
        border-radius: 0.7rem;
        text-decoration: none;
        color: inherit;
        transition: background-color 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .record-item div {
        margin-bottom: 5px;
    }
    .record-item:hover {
        background-color: #f9f9f9;
    }
    .form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
    }
    .form-group label {
        flex: 1;
    }
    .form-group span {
        flex: 1;
        text-align: right;
    }

    .form-container {
        border: 2px solid #ccc; /* 邊框顏色和寬度 */
        border-radius: 15px;    /* 圓角半徑 */
        padding: 15px;          /* 內邊距 */
        margin: 10px 0;         /* 外邊距 */
    }
 
    
</style>
<script>
    function createRecord() {
        var form = document.getElementById('toCreateForm');
        var params = new URLSearchParams(new FormData(form)).toString();
        window.location.href = 'CreateRecordList.php?' + params;
    }
</script>
<html lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>新增訪視紀錄表單</title>

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
            <div class="justify-content-center align-items-center min-vh-100">
                <div class="container">
                    <div class="row center-section">
                        
                        <div class="col-md-9 col-lg-12 text-md-start text-center py-4">                    
                            <br>
                            <form  id="createForm" method="post" action="create_data.php">

                                    <?php
                                    // 包含資料庫連接的程式碼
                                    include 'connect.php';

                                    // 獲取 URL 中的 StudentID, year 和 semester
                                    $StudentID = isset($_GET['StudentID']) ? $_GET['StudentID'] : '';
                                    $AcademicYear = isset($_GET['AcademicYear']) ? $_GET['AcademicYear'] : '';
                                    $Semester = isset($_GET['Semester']) ? $_GET['Semester'] : '';

                                    // 檢查 StudentID, AcademicYear 和 Semester 是否為空
                                    if (empty($StudentID) || empty($AcademicYear) || empty($Semester)) {
                                        echo "StudentID, AcademicYear, and Semester are required.";
                                        exit;
                                    }
                                    
                                    // 由 'V' 串聯 URL 中的 StudentID, AcademicYear 和 Semester
                                    $VisitID = 'V' . $StudentID . $AcademicYear . $Semester;

                                    //INSERT 插入數據
                                    $insert_sql = "INSERT INTO VisitRecord (VisitID, AccommodationID, VisitDate, StudentID, TeacherID)
                                    SELECT ?, sa.AccommodationID, NULL AS VisitDate, sa.StudentID,  s.AdvisorID
                                    FROM StudentAccommodation sa
                                    JOIN Students s ON sa.StudentID = s.StudentID
                                    WHERE sa.StudentID = ? AND sa.AcademicYear = ? AND sa.Semester = ?";

                                    // 準備和執行 SQL 語句
                                    if ($stmt = $conn->prepare($insert_sql)) {
                                        $stmt->bind_param("ssss", $VisitID, $StudentID, $AcademicYear, $Semester);
                                        if ($stmt->execute()) {
                                            //echo "紀錄產生成功.";
                                        } else {
                                            echo "Error executing statement: " . $stmt->error;
                                        }
                                            $stmt->close();
                                    } else {
                                        echo "Error preparing statement: " . $conn->error;
                                    }

                                    
                                    // 查詢
                                    $student_info_sql = "
                                    SELECT 
                                        s.Department,
                                        s.Name AS StudentName,
                                        sa.Phone AS StudentContactPhone,
                                        t.Name AS TeacherName,
                                        sa.AccommodationID
                                    FROM StudentAccommodation sa
                                    JOIN Students s ON sa.StudentID = s.StudentID
                                    JOIN Teachers t ON s.AdvisorID = t.TeacherID
                                    WHERE sa.StudentID = ? AND sa.AcademicYear = ? AND sa.Semester = ?";
                        
                                    if ($stmt = $conn->prepare($student_info_sql)) {
                                        $stmt->bind_param("sss", $StudentID, $AcademicYear, $Semester);
                                        $stmt->execute();
                                        $stmt->bind_result($Department, $StudentName, $Phone, $AdvisorName, $AccommodationID);
                                        $stmt->fetch();
                                        $stmt->close();
                                    } else {
                                        echo "Error preparing statement: " . $conn->error;
                                    }
                            
                                    // 關閉資料庫連接
                                    $conn->close();
                                    ?>
                                    <div class="card" style="margin: 10px;">
                                        <div class="card-body">
                                            <form>
                                                <h4 class="fw-bold font-sans-serif text-center" >訪視紀錄表單 </h4>
                                                <br>
                                
                                                <div class="form-container">
                                                    <!-- 系級資訊區域 -->
                                                    <div class="form-group">
                                                        <label for="department">系級</label>
                                                        <span id="department" ><?php echo htmlspecialchars($Department); ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="studentID">學號</label>
                                                        <span id="studentID"><?php echo htmlspecialchars($StudentID); ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="studentName">姓名</label>
                                                        <span id="studentName"><?php echo htmlspecialchars($StudentName); ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phone">連絡電話</label>
                                                        <span id="phone"><?php echo htmlspecialchars($Phone); ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="advisor">導師</label>
                                                        <span  id="advisor"><?php echo htmlspecialchars($AdvisorName); ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="visitDate">訪視日期</label>
                                                        <input type="date" class="form-control" id="VisitDate" name="VisitDate" required style="width: 50%;">
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="form-container">
                                                    <?php
                                                    function renderRadioGroup($name, $options) {
                                                        echo '<div class="form-group">';
                                                        echo "<label>{$options['label']}</label><br>";
                                                        foreach ($options['choices'] as $value => $label) {
                                                            echo '<div class="form-check form-check-inline">';
                                                            echo "<input class=\"form-check-input\" type=\"radio\" name=\"{$name}\" value=\"{$value}\" required>";
                                                            echo "<label class=\"form-check-label\" for=\"{$name}_{$value}\">{$label}</label>";
                                                            echo '</div>';
                                                        }
                                                        echo '</div>';
                                                    }

                                                    $radioGroups = [
                                                        'DepositRequirement' => ['label' => '押金要求', 'choices' => ['合理' => '合理', '不合理' => '不合理']],
                                                        'UtilityRequirement' => ['label' => '水電費要求', 'choices' => ['合理' => '合理', '不合理' => '不合理']],
                                                        'LivingEnvironment' => ['label' => '居家環境', 'choices' => ['佳' => '佳', '適中' => '適中', '欠佳' => '欠佳']],
                                                        'LivingFacilities' => ['label' => '生活設施', 'choices' => ['佳' => '佳', '適中' => '適中', '欠佳' => '欠佳']],
                                                        'VisitStatus' => ['label' => '訪視現況', 'choices' => ['生活規律' => '生活規律', '適中' => '適中', '待加強' => '待加強']],
                                                        'HostGuestInteraction' => ['label' => '主客相處', 'choices' => ['和睦' => '和睦', '欠佳' => '欠佳']],
                                                    ];

                                                    foreach ($radioGroups as $name => $options) {
                                                        renderRadioGroup($name, $options);
                                                    }
                                                    ?>
                                                </div>
                                                <!-- Checkboxes for visit results -->
                                                <h4 class="fw-bold font-sans-serif">訪視結果</h4>
                                                <div class="form-container">
                                                    <div class="form-group">
                                                        <label><input type="checkbox" id="GoodCondition" name="GoodCondition"> 整體賃居狀況良好</label><br>
                                                        <label><input type="checkbox" id="ContactParents" name="ContactParents"> 聯繫家長關注</label><br>
                                                        <label><input type="checkbox" id="AssistanceNeeded" name="AssistanceNeeded"> 安全堪四慮請協助</label><br>
                                                        <label>其他記載或建議事項:<br>
                                                            <textarea class="form-control" id="AdditionalNotes" name="AdditionalNotes"></textarea>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Caring items -->
                                                <h4 class="fw-bold font-sans-serif">關懷宣導項目</h4>
                                                <div class="form-container">
                                                    <div class="form-group">
                                                        <label><input type="checkbox" id="TrafficSafety" name="TrafficSafety"> 交通安全</label><br>
                                                        <label><input type="checkbox" id="NoSmoking" name="NoSmoking"> 拒絕菸害</label><br>
                                                        <label><input type="checkbox" id="NoDrugs" name="NoDrugs"> 拒絕毒品</label><br>
                                                        <label><input type="checkbox" id="DenguePrevention" name="DenguePrevention"> 登革熱防治</label><br>
                                                        <label>其他:<br>
                                                            <textarea class="form-control" id="Other" name="Other"></textarea>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Pass VisitID -->
                                                <input type="hidden" name="VisitID" value="<?php echo htmlspecialchars($VisitID); ?>">
                                                <div class="d-flex justify-content-center mt-3">
                                                    <button class="btn btn-primary me-2" type="submit">提交</button>
                                                    <button class="btn btn-outline-primary" type="button" id="cancelButton">取消修改</button>
                                                </div>

                                                <script>
                                                    document.getElementById('cancelButton').addEventListener('click', function() {
                                                        if (confirm("確定要取消修改嗎？")) {
                                                            // 執行 AJAX 請求删除記錄
                                                            var xhr = new XMLHttpRequest();
                                                            xhr.open("POST", "delete_data.php", true);
                                                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                                            xhr.onreadystatechange = function() {
                                                                if (xhr.readyState == 4 && xhr.status == 200) {
                                                                    // 删除成功後將用戶重定向到指定頁面
                                                                    window.location.href = 'CreateRecordList.php?StudentID=<?php echo $StudentID; ?>&AcademicYear=<?php echo $AcademicYear; ?>&Semester=<?php echo $Semester; ?>';
                                                                }
                                                            };
                                                            // 發送包含要删除的 VisitID 的數據
                                                            xhr.send("VisitID=<?php echo $VisitID; ?>");
                                                        }
                                                    });
                                                </script>

                                            </form>
                                        </div>
                                    </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>



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