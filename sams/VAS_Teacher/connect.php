<?php
// connect.php
$servername = "localhost";
$username = "root"; // ← 請改為你的帳號
$password = "summ0605"; // ← 請改為你的密碼
$dbname = "sams"; // ← 或你指定的資料庫名稱


// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}
?>
