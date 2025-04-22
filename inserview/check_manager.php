<?php
include './db/conn.php';

$year = isset($_GET['year']) ? mysqli_real_escape_string($conn, $_GET['year']) : '';   

if (!$conn) {
    die("Database connection failed.");
}

$sql = "SELECT * FROM medical_creates WHERE status = 0";

if ($year && $year !== 'All') {
    $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(medical, '$.year')) = '$year'";
}

$sql .= " ORDER BY id DESC";

// $result = mysqli_query($conn, $sql);

// if (!$result) {
//     die("Query failed: " . mysqli_error($conn));
// }

// if (mysqli_num_rows($result) > 0) {
//     while ($row = mysqli_fetch_assoc($result)) {
//         echo "<p>ID: {$row['id']} - Year: " . json_decode($row['medical'], true)['year'] . "</p>";
//     }
// } else {
//     echo "<p>No records found.</p>";
// }
// ?>
