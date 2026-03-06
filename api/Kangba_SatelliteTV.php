<?php
/*
康巴卫视,kangba.php
*/

$url = 'https://mapi.kangbatv.com/api/v1/channel_detail.php?channel_id=17';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_REFERER, 'https://www.kangbatv.com/');
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
$response = curl_exec($ch);
curl_close($ch);

$m3u8 = json_decode($response, true)[0]['m3u8'];

header('Access-Control-Allow-Origin: *');
header('Location: ' . $m3u8);
