<?php
$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ดึงข้อมูลพนักงาน
$sql = "SELECT * FROM permissions";
$result = $conn->query($sql);

// ดึงข้อมูลสิทธิ์การเข้าถึง
$permissions_sql = "SELECT * FROM page_access";
$permissions_result = $conn->query($permissions_sql);

$permissions = [];
while ($row = $permissions_result->fetch_assoc()) {
    $permissions[$row['employee_id']][$row['page']] = $row['permission'];
}
