<?php
session_start(); // 啟動會話

// 檢查會話中是否存在有效的登入信息
if (!isset($_SESSION['username']) || !isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] != '導師') {
    header('Location: login.html');
    exit;
}
// 從會話中獲取用戶名、權限等信息
$Username = $_SESSION['username'];

$PermissionLevel = $_SESSION['permissionLevel'];
$UserID = $_SESSION['userid'];
$TeacherName = $_SESSION['teacherName'];

// 包含資料庫連接文件
include 'connect.php';

// 根據 UserID 獲取用戶資料
function getUserData($conn, $userID) {
    $sql = "SELECT * FROM teachers WHERE TeacherID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();
    return $userData;
}
switch ($PermissionLevel) {
    case '導師':
        $userData = getUserData($conn, $UserID);
        break;
    default:
        $conn->close();
        exit("<h1>未知的權限等級</h1><p>請提供有效的權限等級。</p>");
}

?>
<!DOCTYPE html>
<html lang="zh-Hant" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>基本資料</title>
    <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
    <link rel="manifest" href="static/image/manifest.json">
    <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
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

        <section class="pt-2 pb-11 bg-600" id="view-teacher-info">   
            <div class="pt-4 container mt-4">
                <?php if ($userData): ?>
                    <?php switch ($PermissionLevel):
                        case '導師': ?>
                            
                            <div class="mt-4 d-flex justify-content-center align-items-center"  style="margin-bottom: 20px;">       
                                <h1 class='fw-bold font-sans-serif text-center' style='flex: 0.6; margin-bottom: 20px;'>基本資料</h1> 
                            </div>
                            <div class="card" style="width: 60%; margin: 10px auto; display: flex; align-items: center; justify-content: center;">
                                <div class="card-body" style="width: 100%;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-width-custom">
                                            <style>
                                            .table-width-custom {
                                                width: 80%; /* 根據您的需求調整寬度百分比或具體數值 */
                                                margin: 0 auto; /* 讓表格置中顯示 */
                                            }
                                            .table-width-custom th,
                                            .table-width-custom td {
                                                text-align: center; /* Apply text alignment to both table header and data cells */
                                            }
                                            </style>
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>項目</th>
                                                    <th>內容</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td>導師ID</td><td><?= htmlspecialchars($userData['TeacherID']) ?></td></tr>
                                                <tr><td>姓名</td><td><?= htmlspecialchars($userData['Name']) ?></td></tr>
                                                <tr><td>職位</td><td><?= htmlspecialchars($userData['Rank']) ?></td></tr>
                                                <tr><td>聯絡電話</td><td><?= htmlspecialchars($userData['ContactPhone']) ?></td></tr>
                                                <tr><td>Email</td><td><?= htmlspecialchars($userData['T_Email']) ?></td></tr>
                                                <tr><td>辦公室地址</td><td><?= htmlspecialchars($userData['OfficeAddress']) ?></td></tr>
                                                <tr><td>辦公室電話</td><td><?= htmlspecialchars($userData['OfficePhone']) ?></td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-center align-items-center" style="margin-bottom: 20px;">
                                <button class="btn btn-primary" onclick="window.location.href='edit_view_user_pro.php?UserID=<?php echo htmlspecialchars($UserID); ?>'">修改基本資料</button>
                            </div>

                            <?php break;
                        default: ?>
                            <h1>未知的權限等級</h1>
                            <p>請提供有效的權限等級。</p>
                    <?php endswitch; ?>
                <?php else: ?>
                    <p>使用者ID無效或使用者不存在。</p>
                <?php endif; ?>

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
<?php $conn->close(); ?>
