<?php 
include '../db/conn.php';
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 
$sql = "SELECT * FROM m_dates ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

?>