<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
$name = $_SESSION['username'];
//username=JIN
$permissionLevel = $_SESSION['permissionLevel']; 
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
    <title>學生住宿資料表單</title>
    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="static/image/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="static/image/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="static/image/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="static/image/favicon.ico">
    <link rel="manifest" href="static/image/manifest.json">
    <meta name="msapplication-TileImage" content="static/image/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <link href="static/css/theme.min.css" rel="stylesheet">
    <link href="modify.css" rel="stylesheet">
  </head>
  <style>
    .btn-primary {
      min-width: 80px; /* 調整這個寬度以適應按鈕的內容 */
      white-space: nowrap; /* 禁止換行 */
    }
    .card-body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .card {
      margin-bottom: 10px; /* 調整卡片之間的距離 */
    }
  </style>
  
  <body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
      <!-- ============================================-->
      <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block"
            data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container">
              <a class="navbar-brand" href="HouseInfoManage.php"><img src="static/picture/nuk.png" height="45" alt="logo"></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page"
                                href="rental_info.php">租屋資訊</a></li>
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page"
                                href="forum.php">交流平台</a></li>
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page" href="stuProfile.php">
                                基本資料</a></li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($permissionLevel); ?> <?php echo htmlspecialchars($name); ?></a>
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
      <!-- <section> begin ============================-->

      <section class="pt-6 bg-600" id="home">
        <div class="container2">
          <form action="house_form.php" method="post">
            <div class="row align-items-center">
              <div class="123">
                <h1 class="fw-bold font-sans-serif">學生住宿資料表單</h1>
                <br>
                <div class="form-group">
                    <label for="department">系級：</label>
                    <span id="department"></span>
                </div>
                <div class="form-group">
                    <label for="student_id">學號：</label>
                    <span id="student_id"></span>
                </div>
                <div class="form-group">
                    <label for="name">姓名：</label>
                    <span id="name"></span>
                </div>
                <div class="form-group">
                    <label for="phone">電話：</label>
                    <span id="phone"></span>
                </div>
                
                <div class="form-group">
                    <label for="address">住宿地址：</label>
                    <span id="address"></span>
                </div>


                <br>
                <div class="form-group">
                  <label for="year">學年：</label>
                  <input type="text" id="year" name="year">
                </div>
                <div class="form-group">
                  <label for="semester">學期：</label>
                  <input type="text" id="semester" name="semester">
                </div>
                <div class="form-group">
                  <label for="landlord_name">房東姓名：</label>
                  <input type="text" id="landlord_name" name="landlord_name">
                </div>
                <div class="form-group">
                  <label for="landlord_phone">房東電話：</label>
                  <input type="text" id="landlord_phone" name="landlord_phone">
                </div>

                <div class="form-group">
                  <label for="monthly_rent">每月租金：</label>
                  <input type="number" id="monthly_rent" name="monthly_rent">
                </div>
                <div class="form-group">
                  <label for="deposit">押金：</label>
                  <input type="number" id="deposit" name="deposit">
                </div>
                <br>
                <div class="form-group">
                  <label>租屋型態：</label>
                      <div class="radio-group">
                        <input type="radio" id="studio" name="room" value="套房">
                        <label for="studio">套房</label>
                        <input type="radio" id="share" name="room" value="雅房">
                        <label for="share">雅房</label>
                      </div>
                    </div>
                  <div class="form-group">
                    <label>房間類型：</label>
         

                    <div class="form-group">
                      <input type="radio" id="house" name="house_type" value="獨棟透天">
                      <label for="house">獨棟透天</label>

                      <input type="radio" id="apartment" name="house_type" value="公寓(五樓以下)">
                      <label for="apartment">公寓(五樓以下)</label>

                      <input type="radio" id="community" name="house_type" value="大樓(六樓以上)">
                      <label for="community">大樓(六樓以上)</label>
                      
                      <input type="radio" id="dorm" name="house_type" value="大型學舍">
                      <label for="dorm">大型學舍</label>
                    </div>
                  </div>
                
                <div class="form-group">
                  <label>是否推薦其他同學租賃：</label>
                  <div class="radio-group">
                    <input type="radio" id="recommend_yes" name="recommend" value="是">
                    <label for="recommend_yes">是</label>
                    <input type="radio" id="recommend_no" name="recommend" value="否">
                    <label for="recommend_no">否</label>
                  </div>
                </div>
                <hr>
                <div class="form-group">
                  <label>木造隔間或鐵皮加蓋：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_1_yes" name="safe_1" value="是">
                    <label for="safe_1_yes">是</label>
                    <input type="radio" id="safe_1_no" name="safe_1" value="否">
                    <label for="safe_1_no">否</label>
                  </div>
                </div>
                <div class="form-group">
                  <label>使用多種電器(高耗能)，是否同時插在同一條延長線：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_2_yes" name="safe_2" value="是">
                    <label for="safe_2_yes">是</label>
                    <input type="radio" id="safe_2_no" name="safe_2" value="否">
                    <label for="safe_2_no">否</label>
                  </div>
                </div>

                <div class="form-group">
                  <label>火警警報器或偵測器：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_3_yes" name="safe_3" value="是">
                    <label for="safe_3_yes">是</label>
                    <input type="radio" id="safe_3_no" name="safe_3" value="否">
                    <label for="safe_3_no">否</label>
                  </div>
                </div>

                <div class="form-group">
                  <label>有滅火器且功能正常：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_4_yes" name="safe_4" value="是">
                    <label for="safe_4_yes">是</label>
                    <input type="radio" id="safe_4_no" name="safe_4" value="否">
                    <label for="safe_4_no">否</label>
                  </div>
                </div>

                <div class="form-group">
                  <label>熱水器安全良好，無一氧化炭中毒：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_5_yes" name="safe_5" value="是">
                    <label for="safe_5_yes">是</label>
                    <input type="radio" id="safe_5_no" name="safe_5" value="否">
                    <label for="safe_5_no">否</label>
                  </div>
                </div>

                <div class="form-group">
                  <label>逃生通道順暢：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_6_yes" name="safe_6" value="是">
                    <label for="safe_6_yes">是</label>
                    <input type="radio" id="safe_6_no" name="safe_6" value="否">
                    <label for="safe_6_no">否</label>
                  </div>
                </div>
                <div class="form-group">
                  <label>門禁及鎖具良好管理：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_7_yes" name="safe_7" value="是">
                    <label for="safe_7_yes">是</label>
                    <input type="radio" id="safe_7_no" name="safe_7" value="否">
                    <label for="safe_7_no">否</label>
                  </div>
                </div>

                <div class="form-group">
                  <label>分間6個以上房間或10個以上床位：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_8_yes" name="safe_8" value="是">
                    <label for="safe_8_yes">是</label>
                    <input type="radio" id="safe_8_no" name="safe_8" value="否">
                    <label for="safe_8_no">否</label>
                  </div>
                </div>
                <div class="form-group">
                  <label>有安裝照明設備(停車場及周邊)：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_9_yes" name="safe_9" value="是">
                    <label for="safe_9_yes">是</label>
                    <input type="radio" id="safe_9_no" name="safe_9" value="否">
                    <label for="safe_9_no">否</label>
                  </div>
                </div>

                <div class="form-group">
                  <label>有安裝監視器：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_10_yes" name="safe_10" value="是">
                    <label for="safe_10_yes">是</label>
                    <input type="radio" id="safe_10_no" name="safe_10" value="否">
                    <label for="safe_10_no">否</label>
                  </div>
                </div>

                <div class="form-group">
                  <label>了解熟悉電路安全及逃生要領：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_11_yes" name="safe_11" value="是">
                    <label for="safe_11_yes">是</label>
                    <input type="radio" id="safe_11_no" name="safe_11" value="否">
                    <label for="safe_11_no">否</label>
                  </div>
                </div>

                
                <div class="form-group">
                  <label>使用內政部定型化租賃契約：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_12_yes" name="safe_12" value="是">
                    <label for="safe_12_yes">是</label>
                    <input type="radio" id="safe_12_no" name="safe_12" value="否">
                    <label for="safe_12_no">否</label>
                  </div>
                </div>
                <div class="form-group">
                  <label>熟悉派出所、醫療、消防隊、學校校安專線電話：</label>
                  <div class="radio-group">
                    <input type="radio" id="safe_13_yes" name="safe_13" value="是">
                    <label for="safe_13_yes">是</label>
                    <input type="radio" id="safe_13_no" name="safe_13" value="否">
                    <label for="safe_13_no">否</label>
                  </div>
                </div>
                <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary">提交</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </section>

      <?php
      require_once 'connect.php';  // 確認包含正確路徑

      $sql_userid = "SELECT UserID FROM accountmanagement WHERE Username = '$name'";
      $result_userid = $conn->query($sql_userid);

      if ($result_userid === false) {

      } elseif ($result_userid->num_rows > 0) {
          // 獲取 UserID
          $row_userid = $result_userid->fetch_assoc();
          $usr = $row_userid['UserID'];
          

          // 使用查詢到的 UserID 進行下一步查詢
          $sql = "SELECT Department, Name, StudentID, ContactPhone, HomeAddress FROM students WHERE StudentID = '$usr'";
          

          $result = $conn->query($sql);

          if ($result === false) {
              
          } elseif ($result->num_rows > 0) {
              // 輸出第一行數據
              $row = $result->fetch_assoc();
              echo "<script>
                      document.getElementById('department').textContent = '" . htmlspecialchars($row["Department"], ENT_QUOTES) . "';
                      document.getElementById('student_id').textContent = '" . htmlspecialchars($row["StudentID"], ENT_QUOTES) . "';
                      document.getElementById('name').textContent = '" . htmlspecialchars($row["Name"], ENT_QUOTES) . "';
                      document.getElementById('phone').textContent = '" . htmlspecialchars($row["ContactPhone"], ENT_QUOTES) . "';
                      document.getElementById('address').textContent = '" . htmlspecialchars($row["HomeAddress"], ENT_QUOTES) . "';
                    </script>";
          } else {
              echo "<p>沒有找到資料</p>";
          }
      } else {
          echo "<p>用戶不存在</p>";
      }

      $conn->close();
      ?>
  
  <script src="static/js/popper.min.js"></script>
  <script src="static/js/bootstrap.min.js"></script>
  <script src="static/js/is.min.js"></script>
  <script src="static/js/polyfill.min.js"></script>
  <script src="static/js/all.min.js"></script>
  <script src="static/js/theme.js"></script>
  <link href="static/css/css2.css" rel="stylesheet">
  </body>

</html>



