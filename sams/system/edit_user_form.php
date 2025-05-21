<?php
session_start();


if (!isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] != '管理員') {
  // 如果不是管理員，則導向到主頁面或登入頁面
  header('Location: login.html');
  exit;
}

$displayUsername = $_SESSION['username'] ; // 使用不同的變量名來避免覆蓋
$displayPermissionLevel = $_SESSION['permissionLevel'] ;

?>

<?php
require 'connect.php';


$userID = $_GET['userID'] ;
$user = null;

if ($userID) {
    $stmt = $conn->prepare("SELECT UserID, Username FROM AccountManagement WHERE UserID = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUserID = $_POST['userID'];
    $username = $_POST['username'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update User in Users Table
        $updateUserStmt = $conn->prepare("UPDATE Users SET UserID = ? WHERE UserID = ?");
        $updateUserStmt->bind_param("ss", $newUserID, $userID);
        $updateUserStmt->execute();

        // Update Account in AccountManagement Table
        $updateAccountStmt = $conn->prepare("UPDATE AccountManagement SET UserID = ?, AccountID = ?, Username = ? WHERE UserID = ?");
        $updateAccountStmt->bind_param("ssss", $newUserID, $newUserID, $username, $userID);
        $updateAccountStmt->execute();

        // Commit transaction
        $conn->commit();

        echo "<script>alert('用戶更新成功'); window.location.href='edit_user.php?userID=$newUserID';</script>";
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "<script>alert('用戶更新失敗" . $e->getMessage() . "');</script>";
    }

    $updateUserStmt->close();
    $updateAccountStmt->close();
} else {
    
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>編輯用戶資料</title>
    <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
    <link rel="manifest" href="static/image/manifest.json">
    <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <link href="static/css/theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
  width: 100%; /* 調整寬度 */
  max-width: 1200px; /* 或設定一個更大的最大寬度 */
  padding: 0 15px; /* 確保左右有足夠的填充 */
  margin: auto; /* 水平居中 */
}

form {
  display: flex;
  flex-direction: column;
  align-items: center;
}
label, input, select, button {
    width: 90%; /* 調整寬度 */
    margin-bottom: 10px; /* 調整間距 */
    padding: 8px;
    border-radius: 5px;
  }
  button {
    background-color: #220088; /* 深藍色 */
    color: white; /* 文字顏色 */
    cursor: pointer; /* 滑鼠指標 */
  }
  button:hover {
    background-color: #ffc107; /* 懸停時的背景色 */
    color: #220088; /* 懸停時的文字顏色 */
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

        <section class="pt-6 bg-600" id="edit-user">
            <div class="container">
               
                    <div class="col-md-12 text-md-start text-center py-6">
                        <h1 class="fw-bold font-sans-serif mb-5">編輯用戶資料</h1>
                        <form method="post" action="">
                      <input type="text" id="userID" name="userID" placeholder="使用者ID" value="<?php echo htmlspecialchars($user['UserID']); ?>" required><br>
                      <input type="text" id="username" name="username" placeholder="用戶名" value="<?php echo htmlspecialchars($user['Username']); ?>" required><br>
                      <button type="submit">保存更改</button>

                </form>
                
                </div>
            </div>
        </section>
    </main>

    <script src="static/js/popper.min.js"></script>
  <script src="static/js/bootstrap.min.js"></script>
  <script src="static/js/is.min.js"></script>
  <script src="static/js/polyfill.min.js"></script>
  <script src="static/js/all.min.js"></script>
  <script src="static/js/theme.js"></script>
  <link href="static/css/css2.css" rel="stylesheet">
</body>

</html>

