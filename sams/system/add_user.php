<?php
session_start();


if (!isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] != '管理員') {
  // 如果不是管理員，則導向到主頁面或登入頁面
  header('Location: login.html');
  exit;
}

$displayUsername = $_SESSION['username']; // 使用不同的變量名來避免覆蓋
$displayPermissionLevel = $_SESSION['permissionLevel'] ;

?>

<?php
require 'connect.php';

$userID = $_POST['userID'] ;
$userType = $_POST['userType'];
$accountID = $_POST['accountID'] ;
$username = $_POST['username'] ;
$password = $_POST['password'] ;
$permissionLevel = $_POST['permissionLevel'] ;

if (!empty($userID) && !empty($userType) && !empty($accountID) && !empty($username) && !empty($password) && !empty($permissionLevel)) {
    $conn->begin_transaction();
    try {
        $sql1 = "INSERT INTO Users (UserID, UserType) VALUES (?, ?)";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("ss", $userID, $userType);
        $stmt1->execute();

        $sql2 = "INSERT INTO AccountManagement (AccountID, UserID, Username, Password, PermissionLevel) VALUES (?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("sssss", $accountID, $userID, $username, $password, $permissionLevel);
        $stmt2->execute();

        $conn->commit();
        echo "<script>alert('新帳號新增成功'); window.location.href='add_user.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('新增帳號失敗: " . $e->getMessage() . "');</script>";
    }

    $stmt1->close();
    $stmt2->close();
} else {
    echo "<script>alert('所有欄位都是必填的');</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>新增帳號</title>
  <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
  <link rel="manifest" href="static/image/manifest.json">
  <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
  <meta name="theme-color" content="#ffffff">
  <link href="static/css/theme.min.css" rel="stylesheet">
  <link rel="stylesheet" href="profile_management.css">
  <style>
  .navbar {
  background-color: rgba(255, 255, 255, 0.95); /* 確保背景色始終半透明，不完全透明 */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* 添加陰影提升層次感 */
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 1050; /* 確保導航列在最上層 */
}




.container {
  margin-top: 20px;
}
form {
  display: flex;
  flex-direction: column;
  align-items: center;
}
input, select, button {
  margin-bottom: 20px;
  width: 100%;
  padding: 10px;
  border: 1px solid #220088;
  border-radius: 5px;
}
button {
  background-color: #220088;
  color: #fff;
  cursor: pointer;
}
button:hover {
  background-color: #ffc107;
  color: #220088;
}
table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 8px;
      text-align: left;
    }

  </style>
</head>

<body>
  <main class="main" id="top">
  <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block"
            data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container"><a class="navbar-brand" href="account_management.php"><img src="static/picture/nuk.png" height="45"
                        alt="logo"></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">
                    </span></button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                        <li class="nav-item px-2"><a class="nav-link active" aria-current="page" href="management.php">管理員主頁</a></li>

                        
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                        <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($displayPermissionLevel); ?> <?php echo htmlspecialchars($displayUsername); ?></a>

                        </li>
                    </ul>
                    <div id="userStatus" class="ms-lg-2">
                        <a class="btn btn-primary" href="logout.php" role="button">登出</a>
                    </div>
                    
                    
                </div>
            </div>
    </nav>
    <section class="pt-6 bg-600" id="add-user">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-12 text-md-start text-center py-6">
            <h1 class="fw-bold font-sans-serif mb-5">新增用戶</h1>
            <form action="add_user_process.php" method="post">
              <label for="userID">使用者ID:</label>
              <input type="text" id="userID" name="userID" required>
              
              <label for="userType">使用者類型:</label>
              <select id="userType" name="userType" required>
                <option value="管理員">管理員</option>
                <option value="導師">導師</option>
                <option value="學生">學生</option>
                <option value="房東">房東</option>
              </select>
              
              <label for="accountID">帳號ID(AccountID):</label>
              <input type="text" id="accountID" name="accountID" required>
              
              <label for="username">帳號名稱(Username):</label>
              <input type="text" id="username" name="username" required>
              
              <label for="password">密碼:</label>
              <input type="password" id="password" name="password" required>
              
              <label for="permissionLevel">權限等級:</label>
              <select id="permissionLevel" name="permissionLevel" required>
                <option value="管理員">管理員</option>
                <option value="導師">導師</option>
                <option value="學生">學生</option>
                <option value="房東">房東</option>
              </select>
              
              <button type="submit">保存更改</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

</body>

</html>
