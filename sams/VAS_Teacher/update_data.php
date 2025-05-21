<?php
// 包含資料庫連接文件
include_once 'connect.php';

// 獲取表單提交的數據
$VisitID = $_POST['VisitID'];
$DepositRequirement = $_POST['DepositRequirement'];
$UtilityRequirement = $_POST['UtilityRequirement'];
$LivingEnvironment = $_POST['LivingEnvironment'];
$LivingFacilities = $_POST['LivingFacilities'];
$VisitStatus = $_POST['VisitStatus'];
$HostGuestInteraction = $_POST['HostGuestInteraction'];
// 使用 isset 函數來設置復選框的值
$GoodCondition = isset($_POST['GoodCondition']) && $_POST['GoodCondition'] === '是' ? '是' : '否';
$ContactParents = isset($_POST['ContactParents']) && $_POST['ContactParents'] === '是' ? '是' : '否';
$AssistanceNeeded = isset($_POST['AssistanceNeeded']) && $_POST['AssistanceNeeded'] === '是' ? '是' : '否';
$AdditionalNotes = isset($_POST['AdditionalNotes']) ? $_POST['AdditionalNotes'] : '';
$TrafficSafety = isset($_POST['TrafficSafety']) && $_POST['TrafficSafety'] === '是' ? '是' : '否';
$NoSmoking = isset($_POST['NoSmoking']) && $_POST['NoSmoking'] === '是' ? '是' : '否';
$NoDrugs = isset($_POST['NoDrugs']) && $_POST['NoDrugs'] === '是' ? '是' : '否';
$DenguePrevention = isset($_POST['DenguePrevention']) && $_POST['DenguePrevention'] === '是' ? '是' : '否';
$Other = isset($_POST['Other']) ? $_POST['Other'] : '';


// 更新數據庫中的相應記錄
$sql = "
    UPDATE VisitRecord 
    SET 
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
$stmt->bind_param(
    "ssssssssssssssss",
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

if ($stmt->execute()) {
    echo "<script>
        alert('記錄更新成功');
        window.location.href = 'QueryViewRecord.php?VisitID=$VisitID';
    </script>";
} else {
    
    echo "<script>
        alert('更新記錄時出錯: " . $stmt->error . "');
        window.location.href = 'UpdateRecord.php?VisitID=$VisitID';
    </script>";
}

// 關閉語句和連接
$stmt->close();
$conn->close();

exit();
?>