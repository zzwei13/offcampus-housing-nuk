<?php
$servername = "localhost";
$username = "root"; // ← 請改為你的帳號
$password = "summ0605"; // ← 請改為你的密碼
$dbname = "sams"; // ← 或你指定的資料庫名稱

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("ERROR: Database connection failed: " . $conn->connect_error);
}
?>