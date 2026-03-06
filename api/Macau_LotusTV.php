<?php
/* 澳门莲花卫视  */

$master  = 'https://live-hls.macaulotustv.com/lotustv/lotustv.m3u8';
$referer = 'https://www.lotustv.mo/';
$base    = 'https://live-hls.macaulotustv.com/';

$ch = curl_init($master);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT        => 8,
    CURLOPT_HTTPHEADER     => ["Referer: $referer"],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
]);
$body = curl_exec($ch);
curl_close($ch);

if ($body === false) {
    http_response_code(502);
    exit();          // 静默失败，不暴露任何细节
}

/* 相对路径 → 绝对路径 */
$lines = explode("\n", $body);
foreach ($lines as &$l) {
    $l = rtrim($l);
    if ($l !== '' && $l[0] !== '#' && !preg_match('#^https?://#', $l)) {
        $l = $base . ltrim($l, './');
    }
}

header('Content-Type: application/vnd.apple.mpegurl');
header('Cache-Control: no-cache');
echo implode("\n", $lines);