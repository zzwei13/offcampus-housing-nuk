<?php
session_start(); // 啟動會話

require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $inputUsername = $_POST['username'];
  $inputPassword = $_POST['password'];
  $inputPermissionLevel = $_POST['permissionLevel'];

  // 準備和綁定
  $stmt = $conn->prepare("SELECT * FROM accountmanagement WHERE Username = ? AND Password = ? AND PermissionLevel = ?");
  $stmt->bind_param("sss", $inputUsername, $inputPassword, $inputPermissionLevel);

  // 執行語句
  $stmt->execute();
  $result = $stmt->get_result();

  // 檢查結果
  if ($result->num_rows > 0) {
    // 登入成功，將用戶信息存儲在會話中
    $_SESSION['username'] = $inputUsername;
    $_SESSION['permissionLevel'] = $inputPermissionLevel;
    
    // 導向到帳號管理頁面
    if($inputPermissionLevel == "管理員")
      header("Location: management.php");
    else if($inputPermissionLevel == "學生")
      header("Location: HouseInfoManage.php");
    exit();
  } else {
    // 登入失敗，返回登入頁面並顯示錯誤訊息
    header("Location: login.html?error=1");
    exit();
  }

  // 關閉語句
  $stmt->close();
}
// 關閉連接
$conn->close();
?>
