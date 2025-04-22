<?php
// include '../db/conn.php';

// if (isset($_GET['employee_id'])) {
//     $employee_id = $_GET['employee_id'];

//     $sql = $conn->prepare("DELETE FROM permissions WHERE employee_id = ?");
//     $sql->bind_param("s", $employee_id); 
//     $sql->execute();

//     $sql = $conn->prepare("DELETE FROM page_access WHERE employee_id = ?");
//     $sql->bind_param("s", $employee_id); 
//     $sql->execute();

//     $sql->close();
//     $conn->close();

//     header("Location: {$_SERVER['HTTP_REFERER']}");
//     exit;
// } else {
//     echo "ไม่พบ employee_id";
// }
