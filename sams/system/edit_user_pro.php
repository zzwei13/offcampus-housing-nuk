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
  <title>編輯用戶基本資料</title>
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
    <section class="pt-6 bg-600" id="edit-user-info">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-12 text-md-start text-center py-6">
            <h1 class="fw-bold font-sans-serif mb-5">編輯用戶基本資料</h1>
            <input type="text" id="searchInput" placeholder="輸入用戶ID或姓名搜尋..." onkeyup="searchUsers()">
            <div id="results">
              <?php
                include 'connect.php';

                // 定義每頁顯示的用戶數量
                $usersPerPage = 10;

                // 獲取總用戶數量，過濾掉管理員
                $sqlCount = "SELECT COUNT(*) AS total FROM accountmanagement WHERE PermissionLevel != '管理員'";
                $resultCount = $conn->query($sqlCount);
                $rowCount = $resultCount->fetch_assoc();
                $totalUsers = $rowCount['total'];

                // 獲取當前頁碼
                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                // 計算偏移量
                $offset = ($page - 1) * $usersPerPage;

                // 查詢用戶的 UserID, Username, PermissionLevel 和相應的姓名，過濾掉管理員
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
                      switch ($row['PermissionLevel']) {
                          case '學生':
                              echo "<td>" . ($row['StudentName'] ? $row['StudentName'] : "未知學生") . "</td>";
                              break;
                          case '導師':
                              echo "<td>" . ($row['TeacherName'] ? $row['TeacherName'] : "未知導師") . "</td>";
                              break;
                          case '房東':
                              echo "<td>" . ($row['LandlordName'] ? $row['LandlordName'] : "未知房東") . "</td>";
                              break;
                          default:
                              echo "<td>" . $row['Username'] . "</td>";
                              break;
                      }

                      // 添加編輯按鈕，將 UserID 和 PermissionLevel 作為參數傳遞
                      echo "<td><a href='edit_view_user_pro.php?UserID=" . $row['UserID'] . "&PermissionLevel=" . $row['PermissionLevel'] . "' class='btn btn-primary'>編輯</a></td>";
                      echo "</tr>";
                  }

                  // 分頁邏輯
                  $totalPages = ceil($totalUsers / $usersPerPage);

                  echo "<tr><td colspan='3'><div class='pagination'>";
                  for ($i = 1; $i <= $totalPages; $i++) {
                      echo "<a href='edit_user_pro.php?page=$i'>$i</a>";
                  }
                  echo "</div></td></tr>";
                  echo "</table>";
                } else {
                  echo "沒有找到任何用戶。";
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
  </main>
  <script>
    function searchUsers() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("searchInput");
      filter = input.value.toUpperCase();
      table = document.querySelector("table");
      tr = table.getElementsByTagName("tr");

      for (i = 1; i < tr.length; i++) { // 從1開始以跳過表頭
        tr[i].style.display = "none"; // 默認隱藏所有行
        td = tr[i].getElementsByTagName("td");
        for (var j = 0; j < td.length; j++) {
          if (td[j]) {
            txtValue = td[j].textContent || td[j].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = ""; // 當找到匹配項時，顯示該行
              break;
            }
          }
        }
      }
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
