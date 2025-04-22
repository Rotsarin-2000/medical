<?php
include '../db/conn.php';

if (!$conn) {
    die("Database connection failed.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM m_dates WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $d_id = $row['d_id'];

        $sql_create = "SELECT * FROM m_creates WHERE d_id = ? AND status = 0 ORDER BY id ASC";
        $stmt_create = $conn->prepare($sql_create);
        
        if ($stmt_create === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt_create->bind_param("i", $d_id);
        
        if ($stmt_create->execute()) {
            $result_create = $stmt_create->get_result();

        } else {
            die("Execution failed: " . $stmt_create->error);
        }

        $stmt_create->close();
    } else {
        echo "No record found with the specified ID.";
    }
} else {
    die("Execution failed: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
