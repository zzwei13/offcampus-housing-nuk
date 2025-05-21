<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../system/login.html");
    exit();
}
$name = $_SESSION['username'];
$permissionLevel = $_SESSION['permissionLevel']; 

require_once 'connect.php';

// 住宿表單ID(AccommodationID) 是由 學號+學年+學期 組成
function generateAccommodationID($studentID, $academicYear, $semester) {
    return $studentID . $academicYear . $semester;
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data with isset to prevent warnings
    $AcademicYear = isset($_POST['year']) ? $_POST['year'] : null;
    $Semester = isset($_POST['semester']) ? $_POST['semester'] : null;
    $LandlordName = isset($_POST['landlord_name']) ? $_POST['landlord_name'] : null;
    $LandlordPhoneNumber = isset($_POST['landlord_phone']) ? $_POST['landlord_phone'] : null;
    $MonthlyRent = isset($_POST['monthly_rent']) ? $_POST['monthly_rent'] : null;
    $Deposit = isset($_POST['deposit']) ? $_POST['deposit'] : null;
    $HousingType = isset($_POST['room']) ? $_POST['room'] : null;
    $RentalType = isset($_POST['house_type']) ? $_POST['house_type'] : null;
    $RecommendOthers = isset($_POST['recommend']) ? $_POST['recommend'] : null;
    $WoodenPartitionsOrIronSheet = isset($_POST['safe_1']) ? $_POST['safe_1'] : null;
    $HighPowerDevicesOnSingleExtension = isset($_POST['safe_2']) ? $_POST['safe_2'] : null;
    $FireAlarmOrSmokeDetector = isset($_POST['safe_3']) ? $_POST['safe_3'] : null;
    $FunctionalFireExtinguisher = isset($_POST['safe_4']) ? $_POST['safe_4'] : null;
    $SafeWaterHeater = isset($_POST['safe_5']) ? $_POST['safe_5'] : null;
    $ClearEscapeRoute = isset($_POST['safe_6']) ? $_POST['safe_6'] : null;
    $GoodSecurity = isset($_POST['safe_7']) ? $_POST['safe_7'] : null;
    $MoreThan6RoomsOr10Beds = isset($_POST['safe_8']) ? $_POST['safe_8'] : null;
    $InstalledLighting = isset($_POST['safe_9']) ? $_POST['safe_9'] : null;
    $InstalledCCTV = isset($_POST['safe_10']) ? $_POST['safe_10'] : null;
    $FamiliarWithSafetyProcedures = isset($_POST['safe_11']) ? $_POST['safe_11'] : null;
    $StandardLeaseContract = isset($_POST['safe_12']) ? $_POST['safe_12'] : null;
    $FamiliarWithEmergencyContacts = isset($_POST['safe_13']) ? $_POST['safe_13'] : null;


    // Validate required fields
    if (empty($AcademicYear) || empty($Semester) || empty($LandlordName) || empty($LandlordPhoneNumber) || empty($MonthlyRent) || empty($Deposit) || empty($HousingType) || empty($RentalType) || 
        empty($RecommendOthers) || empty($WoodenPartitionsOrIronSheet) || empty($HighPowerDevicesOnSingleExtension) || empty($FireAlarmOrSmokeDetector) || empty($FunctionalFireExtinguisher) || 
        empty($SafeWaterHeater) || empty($ClearEscapeRoute) || empty($GoodSecurity) || empty($MoreThan6RoomsOr10Beds) || empty($InstalledLighting) || empty($InstalledCCTV) || 
        empty($FamiliarWithSafetyProcedures) || empty($StandardLeaseContract) || empty($FamiliarWithEmergencyContacts)) {
        echo "<script>
                alert('更新記錄時出錯，請重新填寫表單');
                window.location.href = 'house_information.php';
              </script>";
        exit();
    }

    // Fetching data from the database
    $sql_userid = "SELECT UserID FROM accountmanagement WHERE Username = '$name'";
    $result_userid = $conn->query($sql_userid);

    if ($result_userid === false) {
        // Handle SQL error
        echo "<script>
                alert('資料庫查詢錯誤');
                window.location.href = 'house_information.php';
              </script>";
        exit();
    }

    if ($result_userid->num_rows > 0) {
        // 獲取 UserID
        $row_userid = $result_userid->fetch_assoc();
        $usr = $row_userid['UserID'];

        $sql_fetch = "SELECT Department, StudentID, Name, ContactPhone, HomeAddress FROM students WHERE StudentID = '$usr'";
        $result_fetch = $conn->query($sql_fetch);
    }

    if ($result_fetch->num_rows > 0) {
        // Output data of the latest record
        $row_fetch = $result_fetch->fetch_assoc();
        $Department = $row_fetch["Department"];
        $StudentID = $row_fetch["StudentID"];
        $Name = $row_fetch["Name"];
        $Phone = $row_fetch["ContactPhone"];
        $Address = $row_fetch["HomeAddress"];
    } else {
        echo "<script>
                alert('No data found in the students table.');
                window.location.href = 'house_information.php';
              </script>";
        exit();
    }

    // Generate unique AccommodationID
    $AccommodationID = generateAccommodationID($StudentID, $AcademicYear, $Semester);

    // Prepare SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO studentaccommodation (AccommodationID, Department, StudentID, Name, Phone, Address, AcademicYear, Semester, LandlordName, LandlordPhoneNumber, MonthlyRent, Deposit, HousingType, RentalType, RecommendOthers, WoodenPartitionsOrIronSheet, HighPowerDevicesOnSingleExtension, FireAlarmOrSmokeDetector, FunctionalFireExtinguisher, SafeWaterHeater, ClearEscapeRoute, GoodSecurity, MoreThan6RoomsOr10Beds, InstalledLighting, InstalledCCTV, FamiliarWithSafetyProcedures, StandardLeaseContract, FamiliarWithEmergencyContacts) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Bind parameters and execute the statement
    $stmt->bind_param("ssssssssssssssssssssssssssss", $AccommodationID, $Department, $StudentID, $Name, $Phone, $Address, $AcademicYear, $Semester, $LandlordName, $LandlordPhoneNumber, $MonthlyRent, $Deposit, $HousingType, $RentalType, $RecommendOthers, $WoodenPartitionsOrIronSheet, $HighPowerDevicesOnSingleExtension, $FireAlarmOrSmokeDetector, $FunctionalFireExtinguisher, $SafeWaterHeater, $ClearEscapeRoute, $GoodSecurity, $MoreThan6RoomsOr10Beds, $InstalledLighting, $InstalledCCTV, $FamiliarWithSafetyProcedures, $StandardLeaseContract, $FamiliarWithEmergencyContacts);

    if ($stmt->execute()) {
        echo "<script>
                alert('記錄更新成功');
                window.location.href = 'house_choose.php';
              </script>";
    } else {
        echo "<script>
                alert('記錄更新失敗');
                window.location.href = 'house_information.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
