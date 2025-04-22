<?php

// $api_url = "api.center.tsmolymer.co.th";

// $data = [];
// $data["send"] = json_encode([
//     "path" => "share",
//     'type' => 'user_tsm',
//     "session_employee" => $_COOKIE["session_employee"]
// ], JSON_UNESCAPED_UNICODE);


// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, $api_url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// $response = curl_exec($ch);

// // print_r($response);

// if ($response === false) {
//     die('Error occurred while fetching the data: ' . curl_error($ch));
// }

// curl_close($ch);
// echo "<pre>";
// // echo $response;
// $response = json_decode($response, true);
// print_r($response)

// -------------------------------------------------------------------------------------------------------------//
$api_url = "https://center.tsmolymer.co.th/center";

// $api_url = "http://127.0.0.1:4000/center";

$data = [];
$data["send"] = json_encode([
    "path" => "share",
    "type" => "user_tsm",
    "session_employee" => $_COOKIE["session_employee"]
], JSON_UNESCAPED_UNICODE);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);

// print_r($data);
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
}

curl_close($ch);

$data = json_decode($response, true);
print_r($data);
// -----------------------------------------------------------------------------------------------------------//

// function response_user()
// {

//     if (!isset($_COOKIE["session_employee"])) {
//         die('Error: Session cookie not set.');
//     }

//     $api_url = "https://api.center.tsmolymer.co.th/center";

//     // $api_url = "http://127.0.0.1:4000/center";

//     $data = [];
//     $data["send"] = json_encode([
//         "path" => "basic",
//         "type" => 'user_name_chk',
//         "session_employee" => $_COOKIE["session_employee"]
//     ], JSON_UNESCAPED_UNICODE);

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $api_url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

//     $response = curl_exec($ch);

//     // print_r($data);
//     if ($response === false) {
//         die('Error occurred while fetching the data: ' . curl_error($ch));
//     }

//     curl_close($ch);

//     $response = json_decode($response, true);
//     return $response;
// }

// $response_chk = response_user();
// if ($response_chk === null) {
//     die('Error: API response is null.');
// }

// print_r($response_chk);
// ?>
