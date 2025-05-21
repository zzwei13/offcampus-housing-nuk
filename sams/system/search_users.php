<?php
include 'connect.php';

// 檢查連接是否存在
if (!isset($conn)) {
    die("ERROR: Database connection failed.");
}

// 設置字符集為utf8mb4
mysqli_set_charset($conn, "utf8mb4");

$q = isset($_GET['q']) ? $_GET['q'] : '';

// 查詢用戶的 UserID 和 Username
$sql = "SELECT UserID, Username, PermissionLevel FROM accountmanagement WHERE UserID LIKE ? OR Username LIKE ?";
$stmt = $conn->prepare($sql);

// 檢查準備語句是否成功
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$searchTerm = '%' . mb_convert_encoding($q, "utf-8", "auto") . '%';
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>用戶ID</th><th>姓名</th><th>操作</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['UserID'] . "</td>";

        // 根據PermissionLevel來決定顯示的姓名
        switch ($row['PermissionLevel']) {
            case '管理員':
                echo "<td>" . $row['Username'] . "</td>";
                break;
            case '學生':
                $studentQuery = $conn->prepare("SELECT Name FROM students WHERE StudentID = ?");
                $studentQuery->bind_param("i", $row['UserID']);
                $studentQuery->execute();
                $studentResult = $studentQuery->get_result();
                if ($studentResult->num_rows > 0) {
                    $studentRow = $studentResult->fetch_assoc();
                    echo "<td>" . $studentRow['Name'] . "</td>";
                } else {
                    echo "<td>未知學生</td>";
                }
                $studentQuery->close();
                break;
            case '導師':
                $teacherQuery = $conn->prepare("SELECT Name FROM teachers WHERE TeacherID = ?");
                $teacherQuery->bind_param("i", $row['UserID']);
                $teacherQuery->execute();
                $teacherResult = $teacherQuery->get_result();
                if ($teacherResult->num_rows > 0) {
                    $teacherRow = $teacherResult->fetch_assoc();
                    echo "<td>" . $teacherRow['Name'] . "</td>";
                } else {
                    echo "<td>未知導師</td>";
                }
                $teacherQuery->close();
                break;
            case '房東':
                $landlordQuery = $conn->prepare("SELECT Name FROM landlords WHERE LandlordID = ?");
                $landlordQuery->bind_param("i", $row['UserID']);
                $landlordQuery->execute();
                $landlordResult = $landlordQuery->get_result();
                if ($landlordResult->num_rows > 0) {
                    $landlordRow = $landlordResult->fetch_assoc();
                    echo "<td>" . $landlordRow['Name'] . "</td>";
                } else {
                    echo "<td>未知房東</td>";
                }
                $landlordQuery->close();
                break;
            default:
                echo "<td>未知身份</td>";
                break;
        }

        echo "<td><a href='view_user.php?id=" . $row['UserID'] . "' class='btn btn-info'>查看資料</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "沒有找到匹配的用戶。";
}

// 關閉數據庫連接
$conn->close();
?>
