<?php
$id = $_GET['id'];
$n = [
    'xwzh' => 33, // 桂林新闻综合
    'glgg' => 34, // 桂林公共
    'glkj' => 35, // 桂林科教旅游
];
$url = "https://guilinbcnew.zainanjing365.com//share/live/detailTv?resourceId={$n[$id]}&appscheme=gdmm-zaiguilin";
$userAgent = 'Mozilla/5.0 (Linux; U; Android 13; zh-cn; PGZ110 Build/TP1A.220905.001) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/109.0.5414.86 MQQBrowser/13.8 Mobile Safari/537.36 COVC/046915';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
$response = curl_exec($ch);
curl_close($ch);
$startPos = strpos($response, 'const videoUrl = "');
if ($startPos !== false) {
    $startPos += strlen('const videoUrl = "');
    $endPos = strpos($response, '"', $startPos);
    if ($endPos !== false) {
        $videoUrl = substr($response, $startPos, $endPos - $startPos);
        header("Location: $videoUrl");
        exit;
   }
}
?>