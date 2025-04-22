<?php
include '../db/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $d_id = $_POST['d_id'];
    $date = $_POST['date'];

    $sql = "UPDATE m_dates SET date = ? WHERE id = ? AND d_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sii", $date, $id, $d_id);
        if ($stmt->execute()) {
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        } else {
            echo "An error occurred while updating the data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid ID.";
    }
} else {
    echo "Please submit the form correctly.";
}

$conn->close();

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     if (!empty($_POST['id']) && !empty($_POST['d_id']) && !empty($_POST['date'])) {
//         $id = intval($_POST['id']);
//         $d_id = intval($_POST['d_id']);
//         $date = trim($_POST['date']);

//         // Validate date format (YYYY-MM-DD)
//         if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
//             die("Invalid date format. Please use YYYY-MM-DD.");
//         }

//         // อัปเดต date และ d_id
//         $sql = "UPDATE m_dates SET date = ?, d_id = ? WHERE id = ?";

//         if ($stmt = mysqli_prepare($conn, $sql)) {
//             // Bind ค่าทั้ง 3 ค่า
//             mysqli_stmt_bind_param($stmt, "sii", $date, $d_id, $id);

//             if (mysqli_stmt_execute($stmt)) {
//                 if (mysqli_stmt_affected_rows($stmt) > 0) {
//                     header("Location: {$_SERVER['HTTP_REFERER']}");
//                     exit;
//                 } else {
//                     echo "ไม่มีการอัปเดตข้อมูล โปรดตรวจสอบ ID อีกครั้ง";
//                 }
//             } else {
//                 echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . mysqli_stmt_error($stmt);
//             }

//             mysqli_stmt_close($stmt);
//         } else {
//             echo "ไม่สามารถเตรียมคำสั่ง SQL ได้: " . mysqli_error($conn);
//         }
//     } else {
//         echo "กรุณากรอกข้อมูลให้ครบ";
//     }
// } else {
//     echo "คำขอไม่ถูกต้อง";
// }

// mysqli_close($conn);