<?php
include './db/conn.php';


$year = isset($_GET['year']) ? $_GET['year'] : '';   
$department = isset($_GET['department']) ? $_GET['department'] : ''; 
$employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : ''; 

if (!$conn) {
    die("Database connection failed.");
}

$sql = "SELECT * FROM medical_creates WHERE status = 0";


if ($year && $year != 'All') {
    $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.year')) = '$year'";  
}

if ($department && $department != 'All') {
    $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.department')) = '$department'";  
}

if ($employee_id && $employee_id != 'All') {
    $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.employee_id')) = '$employee_id'";  
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>
