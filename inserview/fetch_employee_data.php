<?php
$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$employee_name = $_POST['employee_name'];

$sql = "SELECT * FROM medical_creates WHERE status = 0 AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.employee_name')) = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $employee_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        $medical_data = json_decode($row['medical'], true);
        echo "<tr>
            <td>{$counter}</td>
            <td>{$medical_data['date']}</td>
            <td>{$medical_data['employee_name']}</td>
            <td>{$medical_data['employee_id']}</td>
            <td>{$medical_data['department']}</td>
            <td>{$medical_data['workage_year']}</td>
            <td>" . number_format($medical_data['amount'], 2) . "</td>
            <td>{$medical_data['remark_memo']}</td>
            <td>" . number_format($medical_data['price'], 2) . "</td>

        </tr>";
        $counter++;
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
}

$stmt->close();
$conn->close();
// <td>" . number_format($medical_data['balance'], 2) . "</td>