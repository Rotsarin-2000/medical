<?php
// include '../db/conn.php';

// if (!$conn) {
//     die('Connection failed: ' . mysqli_connect_error());
// }

// if (isset($_POST['id']) && isset($_POST['status'])) {
//     $id = $_POST['id'];
//     $status = $_POST['status'];

//     $sql = "UPDATE m_dates SET status = ? WHERE id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('ii', $status, $id);

//     if ($stmt->execute()) {
//         $update_creates_sql = "UPDATE m_creates SET value = 0, balance = ?, remark_memo = NULL, price = NULL 
//                                WHERE d_id = (SELECT d_id FROM m_dates WHERE id = ?)";
//         $update_stmt = $conn->prepare($update_creates_sql);

//         if ($update_stmt) {
//             $update_stmt->bind_param('ii', $balance, $id);
//             if ($update_stmt->execute()) {
//                 header("Location: /medical.php?success=3");
//             } else {
//                 header("Location: /medical.php?error=4");
//             }
//             $update_stmt->close();
//         } else {
//             header("Location: /medical.php?error=5");
//         }
//     } else {
//         header("Location: /medical.php?error=3");
//     }
//     $stmt->close();
// }

// $conn->close();

$servername = 'localhost';
$username = 'tsmmedicaldb';
$password = 'Wd719z$4c';
$dbname = 'tsm_medical_2023';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $update_dates_sql = "UPDATE m_dates SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_dates_sql);
    $stmt->bind_param('ii', $status, $id);

    if ($stmt->execute()) {

        $fetch_did_sql = "SELECT d_id FROM m_dates WHERE id = ?";
        $fetch_stmt = $conn->prepare($fetch_did_sql);
        $fetch_stmt->bind_param('i', $id);
        $fetch_stmt->execute();
        $fetch_stmt->bind_result($d_id);
        $fetch_stmt->fetch();
        $fetch_stmt->close();

        if ($d_id) {

            $fetch_medical_sql = "SELECT medical FROM medical_creates WHERE d_id = ?";
            $fetch_medical_stmt = $conn->prepare($fetch_medical_sql);
            $fetch_medical_stmt->bind_param('i', $d_id);
            $fetch_medical_stmt->execute();
            $fetch_medical_stmt->bind_result($medical_json);
            $fetch_medical_stmt->fetch();
            $fetch_medical_stmt->close();

            if ($medical_json) {
                $medical_array = json_decode($medical_json, true);

                if ($medical_array) {

                    $medical_array['value'] = null;
                    $balance = isset($_POST['balance']) ? $_POST['balance'] : 0;
                    $medical_array['balance'] = $balance;
                    $medical_array['remark_memo'] = null;
                    $medical_array['price'] = null;

                    $updated_medical_json = json_encode($medical_array, JSON_UNESCAPED_UNICODE);

                    $update_medical_sql = "UPDATE medical_creates SET medical = ?, status = 3 WHERE d_id = ?";
                    $update_medical_stmt = $conn->prepare($update_medical_sql);
                    $update_medical_stmt->bind_param('si', $updated_medical_json, $d_id);

                    if ($update_medical_stmt->execute()) {
                        header("Location: {$_SERVER['HTTP_REFERER']}");
                    } else {
                        header("Location: {$_SERVER['HTTP_REFERER']}");
                    }
                    $update_medical_stmt->close();
                } else {
                    echo "Failed to decode medical array.";
                }
            } else {
                header("Location: {$_SERVER['HTTP_REFERER']}");
            }
        } else {
            echo "No d_id found for the specified id.";
        }
    } else {
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }

    $stmt->close();
}

$conn->close();

// if (isset($_POST['id'])) {
//     $id = $_POST['id'];

//     // คำสั่ง SQL สำหรับลบข้อมูล
//     $delete_sql = "DELETE FROM m_dates WHERE id = ?";
//     $stmt = $conn->prepare($delete_sql);

//     if ($stmt) {
//         $stmt->bind_param('i', $id);

//         if ($stmt->execute()) {
//             header("Location: {$_SERVER['HTTP_REFERER']}");
//         } else {
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

