<?php
session_start(); // 啟動會話

// 檢查用戶是否已登入及是否為管理員
if (!isset($_SESSION['username']) || $_SESSION['permissionLevel'] !== '管理員') {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require 'connect.php';

$info_id = $_GET['info_id'];

// 準備SQL語句
$sql = "DELETE FROM rentalinformation WHERE InformationID = ?";

// 預處理和綁定
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $info_id);

// 執行語句
$stmt->execute();

$stmt->close();
$conn->close();
    echo '<script>';
    echo 'window.location.href = "ad_view.php"';
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
    <title>詳細資訊</title>
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

        <section class="pt-6 bg-600" id="home">
            <div class="container">
                <h1 style="display: block" class="fw-bold font-sans-serif">租屋資訊</h1>
                <br>
                <table class="table caption-top table-striped">
                    <thead>
                    </thead>
                    <tbody>
                        <?php
                        require 'connect.php';

                        //接收info_id
                        $info_id = $_GET['info_id'];
                        
                        
                            $stmt = $conn->prepare("SELECT * FROM rentalinformation WHERE InformationID = ?");
                            $stmt->bind_param("s", $info_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                                // 輸出資料
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><th>ID</th><td>" . $row["InformationID"] . "</td></tr>";
                                    echo "<tr><th>標題</th><td>" . $row["RentTitle"] . "</td></tr>";
                                    echo "<tr><th>屋齡</th><td>" . $row["Age"] . "</td></tr>";
                                    echo "<tr><th>房屋類型</th><td>" . $row["Type"] . "</td></tr>";
                                    echo "<tr><th>地址</th><td>" . $row["Address"] . "</td></tr>";
                                    echo "<tr><th>租金</th><td>" . $row["Rent"] . "</td></tr>";
                                    echo "<tr><th>水費</th><td>" . $row["WaterFee"] . "</td></tr>";
                                    echo "<tr><th>電費</th><td>" . $row["ElectricityFee"] . "</td></tr>";
                                    echo "<tr><th>聯絡人電話</th><td>" . $row["ContactPerson"] . "</td></tr>";
                                    echo "<tr><th>開始日期</th><td>" . $row["StartDate"] . "</td></tr>";
                                    echo "<tr><th>結束日期</th><td>" . $row["EndDate"] . "</td></tr>";
                                    echo "<tr><th>設施</th><td>";
                                    echo $row["Internet"] ? "網路, " : "";
                                    echo $row["Bed"] ? "床, " : "";
                                    echo $row["AirConditioner"] ? "冷氣, " : "";
                                    echo $row["Refrigerator"] ? "冰箱, " : "";
                                    echo $row["PetAllowed"] ? "允許養寵物, " : "";
                                    echo $row["WashingMachine"] ? "洗衣機, " : "";
                                    echo $row["TV"] ? "電視, " : "";
                                    echo $row["CableTV"] ? "第四台, " : "";
                                    echo $row["WaterHeater"] ? "熱水器, " : "";
                                    echo $row["Gas"] ? "瓦斯, " : "";
                                    echo $row["Wardrobe"] ? "衣櫃, " : "";
                                    echo $row["Desk"] ? "桌子, " : "";
                                    echo $row["Elevator"] ? "電梯, " : "";
                                    echo $row["CarParkingSpace"] ? "汽車停車位, " : "";
                                    echo $row["MotorbikeParkingSpace"] ? "機車停車位, " : "";
                                    echo "<tr><th>詳細內容</th><td>" . $row["Detail"] . "</td></tr>";
                                    echo "</td></tr>";
                                }
                        
                                // 顯示照片
                                $stmt = $conn->prepare("SELECT Image FROM rentalimages WHERE InformationID = ?");
                                $stmt->bind_param("s", $info_id);
                                $stmt->execute();
                                $imgResult = $stmt->get_result();
                        
                                echo "<tr><th>照片</th><td>";
                                while ($imgRow = $imgResult->fetch_assoc()) {
                                    echo '<img src="data:image/jpeg;base64,' . base64_encode($imgRow['Image']) . '" height="200"/><br>';
                                }
                                echo "</td></tr>";
                            } else {
                                echo "<p>未找到 ID 為 $info_id 的租屋資訊。</p>";
                            }
                        
                        ?>
                    </tbody>
                </table>
                <form method="post">
                    <input type="submit" name="delete" value="刪除">
                </form>
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