<?php
include '../db/conn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['id']) && isset($_POST['id'])) {
    $id = trim($_POST['id']);
    $status = trim($_POST['status']);
    $d_id = trim($_POST['d_id']);
    $date = trim($_POST['date'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $employee_id = trim($_POST['employee_id'] ?? '');
    $employee_name = trim($_POST['employee_name'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $start_work = trim($_POST['start_work'] ?? '');
    $duo_work = trim($_POST['duo_work'] ?? '');
    $age_year = trim($_POST['age_year'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $value = trim($_POST['value'] ?? '');
    $balance = trim($_POST['balance'] ?? '');
    $remark_memo = trim($_POST['remark_memo'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $create_date = trim($_POST['create_date'] ?? '');

    $sql = "UPDATE m_creates SET status = ?, d_id = ?, date = ?, year = ?, employee_id = ?, employee_name = ?, department = ?, start_work = ?, duo_work = ?, age_year = ?, birthday = ?, amount = ?, value = ?, balance = ?, remark_memo = ?, price = ?, create_date = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            'sssssssssssssssssi',
            $status,
            $d_id,
            $date,
            $year,
            $employee_id,
            $employee_name,
            $department,
            $start_work,
            $duo_work,
            $age_year,
            $birthday,
            $amount,
            $value,
            $balance,
            $remark_memo,
            $price,
            $create_date,
            $id
        );

        if ($stmt->execute()) {
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        } else {
            echo "Failed to execute the SQL statement. Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to prepare the SQL statement. Error: " . $conn->error;
    }
}

$conn->close();
?>
