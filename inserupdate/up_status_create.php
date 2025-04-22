<?php
// include '../db/conn.php';

// if (!$conn) {
//     die('Connection failed: ' . mysqli_connect_error());
// }

// if (isset($_POST['id']) && isset($_POST['status'])) {
//     $id = $_POST['id'];
//     $status = $_POST['status'];

//     $sql = "UPDATE medical_creates SET status = ?, value = 0, balance = ?, remark_memo = NULL, price = NULL WHERE id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('iii', $status, $balance, $id);

//     if ($stmt->execute()) {
//         header("Location: {$_SERVER['HTTP_REFERER']}");  
//     } else {
//         header("Location: {$_SERVER['HTTP_REFERER']}?error=1");
//     }
//     $stmt->close();
// }

// $conn->close();

include '../db/conn.php';

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $sql = "SELECT medical FROM medical_creates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($medical_json);
    $stmt->fetch();

    $stmt->close();

    $medical_array = json_decode($medical_json, true);

    if ($medical_array) {
        $medical_array['value'] = 0;

        $balance = isset($_POST['balance']) ? $_POST['balance'] : null; 
        $medical_array['balance'] = $balance;

        $medical_array['remark_memo'] = null;
        $medical_array['price'] = null;

        $updated_medical_json = json_encode($medical_array, JSON_UNESCAPED_UNICODE);

        $update_sql = "UPDATE medical_creates SET status = ?, medical = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('isi', $status, $updated_medical_json, $id);

        if ($update_stmt->execute()) {
            header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            header("Location: {$_SERVER['HTTP_REFERER']}?error=1");
        }
        $update_stmt->close();
    } else {
        echo "Failed to decode medical array.";
    }

    $conn->close();
}


// include '../db/conn.php';

// if (!$conn) {
//     die('Connection failed: ' . mysqli_connect_error());
// }

// if (isset($_POST['id'])) {
//     $id = $_POST['id'];

//     // คำสั่ง SQL สำหรับลบข้อมูล
//     $delete_sql = "DELETE FROM medical_creates WHERE id = ?";
//     $stmt = $conn->prepare($delete_sql);

//     if ($stmt) {
//         $stmt->bind_param('i', $id);

//         if ($stmt->execute()) {
//             // ลบสำเร็จ กลับไปหน้าก่อนหน้า
//             header("Location: {$_SERVER['HTTP_REFERER']}");
//         } else {
//             // ลบไม่สำเร็จ ส่ง error ไปยัง URL
//             header("Location: {$_SERVER['HTTP_REFERER']}?error=1");
//         }

//         $stmt->close();
//     } else {
//         echo "Failed to prepare delete statement.";
//     }

//     $conn->close();
// } else {
//     echo "ID not set.";
// }
