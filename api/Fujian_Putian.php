<?php
// 莆田电视台直播源
// 转换自 ptxl.js
// 使用: ptxl.php?id=pttv1 或 ptxl.php?id=pttv2 等

error_reporting(0);

// 获取id参数，默认为 pttv1
$id = $_GET['id'] ?? 'pttv1';

// 频道映射表
$channelMap = [
    'pttv1'  => 4,   // 莆田新闻综合
    'pttv2'  => 5,   // 莆田2
    'ptxy'   => 6,   // 莆田仙游
    'cctv1'  => 27,  // CCTV1综合
    'cctv13' => 28   // CCTV13新闻
];

// 获取对应的频道ID
$channelId = $channelMap[$id] ?? $channelMap['pttv1'];

// 构建请求URL
$apiUrl = "https://mapi.ptbtv.com/api/v1/channel.php?channel_id={$channelId}";

// 发送GET请求
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_TIMEOUT => 10
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 检查请求是否成功
if ($response === false || $httpCode !== 200) {
    http_response_code(502);
    die(json_encode(['error' => 'API请求失败']));
}

// 解析JSON响应
$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    die(json_encode([
        'error' => 'JSON解析失败: ' . json_last_error_msg()
    ]));
}

// 提取播放地址
if (!empty($data) && isset($data[0]['m3u8'])) {
    $playUrl = $data[0]['m3u8'];
    
    // 设置跨域头
    header('Access-Control-Allow-Origin: *');
    
    // 重定向到播放地址
    header("Location: {$playUrl}");
    exit;
} else {
    http_response_code(500);
    die(json_encode([
        'error' => '无法获取播放地址',
        'channel_id' => $channelId,
        'available_channels' => array_keys($channelMap)
    ]));
}
?>