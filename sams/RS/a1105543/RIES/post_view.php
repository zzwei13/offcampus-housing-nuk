<?php
date_default_timezone_set('Asia/Taipei'); // Replace 'Asia/Taipei' with your desired timezone

session_start();
// 檢查是否已登入且用戶為管理員
if (!isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] != '管理員') {
    // 如果不是管理員，則導向到主頁面或登入頁面
    header('Location: login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'connect.php';
    
    $post_id = $_POST['post_id'];

    // 刪除所有留言
    $sql = "DELETE FROM comments WHERE PostID = ?";
    
    // 預處理和綁定
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $post_id);
    
    // 執行語句
    $stmt->execute();
    
    // 刪除文章
    $sql = "DELETE FROM forum WHERE PostID = ?";
    
    // 預處理和綁定
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $post_id);
    
    // 執行語句
    $stmt->execute();
    
    $stmt->close();
    $conn->close();

    echo '<script type="text/javascript">';
    echo 'alert("已刪除");';
    echo 'window.location.href = "post_delete.php"';
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

.post-container {
    width: 700px;
    height: 700px;
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
    width: 300px;
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
    margin-bottom: 320px;
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
    
    <section class="pt-6 bg-600" id="home">
        
        <div class="d-flex justify-content-center  min-vh-100">
            <div align="center" class="mt-4">
                <a href="post_delete.php" style="display: inline-block; margin-right: 685px; margin-bottom: 45px; text-decoration: none;">
                    <div style="font-size: 24px;">&lt;</div>
                </a>
                    <?php
                    require 'connect.php';
                    //接收PostID
                    $post_id=$_GET['post_id'];
                    $query = "SELECT a.PostID, a.Title, a.Content, a.PostDate, COUNT(c.CommentID) AS CommentCount, 
                     CONCAT(
                         CASE WHEN a.PostType = '學生' THEN s.Name ELSE t.Name END,
                         ' (',
                         CASE WHEN a.PostType = '學生' THEN '學生' ELSE '老師' END,
                         ')'
                     ) AS PosterName
                    FROM forum a
                    LEFT JOIN students s ON a.PosterID = s.StudentID AND a.PostType = '學生'
                    LEFT JOIN teachers t ON a.PosterID = t.TeacherID AND a.PostType = '老師'
                    LEFT JOIN comments c ON a.PostID = c.PostID
                    WHERE a.PostID = '$post_id'
                    GROUP BY a.PostID";
          
                    $result = mysqli_query($conn, $query);

                    // Check if query executed successfully
                    if ($result) {
                        // Check if there are rows returned
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="post-container" style="position: relative">';
                                echo '<h1 class="fw-medium text-start font-sans-serif">' . $row['Title'] . '</h1>';
                                echo '<p align="left">發文者: ' . $row['PosterName'] . '</p>';
                                // Display Post Date發文日期: 
                                echo '<p align="left" >' . $row['PostDate'] . '</p>';
                                echo '<p class="pbottom text-start">' . $row['Content'] . '</p>';
                                
                                // Reply form
                                echo '<form method="post">';
    echo '<input type="hidden" name="post_id" value=' . $row['PostID'] . '>';
    echo '<input type="submit" value="刪除">';
    echo '</form>';

                                echo '</div>';

                                // View comments button
                                echo '<div class="mt-4 d-flex justify-content-center align-items-center" style="margin-bottom: 20px;">';
                                echo '<a href="post_comment.php?post_id=' . $row['PostID'] . '" class="btn btn-primary">查看留言 (' . $row['CommentCount'] . ')</a>';
                                echo '</div>';
                            }
                        } else {
                            echo '未找到相應的文章'; // Handle case where no rows found
                        }
                    } else {
                        // Handle query execution error
                        echo 'Error: ' . mysqli_error($conn);
                    }
                    ?>
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