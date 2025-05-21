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


$userID = $_GET['userID'] ;

if ($userID) {
    // 開始事務
    $conn->begin_transaction();
    try {
        // 刪除 AccountManagement 表中的記錄
        $stmt1 = $conn->prepare("DELETE FROM AccountManagement WHERE UserID = ?");
        $stmt1->bind_param("s", $userID);
        $stmt1->execute();
        $stmt1->close();

        // 刪除 Users 表中的記錄
        $stmt2 = $conn->prepare("DELETE FROM Users WHERE UserID = ?");
        $stmt2->bind_param("s", $userID);
        $stmt2->execute();
        $stmt2->close();

        // 提交事務
        $conn->commit();
        echo "<script>alert('用戶已成功刪除'); window.location.href='delete_user.php';</script>";
    } catch (Exception $e) {
        // 回滾事務
        $conn->rollback();
        echo "<script>alert('刪除用戶失敗：" . $e->getMessage() . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('無效的用戶ID'); window.history.back();</script>";
}

$conn->close();
?>
