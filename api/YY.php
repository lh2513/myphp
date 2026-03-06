<?php
function initCurl($url, $headers = []) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => $headers
    ]);
    return $ch;
}
$rid = isset($_GET['id']) ? trim($_GET['id']) : '1354210357';
$ch = initCurl(
    "http://interface.yy.com/hls/new/get/{$rid}/{$rid}/1200?source=wapyy&callback=jsonp3",
    ['User-Agent: Mozilla/5.0','Referer: http://www.yy.com/']
);
preg_match('/"hls":"(.*?)"/', curl_exec($ch), $m) or exit('未找到有效的HLS地址');
curl_close($ch);
$ch = initCurl(
    str_replace('\\/', '/', $m[1]),
    ['Referer: https://wap.yy.com/', 'Accept: */*']
);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) ?: exit('无法获取最终跳转地址');
curl_close($ch);
header("Location: $url");