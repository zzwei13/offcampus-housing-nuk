<?php

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
    $newId = 'C' . str_pad($number, 3, '0', STR_PAD_LEFT);
    
    return $newId;
  }
  
  // 連接到資料庫
  require 'connect.php';
  
  //獲取CommentId
  $result = $conn->query("SELECT * FROM comments ORDER BY CommentID DESC LIMIT 1");
  if ($result) {
      $row = $result->fetch_assoc();
      $commentid = incrementId($row['CommentID']);
  } else {
      $commentid = 'C001';
  }
  
//獲得使用者ID
$query = "SELECT * FROM accountmanagement WHERE Username = '" . $_SESSION['username'] . "'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
  
  
  // 從表單獲取文字
  
  $postid = $_POST['post_id'];
  $commenterid = $row['UserID'];
  $commentcontent = $_POST['content'];
  $commentdate = date('Y-m-d');




  //新增留言
  $sql = "INSERT INTO comments VALUES (?,?,?,?,?)";
  // 預處理和綁定
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $commentid,$postid,$commenterid,$commentcontent,$commentdate);
  // 執行語句
  $stmt->execute();


  //取得原本的留言數
  $query = "SELECT * FROM forum WHERE PostID = '$postid'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $comment_count = $row['CommentCount'] + 1;


  //留言數+1
  $sql = "UPDATE forum SET CommentCount = ? WHERE PostID = ?";
  // 預處理和綁定
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $comment_count, $postid);
  // 執行語句
  $stmt->execute();

  $stmt->close();
  $conn->close();

  echo '<script type="text/javascript">';
  echo 'alert("留言成功");';
  echo 'window.location.href = "comment.php?post_id=' . $row['PostID'] . '";';
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
    <title>交流平台</title>
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
  width: 900px;
  height: 900px;
  overflow-y: auto; /* 啟用滾動條 */
  overflow-x: hidden;
  border: 1px solid #ccc;
  border-radius: 9px
}
.post-container {
  width: 700px;
  border: 0.5px solid #ccc;
  border-radius: 15px;
  padding: 15px;
  margin: 5px 0;
  background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

.textbox {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 500px;
    height: 40px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 6px;
    
    outline: none;
    transition: all 0.3s ease;
}

.textbox:focus {
    border-color: #FDC800;
   
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
    margin-bottom: 10px;
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
    
    <section class="pt-6 bg-600" id="home">
        <h1 class="fw-bold font-sans-serif text-center">交流平台 </h4>
        <div class="d-flex justify-content-center align-items-center min-vh-100">
            <div align="center" class="mt-4">
                <button onclick="window.location.href='post.php'" style="margin-left:800px; height: 40px; width: 100px" class="btn btn-secondary" >發布貼文</button>
                <div class="scrollable bg-white" style="margin-top:10px;">
                    <?php
                    require 'connect.php';

                    $query = "SELECT a.PostID, a.Title, a.Content, a.CommentCount,b.Name FROM forum a INNER JOIN students b ON a.PosterID = b.StudentID WHERE a.PostType = '學生' UNION ALL SELECT a.PostID, a.Title, a.Content,a.CommentCount,c.Name FROM forum a INNER JOIN teachers c ON a.PosterID = c.TeacherID WHERE a.PostType = '老師' ORDER BY PostID DESC";
                    $result = mysqli_query($conn, $query);

                    while($row = mysqli_fetch_assoc($result)) {
                        echo '<a href=" ViewPost.php?post_id=' . $row['PostID'] . '" class="post-container" style="position: relative; display: block; text-decoration: none;">';
                        //echo '<p align="left">發文者: ' . $row['Name'] . '</p>';
                        echo '<h1 class="fw-medium text-start font-sans-serif" align="left">' . $row['Title'] . '</h1>';
                        echo '<p align="left" class="pbottom text-start">' . $row['Content'] . '</p>';

                        echo '<p align="left">留言 ' . $row['CommentCount'] .'</p>';
                        echo '</a>';

               
                    }
                    ?>
                </div>

        </div>
    </section>



    </main><!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->


    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="static/js/popper.min.js"></script>
    <script src="static/js/bootstrap.min.js"></script>
    <script src="static/js/is.min.js"></script>
    <script src="static/js/polyfill.min.js"></script>
    <script src="static/js/all.min.js"></script>
    <script src="static/js/theme.js"></script>
    <link href="static/css/css2.css" rel="stylesheet">
    
</body>

</html>