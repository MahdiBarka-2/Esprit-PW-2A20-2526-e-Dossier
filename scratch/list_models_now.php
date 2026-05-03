<?php
$key = 'AIzaSyBJc_-O_wlb36Eh4H9IcBzz9wGGG-rhDAc';
$url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $key;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$res = curl_exec($ch);
if(curl_errno($ch)) echo "CURL ERROR: " . curl_error($ch);
else echo $res;
?>
