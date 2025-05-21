<?php

session_start(); // 啟動會話

// 檢查會話中是否存在有效的登入信息，檢查username是否存在資料庫中，如果不存在，則導向到登入頁面
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit;
} 
// 包含資料庫連接文件
include 'connect.php';

// 獲取 URL 中的參數
$Level = $_SESSION['permissionLevel'];
$name = $_SESSION['username'];;


// 包含資料庫連接文件
include 'connect.php';

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

// 檢查欄位是否允許為NULL
function checkFieldNullability($conn, $table, $field) {
    $sql = "SHOW COLUMNS FROM $table LIKE '$field'";
    $result = $conn->query($sql);
    $column = $result->fetch_assoc();
    return $column['Null'] === 'YES';
}

// 檢查導師ID是否存在
function checkTeacherExists($conn, $advisorID) {
    $sql = "SELECT 1 FROM teachers WHERE TeacherID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $advisorID);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
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

$errorMsg = '';

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 獲取表單提交的數據
    $userID = $_POST['UserID'];
    $permissionLevel = $_POST['PermissionLevel'];
    
    // 構建更新語句和參數
    $sql = "";
    $params = [];
    $types = "";

    switch ($permissionLevel) {
        case '學生':
            $fields = ['Name', 'Department', 'Grade', 'Gender', 'AdvisorID', 'ContactPhone', 'S_Email', 'HomeAddress', 'HomePhone', 'EmergencyContactName', 'EmergencyContactPhone'];
            foreach ($fields as $field) {
                $value = isset($_POST[$field]) ? $_POST[$field] : '';
                if ($field == 'AdvisorID' && $value !== '' && !checkTeacherExists($conn, $value)) {
                    $errorMsg = "導師ID $value 不存在。";
                    break;
                }
                if ($value === '' && !checkFieldNullability($conn, 'students', $field)) {
                    $errorMsg = "欄位 $field 不能為空。";
                    break;
                }
                $params[] = $value !== '' ? $value : null;
                $types .= "s";
            }
            if (empty($errorMsg)) {
                $params[] = $userID;
                $types .= "s";
                $sql = "UPDATE students SET Name=?, Department=?, Grade=?, Gender=?, AdvisorID=?, ContactPhone=?, S_Email=?, HomeAddress=?, HomePhone=?, EmergencyContactName=?, EmergencyContactPhone=? WHERE StudentID=?";
            }
            break;
        case '導師':
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
                $params[] = $userID;
                $types .= "s";
                $sql = "UPDATE teachers SET Name=?, Rank=?, ContactPhone=?, T_Email=?, OfficeAddress=?, OfficePhone=? WHERE TeacherID=?";
            }
            break;
        case '房東':
            $fields = ['Name', 'ContactPhone', 'L_Email'];
            foreach ($fields as $field) {
                $value = isset($_POST[$field]) ? $_POST[$field] : '';
                if ($value === '' && !checkFieldNullability($conn, 'landlords', $field)) {
                    $errorMsg = "欄位 $field 不能為空。";
                    break;
                }
                $params[] = $value !== '' ? $value : null;
                $types .= "s";
            }
            if (empty($errorMsg)) {
                $params[] = $userID;
                $types .= "s";
                $sql = "UPDATE landlords SET Name=?, ContactPhone=?, L_Email=? WHERE LandlordID=?";
            }
            break;
        default:
            $errorMsg = "未知的權限等級。";
    }

    if (empty($errorMsg)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        // 執行更新並檢查結果
        if ($stmt->execute()) {
            echo "<script>
                    alert('資料已成功更新。');
                    window.location.href = '" . $_SERVER['PHP_SELF'] . "?PermissionLevel=" . urlencode($permissionLevel) . "&UserID=" . urlencode($userID) . "';
                  </script>";
        } else {
            echo "<p>更新資料時出錯: " . $stmt->error . "</p>";
        }

        $stmt->close();

        // 注释掉 PHP 的重定向
        // header("Location: " . $_SERVER['PHP_SELF'] . "?PermissionLevel=" . urlencode($permissionLevel) . "&UserID=" . urlencode($userID));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>新增帳號</title>
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
    


        <div class="container mt-4">
            <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($errorMsg) ?>
            </div>
            <?php endif; ?>
            <?php if ($userData): ?> 
            <form method="POST" action=""> 
                <input type="hidden" name="PermissionLevel" value="<?= htmlspecialchars($permissionLevel) ?>"> 
                <input type="hidden" name="UserID" value="<?= htmlspecialchars($userID) ?>"> 
                <?php switch ($permissionLevel): case '學生': ?> 
                <h1>編輯學生資料</h1> 
                <div class="form-group"> 
                    <label for="StudentID">學號</label> 
                    <input type="text" class="form-control" id="StudentID" value="<?= htmlspecialchars($userData['StudentID']) ?>" disabled> 
                </div> 
                <div class="form-group"> 
                    <label for="Name">姓名</label> 
                    <input type="text" class="form-control" id="Name" name="Name" value="<?= htmlspecialchars($userData['Name']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="Department">系級</label> 
                    <input type="text" class="form-control" id="Department" name="Department" value="<?= htmlspecialchars($userData['Department']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="Grade">年級</label> 
                    <input type="text" class="form-control" id="Grade" name="Grade" value="<?= htmlspecialchars($userData['Grade']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="Gender">性別</label> 
                    <input type="text" class="form-control" id="Gender" name="Gender" value="<?= htmlspecialchars($userData['Gender']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="AdvisorID">導師ID</label> 
                    <input type="text" class="form-control" id="AdvisorID" name="AdvisorID" value="<?= htmlspecialchars($userData['AdvisorID']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="ContactPhone">聯絡電話</label> 
                    <input type="text" class="form-control" id="ContactPhone" name="ContactPhone" value="<?= htmlspecialchars($userData['ContactPhone']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="S_Email">學生電子郵件</label> 
                    <input type="email" class="form-control" id="S_Email" name="S_Email" value="<?= htmlspecialchars($userData['S_Email']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="HomeAddress">家庭地址</label> 
                    <input type="text" class="form-control" id="HomeAddress" name="HomeAddress" value="<?= htmlspecialchars($userData['HomeAddress']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="HomePhone">家用電話</label> 
                    <input type="text" class="form-control" id="HomePhone" name="HomePhone" value="<?= htmlspecialchars($userData['HomePhone']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="EmergencyContactName">緊急聯絡人姓名</label> 
                    <input type="text" class="form-control" id="EmergencyContactName" name="EmergencyContactName" value="<?= htmlspecialchars($userData['EmergencyContactName']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="EmergencyContactPhone">緊急聯絡人電話</label> 
                    <input type="text" class="form-control" id="EmergencyContactPhone" name="EmergencyContactPhone" value="<?= htmlspecialchars($userData['EmergencyContactPhone']) ?>"> 
                </div> 
                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">儲存變更</button> 
                <?php break; case '導師': ?> 
                <h1>編輯導師資料</h1> 
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
                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">保存變更</button> 
                <?php break; case '房東': ?> 
                <h1>編輯房東資料</h1> 
                <div class="form-group"> 
                    <label for="LandlordID">房東ID</label> 
                    <input type="text" class="form-control" id="LandlordID" value="<?= htmlspecialchars($userData['LandlordID']) ?>" disabled> 
                </div> 
                <div class="form-group"> 
                    <label for="Name">姓名</label> 
                    <input type="text" class="form-control" id="Name" name="Name" value="<?= htmlspecialchars($userData['Name']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="ContactPhone">聯絡電話</label> 
                    <input type="text" class="form-control" id="ContactPhone" name="ContactPhone" value="<?= htmlspecialchars($userData['ContactPhone']) ?>"> 
                </div> 
                <div class="form-group"> 
                    <label for="L_Email">房東電子郵件</label> 
                    <input type="email" class="form-control" id="L_Email" name="L_Email" value="<?= htmlspecialchars($userData['L_Email']) ?>"> 
                </div> 
                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">保存變更</button> 
                <?php break; default: ?> 
                <h1>未知的權限等級</h1> 
                <p>請提供有效的權限等級。</p> 
                <?php endswitch; ?> 
            </form> 
            <?php else: ?> 
            <p>使用者ID無效或使用者不存在。</p> 
            <?php endif; ?> 
            <!-- 返回上一頁按鈕 --> 
            <div class="mt-4"> 
                <button class="btn btn-secondary" onclick="window.location.href='edit_user_pro.php'">回上一頁</button> 
            </div> 
        </div> 
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
