<?php
$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$d_id = $_POST['d_id'];
$date = $_POST['date'];
$year = $_POST['year'];
$employee_id = $_POST['employee_id'];
$employee_name = $_POST['employee_name'];
$department = $_POST['department'];
$start_work = $_POST['start_work'];
$duo_work = $_POST['duo_work'];
$workage_year = $_POST['workage_year'];
$age_year = $_POST['age_year'];
$birthday = $_POST['birthday'];
$amount = $_POST['amount'];
$value = $_POST['value'];
$balance = $_POST['balance'];
$remark_memo = $_POST['remark_memo'];
$owner = $_POST['owner'];
$price = $_POST['price'];
$note = $_POST['note'];
$status = 0;
$create_date = date('Y-m-d H:i:s');

$medical_array = [  
    'date' => $date,
    'year' => $year,
    'employee_id' => $employee_id,
    'employee_name' => $employee_name,
    'department' => $department,
    'start_work' => $start_work,
    'duo_work' => $duo_work,
    'workage_year' => $workage_year,
    'age_year' => $age_year,
    'birthday' => $birthday,
    'amount' => $amount,
    'value' => $value,
    'balance' => $balance,
    'remark_memo' => $remark_memo,
    'owner' => $owner,
    'price' => $price,
    'note' => $note,
];

$medical_json = json_encode($medical_array, JSON_UNESCAPED_UNICODE);

$sql = $conn->prepare("INSERT INTO medical_creates (d_id, medical, status, create_date) VALUES (?, ?, ?, ?)");

if ($sql === false) {
    die("Prepare failed: " . $conn->error);
}

$sql->bind_param("ssss", $d_id, $medical_json, $status, $create_date);

if ($sql->execute()) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
} else {
    die("Insert failed: " . $sql->error);
}

$sql->close();
$conn->close();
?>
