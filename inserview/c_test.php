<?php
include '../db/conn.php';

if (!$conn) {
    die("Database connection failed.");
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

        $sql_create = "SELECT * FROM m_creates WHERE d_id = ? AND status = 0 ORDER BY id DESC";
        $stmt_create = $conn->prepare($sql_create);
        $stmt_create->bind_param("s", $d_id);
        $stmt_create->execute();
        $result_create = $stmt_create->get_result();

    } else {
        echo "No record found with the specified ID in m_dates.";
    }

    $stmt->close();
    $stmt_create->close();
} else {
    echo "Invalid ID.";
}
