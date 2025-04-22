<?php
include '../db/conn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ตรวจสอบว่า id ถูกส่งมาหรือไม่
if (isset($_POST['id'])) {
    // รับข้อมูลจากฟอร์ม
    $date = $_POST['date'];
    $year = $_POST['year'];
    $employee_id = $_POST['employee_id'];
    $employee_name = $_POST['employee_name'];
    $department = $_POST['department'];
    $birthday = $_POST['birthday'];
    $start_work = $_POST['start_work'];
    $duo_work = $_POST['duo_work'];
    $age_year = $_POST['age_year'];
    $workage_year = $_POST['workage_year'];
    $amount = $_POST['amount'];
    $value = $_POST['value'];
    $balance = $_POST['balance'];
    $remark_memo = $_POST['remark_memo'];
    $owner = $_POST['owner'];
    $price = $_POST['price'];
    $note = $_POST['note'];
    $id = $_POST['id']; 

    $medical_array = [
        'date' => $date,
        'year' => $year,
        'employee_id' => $employee_id,
        'employee_name' => $employee_name,
        'department' => $department,
        'birthday' => $birthday,
        'start_work' => $start_work,
        'duo_work' => $duo_work,
        'age_year' => $age_year,
        'workage_year' => $workage_year,
        'amount' => $amount,
        'value' => $value,
        'balance' => $balance,
        'remark_memo' => $remark_memo,
        'owner' => $owner,
        'price' => $price,
        
        'note' => $note
    ];

    $medical_json = json_encode($medical_array, JSON_UNESCAPED_UNICODE);

    $sql = "UPDATE medical_creates SET medical = ? WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
  
        mysqli_stmt_bind_param($stmt, "si", $medical_json, $id);

        if (mysqli_stmt_execute($stmt)) {

            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        } else {
            echo "Failed to execute the SQL statement. Error: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to prepare the SQL statement. Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);

// if (isset($_POST['id']) && isset($_POST['d_id'])) {
//     $d_id = $_POST['d_id'];
//     $date = $_POST['date'];
//     $year = $_POST['year'];
//     $employee_id = $_POST['employee_id'];
//     $employee_name = $_POST['employee_name'];
//     $department = $_POST['department'];
//     $birthday = $_POST['birthday'];
//     $start_work = $_POST['start_work'];
//     $duo_work = $_POST['duo_work'];
//     $age_year = $_POST['age_year'];
//     $workage_year = $_POST['workage_year'];
//     $amount = $_POST['amount'];
//     $value = $_POST['value'];
//     $balance = $_POST['balance'];
//     $remark_memo = $_POST['remark_memo'];
//     $price = $_POST['price'];
//     $owner = $_POST['owner'];
//     $note = $_POST['note'];
//     $id = $_POST['id'];

//     // Create JSON object
//     $medical_array = [
//         'date' => $date,
//         'year' => $year,
//         'employee_id' => $employee_id,
//         'employee_name' => $employee_name,
//         'department' => $department,
//         'birthday' => $birthday,
//         'start_work' => $start_work,
//         'duo_work' => $duo_work,
//         'age_year' => $age_year,
//         'workage_year' => $workage_year,
//         'amount' => $amount,
//         'value' => $value,
//         'balance' => $balance,
//         'remark_memo' => $remark_memo,
//         'price' => $price,
//         'owner' => $owner,
//         'note' => $note
//     ];

//     $medical_json = json_encode($medical_array, JSON_UNESCAPED_UNICODE);

//     // Corrected SQL Query
//     $sql = "UPDATE medical_creates SET medical = ?, d_id = ? WHERE id = ?";

//     if ($stmt = mysqli_prepare($conn, $sql)) {
//         // Bind all necessary parameters
//         mysqli_stmt_bind_param($stmt, "sii", $medical_json, $d_id, $id);

//         // Execute and check for errors
//         if (mysqli_stmt_execute($stmt)) {
//             header("Location: {$_SERVER['HTTP_REFERER']}");
//             exit();
//         } else {
//             echo "Failed to execute the SQL statement. Error: " . mysqli_stmt_error($stmt);
//         }

//         mysqli_stmt_close($stmt);
//     } else {
//         echo "Failed to prepare the SQL statement. Error: " . mysqli_error($conn);
//     }
// } else {
//     echo "Missing required POST parameters.";
// }

// mysqli_close($conn);