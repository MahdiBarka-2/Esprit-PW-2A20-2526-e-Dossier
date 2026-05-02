<?php
$key = 'AIzaSyARCki5DDq66ZtxUK3yyW5eIoOvdtfR3po';
$ch = curl_init('https://generativelanguage.googleapis.com/v1/models?key=' . $key);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
$data = json_decode($res, true);
if (isset($data['models'])) {
    foreach ($data['models'] as $m) {
        if (in_array('generateContent', $m['supportedGenerationMethods'])) {
            echo $m['name'] . "\n";
        }
    }
} else {
    echo $res;
}
?>
