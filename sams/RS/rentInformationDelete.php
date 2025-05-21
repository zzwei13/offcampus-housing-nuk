<?php
session_start();

// 檢查使用者是否已登入及是否為房東
if (!isset($_SESSION['username']) || $_SESSION['permissionLevel'] !== '房東') {
    header('Location: login.html');
    exit();
}

if (isset($_GET['InformationID'])) {
    require_once 'db_connect.php';

    $informationID = $_GET['InformationID'];
    $username = $_SESSION['username'];

    // 防止 SQL 注入攻擊
    $informationID = $conn->real_escape_string($informationID);
    $username = $conn->real_escape_string($username);

    // 刪除租屋照片
    $deleteImgSql = "DELETE FROM rentalimages WHERE InformationID = '$informationID'";
    if ($conn->query($deleteImgSql) === TRUE) {
        // 刪除租屋資訊
        $deleteInfoSql = "DELETE FROM rentalinformation WHERE InformationID = '$informationID' AND Username = '$username'";
        if ($conn->query($deleteInfoSql) === TRUE) {
            // 成功刪除後導向回租屋資訊清單
            header('Location: rentInformation.php');
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
} else {
    // 如果未提供 InformationID，則重導向回租屋資訊清單
    header('Location: rentInformation.php');
    exit();
}
?>
