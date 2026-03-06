<?php
$channelMap = [
    'cczh' => '36',  // 长春综合
    'wlty' => '37',  // 文旅体育
    'smsh' => '38'   // 市民生活
];

$channelKey = isset($_GET['id']) ? $_GET['id'] : 'smsh';

if (!isset($channelMap[$channelKey])) {
    die("无效的频道ID，请检查参数");
}

$resourceId = $channelMap[$channelKey];
$m3u8 = get_live_m3u8($resourceId);

if (empty($m3u8)) {
    die("获取直播流失败，请稍后重试");
}

header("Access-Control-Allow-Origin: *");
header("Location: " . $m3u8);
exit;


function get_live_m3u8($resourceId) {
    $apiUrl = "https://ccms.njgdmm.com/changchun/api/api-bc/share/liveTvById?resourceId={$resourceId}";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 10
    ]);
    $apiResponse = curl_exec($ch);
    curl_close($ch);
   
    if ($apiResponse === false) {
        return false;
    }
   
    $apiData = json_decode($apiResponse, true);
   
    if (json_last_error() !== JSON_ERROR_NONE || empty($apiData['error']) || $apiData['error'] != 200) {
        return false;
    }
   
    return $apiData['data']['url'] ?? false;
}
?>