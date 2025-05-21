<?php
// 包含資料庫連接文件
include 'connect.php';

// 獲取前端傳遞的參數
$userID = isset($_GET['UserID']) ? $_GET['UserID'] : '';
$permissionLevel = isset($_GET['PermissionLevel']) ? $_GET['PermissionLevel'] : '';

// 檢查參數是否有效
if (empty($userID) || empty($permissionLevel)) {
    header("Location: delete_user_pro.php?result=invalid");
    exit;

}


// 根據 PermissionLevel 決定要連接的表格
$tableName = '';
switch ($permissionLevel) {
    case '學生':
        $tableName = 'students';
        break;
    case '導師':
        $tableName = 'teachers';
        break;
    case '房東':
        $tableName = 'landlords';
        break;
    default:
        header("Location: delete_user_pro.php?result=unknown_permission");
        exit;
}

// 檢查表格是否存在
if (!in_array($tableName, ['students', 'teachers', 'landlords'])) {
    header("Location: delete_user_pro.php?result=invalid_table");
    exit;
}

// 構建 SQL 語句，將表格中符合 UserID 的資料欄位設置為 NULL
$sql = "UPDATE $tableName SET ";
switch ($tableName) {
    case 'students':
        $sql .= "department = NULL, grade = NULL, gender = NULL, ContactPhone = NULL, S_Email = NULL, HomeAddress = NULL, HomePhone = NULL, EmergencyContactName = NULL, EmergencyContactPhone = NULL";
        $sql .= " WHERE StudentID = ?";
        break;
    case 'teachers':
        $sql .= " Rank = NULL, ContactPhone = NULL, T_Email = NULL, OfficeAddress = NULL, OfficePhone = NULL";
        $sql .= " WHERE TeacherID = ?";
        break;
    case 'landlords':
        $sql .= " L_Email = NULL";
        $sql .= " WHERE LandlordID = ?";
        break;
    default:
        break;
}

// 執行 SQL 語句
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);

if ($stmt->execute()) {
    header("Location: delete_user_pro.php?result=success");
} else {
    header("Location: delete_user_pro.php?result=failure&error=" . urlencode($conn->error));
}

// 關閉數據庫連接
$stmt->close();
$conn->close();
?>
