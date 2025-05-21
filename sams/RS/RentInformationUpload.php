<?php

session_start(); // 啟動會話

// 檢查會話中是否存在有效的登入信息，檢查username是否存在資料庫中，如果不存在，則導向到登入頁面
if (!isset($_SESSION['username'])) {
    header('Location: ../system/login.html'); // 使用相對路徑進行重定向
    exit;
}

// 包含資料庫連接文件
include 'db_connect.php';

// 獲取會話中的參數
$permissionLevel = $_SESSION['permissionLevel'];
$username = $_SESSION['username'];

// 判斷 permissionLevel 是否為 "房東"
if ($permissionLevel !== "房東") {
    echo "您沒有管理員權限訪問此頁面。";
    exit;
}


// 檢查是否有表單送出
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db_connect.php';

    // 接收表單資料
    $rentTitle = $_POST['RentTitle'];
    $age = isset($_POST['Age']) ? $_POST['Age'] : NULL;
    $type = isset($_POST['Type']) ? $_POST['Type'] : NULL;
    $address = $_POST['Address'];
    $rent = $_POST['Rent'];
    $waterFee = $_POST['WaterFee'];
    $electricityFee = $_POST['ElectricityFee'];
    $contactPerson = $_POST['ContactPerson'];
    $photo = isset($_POST['Photo']) ? $_POST['Photo'] : NULL;
    $startDate = $_POST['StartDate'];
    $endDate = isset($_POST['EndDate']) ? $_POST['EndDate'] : NULL;
    $internet = isset($_POST['Internet']) ? 1 : 0;
    $bed = isset($_POST['Bed']) ? 1 : 0;
    $airConditioner = isset($_POST['AirConditioner']) ? 1 : 0;
    $refrigerator = isset($_POST['Refrigerator']) ? 1 : 0;
    $petAllowed = isset($_POST['PetAllowed']) ? 1 : 0;
    $washingMachine = isset($_POST['WashingMachine']) ? 1 : 0;
    $tv = isset($_POST['TV']) ? 1 : 0;
    $cableTV = isset($_POST['CableTV']) ? 1 : 0;
    $waterHeater = isset($_POST['WaterHeater']) ? 1 : 0;
    $gas = isset($_POST['Gas']) ? 1 : 0;
    $wardrobe = isset($_POST['Wardrobe']) ? 1 : 0;
    $desk = isset($_POST['Desk']) ? 1 : 0;
    $elevator = isset($_POST['Elevator']) ? 1 : 0;
    $carParkingSpace = isset($_POST['CarParkingSpace']) ? 1 : 0;
    $motorbikeParkingSpace = isset($_POST['MotorbikeParkingSpace']) ? 1 : 0;
    $detail = isset($_POST['Detail']) ? $_POST['Detail'] : NULL;

    // 取得當前最大的InformationID
    $sql = "SELECT InformationID FROM rentalinformation ORDER BY InformationID DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maxID = $row['InformationID'];
        $newIDNum = (int)substr($maxID, 1) + 1;
        $informationID = 'R' . str_pad($newIDNum, 3, '0', STR_PAD_LEFT);
    } else {
        // 如果表為空，從R001開始
        $informationID = 'R001';
    }

    // 準備 SQL 語句
    $sql = "INSERT INTO rentalinformation (
        InformationID, RentTitle, Username, Age, Type, Address, Rent, WaterFee, ElectricityFee, ContactPerson, Photo, StartDate, EndDate, Internet, Bed, AirConditioner, Refrigerator, PetAllowed, WashingMachine, TV, CableTV, WaterHeater, Gas, Wardrobe, Desk, Elevator, CarParkingSpace, MotorbikeParkingSpace, Detail, Review
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $review = 'invalid';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssissdddssssssiiiiiiiiiiiiiss",
        $informationID,
        $rentTitle,
        $_SESSION['username'],
        $age,
        $type,
        $address,
        $rent,
        $waterFee,
        $electricityFee,
        $contactPerson,
        $photo,
        $startDate,
        $endDate,
        $internet,
        $bed,
        $airConditioner,
        $refrigerator,
        $petAllowed,
        $washingMachine,
        $tv,
        $cableTV,
        $waterHeater,
        $gas,
        $wardrobe,
        $desk,
        $elevator,
        $carParkingSpace,
        $motorbikeParkingSpace,
        $detail,
        $review
    );

    if ($stmt->execute()) {
        // 處理圖片上傳
        if (isset($_FILES['files']['tmp_name'])) {
            foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
                //處理上傳檔案
                if (is_uploaded_file($tmp_name)) {
                    $imgData = file_get_contents($tmp_name);
                    $imgStmt = $conn->prepare("INSERT INTO rentalimages (InformationID, Image) VALUES (?, ?)");
                    $imgStmt->bind_param("sb", $informationID, $imgData);
                    $imgStmt->send_long_data(1, $imgData);
                    $imgStmt->execute();
                    $imgStmt->close();
                }

                /*$imgData = addslashes(file_get_contents($tmp_name));
                $insert = $conn->query("INSERT INTO rentalimages (InformationID, Image) VALUES ('$informationID', '$imgData')");
                if ($insert) {
                    echo "The file has been uploaded successfully.";
                } else {
                    echo "File upload failed, please try again.";
                }*/
            }
        }
        echo "新租屋資訊已成功新增。";
        header("Location: rentInformation.php");
    } else {
        echo "錯誤: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
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
    <title>租屋資訊上傳</title>

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
</head>

<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">

        <!-- ============================================-->

        <!-- ============================================-->

        <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container"><a class="navbar-brand" href="landlordPage.php"><img src="static/picture/nuk.png" height="45" alt="logo"></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">
                    </span></button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                        <li class="nav-item px-2"><a class="nav-link active" aria-current="page" href="landlordPage.php">房東主頁</a></li>
 

                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($permissionLevel); ?> <?php echo htmlspecialchars($username); ?></a>
                        </li>
                    </ul>
                    <div id="userStatus" class="ms-lg-2">
                        <a class="btn btn-primary" href=" ../system/logout.php" role="button">登出</a>
                    </div>


                </div>
            </div>
        </nav>
        <section class="pt-6 bg-600" id="home">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-7 col-lg-6">
                        <style>
                            .form-container {
                                border: 2px solid #ccc;
                                /* 邊框顏色和寬度 */
                                border-radius: 15px;
                                /* 圓角半徑 */
                                padding: 30px;
                                /* 內邊距 */
                                margin: 15px 0;
                                /* 外邊距 */
                            }
                        </style>
                        <h1 class="fw-bold font-sans-serif">租屋資訊上傳</h1>
                        <br>
                        <form action="rentInformationUpload.php" method="post" enctype="multipart/form-data">
                            <!-- 圓角矩形包裹區域開始 -->
                            <div class="form-container">
                                <!-- 系級資訊區域 -->
                                <div class="form-group">
                                    <p>標題：<input type="text" name="RentTitle" style="width: 100%;" required></p>
                                </div>
                                <div class="form-group">
                                    <p>屋齡：<input type="number" name="Age"></p>
                                </div>
                                <div class="form-group">
                                    <p>房屋類型：<input type="text" name="Type"></p>
                                </div>
                                <div class="form-group">
                                    <p>地址：<input type="text" name="Address" style="width: 100%;"></p>
                                </div>
                                <div class="form-group">
                                    <p>租金：<input type="text" name="Rent" size="10">/月</p>
                                </div>
                                <div class="form-group">
                                    <p>水費：<input type="text" name="WaterFee" size="10">/月</p>
                                </div>
                                <div class="form-group">
                                    <p>電費：<input type="text" name="ElectricityFee" size="10">/度</p>
                                </div>
                                <div class="form-group">
                                    <p>聯絡人電話：<input type="text" name="ContactPerson" size="20"></p>
                                </div>
                                <div class="form-group">
                                    <p>開始日期：<input type="date" name="StartDate" required></p>
                                    <p>結束日期：<input type="date" name="EndDate" required></p>
                                </div>
                                <div class="form-group">
                                    <p>其他(可複選):
                                        <input type="checkbox" name="Internet" checked>網路
                                        <input type="checkbox" name="Bed">床
                                        <input type="checkbox" name="AirConditioner">冷氣
                                        <input type="checkbox" name="Refrigerator">冰箱
                                        <input type="checkbox" name="PetAllowed">養寵物
                                        <input type="checkbox" name="WashingMachine">洗衣機
                                        <br />
                                        <input type="checkbox" name="TV">電視
                                        <input type="checkbox" name="CableTV">第四台
                                        <input type="checkbox" name="WaterHeater">熱水器
                                        <input type="checkbox" name="Gas">瓦斯
                                        <input type="checkbox" name="Wardrobe">衣櫃
                                        <input type="checkbox" name="Desk">桌子
                                        <input type="checkbox" name="Elevator">電梯
                                    </p>
                                    <input type="checkbox" name="CarParkingSpace">汽車停車位</p>
                                    <input type="checkbox" name="MotorbikeParkingSpace">機車停車位</p>
                                </div>
                                <div>
                                    <p>上傳照片</p>
                                </div>
                                <div class="form-group">
                                    選擇檔案: <input type="file" name="files[]" multiple /><br />
                                </div>
                                <div>
                                    <p style="font-size: 25px;">詳細內容</p>
                                    <textarea style="width: 100%; height: 200px; resize: none;" name="Detail"></textarea>
                                </div>
                            </div>

                            <div style="margin-bottom: 20px;"></div>
                            <div style="margin-bottom: 20px;"></div>
                            <button type="submit" class="btn btn-primary">上傳</button>
                            <a class="btn btn-primary" href="rentInformation.php">取消</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- ============================================-->
        <!-- <section> begin ============================-->
        <section>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 col-lg-4 mb-5"></div>
                    <div class="col-md-6 col-lg-5">

                    </div>
                </div>
            </div><!-- end of .container-->
        </section><!-- <section> close ============================-->
        <!-- ============================================-->

        <!-- ============================================-->

        <!-- ============================================-->

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