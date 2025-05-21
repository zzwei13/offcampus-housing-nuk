<?php
session_start(); // 啟動會話

// 檢查會話中是否存在有效的登入信息
if (!isset($_SESSION['username']) || !isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] != '導師') {
    header('Location: login.html');
    exit;
}
// 包含資料庫連接文件
include 'connect.php';

// 獲取 URL 中的參數
// 確保 $_GET['UserID'] 存在並且不為空
if (isset($_GET['UserID']) && !empty($_GET['UserID'])) {
    $UserID = $_GET['UserID'];
    
    // 這裡可以使用 $userID 來進行相應的資料庫查詢或其他操作
    //echo "UserID: " . htmlspecialchars($UserID);
} else {
    echo "未提供 UserID 參數。";
}

// 根據 UserID 獲取用戶資料
function getUserData($conn, $UserID) {
    $sql = "SELECT * FROM teachers WHERE TeacherID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $UserID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result === false) {
        die('get_result() failed: ' . htmlspecialchars($stmt->error));
    }
    $userData = $result->fetch_assoc();
    $stmt->close();
    return $userData;
}

// 檢查欄位是否允許為NULL
function checkFieldNullability($conn, $table, $field) {
    $sql = "SHOW COLUMNS FROM $table LIKE '$field'";
    $result = $conn->query($sql);
    $column = $result->fetch_assoc();
    return $column['Null'] === 'YES';
}

// 根據 UserID 獲取用戶資料
$username = $_SESSION['username']; // 從會話中獲取用戶名
$PermissionLevel = $_SESSION['permissionLevel']; 
$TeacherName = $_SESSION['teacherName'];

$userData = null;
if ($PermissionLevel === '導師') {
    $userData = getUserData($conn, $UserID);
    if ($userData === null) {
        die('No user data found for the given UserID.');
    }
}

$errorMsg = '';

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 獲取表單提交的數據
    $userID = $_POST['UserID'];
    $PermissionLevel = $_POST['PermissionLevel'];

    // 構建更新語句和參數
    $sql = "";
    $params = [];
    $types = "";

    if ($PermissionLevel === '導師') {
        $fields = ['Name', 'Rank', 'ContactPhone', 'T_Email', 'OfficeAddress', 'OfficePhone'];
        foreach ($fields as $field) {
            $value = isset($_POST[$field]) ? $_POST[$field] : '';
            if ($value === '' && !checkFieldNullability($conn, 'teachers', $field)) {
                $errorMsg = "欄位 $field 不能為空。";
                break;
            }
            $params[] = $value !== '' ? $value : null;
            $types .= "s";
        }
        if (empty($errorMsg)) {
            $params[] = $UserID;
            $types .= "s";
            $sql = "UPDATE teachers SET Name=?, Rank=?, ContactPhone=?, T_Email=?, OfficeAddress=?, OfficePhone=? WHERE TeacherID=?";
        }
    } else {
        $errorMsg = "未知的權限等級。";
    }

    if (empty($errorMsg)) {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param($types, ...$params);

        // 執行更新並檢查結果
        if ($stmt->execute()) {
            // 更新 session 中的 teacherName
            $_SESSION['teacherName'] = $_POST['Name'];

            echo "<script>
                alert('資料已成功更新。');
                window.location.href = 'TeacherProfile.php';
            </script>";
            exit;
        } else {
            echo "<p>更新資料時出錯: " . htmlspecialchars($stmt->error) . "</p>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>編輯基本資料</title>
  <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
  <link rel="manifest" href="static/image/manifest.json">
  <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
  <meta name="theme-color" content="#ffffff">
  <link href="static/css/theme.min.css" rel="stylesheet">

  <style>
    body {
        background-color: #f8f9fa; /* 藍色 */
    }
    .menu {
      margin-top: 20px;
    }
    .menu ul {
      list-style: none;
      padding: 0;
    }
    .menu ul li {
      margin-bottom: 10px;
    }
    .menu ul li a {
      text-decoration: none;
      color: #fff;
      background-color: #220088; /* 深藍色 */
      padding: 10px;
      display: block;
      border: 1px solid #220088; /* 深藍色 */
      border-radius: 5px;
      text-align: center;
    }
    .menu ul li a:hover {
      background-color: #ffc107; /* 黃色 */
      border-color: #ffc107; /* 黃色 */
      color: #220088; /* 深藍色 */
    }
  </style>
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
            <div class="d-flex justify-content-center  min-vh-100">
                <div class="container mt-4">
                   
                    <?php if (!empty($errorMsg)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($errorMsg) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($userData): ?>
                    
                            <form method="POST" action="">
                          
                                <a href="TeacherProfile.php" style="display: inline-block; margin-right: 685px; margin-bottom: 45px; text-decoration: none;">
                                        <div style="font-size: 24px;">&lt;</div>
                                    </a>
                                    <h1 class='fw-bold font-sans-serif 'style="text-align: center;margin-bottom:20px;">編輯基本資料</h1>
                                <input type="hidden" name="PermissionLevel" value="<?= htmlspecialchars($PermissionLevel) ?>"> 
                                <input type="hidden" name="UserID" value="<?= htmlspecialchars($UserID) ?>"> 
                                <div class="card" style="width: 60%; margin: 10px auto;">
                                    <div class="card-body">
                                        <div class="container">
                                        <div class="form-group">
                                            <label for="TeacherID">教師ID</label>
                                            <input type="text" class="form-control" id="TeacherID" value="<?= htmlspecialchars($userData['TeacherID']) ?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="Name">姓名</label>
                                            <input type="text" class="form-control" id="Name" name="Name" value="<?= htmlspecialchars($userData['Name']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="Rank">職位</label>
                                            <input type="text" class="form-control" id="Rank" name="Rank" value="<?= htmlspecialchars($userData['Rank']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="ContactPhone">聯絡電話</label>
                                            <input type="text" class="form-control" id="ContactPhone" name="ContactPhone" value="<?= htmlspecialchars($userData['ContactPhone']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="T_Email">導師電子郵件</label>
                                            <input type="email" class="form-control" id="T_Email" name="T_Email" value="<?= htmlspecialchars($userData['T_Email']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="OfficeAddress">辦公室地址</label>
                                            <input type="text" class="form-control" id="OfficeAddress" name="OfficeAddress" value="<?= htmlspecialchars($userData['OfficeAddress']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="OfficePhone">辦公室電話</label>
                                            <input type="text" class="form-control" id="OfficePhone" name="OfficePhone" value="<?= htmlspecialchars($userData['OfficePhone']) ?>">
                                        </div>
                                        </div>
                                        <div class="mt-4 d-flex justify-content-center align-items-center" style="margin-bottom: 20px;">
                                            <button type="submit" class="btn btn-primary" style="margin-top: 20px;">保存變更</button>
                                        </div>

                                    </div>
                                </div> 
                            </form>
                        
                    <?php else: ?>
                    <p>使用者ID無效或使用者不存在。</p>
                    <?php endif; ?>
                    <!-- 返回上一頁按鈕 -->
                    
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