<?php

session_start(); // 啟動會話

// 檢查用戶是否已登入及是否為管理員
if (!isset($_SESSION['username']) || $_SESSION['permissionLevel'] !== '管理員') {
    header('Location: login.html');
    exit();
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
    <title><?php echo basename(__FILE__); ?></title>
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
  border-radius: 10px;
  padding: 15px;
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

    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block"
            data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container"><a class="navbar-brand" href="../../../system/management.php"><img src="static/picture/nuk.png" height="45"
                        alt="logo"></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">
                    </span></button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                        <li class="nav-item px-2"><a class="nav-link active" aria-current="page" href=" ../../../system/management.php">管理員主頁</a></li>
 
                        
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($_SESSION['permissionLevel']); ?>  <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                        </li>
                    </ul>
                    <div id="userStatus" class="ms-lg-2">
                        <a class="btn btn-primary" href="logout.php" role="button">登出</a>
                    </div>
                    
                    
                </div>
            </div>
    </nav>


    <section class="pt-3 bg-600" id="home">
    
    <div class="d-flex justify-content-center min-vh-100">
      <div align="center">
        <?php
        require 'connect.php';

        //接收PostID
        $post_id=$_GET['post_id'];
        $query = "SELECT b.Name, a.CommentContent, a.CommentDate, a.CommentID FROM comments a INNER JOIN students b ON a.CommenterID = b.StudentID WHERE a.PostID = '$post_id' UNION ALL
        SELECT c.Name, a.CommentContent, a.CommentDate, a.CommentID FROM comments a INNER JOIN teachers c ON a.CommenterID = c.TeacherID WHERE a.PostID = '$post_id' ORDER BY CommentID ASC;";
        $result = mysqli_query($conn, $query);
       
        echo '<div style="display: inline-block; margin-right: 590px;">';
        echo '<a href="post_view.php?post_id=' . $post_id . '" style="font-size: 24px;">&lt;</a>';
        echo '</div>';
        echo '<h4 class="fw-semi-bold font-sans-serif">所有留言</h4>';
       

        // 顯示返回鏈接
        echo '<div class="scrollable" style="margin-top:10px;">';
       
        while($row = mysqli_fetch_assoc($result)) {
            echo '<p align="left">' . $row['Name'] . ': ' . $row['CommentContent'] . ' (' . $row['CommentDate'] . ')</p>';
        }

        ?>
        </div>
    </div>
    </div>
</section>


        
    </main><!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->


    <script>
        function post(){
            var text = document.getElementById("mytext").value;
            alert(text);
        }
        function reset(){
            document.getElementById("mytext").value = "";
        }
    </script>
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