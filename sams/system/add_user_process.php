<?php
session_start();
// 檢查是否已登入且用戶為管理員
if (!isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] != '管理員') {
    // 如果不是管理員，則導向到主頁面或登入頁面
    header('Location: login.html');
    exit;
}
?>
<?php
require 'connect.php';


$userID = $_POST['userID'] ;
$userType = $_POST['userType'] ;
$accountID = $_POST['accountID'] ;
$username = $_POST['username'] ;
$password = $_POST['password'] ;
$permissionLevel = $_POST['permissionLevel'] ;

$message = '';
if ($userID && $userType && $accountID && $username && $password && $permissionLevel) {
    $sql = "INSERT INTO Users (UserID, UserType) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $userID, $userType);
    $stmt->execute();

    $sql = "INSERT INTO AccountManagement (AccountID, UserID, Username, Password, PermissionLevel) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $accountID, $userID, $username, $password, $permissionLevel);
    if ($stmt->execute()) {
        $message = '新增帳號成功';
    } else {
        $message = '新增帳號失敗';
    }
} else {
    $message = '所有欄位都是必填的';
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="zh" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>新增用戶</title>
    <script src="static/js/popper.min.js"></script>
    <script src="static/js/bootstrap.min.js"></script>
    <script src="static/js/is.min.js"></script>
    <script src="static/js/polyfill.min.js"></script>
    <script src="static/js/all.min.js"></script>
    <script src="static/js/theme.js"></script>
    <link href="static/css/theme.min.css" rel="stylesheet">
</head>
<body>
    <script>
        alert('<?php echo $message; ?>');
        window.location.href = 'add_user.php'; // 自動跳轉回新增用戶頁面
    </script>
</body>
</html>
