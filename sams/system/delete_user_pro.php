<?php
session_start(); // 啟動會話

// 檢查會話中是否存在有效的登入信息，檢查username是否存在資料庫中，如果不存在，則導向到登入頁面
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit;
} 
// 包含資料庫連接文件
include 'connect.php';

// 獲取 URL 中的參數
$permissionLevel = $_SESSION['permissionLevel'];
$username = $_SESSION['username'];


if ($permissionLevel !== "管理員") {
  echo "您沒有管理員權限訪問此頁面。";
  exit;
}
?>
<!DOCTYPE html>
<html lang="zh" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>刪除用戶基本資料</title>
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
    .btn-primary {
      min-width: 80px; /* 調整這個寬度以適應按鈕的內容 */
      white-space: nowrap; /* 禁止換行 */
    }
    .btn-danger {
      min-width: 80px; /* 調整這個寬度以適應按鈕的內容 */
    }
    .pagination {
      text-align: center;

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
    #searchInput {
      padding: 10px;
      border: 1px solid #220088;
      border-radius: 5px;
      width: 100%;
      margin-bottom: 20px;
    }

    button {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
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
    }

    table,
    th,
    td {
      border: 1px solid #ddd;
    }

    th,
    td {
      padding: 8px;
      text-align: left;
    }

    .pagination {
      text-align: center;
    }

    .pagination a {
      color: #6A6AFF;
      text-decoration: none;
      margin: 0 5px;
    }

    .pagination a:hover {
      text-decoration: underline;
    }
    .navbar-brand {
      font-family: 'Microsoft JhengHei', sans-serif; /* 設置字體為微軟正黑體 */
    }
    .back-button {
      margin-top: 20px;
      text-align: center;
    }
    .back-button a {
      text-decoration: none;
      color: #fff;
      background-color: #007bff;
      padding: 10px 20px;
      border-radius: 5px;
      display: inline-block;
    }
    .back-button a:hover {
      background-color: #0056b3;
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
    <section class="pt-6 bg-600" id="delete-user-info">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-12 text-md-start text-center py-6">
            <h1 class="fw-bold font-sans-serif mb-5">刪除用戶基本資料</h1>
            <input type="text" id="searchInput" placeholder="輸入用戶ID或姓名搜尋..." onkeyup="searchUsers()">
            <div id="results">
              <?php
                include 'connect.php';

                $usersPerPage = 10;
                $sqlCount = "SELECT COUNT(*) AS total FROM accountmanagement WHERE PermissionLevel != '管理員'";
                $resultCount = $conn->query($sqlCount);
                $rowCount = $resultCount->fetch_assoc();
                $totalUsers = $rowCount['total'];
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($page - 1) * $usersPerPage;
                  $sql = "SELECT am.UserID, am.Username, am.PermissionLevel, 
                  s.Name AS StudentName, t.Name AS TeacherName, l.Name AS LandlordName
                  FROM accountmanagement AS am
                  LEFT JOIN students AS s ON am.UserID = s.StudentID
                  LEFT JOIN teachers AS t ON am.UserID = t.TeacherID
                  LEFT JOIN landlords AS l ON am.UserID = l.LandlordID
                  WHERE am.PermissionLevel != '管理員'
                  LIMIT $offset, $usersPerPage";
        
                  $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>用戶ID</th><th>姓名</th><th>操作</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['UserID'] . "</td>";
            
                    // 根據PermissionLevel來決定顯示的姓名
                    $name = "";
                    switch ($row['PermissionLevel']) {
                        case '學生':
                            $name = $row['StudentName'] ? $row['StudentName'] : "未知學生";
                            break;
                        case '導師':
                            $name = $row['TeacherName'] ? $row['TeacherName'] : "未知導師";
                            break;
                        case '房東':
                            $name = $row['LandlordName'] ? $row['LandlordName'] : "未知房東";
                            break;
                        default:
                            $name = $row['Username'];
                            break;
                    }
            
                    echo "<td>" . $name . "</td>";
                    
                    // 傳遞 PermissionLevel 和 UserID
                    echo "<td><a href='delete_pro_Implementation.php?UserID=" . $row['UserID'] . "&PermissionLevel=" . $row['PermissionLevel'] . "' class='btn btn-danger'>刪除</a></td>";
                    echo "</tr>";
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
                echo "</table>";
            } else {
                echo "<p>沒有符合條件的用戶。</p>";
            }

                $conn->close();
              ?>
                <div class="back-button">
                <a href="profile_management.php">返回上一頁</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script>
      function searchUsers() {
        var input = document.getElementById('searchInput').value.toLowerCase();
        var table = document.querySelector('table');
        var tr = table.getElementsByTagName('tr');

        for (var i = 1; i < tr.length - 1; i++) {
          var tdUserID = tr[i].getElementsByTagName('td')[0];
          var tdName = tr[i].getElementsByTagName('td')[1];
          if (tdUserID || tdName) {
            var userIDValue = tdUserID.textContent || tdUserID.innerText;
            var nameValue = tdName.textContent || tdName.innerText;
            if (userIDValue.toLowerCase().indexOf(input) > -1 || nameValue.toLowerCase().indexOf(input) > -1) {
              tr[i].style.display = '';
            } else {
              tr[i].style.display = 'none';
            }
          }
        }
      }

      // 顯示刪除結果提示
      <?php
        if (isset($_GET['result'])) {
            switch ($_GET['result']) {
                case 'success':
                    echo "alert('用戶資料已成功刪除');";
                    break;
                case 'failure':
                    echo "alert('刪除用戶資料時出現錯誤: " . $_GET['error'] . "');";
                    break;
                case 'invalid':
                    echo "alert('無效的參數');";
                    break;
                case 'unknown_permission':
                    echo "alert('未知的權限等級');";
                    break;
                case 'invalid_table':
                    echo "alert('無效的表格名稱');";
                    break;
                default:
                    break;
            }
        }
      ?>
    </script>
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
