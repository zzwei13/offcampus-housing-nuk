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



<!-- form都調用資料庫資料提供系統管理員"查詢"並"檢視" -->
<!DOCTYPE html>


<html lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>訪視管理-查詢</title>

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
        function goBack() {
            document.getElementById('backForm').submit();
        }
    </script>
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
                            <a class="nav-link" aria-current="page" href="TeacherProfile.php?">基本資料</a>
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
            <h2 class="fw-bold font-sans-serif text-center">訪視紀錄列表</h2>
        </section>
        <section class="center-section bg-600 pt-1 pb-5 ">
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
                .custom-select {
                    width: 200px; /* 自定義選單寬度 */
                    margin-right: 10px; /* Margin between select and input */
                }
                .btn-outline-primary {
                    width: 190px; /* 自定義按鈕寬度 */
                }
            </style>
            <form id="backForm" action="VisitRecordManage.php" method="get" style="margin-right: 20px;">
                <button type="submit" form="backForm" class="btn btn-secondary"> < </button>
            </form>
            <form class="d-flex my-3 d-block custom-search" action="RecordList.php" method="GET">
                
                <input class="form-control me-2" type="search" name="StudentID" placeholder="輸入學生學號" aria-label="Search">
                
                <select class="form-control me-2" name="year">
                    <option value="">選擇學年</option>
                    <!-- 這裡會插入來自合併資料表的學年選項 -->
                    <?php
                    include 'connect.php';
                    $query = "
                        SELECT DISTINCT sa.AcademicYear as year
                        FROM VisitRecord vr
                        JOIN StudentAccommodation sa ON vr.AccommodationID = sa.AccommodationID
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
                    <option value="1">第一學期</option>
                    <option value="2">第二學期</option>
                </select>
                <button class="btn btn-primary" type="submit" >查詢</button>
            </form>

        </section>

        <section class="pt-0 pb-2 bg-600" id="home">
            <div class="justify-content-center align-items-center min-vh-100">
                <div class="container">
                    <div class="row center-section">
                        <!--div class="col-md-5 col-lg-6 order-0 order-md-1 text-end"--></div>
                        <div class="col-md-9 col-lg-12 text-md-start text-center py-4">

                            <form>
                                <div class="form-container">
                                    <!---->
                                    <?php
                                    // 包含資料庫連接的程式碼
                                    include 'connect.php';

                                    // 獲取 URL 中的 StudentID, year 和 semester
                                    $StudentID = isset($_GET['StudentID']) ? $_GET['StudentID'] : '';
                                    $year = isset($_GET['year']) ? $_GET['year'] : '';
                                    $Semester = isset($_GET['semester']) ? $_GET['semester'] : '';
                                    
                                    // 檢查 studentIdName 是否為空
                                    if (empty($StudentID)) {
                                        echo '<div style="text-align: center;">請至少輸入查詢對象之學號!</div>';
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
                                        // echo '<div style="text-align: center;">權限匹配，以下查看該學生的資料。</div>';
                                        // 設置基礎的 SQL 查詢語句
                                        $sql = "SELECT vr.VisitID , sa.AcademicYear , sa.Semester , vr.VisitDate , vr.StudentID   
                                        FROM VisitRecord vr
                                        JOIN Students s ON vr.StudentID = s.StudentID
                                        JOIN Teachers t ON vr.TeacherID = t.TeacherID
                                        JOIN StudentAccommodation sa ON vr.AccommodationID = sa.AccommodationID
                                        
                                        WHERE 1=1";

                                        // 添加根據學生學號的條件
                                        if (!empty($StudentID)) {
                                            $sql .= " AND vr.StudentID  LIKE ?";
                                        }

                                        // 添加根據年份的條件
                                        if (!empty($year)) {
                                            $sql .= " AND sa.AcademicYear = ?";
                                        }

                                        // 添加根據學期的條件
                                        if (!empty($Semester)) {
                                            $sql .= " AND sa.Semester = ?";
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
                                        if (!empty($StudentID) && !empty($year) && !empty($Semester)) {
                                            $stmt->bind_param("sss", $StudentID, $year, $Semester);
                                        } elseif (!empty($StudentID) && !empty($year)) {
                                            $stmt->bind_param("ss", $StudentID, $year);
                                        } elseif (!empty($StudentID) && !empty($Semester)) {
                                            $stmt->bind_param("ss", $StudentID, $Semester);
                                        } elseif (!empty($year) && !empty($Semester)) {
                                            $stmt->bind_param("ss", $year, $Semester);
                                        } elseif (!empty($StudentID)) {
                                            $stmt->bind_param("s", $StudentID);
                                        } elseif (!empty($year)) {
                                            $stmt->bind_param("s", $year);
                                        } elseif (!empty($Semester)) {
                                            $stmt->bind_param("s", $Semester);
                                        }
                                        
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        // 檢查查詢結果
                                        if ($result->num_rows > 0) {
                                            echo "<div class='records-container'>";
                                            
                                            // 輸出每條紀錄
                                            while ($row = $result->fetch_assoc()) {
                                                //$vid = urlencode($row['vid']);
                                                $VisitID = urlencode($row['VisitID']);
                                                $StudentID = urlencode($row['StudentID']);
                                                $VisitDate = urlencode($row['VisitDate']);
                                                $AcademicYear = urlencode($row['AcademicYear']);
                                                $Semester = urlencode($row['Semester']);
                                                
                                                echo "<a href='QueryViewRecord.php?VisitID=$VisitID' class='record-item'>";
                                                echo "<div class='record-details'>";
                                                echo "<strong>訪視紀錄編號:</strong> " . $row['VisitID'] . "<br>";
                                                echo "<strong>學號:</strong> " . $row['StudentID'] . "<br>";
                                                echo "<strong>訪視日期:</strong> " . $row['VisitDate'] . "<br>";
                                                echo "<strong>學年度:</strong> " . $row['AcademicYear'] . "<br>";
                                                echo "<strong>學期:</strong> " . $row['Semester'];
                                                echo "</div>";
                                                echo "</a>";

                                            }
                                            
                                            echo "</div>";
                                        } else {
                                        
                                            echo'<div style="text-align: center;">查無訪視紀錄</div>';
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