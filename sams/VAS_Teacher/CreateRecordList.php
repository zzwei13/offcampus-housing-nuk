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
$UserID = $_SESSION['userid'] ;
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
        display: flex;
    }
    .btn-primary {
        min-width: 80px; /* 調整這個寬度以適應按鈕的內容 */
        white-space: nowrap; /* 禁止換行 */
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
    <title>訪視管理-新增</title>

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

        <section class=" pt-6 pb-5 center-section bg-600 ">
            <h2 class="fw-bold font-sans-serif text-center">新增訪視紀錄表單</h2>
        </section>
        <section class="center-section bg-600 pt-1 pb-5 ">
            
            <form id="backForm" action="VisitRecordManage.php" method="get" style="margin-right: 20px;">
                <button type="submit" form="backForm" class="btn btn-secondary"> < </button>
            </form>

            <form class="d-flex my-3 d-block custom-search" action="CreateRecordList.php" method="GET">
                <input class="form-control me-2" type="search" name="StudentID" placeholder="輸入學生學號" aria-label="Search">
                <select class="form-control me-2" name="year">
                    <option value="">選擇學年</option>
                    <!-- 這裡會插入來自合併資料表的學年選項 -->
                    <?php
                    include 'connect.php';
                    $query = "
                        SELECT DISTINCT sa.AcademicYear as year
                        FROM StudentAccommodation sa
                        LEFT JOIN VisitRecord vr ON sa.AccommodationID = vr.AccommodationID
                        WHERE vr.AccommodationID IS NULL
                        ORDER BY year DESC
                    ";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['year']."'>".$row['year']."</option>";
                    }
                    ?>
                </select>
                <select class="form-control me-2" name="semester">
                    <option value="">選擇學期</option>
                    <!-- 這裡會插入來自合併資料表的學期選項 -->
                    <?php
                    include 'connect.php';

                    $query = "
                        SELECT DISTINCT sa.Semester as semester
                        FROM StudentAccommodation sa
                        LEFT JOIN VisitRecord vr ON sa.AccommodationID = vr.AccommodationID
                        WHERE vr.AccommodationID IS NULL
                        ORDER BY semester DESC
                    ";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['semester']."'>第 ".$row['semester']." 學期</option>";
                    }
                    ?>
                </select>
                <button class="btn btn-primary" type="submit">查詢</button>
            </form>
        </section>

        <section class="pt-2 bg-600" id="home">
            <div class="justify-content-center align-items-center min-vh-100">
                <div class="container">
                    <div class="row center-section">
                        <!--div class="col-md-5 col-lg-6 order-0 order-md-1 text-end"--></div>
                        <div class="col-md-9 col-lg-12 text-md-start text-center py-4">
                            
                            <br>
                            <form>
                                <div class="form-container">
                                    <!---->
                                    <?php
                                    // 包含資料庫連接的程式碼
                                    include 'connect.php';

                                    // 獲取 URL 中的 StudentID, year 和 semester
                                    $StudentID = isset($_GET['StudentID']) ? $_GET['StudentID'] : '';
                                    $AcademicYear = isset($_GET['AcademicYear']) ? $_GET['AcademicYear'] : '';
                                    $Semester = isset($_GET['Semester']) ? $_GET['Semester'] : '';

                                    // 檢查 studentIdName 是否為空
                                    if (empty($StudentID)) {
                                        echo '<div style="text-align: center">請至少輸入查詢對象之學號!</div>';
                                        exit;
                                    }

                                    //
                                    $UserID = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
                                    //echo "UserID: $UserID";
                                    // 查询 StudentID 的 AdvisorID
                                    $sql0 = "SELECT AdvisorID FROM students WHERE StudentID = ?";
                                    $stmt = $conn->prepare($sql0);
                                    $stmt->bind_param("s", $StudentID);
                                    $stmt->execute();
                                    $stmt->bind_result($AdvisorID);
                                    $stmt->fetch();
                                    $stmt->close();
                                    if ($AdvisorID == $UserID) {
                                        // 权限匹配，继续处理你的逻辑
                                        //echo '<div style="text-align: center;">權限匹配，以下查看該學生的資料。</div>';
                                        // 在这里添加你的处理逻辑，例如查询 StudentAccommodation 和 VisitRecord 表等
                                        //查詢StudentID在StudentAccommodation表中存在，但在VisitRecord表中不存在相同的AccommodationID的紀錄
                                        $sql = "SELECT sa.StudentID, sa.AcademicYear, sa.Semester
                                        FROM StudentAccommodation sa
                                        LEFT JOIN VisitRecord vr ON sa.AccommodationID = vr.AccommodationID
                                        WHERE vr.AccommodationID IS NULL";

                                        // 添加條件子句
                                        $conditions = [];
                                        $params = [];
                                        $types = '';

                                        if (!empty($StudentID)) {
                                            $conditions[] = "sa.StudentID LIKE ?";
                                            $params[] = "%" . $StudentID . "%"; // 使用 LIKE 和通配符
                                            $types .= 's';
                                        }
                                
                                        if (!empty( $AcademicYear)) {
                                            $conditions[] = "sa.AcademicYear = ?";
                                            $params[] =  $AcademicYear;
                                            $types .= 's';
                                        }
                                
                                        if (!empty($Semester)) {
                                            $conditions[] = "sa.Semester = ?";
                                            $params[] = $Semester;
                                            $types .= 's';
                                        }
                                
                                        if (!empty($conditions)) {
                                            $sql .= " AND " . implode(" AND ", $conditions);
                                        }
                                
                                        $sql .= " LIMIT 15"; // 每頁最多顯示 15 筆
                                
                                        // 準備和執行 SQL 語句
                                        $stmt = $conn->prepare($sql);

                                        // 檢查 prepare 是否成功
                                        if ($stmt === false) {
                                            echo "Prepare statement failed: " . $conn->error;
                                            exit;
                                        }

                                        // 綁定參數
                                        if (!empty($params)) {
                                            $stmt->bind_param($types, ...$params);
                                        }

                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        // 檢查查詢結果
                                        if ($result->num_rows > 0) {
                                            echo "<div class='records-container'>";

                                            // 輸出每條記錄
                                            while ($row = $result->fetch_assoc()) {

                                                $StudentID = urlencode($row['StudentID']);
                                                $AcademicYear = urlencode($row['AcademicYear']);
                                                $Semester = urlencode($row['Semester']);

                                                echo "<a href='CreateRecord.php?StudentID=$StudentID&AcademicYear=$AcademicYear&Semester=$Semester ' class='record-item'>";
                                                echo "<div class='record-details'>";
                                                echo "<strong>學號:</strong> " . $row['StudentID'] . "<br>";
                                                echo "<strong>學年:</strong> " . $row['AcademicYear'] . "<br>";
                                                echo "<strong>學期:</strong> " . $row['Semester'] . "<br>";
                                                echo "</div>";
                                                echo "</a>";
                                            }

                                            echo "</div>";
                                        } else {
                                            echo "<script>
                                                if (confirm('訪視紀錄無法重複新增，是否前往\"訪視紀錄列表\"查詢？')) {
                                                    window.location.href = 'RecordList.php';
                                                } else {
                                                    window.location.href = 'CreateRecordList.php';
                                                }
                                            </script>";
                                        }
                                        // 關閉連接
                                        $stmt->close();
                                        
                                    } else {
                                        // 权限不匹配，提示用户重新输入
                                        echo '<div style="text-align: center">您不具查看該學生的權限，請重新輸入學號。</div>';
                                    }
                                    
                                    
                                    
                                    $conn->close();
                                    ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

  
    </main><!-- ===============================================-->
 
    <script src="static/js/popper.min.js"></script>
    <script src="static/js/bootstrap.min.js"></script>
    <script src="static/js/is.min.js"></script>
    <script src="static/js/polyfill.min.js"></script>
    <script src="static/js/all.min.js"></script>
    <script src="static/js/theme.js"></script>
    <link href="static/css/css2.css" rel="stylesheet">
</body>

</html>