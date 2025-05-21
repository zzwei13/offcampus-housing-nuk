<?php
session_start(); // Start session if not already started

// Check if user is logged in and has appropriate permission level
if (!isset($_SESSION['username']) || !isset($_SESSION['permissionLevel']) || $_SESSION['permissionLevel'] !== '學生') {
    header('Location: login.html');
    exit;
}

include 'connect.php'; // Include database connection script

// Function to fetch current user data
function getUserData($conn, $usr) {
    $sql = "SELECT * FROM students WHERE StudentID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $usr);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();
    return $userData;
}

$name = $_SESSION['username'];
$permissionLevel = $_SESSION['permissionLevel'];

// Get UserID
$sql_userid = "SELECT UserID FROM accountmanagement WHERE Username = ?";
$stmt_userid = $conn->prepare($sql_userid);
if (!$stmt_userid) {
    die("Prepare failed: " . $conn->error);
}
$stmt_userid->bind_param("s", $name);
$stmt_userid->execute();
$result_userid = $stmt_userid->get_result();

if (!$result_userid || $result_userid->num_rows === 0) {
    exit("<h1>用戶名無效</h1><p>找不到該用戶名的用戶ID。</p>");
}

$row_userid = $result_userid->fetch_assoc();
$usr = $row_userid['UserID'];

// Fetch current user data
$userData = getUserData($conn, $usr);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data and update database
    $studentID = $usr; // Use the UserID fetched earlier
    $newDepartment = $_POST['Department'];
    $newName = $_POST['Name'];
    $newGrade = $_POST['Grade'];
    $newGender = $_POST['Gender'];
    $newContactPhone = $_POST['ContactPhone'];
    $newSEmail = $_POST['S_Email'];
    $newHomeAddress = $_POST['HomeAddress'];
    $newHomePhone = $_POST['HomePhone'];
    $newEmergencyContactName = $_POST['EmergencyContactName'];
    $newEmergencyContactPhone = $_POST['EmergencyContactPhone'];

    // Update query
    $sql_update = "UPDATE students SET Department=?, Name=?, Grade=?, Gender=?, ContactPhone=?, S_Email=?, HomeAddress=?, HomePhone=?, EmergencyContactName=?, EmergencyContactPhone=? WHERE StudentID='$usr'";
    $stmt_update = $conn->prepare($sql_update);
    if (!$stmt_update) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt_update->bind_param("ssssssssss", $newDepartment, $newName, $newGrade, $newGender, $newContactPhone, $newSEmail, $newHomeAddress, $newHomePhone, $newEmergencyContactName, $newEmergencyContactPhone);

    if ($stmt_update->execute()) {
        // Update successful
        header('Location: stuProfile.php'); // Redirect to profile page after successful update
        exit;
    } else {
        // Update failed
        echo "Update failed: " . $stmt_update->error;
    }

    $stmt_update->close();
}

$stmt_userid->close();
$conn->close();
?>




<!DOCTYPE html>
<html lang="zh-Hant" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>修改基本資料 | 租屋管理系統SAMS</title>
    <!-- Include your CSS and JavaScript files -->
    <!-- Example: -->
    <link rel="stylesheet" href="static/css/theme.min.css">
</head>

<body>

    <main class="main">
    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
            <div class="container">
              <a class="navbar-brand" href="HouseInfoManage.php"><img src="static/picture/nuk.png" height="45" alt="logo"></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
                      
                        <li class="nav-item px-2">
                          <a class="nav-link" aria-current="page" href="rental_info.php">租屋資訊</a>
                        </li>
                        <li class="nav-item px-2"><a class="nav-link" aria-current="page"
                                href="forum.php">交流平台</a></li>
                        <li class="nav-item px-2">
                            <a class="nav-link" aria-current="page" href="stuProfile.php?">
                                基本資料
                            </a>
                        </li>

                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">歡迎, <?php echo htmlspecialchars($permissionLevel); ?> <?php echo htmlspecialchars($name); ?></a>
                        </li>
                    </ul>
                    <div id="userStatus" class="ms-lg-2">
                        <a class="btn btn-primary" href="logout.php" role="button">登出</a>
                    </div>
                    
                    <div class="dropdown d-none d-lg-block">
                        <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="dropdownMenuButton1"
                            style="top:55px">
                            <form>
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>



        <section class="pt-2 pb-11 bg-600">
            <div class="container">
                <div class="mt-4">
                    <h1 class='fw-bold font-sans-serif text-center'>修改基本資料</h1>
                </div>
                
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="card" style="width: 60%; margin: 10px auto;">
                    <div class="card-body">
                        <div class="container">
                        
                            <div class="form-group">
                                <label for="StudentID">學生ID</label>
                                <input type="text" class="form-control" id="StudentID" name="StudentID" value="<?= htmlspecialchars($userData['StudentID']) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Department">學系</label>
                                <input type="text" class="form-control" id="Department" name="Department" value="<?= htmlspecialchars($userData['Department']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="Name">姓名</label>
                                <input type="text" class="form-control" id="Name" name="Name" value="<?= htmlspecialchars($userData['Name']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="Grade">年級</label>
                                <input type="text" class="form-control" id="Grade" name="Grade" value="<?= htmlspecialchars($userData['Grade']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="Gender">性別</label>
                                <input type="text" class="form-control" id="Gender" name="Gender" value="<?= htmlspecialchars($userData['Gender']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="ContactPhone">連絡電話</label>
                                <input type="text" class="form-control" id="ContactPhone" name="ContactPhone" value="<?= htmlspecialchars($userData['ContactPhone']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="S_Email">Email</label>
                                <input type="email" class="form-control" id="S_Email" name="S_Email" value="<?= htmlspecialchars($userData['S_Email']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="HomeAddress">地址</label>
                                <input type="text" class="form-control" id="HomeAddress" name="HomeAddress" value="<?= htmlspecialchars($userData['HomeAddress']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="HomePhone">家電</label>
                                <input type="text" class="form-control" id="HomePhone" name="HomePhone" value="<?= htmlspecialchars($userData['HomePhone']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="EmergencyContactName">緊急聯絡人</label>
                                <input type="text" class="form-control" id="EmergencyContactName" name="EmergencyContactName" value="<?= htmlspecialchars($userData['EmergencyContactName']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="EmergencyContactPhone">緊急聯絡人電話</label>
                                <input type="text" class="form-control" id="EmergencyContactPhone" name="EmergencyContactPhone" value="<?= htmlspecialchars($userData['EmergencyContactPhone']) ?>">
                            </div>

                            <div class="mt-4 d-flex justify-content-center align-items-center" style="margin-bottom: 20px;">
                                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">保存變更</button>
                            </div>
                      
                        </div>
                    </div>
                </div>
                </form>
        </section>
    </main>

    <!-- Include your JavaScript files -->
    <!-- Example: -->
    <script src="static/js/bootstrap.min.js"></script>
    <script src="static/js/theme.js"></script>
</body>

</html>
