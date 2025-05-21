<?php
date_default_timezone_set('Asia/Taipei'); // Replace 'Asia/Taipei' with your desired timezone
session_start();
// 檢查是否已登入且用戶為學生
if (!isset($_SESSION['permissionLevel']) || ($_SESSION['permissionLevel'] != '學生' && $_SESSION['permissionLevel'] != '導師')) {
    // 如果不是學生或導師，則導向到主頁面或登入頁面
    header('Location: login.html');
    exit;
}
$username = $_SESSION['username']; // 從會話中獲取用戶名
$PermissionLevel = $_SESSION['permissionLevel']; 
$TeacherName = $_SESSION['teacherName'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//function
function incrementId($id) {
  // 提取數字部分，假設前綴總是一個字元
  $number = substr($id, 1);
  
  // 將數字部分轉換為整數並加1
  $number = intval($number) + 1;
  
  // 將新的數字部分與前綴結合，並確保總是三位數
  $newId = 'P' . str_pad($number, 3, '0', STR_PAD_LEFT);
  
  return $newId;
}

// 連接到資料庫
require 'connect.php';

//獲取PostId
$result = $conn->query("SELECT * FROM forum ORDER BY PostID DESC LIMIT 1");
if ($result) {
    $row = $result->fetch_assoc();
    $postid = incrementId($row['PostID']);
} else {
    $postid = 'P001';
}


$query = "SELECT * FROM accountmanagement WHERE Username = '" . $_SESSION['username'] . "'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// 從表單獲取文字



$posterid = $row['UserID'];
$postertype = $_SESSION['permissionLevel'];
if($_SESSION['permissionLevel'] == '導師')
$postertype = "老師";

$title = $_POST['title'];
$content = $_POST['content'];
$date = date('Y-m-d');
$count = 0;
// 準備SQL語句
$sql = "INSERT INTO forum VALUES (?,?,?,?,?,?,?)";

// 預處理和綁定
$stmt = $conn->prepare($sql);

$stmt->bind_param("ssssssi", $postid,$posterid,$postertype,$title,$content,$date,$count);

// 執行語句
$stmt->execute();


$stmt->close();
$conn->close();

echo '<script type="text/javascript">';
echo 'alert("發文成功");';
echo 'window.location.href = "forum.php"';
echo '</script>';

    }
?>

<!DOCTYPE html>

<html lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>發文</title>
    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
    <link rel="manifest" href="static/image/manifest.json">
    <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="static/css/theme.min.css" rel="stylesheet">
    <style>
.scrollable {
  width: 600px;
  height: 600px;
  overflow-y: auto; /* 啟用滾動條 */
  overflow-x: hidden;
  border: 3px solid #ccc;
  border-radius: 10px
}
.post-container {
  width: 500px;
  
  border: 2px solid #ccc;
  border-radius: 15px;
  padding: 15px;
  margin: 5px 0;
}
.textbox{
  position: absolute;
  
  bottom: 10px;
  right: 10px;
  width: 370px;
  height: 30px;
}
.comment-button{
  position: absolute;
  
  bottom: 10px;
  left: 10px;
  width: 100px;
  height: 30px;
  color: #fff;
  background-color: #4CAF50;
  border: none;
  border-radius: 15px;
  box-shadow: 0 4px #999;
}
.comment-button:hover {
    color: #fff;
    background-color: #3e8e41;
    text-decoration: none;
}

.comment-button:active {
  background-color: #3e8e41;
  box-shadow: 0 2px #666;
  transform: translateY(2px);
}

.container {
  display: flex;
  justify-content: center;
  align-items: center;
}
.pbottom{
    margin-bottom: 100px;
}
p {
  white-space: normal; /* 允許內容換行 */
  word-wrap: break-word; /* 在長單詞或URL地址內部進行斷行 */
  word-break: break-all; /* 允許在單詞內換行 */
}
    </style>
</head>

<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">

        <!-- ============================================-->
        <!-- <section> begin ============================-->
        <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container">
              <a class="navbar-brand" href="teacher.php"><img src="static/picture/nuk.png" height="45" alt="logo"></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                      
                        <li class="nav-item px-2">
                          <a class="nav-link" aria-current="page" href="rental_info.php">租屋資訊</a>
                        </li>
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page"
                                href="forum.php">交流平台</a></li>
                        <li class="nav-item px-2">
                            <a class="nav-link" aria-current="page" href="TeacherProfile.php?">
                                基本資料
                            </a>
                        </li>

                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($PermissionLevel); ?> <?php echo htmlspecialchars($TeacherName); ?></a>
                        </li>
                    </ul>
                    <div id="userStatus" class="ms-lg-2">
                        <a class="btn btn-primary" href="logout.php" role="button">登出</a>
                    </div>
                    
                    <div class="dropdown d-none d-lg-block">
                        <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="dropdownMenuButton1"
                            style="top:55px">
                            <form>
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
    </nav>
    <section class="pt-1 bg-600" >
        <div>
    </section>
    <section class="pt-1 bg-600" id="home" style=" padding-top: 40px;">
        <div class="d-flex justify-content-center  min-vh-100">
                
            
                <div class="form-container">
                    <a href="forum.php" style="display: inline-block; margin-right: 685px; margin-bottom: 45px; text-decoration: none;">
                        <div style="font-size: 24px;">&lt;</div>
                    </a>
                    <form method="post">
                        <div class="form-group mb-4">
                            <input type="text" name="title" placeholder="標題" class="form-control" required>
                        </div>
                        <div class="form-group mb-4">
                            <textarea name="content" cols="60" rows="10" placeholder="內容" class="form-control" style="resize: none;" required></textarea>
                        </div>
                        <div class="mt-4 d-flex justify-content-center align-items-center" style="margin-bottom: 20px;">  
                            <input type="submit" name="post" value="發文" class="btn btn-primary">
                        </div>
                        
                    </form>
                </div>
            
        </div>
    </section>
    </main><!-- ===============================================-->

    <script src="static/js/popper.min.js"></script>
    <script src="static/js/bootstrap.min.js"></script>
    <script src="static/js/is.min.js"></script>
    <script src="static/js/polyfill.min.js"></script>
    <script src="static/js/all.min.js"></script>
    <script src="static/js/theme.js"></script>
    <link href="static/css/css2.css" rel="stylesheet">
    
</body>

</html>