<?php
// 宁德电视台直播源
// 转换自 ndxl.js
// 使用: ndxl.php?id=ndxwzh 或 ndxl.php?id=ndwhly

error_reporting(0);

// 获取id参数，默认为 ndxwzh
$id = $_GET['id'] ?? 'ndxwzh';

// 频道映射表
$channelMap = [
    'ndxwzh' => 41,  // 宁德新闻综合
    'ndwhly' => 42   // 宁德文化旅游
];

// 获取对应的频道ID
$channelId = $channelMap[$id] ?? $channelMap['ndxwzh'];

// 构建请求URL
$apiUrl = "http://mapi.nddst.com/api/v1/channel.php?channel_id={$channelId}";

// 发送GET请求
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
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
        'response' => $data
    ]));
}
?>