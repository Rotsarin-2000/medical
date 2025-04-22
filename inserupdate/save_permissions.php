<?php
$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// รับข้อมูลจากฟอร์ม
$pages = ['medical', 'check', 'check_manager', 'check_user', 'report', 'permission'];
$employee_ids = array_unique(array_map(function($key) {
    return explode('_', $key)[1];
}, array_keys($_POST)));

foreach ($employee_ids as $employee_id) {

    $employee_name = $_POST["employee_name_{$employee_id}"];
    $department = $_POST["department_{$employee_id}"];
    $level = $_POST["level_{$employee_id}"];
    $position = $_POST["position_{$employee_id}"];

    // อัปเดตข้อมูลในตาราง permissions
    $sql = $conn->prepare("
        UPDATE permissions
        SET employee_name = ?, 
            department = ?, 
            level = ?, 
            position = ?
        WHERE employee_id = ?
    ");
    $sql->bind_param("sssss", $employee_name, $department, $level, $position, $employee_id);
    $sql->execute();

    // อัปเดตข้อมูลในตาราง page_access
    foreach ($pages as $page) {
        $permission = isset($_POST["{$page}_{$employee_id}"]) ? $_POST["{$page}_{$employee_id}"] : 0;

        $sql = $conn->prepare("
            UPDATE page_access
            SET permission = ?
            WHERE employee_id = ? AND page = ?
        ");
        $sql->bind_param("iss", $permission, $employee_id, $page);
        $sql->execute();
    }
}

$sql->close();
$conn->close();

header("Location: {$_SERVER['HTTP_REFERER']}");
exit;
