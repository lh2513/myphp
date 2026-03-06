<?php
error_reporting(0);
date_default_timezone_set('Asia/Shanghai');
header('Content-Type: text/plain; charset=utf-8');
function curl($url) {
    $ch = curl_init();
    curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
CURLOPT_TIMEOUT => 30,CURLOPT_CONNECTTIMEOUT => 10,]);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
$purl=json_decode(curl("https://live-liveapi.vzan.com/api/v1/siteinfo/get_flow_livedetail?liveId=2046370057&types=1001&source=sdk&vid=330913109&domain=www.asiasatv.com"),true)['dataObj']['playUrl'];
header('Location:'.$purl);
exit;