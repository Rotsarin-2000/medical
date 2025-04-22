<?php
include '../db/conn.php';

if (!$conn) {
    die("Database connection failed.");
}

$result = $conn->query("SELECT MAX(d_id) AS max_id FROM m_dates");
$row = $result->fetch_assoc();
$d_id = (isset($row['max_id'])) ? intval($row['max_id']) + 1 : 1; 

$date = $_POST['date'];
$status = 0; 
$create_date = date('Y-m-d H:i:s');

$sql = $conn->prepare("INSERT INTO m_dates (date, d_id, status, create_date) VALUES (?, ?, ?, ?)");

if ($sql === false) {
    die("Prepare failed: " . ($conn->error));
}

$sql->bind_param("ssss", $date, $d_id, $status, $create_date); 

if ($sql->execute()) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
} else {
    die("Insert failed: " . $sql->error);
}
