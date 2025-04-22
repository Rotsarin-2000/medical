<?php
// include '../db/conn.php';

// if (isset($_POST['employee_id'])) {
//     $employee_id = $_POST['employee_id'];

//     $query = "SELECT amount, value, balance FROM m_creates WHERE employee_id = ?";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("s", $employee_id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         $data = $result->fetch_assoc();
//         echo json_encode([
//             'amount' => $data['amount'],
//             'value' => $data['value'],
//             'balance' => $data['balance']
//         ]);
//     } else {
//         echo json_encode(['amount' => null, 'value' => null, 'balance' => null]);
//     }
// }


// include '../db/conn.php';

// if (isset($_POST['employee_id'])) {
//     $employee_id = $_POST['employee_id'];

//     $query = "SELECT medical FROM medical_creates WHERE JSON_EXTRACT(medical, '$.employee_id') = ?";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("s", $employee_id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         $data = $result->fetch_assoc();
//         $medicalData = json_decode($data['medical'], true);

//         if (isset($medicalData['employee_id']) && $medicalData['employee_id'] == $employee_id) {
//             echo json_encode([
//                 'amount' => $medicalData['amount'] ?? null,
//                 'value' => $medicalData['value'] ?? null,
//                 'balance' => $medicalData['balance'] ?? null
//             ]);
//         } else {
//             echo json_encode(['amount' => null, 'value' => null, 'balance' => null]);
//         }
//     } else {
//         echo json_encode(['amount' => null, 'value' => null, 'balance' => null]);
//     }
// }


$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];
    $currentYear = date('Y');

    $query = "SELECT medical FROM medical_creates 
              WHERE JSON_UNQUOTE(JSON_EXTRACT(medical, '$.employee_id')) = ? 
              AND YEAR(create_date) = ?
              ORDER BY create_date DESC 
              LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $employee_id, $currentYear);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $medicalData = json_decode($data['medical'], true);

        echo json_encode([
            'amount' => $medicalData['amount'] ?? null,
            'value' => $medicalData['value'] ?? 0,
            'balance' => $medicalData['balance'] ?? null
        ]);
    } else {
        echo json_encode([
            'amount' => null,
            'value' => 0,
            'balance' => null
        ]);
    }
}
