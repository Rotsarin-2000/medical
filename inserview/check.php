<?php
include './db/conn.php';

if (!$conn) {
    die("Database connection failed.");
}

$year = isset($_GET['year']) ? $_GET['year'] : '';   
$employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : ''; 
$employee_name = isset($_GET['employee_name']) ? $_GET['employee_name'] : ''; 

$isFiltered = isset($_GET['year']) || (isset($_GET['employee_id']) && $_GET['employee_id'] != 'All') || (isset($_GET['employee_name']) && $_GET['employee_name'] != 'All');


if (!$conn) {
    die("Database connection failed.");
}

$sql = "SELECT * FROM medical_creates WHERE status = 0";


if ($year && $year != 'All') {
    $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.year')) = '$year'";  
}

if ($employee_id && $employee_id != 'All') {
    $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.employee_id')) = '$employee_id'";  
}

if ($employee_name && $employee_name != 'All') {
    $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.employee_name')) = '$employee_name'";  
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>

