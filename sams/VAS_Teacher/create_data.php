<?php
// 包含資料庫連接文件
include_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 獲取表單提交的數據
    $VisitID = $_POST['VisitID'];
    $VisitDate = $_POST['VisitDate'];
    $DepositRequirement = $_POST['DepositRequirement'];
    $UtilityRequirement = $_POST['UtilityRequirement'];
    $LivingEnvironment = $_POST['LivingEnvironment'];
    $LivingFacilities = $_POST['LivingFacilities'];
    $VisitStatus = $_POST['VisitStatus'];
    $HostGuestInteraction = $_POST['HostGuestInteraction'];

    // 使用 isset 函數來設置復選框的值
    $GoodCondition = isset($_POST['GoodCondition']) ? '是' : '否';
    $ContactParents = isset($_POST['ContactParents']) ? '是' : '否';
    $AssistanceNeeded = isset($_POST['AssistanceNeeded']) ? '是' : '否';
    $AdditionalNotes = isset($_POST['AdditionalNotes']) ? htmlspecialchars($_POST['AdditionalNotes'], ENT_QUOTES) : '';
    $TrafficSafety = isset($_POST['TrafficSafety']) ? '是' : '否';
    $NoSmoking = isset($_POST['NoSmoking']) ? '是' : '否';
    $NoDrugs = isset($_POST['NoDrugs']) ? '是' : '否';
    $DenguePrevention = isset($_POST['DenguePrevention']) ? '是' : '否';
    $Other = isset($_POST['Other']) ? htmlspecialchars($_POST['Other'], ENT_QUOTES) : '';

    // For debugging: Print all received POST data (optional, for development purposes)
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    // 更新數據庫中的相應記錄
    $sql = "
        UPDATE VisitRecord 
        SET 
            VisitDate = ?, 
            DepositRequirement = ?, 
            UtilityRequirement = ?, 
            LivingEnvironment = ?, 
            LivingFacilities = ?, 
            VisitStatus = ?, 
            HostGuestInteraction = ?, 
            GoodCondition = ?, 
            ContactParents = ?, 
            AssistanceNeeded = ?, 
            AdditionalNotes = ?, 
            TrafficSafety = ?, 
            NoSmoking = ?, 
            NoDrugs = ?, 
            DenguePrevention = ?, 
            Other = ? 
        WHERE VisitID = ?
    ";

    // 準備和執行 SQL 語句
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters and check for errors
    $bind = $stmt->bind_param(
        "sssssssssssssssss", // 16 's' for 16 string parameters
        $VisitDate, 
        $DepositRequirement,
        $UtilityRequirement,
        $LivingEnvironment,
        $LivingFacilities,
        $VisitStatus,
        $HostGuestInteraction,
        $GoodCondition,
        $ContactParents,
        $AssistanceNeeded,
        $AdditionalNotes,
        $TrafficSafety,
        $NoSmoking,
        $NoDrugs,
        $DenguePrevention,
        $Other,
        $VisitID
    );

    if ($bind === false) {
        die('Bind param failed: ' . htmlspecialchars($stmt->error));
    }

    // Execute and check for errors
    if ($stmt->execute()) {
        echo "<script>
            alert('記錄更新成功');
            window.location.href = 'QueryViewRecord.php?VisitID=$VisitID';
        </script>";
    } else {
        echo "更新記錄時出錯: " . htmlspecialchars($stmt->error);
    }

    // 關閉語句和連接
    $stmt->close();
    $conn->close();
}

exit();
?>
