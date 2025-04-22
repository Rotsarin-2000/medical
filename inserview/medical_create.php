<?php
$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $sql = "SELECT * FROM m_dates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_create = $stmt->get_result();

    $d_id = '';
    if ($result_create->num_rows > 0) {
        $row = $result_create->fetch_assoc();
        $d_id = htmlspecialchars($row['d_id']);

        $sql_create = "SELECT * FROM medical_creates WHERE d_id = ? AND status = 0 ORDER BY id DESC";
        $stmt_create = $conn->prepare($sql_create);
        $stmt_create->bind_param("s", $d_id);
        $stmt_create->execute();
        $result_create = $stmt_create->get_result();

        $stmt_create->close();

        $sql_date = "SELECT * FROM medical_creates WHERE d_id = ? AND status = 0 ORDER BY id DESC";
        $stmt_date = $conn->prepare($sql_date);
        $stmt_date->bind_param("s", $d_id);
        $stmt_date->execute();
        $result_date = $stmt_date->get_result();

        $stmt_date->close();
    } else {
        echo "No record found with the specified ID in m_dates.";
    }

    $stmt->close();
} else {
    echo "Invalid ID.";
}

$conn->close();
