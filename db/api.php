<?php
function response_chk($response)
{
    $url = 'https://api1.tsmolymer.co.th/share';
    $data = [
        'type' => $response
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ));

    $response = curl_exec($ch);

    if ($response === false) {
        die('Error occurred while fetching the data: ' . curl_error($ch));
    }

    curl_close($ch);

    return json_decode($response, true);


    // $api_url = "https://api.center.tsmolymer.co.th/center";

    // // $api_url = "http://127.0.0.1:4000/center";

    // $data = [];
    // $data["send"] = json_encode([
    //     "path" => "share",
    //     "type" => "user_tsm",
    //     "session_employee" => $_COOKIE["session_employee"]
    // ], JSON_UNESCAPED_UNICODE);

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $api_url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    // $response = curl_exec($ch);

    // // print_r($data);
    // if (curl_errno($ch)) {
    //     echo 'Curl error: ' . curl_error($ch);
    // }

    // curl_close($ch);

    // $data = json_decode($response, true);
}
