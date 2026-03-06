<?php
$n = [
    'xwzh' => '29', // 西安新闻综合
    'dspd' => '30', // 西安都市
    'swzx' => '31', // 西安商务资讯
    'xjys' => '32', // 西安戏剧影视
    'slpd' => '33'  // 西安丝路频道
];
$id = isset($_GET['id']) ? $_GET['id'] : 'xwzh';
if (isset($_GET['ts'])) {
    $tsUrl = urldecode($_GET['ts']);
    if (strpos($tsUrl, 'http') !== 0) {
        $tsUrl = 'https://v.xiancity.cn' . (strpos($tsUrl, '/') === 0 ? '' : '/') . $tsUrl;
    }
    $tsData = fetchUrl($tsUrl);
        header('Content-Type: video/MP2T');
        echo $tsData;
    exit;
}
$info = "https://v.xiancity.cn/folder8/folder39/folder{$n[$id]}/";
$html = fetchUrl($info);
if (preg_match('/file\s*:\s*[\'"]((?:https?:)?\/\/[^\'"]+\.m3u8)/i', $html, $matches)) {
    $m3u8Url = $matches[1];
    if (strpos($m3u8Url, '//') === 0) {
        $m3u8Url = 'https:' . $m3u8Url;
    } elseif (strpos($m3u8Url, '/') === 0) {
        $m3u8Url = 'https://v.xiancity.cn' . $m3u8Url;
    }
    $m3u8Content = fetchUrl($m3u8Url);
    $proxyUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') .
               $_SERVER['HTTP_HOST'] .
               $_SERVER['SCRIPT_NAME'];
    $m3u8Content = preg_replace_callback(
        '/([^\s]+\.ts)(\?[^\s]*)?/',
        function($matches) use ($proxyUrl) {
            $tsPath = $matches[1];
            $query = isset($matches[2]) ? $matches[2] : '';
            if (strpos($tsPath, 'http') !== 0) {
                $base = dirname($GLOBALS['m3u8Url']);
                $tsPath = rtrim($base, '/') . '/' . ltrim($tsPath, '/');
            }
            return $proxyUrl . '?ts=' . urlencode($tsPath) . $query;
        },
        $m3u8Content
    );
    header('Content-Type: application/vnd.apple.mpegurl');
    echo $m3u8Content;
}
function fetchUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Referer: https://v.xiancity.cn/',
            'User-Agent: Mozilla/5.0'));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
?>