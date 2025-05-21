<?php
session_start(); // 啟動會話

// 檢查用戶是否已登入及是否為房東
if (!isset($_SESSION['username']) || $_SESSION['permissionLevel'] !== '房東') {
    header('Location: ../system/login.html');
    exit();
}

if (!isset($_GET['InformationID'])) {
    echo "<p>請提供 InformationID。</p>";
    exit();
}

$informationID = $_GET['InformationID'];

require_once 'db_connect.php';
// 獲取會話中的參數
$permissionLevel = $_SESSION['permissionLevel'];
$username = $_SESSION['username'];

// 判斷 permissionLevel 是否為 "房東"
if ($permissionLevel !== "房東") {
    echo "您沒有管理員權限訪問此頁面。";
    exit;
}
$stmt = $conn->prepare("SELECT * FROM rentalinformation WHERE InformationID = ?");
$stmt->bind_param("s", $informationID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "<p>未找到 ID 為 $informationID 的租屋資訊。</p>";
    exit();
}

$stmt->close();
$conn->close();
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
    <title>租屋資訊</title>

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

        <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block"
            data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container"><a class="navbar-brand" href="landlordPage.php"><img src="static/picture/nuk.png" height="45"
                        alt="logo"></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">
                    </span></button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                        <li class="nav-item px-2"><a class="nav-link active" aria-current="page" href="landlordPage.php">房東主頁</a></li>
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page"
                                href="pricing.html">租屋資訊</a></li>
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page"
                                href="web-development.html">交流平台</a></li>
                        
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
                <h1 class="fw-bold font-sans-serif">編輯租屋資訊</h1>
                <form action="rentInformationUpdate.php" method="POST">
                    <input type="hidden" name="InformationID" value="<?php echo $informationID; ?>">
                    <div class="mb-3">
                        <label for="RentTitle" class="form-label">標題</label>
                        <input type="text" class="form-control" id="RentTitle" name="RentTitle" value="<?php echo $row['RentTitle']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Age" class="form-label">屋齡</label>
                        <input type="number" class="form-control" id="Age" name="Age" value="<?php echo $row['Age']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Type" class="form-label">房屋類型</label>
                        <input type="text" class="form-control" id="Type" name="Type" value="<?php echo $row['Type']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Address" class="form-label">地址</label>
                        <input type="text" class="form-control" id="Address" name="Address" value="<?php echo $row['Address']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Rent" class="form-label">租金</label>
                        <input type="number" class="form-control" id="Rent" name="Rent" value="<?php echo $row['Rent']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="WaterFee" class="form-label">水費</label>
                        <input type="number" class="form-control" id="WaterFee" name="WaterFee" value="<?php echo $row['WaterFee']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="ElectricityFee" class="form-label">電費</label>
                        <input type="number" class="form-control" id="ElectricityFee" name="ElectricityFee" value="<?php echo $row['ElectricityFee']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="ContactPerson" class="form-label">聯絡人電話</label>
                        <input type="text" class="form-control" id="ContactPerson" name="ContactPerson" value="<?php echo $row['ContactPerson']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="StartDate" class="form-label">開始日期</label>
                        <input type="date" class="form-control" id="StartDate" name="StartDate" value="<?php echo $row['StartDate']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="EndDate" class="form-label">結束日期</label>
                        <input type="date" class="form-control" id="EndDate" name="EndDate" value="<?php echo $row['EndDate']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">設備</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Internet" name="Internet" <?php if ($row['Internet']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="Internet">網路</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Bed" name="Bed" <?php if ($row['Bed']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="Bed">床</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="AirConditioner" name="AirConditioner" <?php if ($row['AirConditioner']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="AirConditioner">冷氣</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Refrigerator" name="Refrigerator" <?php if ($row['Refrigerator']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="Refrigerator">冰箱</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="PetAllowed" name="PetAllowed" <?php if ($row['PetAllowed']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="PetAllowed">養寵物</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="WashingMachine" name="WashingMachine" <?php if ($row['WashingMachine']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="WashingMachine">洗衣機</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="TV" name="TV" <?php if ($row['TV']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="TV">電視</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="CableTV" name="CableTV" <?php if ($row['CableTV']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="CableTV">第四台</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="WaterHeater" name="WaterHeater" <?php if ($row['WaterHeater']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="WaterHeater">熱水器</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Gas" name="Gas" <?php if ($row['Gas']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="AirConGasditioner">瓦斯</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Wardrobe" name="Wardrobe" <?php if ($row['Wardrobe']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="Wardrobe">衣櫃</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Desk" name="Desk" <?php if ($row['Desk']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="Desk">桌子</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Elevator" name="Elevator" <?php if ($row['Elevator']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="Elevator">電梯</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="CarParkingSpace" name="CarParkingSpace" <?php if ($row['CarParkingSpace']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="CarParkingSpace">汽車停車位</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="MotorbikeParkingSpace" name="MotorbikeParkingSpace" <?php if ($row['MotorbikeParkingSpace']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="MotorbikeParkingSpace">機車停車位</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="Detail" class="form-label">詳細內容</label>
                        <textarea class="form-control" id="Detail" name="Detail"><?php echo $row['Detail']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-right: 10px;">提交</button>
                    <?php
                    echo "<a class=\"btn btn-primary\" href='rentInformationDetail.php?InformationID=" . $informationID . "'>取消</a>";
                    ?>
                </form>
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