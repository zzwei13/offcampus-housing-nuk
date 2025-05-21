<?php
session_start(); // 啟動會話

// 檢查會話中是否存在有效的登入信息
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit;
}

// 包含資料庫連接文件
include 'connect.php';
$Level = $_SESSION['permissionLevel'];
$name = $_SESSION['username'];;


// 獲取 URL 中的參數
$permissionLevel = isset($_GET['PermissionLevel']) ? $_GET['PermissionLevel'] : '';
$userID = isset($_GET['UserID']) ? $_GET['UserID'] : '';
if ($Level !== "管理員") {
    echo "您沒有管理員權限訪問此頁面。";
    exit;
}

// 根據不同的權限等級從不同的表中檢索用戶資料
function getUserData($conn, $userID, $table, $idField) {
    $sql = "SELECT * FROM $table WHERE $idField = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID); // 修改為 "s" 來綁定 VARCHAR 類型的參數
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();
    return $userData;
}

// 根據 UserID 獲取用戶資料
$userData = null;
switch ($permissionLevel) {
    case '學生':
        $userData = getUserData($conn, $userID, 'students', 'StudentID');
        break;
    case '導師':
        $userData = getUserData($conn, $userID, 'teachers', 'TeacherID');
        break;
    case '房東':
        $userData = getUserData($conn, $userID, 'landlords', 'LandlordID');
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
    <title>基本資料 | 租屋管理系統SAMS</title>
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
    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block"
            data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container"><a class="navbar-brand" href="management.php"><img src="static/picture/nuk.png" height="45"
                        alt="logo"></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">
                    </span></button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                        <li class="nav-item px-2"><a class="nav-link active" aria-current="page" href="management.php">管理員主頁</a></li>
 
                        
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($Level); ?> <?php echo htmlspecialchars($name); ?></a>
                        </li>
                    </ul>
                    <div id="userStatus" class="ms-lg-2">
                        <a class="btn btn-primary" href="logout.php" role="button">登出</a>
                    </div>                 
                </div>
            </div>
    </nav>
    <section class="pt-6 bg-600" id="view-user-info">   
        <div class="container mt-4">
            <?php if ($userData): ?>
                <?php switch ($permissionLevel):
                    case '學生': ?>
                        <h1>學生的資料畫面</h1>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>項目</th>
                                        <th>內容</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>學號</td><td><?= htmlspecialchars($userData['StudentID']) ?></td></tr>
                                    <tr><td>姓名</td><td><?= htmlspecialchars($userData['Name']) ?></td></tr>
                                    <tr><td>系級</td><td><?= htmlspecialchars($userData['Department']) ?></td></tr>
                                    <tr><td>年級</td><td><?= htmlspecialchars($userData['Grade']) ?></td></tr>
                                    <tr><td>性別</td><td><?= htmlspecialchars($userData['Gender']) ?></td></tr>
                                    <tr><td>導師ID</td><td><?= htmlspecialchars($userData['AdvisorID']) ?></td></tr>
                                    <tr><td>聯絡電話</td><td><?= htmlspecialchars($userData['ContactPhone']) ?></td></tr>
                                    <tr><td>Email</td><td><?= htmlspecialchars($userData['S_Email']) ?></td></tr>
                                    <tr><td>家庭地址</td><td><?= htmlspecialchars($userData['HomeAddress']) ?></td></tr>
                                    <tr><td>家用電話</td><td><?= htmlspecialchars($userData['HomePhone']) ?></td></tr>
                                    <tr><td>緊急聯絡人姓名</td><td><?= htmlspecialchars($userData['EmergencyContactName']) ?></td></tr>
                                    <tr><td>緊急聯絡人電話</td><td><?= htmlspecialchars($userData['EmergencyContactPhone']) ?></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <?php break;
                    case '導師': ?>
                        <h1>導師的資料畫面</h1>
                        <div class="table-responsive">
                            <table class="table table-bordered">
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
                        <?php break;
                    case '房東': ?>
                        <h1>房東的資料畫面</h1>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>項目</th>
                                        <th>內容</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>房東ID</td><td><?= htmlspecialchars($userData['LandlordID']) ?></td></tr>
                                    <tr><td>姓名</td><td><?= htmlspecialchars($userData['Name']) ?></td></tr>
                                    <tr><td>聯絡電話</td><td><?= htmlspecialchars($userData['ContactPhone']) ?></td></tr>
                                    <tr><td>Email</td><td><?= htmlspecialchars($userData['L_Email']) ?></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <?php break;
                    default: ?>
                        <h1>未知的權限等級</h1>
                        <p>請提供有效的權限等級。</p>
                <?php endswitch; ?>
            <?php else: ?>
                <p>使用者ID無效或使用者不存在。</p>
            <?php endif; ?>
            <!-- 返回上一頁按鈕 -->
            <div class="mt-4">
                <button class="btn btn-secondary" onclick="window.location.href='user_pro_list.php'">回上一頁</button>
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
<?php $conn->close(); ?>
