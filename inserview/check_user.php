<?php
include './db/conn.php';

if (!$conn) {
    die("Database connection failed.");
}

if (!$conn) {
    die("Database connection failed.");
}

$sql = "SELECT * FROM medical_creates WHERE status = 0";
$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

// function response_user()
// {
//     // if (!isset($_COOKIE["session_employee"])) {
//     //     die('Error: Session cookie not set.');
//     // }

//     $url = 'https://api1.tsmolymer.co.th/basic';
//     $data = [
//         'type' => 'user_name_chk',
//         'cookie' => $_COOKIE["session_employee"]
//     ];

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
//     curl_setopt($ch, CURLOPT_TIMEOUT, 0);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

//     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//         'Content-Type: application/json',
//         'Content-Length: ' . strlen(json_encode($data))
//     ));

//     $response = curl_exec($ch);

//     if ($response === false) {
//         die('Error occurred while fetching the data: ' . curl_error($ch));
//     }

//     curl_close($ch);

//     $response = json_decode($response, true);
//     // print_r($data);
//     return $response;
// }
// $response_chk = response_user();
?>
