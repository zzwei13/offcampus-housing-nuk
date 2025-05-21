<?php
session_start(); // 啟動會話

// 清除所有會話數據
session_unset();
session_destroy();

// 重定向到首頁
header("Location: index.php");
exit();
?>
