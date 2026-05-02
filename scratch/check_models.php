<?php
$key = 'AIzaSyARCki5DDq66ZtxUK3yyW5eIoOvdtfR3po';
$ch = curl_init('https://generativelanguage.googleapis.com/v1/models?key=' . $key);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
echo $res;
?>
