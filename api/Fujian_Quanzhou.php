<?php
// 泉州电视台直播源
// 转换自 quanzhou.js
// 使用: quanzhou.php?id=qzzh 或 quanzhou.php?id=qzmny

error_reporting(0);

// 获取id参数，默认为 qzzh
$id = $_GET['id'] ?? 'qzzh';

// 频道映射表
$channelMap = [
    'qzzh'  => 'wq95wqbDnMKyd8KiwqzChnt0w5nChcKowoHCoQ/stream_name/news.html',      // 泉州新闻综合
    'qzmny' => 'wq95wqbDnMKyd8KiwqzChnt0w5nChcKofcKh/stream_name/mny.html'        // 泉州闽南语
];

// 获取媒体ID
$mediaId = $channelMap[$id] ?? $channelMap['qzzh'];

// 构建API URL
$apiUrl = "https://control-center.qztv.cn/index/Medias/index/media_id/{$mediaId}";

// 设置请求头
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => [
        'Referer: https://control-center.qztv.cn/',
        'User-Agent: Mozilla/5.0'
    ],
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

// 使用正则提取播放地址
$pattern = '/urls\s*=\s*"([^"]+)"/';
if (preg_match($pattern, $response, $matches)) {
    $liveStreamUrl = $matches[1];
    
    // 设置跨域头
    header('Access-Control-Allow-Origin: *');
    
    // 重定向到播放地址
    header("Location: {$liveStreamUrl}");
    exit;
} else {
    http_response_code(500);
    die(json_encode([
        'error' => '未找到播放地址',
        'channel_id' => $id,
        'response_preview' => substr($response, 0, 200)
    ]));
}
?>