<?php
include '../db/conn.php';

if (!$conn) {
    die("Database connection failed.");
}

$d_id = $_POST['d_id'];
$date = $_POST['date'];
$year = $_POST['year'];
$employee_id = $_POST['employee_id'];
$employee_name = $_POST['employee_name'];
$department = $_POST['department'];
$start_work = $_POST['start_work'];
$duo_work = $_POST['duo_work'];
$age_year = $_POST['age_year'];
$birthday = $_POST['birthday'];
$amount = $_POST['amount'];
$value = $_POST['value'];
$balance = $_POST['balance'];
$remark_memo = $_POST['remark_memo'];
$price = $_POST['price'];
$status = 0;
$create_date = date('Y-m-d H:i:s');

$sql = $conn->prepare("INSERT INTO m_creates (d_id, date, year, employee_id, employee_name, department, start_work, duo_work, age_year, birthday, amount, value, balance, remark_memo, price, status, create_date) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($sql === false) {
    die("Prepare failed: " . $conn->error);
}

$sql->bind_param("ssssssssssiiissis", $d_id, $date, $year, $employee_id, $employee_name, $department, $start_work, $duo_work, $age_year, $birthday, $amount, $value, $balance, $remark_memo, $price, $status, $create_date);

if ($sql->execute()) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
} else {
    die("Insert failed: " . $sql->error);
}

$sql->close();
$conn->close();
