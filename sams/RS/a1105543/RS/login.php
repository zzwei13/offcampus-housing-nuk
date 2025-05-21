<?php
session_start(); // 啟動會話

require 'connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $inputUsername = $_POST['username'];
  $inputPassword = $_POST['password'];
  $inputPermissionLevel = $_POST['permissionLevel'];
  // 查詢使用者名字(導師/學生)
  $query = "SELECT b.Name FROM accountmanagement a INNER JOIN students b ON a.UserID = b.StudentID WHERE a.Username = '$inputUsername' UNION ALL SELECT c.Name FROM accountmanagement a INNER JOIN teachers c ON a.UserID = c.TeacherID WHERE a.Username = '$inputUsername'";
  $result1 = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result1);
  $name = $row['Name'];

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
    $_SESSION['name'] = $name;
    
    // 導向到帳號管理頁面
    #如果是管理員，導向到管理頁面
    if($inputPermissionLevel == "管理員")
    header("Location: management.php");
    #如果是學生，導向到學生頁面
    else if($inputPermissionLevel == "學生")
    header("Location: RS.php");
    else if($inputPermissionLevel == "導師")
    header("Location: RS.php");
    else if($inputPermissionLevel == "房東")
    header("Location: landlordPage.php");
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
