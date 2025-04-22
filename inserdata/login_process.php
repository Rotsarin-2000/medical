<?php
session_start(); // เริ่มต้น session

include '../db/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    $sql = $conn->prepare("SELECT * FROM permissions WHERE user_name = ?");
    $sql->bind_param("s", $user_name);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่าน
        if ($password === $user['password']) {
            // เก็บข้อมูลผู้ใช้ใน session
            $_SESSION['user_name'] = $user['user_name']; 
            $_SESSION['employee_name'] = $user['employee_name']; 
            $_SESSION['department'] = $user['department'];
            $_SESSION['level'] = $user['level']; 
            $_SESSION['employee_id'] = $user['employee_id']; 
            header("Location: /homepage.php"); 
            exit();
        } else {
            echo "รหัสผ่านไม่ถูกต้อง.";
        }
    } else {
        echo "ไม่พบผู้ใช้นี้.";
    }
}
?>
