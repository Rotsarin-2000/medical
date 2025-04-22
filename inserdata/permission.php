<?php
$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$employee_id = $_POST['employee_id'];
$employee_name = $_POST['employee_name'];
$department = $_POST['department'];
$level = $_POST['level'];
$position = $_POST['position'];

$sql = $conn->prepare("INSERT INTO permissions (employee_id, employee_name, department, level, position) 
VALUES (?, ?, ?, ?, ?)");
$sql->bind_param("sssss", $employee_id, $employee_name, $department, $level, $position);

if ($sql->execute()) {

    $employee_id = $_POST['employee_id'];
    $employee_name = $_POST['employee_name'];
    $pages = ['medical', 'check', 'check_user', 'report', 'check_manager', 'permission'];

    foreach ($pages as $page) {
        $permission = isset($_POST[$page]) ? $_POST[$page] : 0;
        $sql = $conn->prepare("INSERT INTO page_access (employee_id, employee_name, page, permission) VALUES (?, ?, ?, ?)");
        $sql->bind_param("sssi", $employee_id, $employee_name, $page, $permission);
        $sql->execute();
    }
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
} else {
    die("Insert failed: " . $sql->error);
}

$sql->close();
$conn->close();

// include '../db/conn.php';

// if (!$conn) {
//     die("Database connection failed.");
// }

// // รับค่าจากฟอร์ม
// $employee_id = $_POST['employee_id'];
// $employee_name = $_POST['employee_name'];
// $department = $_POST['department'];
// $level = $_POST['level'];
// $user_name = $_POST['user_name'];
// $password = $_POST['password'];

// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

// $sql = $conn->prepare("INSERT INTO permissions (employee_id, employee_name, department, level, user_name, password) 
// VALUES (?, ?, ?, ?, ?, ?)");
// $sql->bind_param("ssssss", $employee_id, $employee_name, $department, $level, $user_name, $hashed_password);

// if ($sql->execute()) {
//     $pages = ['medical', 'check', 'check_user', 'report'];
//     foreach ($pages as $page) {
//         $permission = isset($_POST[$page]) ? $_POST[$page] : 0;
//         $sql = $conn->prepare("INSERT INTO page_access (employee_id, page, permission) VALUES (?, ?, ?)");
//         $sql->bind_param("ssi", $employee_id, $page, $permission);
//         $sql->execute();
//     }

//     header("Location: {$_SERVER['HTTP_REFERER']}");
//     exit;
// } else {
//     die("Insert failed: " . $sql->error);
// }

// // ปิดการเชื่อมต่อ
// $sql->close();
// $conn->close();
?>



