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
$newUserID = $_POST['newUserID'] ; // Assuming you are allowing changing of UserID.
$username = $_POST['username'] ;

if (!empty($userID) && !empty($username) && !empty($newUserID)) {
    $updateSQL = "UPDATE AccountManagement SET UserID=?, Username=? WHERE UserID=?";
    $stmt = $conn->prepare($updateSQL);
    $stmt->bind_param("sss", $newUserID, $username, $userID);
    
    if ($stmt->execute()) {
        echo "<script>alert('用戶資料更新成功'); window.location.href='edit_user.php';</script>";
    } else {
        echo "<script>alert('用戶資料更新失敗');</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('All fields are required');</script>";
}
$conn->close();
?>
