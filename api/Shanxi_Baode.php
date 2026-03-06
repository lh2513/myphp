<?php
// 保德电视台直播源
// 转换自 baode.js
// 使用: baode.php?id=https://xxx.com/xxx

error_reporting(0);

// 获取id参数（完整URL）
$id = $_GET['id'] ?? '';

if (empty($id)) {
    http_response_code(400);
    die(json_encode([
        'error' => '缺少id参数',
        'usage' => '请传入完整的频道URL，例如: ?id=https://...'
    ]));
}

// 设置请求头
$headers = [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; HMA-AL00 Build/HUAWEIHMA-AL00; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/88.0.4324.93 Mobile Safari/537.36'
];

// 初始化CURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_TIMEOUT => 10
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $httpCode !== 200) {
    http_response_code(502);
    die(json_encode(['error' => 'API请求失败']));
}

// 移除反斜杠
$playData = str_replace('\\', '', $response);

// 使用正则提取播放地址
$pattern = '/"channelLiveUrl":"(https:\/\/.+?)"/';
if (preg_match($pattern, $playData, $matches)) {
    $finalUrl = $matches[1];
    
    // 设置跨域头
    header('Access-Control-Allow-Origin: *');
    
    // 重定向到播放地址
    header("Location: {$finalUrl}");
    exit;
} else {
    http_response_code(500);
    die(json_encode([
        'error' => '未找到播放地址',
        'response_preview' => substr($playData, 0, 200)
    ]));
}
?>