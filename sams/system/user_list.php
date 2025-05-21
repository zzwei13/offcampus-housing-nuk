<?php
session_start();
require 'connect.php'; // 確保資料庫連接在使用任何資料庫操作前就已建立

if (!isset($_SESSION['permissionLevel']) || ($_SESSION['permissionLevel'] != '導師' && $_SESSION['permissionLevel'] != '管理員')) {
    header('Location: login.html');
    exit;
}

$username = $_SESSION['username'] ; // 使用 null 合併運算符來處理未設置情況
$permissionLevel = $_SESSION['permissionLevel']; // 同上
?>
<!DOCTYPE html>
<html lang="zh" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>帳號管理</title>
  <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
  <link rel="manifest" href="static/image/manifest.json">
  <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
  <meta name="theme-color" content="#ffffff">
  <link href="static/css/theme.min.css" rel="stylesheet">
  <style>
    .container {
      margin-top: 20px;
    }
    #searchInput {
      padding: 10px;
      border: 1px solid #220088; /* 深藍色 */
      border-radius: 5px;
      width: 100%;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 8px;
      text-align: left;
    }
    .pagination {
      text-align: center;
      margin-top: 20px;
    }
    .pagination a {
      color: #6A6AFF; /* 深藍色 */
      text-decoration: none;
      margin: 0 5px;
      padding: 5px 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    .pagination a:hover {
      background-color: #ddd;
    }
  </style>
</head>

<body>
  <main class="main" id="top">
  <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block"
            data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container"><a class="navbar-brand" href="management.php"><img src="static/picture/nuk.png" height="45"
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
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($permissionLevel); ?> <?php echo htmlspecialchars($username); ?></a>
                        </li>
                    </ul>
                    <div id="userStatus" class="ms-lg-2">
                        <a class="btn btn-primary" href="logout.php" role="button">登出</a>
                    </div>
                    
                    
                </div>
            </div>
    </nav>
    <section class="pt-6 bg-600" id="account-management">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-12 text-md-start text-center py-6">
            <h1 class="fw-bold font-sans-serif mb-5">用戶列表</h1>
            <input type="text" id="searchInput" placeholder="搜尋用戶名或權限等級..." class="form-control mb-3">
            <table class="table" id="userTable">
              <thead>
                <tr>
                  <th>用戶ID</th>
                  <th>用戶名</th>
                  <th>權限等級</th>
                </tr>
              </thead>
              <tbody>
                <?php
                require 'connect.php';

                // 定義每頁顯示的用戶數量
                $usersPerPage = 10;

                // 獲取總用戶數量
                $sqlCount = "SELECT COUNT(*) AS total FROM AccountManagement";
                $resultCount = $conn->query($sqlCount);
                $rowCount = $resultCount->fetch_assoc();
                $totalUsers = $rowCount['total'];

                // 獲取當前頁碼
                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                // 計算偏移量
                $offset = ($page - 1) * $usersPerPage;

                // 查詢用戶的 UserID 和 Username
                $sql = "SELECT AccountManagement.Username, Users.UserID, AccountManagement.PermissionLevel 
                        FROM AccountManagement 
                        JOIN Users ON AccountManagement.UserID = Users.UserID 
                        LIMIT $offset, $usersPerPage";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["UserID"] . "</td><td>" . $row["Username"] . "</td><td>" . $row["PermissionLevel"] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>無數據</td></tr>";
                }

                // 顯示頁碼
                $numPages = ceil($totalUsers / $usersPerPage);
                echo "<tr><td colspan='3' style='text-align: center;'>";
                echo "<div class='pagination'>";
                for ($i = 1; $i <= $numPages; $i++) {
                    echo "<a href='?page=$i'>$i</a>";
                }
                echo "</div>";
                echo "</td></tr>";

                // 關閉數據庫連接
                $conn->close();
                ?>
              </tbody>
            </table>
          </div>
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
  <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
      var searchValue = this.value.toLowerCase();
      var tableRows = document.getElementById('userTable').getElementsByTagName('tr');
      for (var i = 1; i < tableRows.length; i++) {
        var td = tableRows[i].getElementsByTagName('td');
        var found = td[0].textContent.toLowerCase().includes(searchValue) || 
                    td[1].textContent.toLowerCase().includes(searchValue) ||
                    td[2].textContent.toLowerCase().includes(searchValue);
        tableRows[i].style.display = found ? '' : 'none';
      }
    });
  </script>
</body>
</html>