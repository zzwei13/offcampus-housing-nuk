<?php
session_start(); // 啟動會話

$servername = "127.0.0.1";
$username = "root";
$password = "chloe920607";
$dbname = "sams";

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

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
    
    // 根據權限等級導向不同頁面
    if ($inputPermissionLevel === "管理員") {
      header("Location: /sams/system/management.php");
    } 
    elseif ($inputPermissionLevel === "學生") {
      header("Location: /sams/VAS_student/HouseInfoManage.php");
    }
    elseif ($inputPermissionLevel === "房東") {
      header("Location: /sams/RS/landlordPage.php");
    }
    elseif ($inputPermissionLevel === "導師") {
      header("Location: /sams/VAS_Teacher/teacher.php");
    }

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
