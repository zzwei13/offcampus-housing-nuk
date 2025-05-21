<?php
include 'connect.php';

// 獲取要删除的 VisitID
$VisitID = isset($_POST['VisitID']) ? $_POST['VisitID'] : '';

// 删除記錄的 SQL
$delete_sql = "DELETE FROM VisitRecord WHERE VisitID = ?";
if ($stmt = $conn->prepare($delete_sql)) {
    $stmt->bind_param("s", $VisitID);
    if ($stmt->execute()) {
        // 返回成功的訊息
        echo "Record deleted successfully.";
    } else {
        // 返回錯誤訊息
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
} else {
    // 返回錯誤訊息
    echo "Error preparing statement: " . $conn->error;
}

// 關資料庫連接
$conn->close();
?>
