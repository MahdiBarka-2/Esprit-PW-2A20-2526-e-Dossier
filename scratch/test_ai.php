<?php
$key = 'AIzaSyCGVsXFMMcujyLMpSatKPPl55J0b3v2gcg';
$url = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash-lite:generateContent?key=' . $key;

$data = [
    "contents" => [["parts" => [["text" => "Hello"]]]]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$res = curl_exec($ch);
if(curl_errno($ch)) echo "CURL ERROR: " . curl_error($ch);
else echo $res;
?>
