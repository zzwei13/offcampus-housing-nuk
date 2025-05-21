<?php
session_start(); // 啟動會話

// 檢查用戶是否已登入及是否為房東
if (!isset($_SESSION['username']) || $_SESSION['permissionLevel'] !== '房東') {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db_connect.php';

    $informationID = $_POST['InformationID'];
    $rentTitle = $_POST['RentTitle'];
    $age = $_POST['Age'];
    $type = $_POST['Type'];
    $address = $_POST['Address'];
    $rent = $_POST['Rent'];
    $waterFee = $_POST['WaterFee'];
    $electricityFee = $_POST['ElectricityFee'];
    $contactPerson = $_POST['ContactPerson'];
    $photo = $_POST['Photo'];
    $startDate = $_POST['StartDate'];
    $endDate = $_POST['EndDate'];
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
    $detail = $_POST['Detail'];

    // 準備 SQL 語句
    $sql = "UPDATE rentalinformation SET RentTitle=?, Age=?, Type=?, Address=?, Rent=?, WaterFee=?, ElectricityFee=?, ContactPerson=?, Photo=?, StartDate=?, EndDate=?, Internet=?, Bed=?, AirConditioner=?, Refrigerator=?, PetAllowed=?, WashingMachine=?, TV=?, CableTV=?, WaterHeater=?, Gas=?, Wardrobe=?, Desk=?, Elevator=?, CarParkingSpace=?, MotorbikeParkingSpace=?, Detail=?, Review=? WHERE InformationID=?";
    $review='invalid';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssssssssiiiiiiiiiiiiiiisss", 
    $rentTitle, 
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
    $review,
    $informationID);

    if ($stmt->execute()) {
        header('Location: rentInformationDetail.php?InformationID=' . $informationID);
        exit();
    } else {
        echo "<p>更新失敗。</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
