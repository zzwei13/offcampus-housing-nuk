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
      margin-top: 20px;
    }
    #searchForm {
      margin-bottom: 20px;
    }
    #searchInput {
      padding: 10px;
      border: 1px solid #220088; /* 深藍色 */
      border-radius: 5px;
      width: 300px;
      margin-right: 10px;
    }
    button {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      background-color: #220088; /* 深藍色 */
      color: #fff;
      cursor: pointer;
    }
    button:hover {
      background-color: #ffc107; /* 黃色 */
      color: #220088; /* 深藍色 */
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
        <div class="row align-items-center">
          <div class="col-md-12 text-md-start text-center py-6">
            <h1 class="fw-bold font-sans-serif mb-5">編輯用戶資料</h1>
            <form id="searchForm" class="d-flex justify-content-center" method="post" action="edit_user.php">
            <input type="text" id="searchInput" name="searchInput" placeholder="輸入用戶名、權限等級或用戶ID..." required>
              <button type="submit">搜尋</button>
            </form>
            <div id="resultsTable">
              <!-- 搜尋結果將在這裡顯示 -->
              <?php
              if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['searchInput'])) {
                require 'connect.php';

                  
                  $searchInput = $_POST['searchInput'];
                  $sql = "SELECT Users.UserID, AccountManagement.Username, AccountManagement.PermissionLevel 
                  FROM AccountManagement 
                  JOIN Users ON AccountManagement.UserID = Users.UserID 
                  WHERE AccountManagement.Username LIKE '%$searchInput%' 
                  OR AccountManagement.PermissionLevel LIKE '%$searchInput%' 
                  OR Users.UserID LIKE '%$searchInput%'";

                  $result = $conn->query($sql);
                  
                  if ($result->num_rows > 0) {
                    echo "<table><thead><tr><th>ID</th><th>用戶名</th><th>權限等級</th><th>操作</th></tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row['UserID'] . "</td><td>" . $row['Username'] . "</td><td>" . $row['PermissionLevel'] . "</td>";
                        echo "<td><button onclick=\"editUser('" . $row['UserID'] . "')\">編輯</button></td></tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>未找到相關用戶</p>";
                }
                
                  $conn->close();
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script>
    function editUser(userID) {
      window.location.href = 'edit_user_form.php?userID=' + userID;
    }
  </script>

  <script src="static/js/popper.min.js"></script>
  <script src="static/js/bootstrap.min.js"></script>
  <script src="static/js/is.min.js"></script>
  <script src="static/js/polyfill.min.js"></script>
  <script src="static/js/all.min.js"></script>
  <script src="static/js/theme.js"></script>
  <link href="static/css/css2.css" rel="stylesheet">
</body>

</html>
