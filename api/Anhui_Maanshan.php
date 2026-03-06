<?php
// 马鞍山电视台直播源
// 转换自 mas.js
// 使用: mas.php?id=xwzh 或 mas.php?channel=新闻综合

error_reporting(0);

// 频道名称映射表
$channelMap = [
    "xwzh" => "新闻综合",
    "kjsh" => "科教生活", 
    "masxwlb" => "马鞍山新闻联播"
];

// 获取传入的参数 (优先级: id > channel > name)
$inputParam = $_GET['id'] ?? $_GET['channel'] ?? $_GET['name'] ?? 'xwzh';

// 根据映射表获取实际的频道名称
$channelName = $channelMap[$inputParam] ?? $inputParam;

// 生成当前时间戳（毫秒）
$timestamp = round(microtime(true) * 1000);

// API URL
$apiUrl = "https://maanshanxinwenwangzhan.masbcx.cn/json/channel/ds/list.json?_t={$timestamp}&appId=pc-4f11e7ed62b349ef8be0035b283a0d9f&siteId=8b233b99cc134eabb6a9c2965c038118";

// 初始化 CURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_ENCODING => 'gzip, deflate', // 自动处理gzip压缩
    CURLOPT_HTTPHEADER => [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
        'Referer: https://www.masbcx.cn/',
        'Accept: application/json, text/plain, */*',
        'Accept-Language: zh-CN,zh;q=0.9'
    ],
    CURLOPT_TIMEOUT => 10
]);

// 执行请求
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// 检查请求是否成功
if ($httpCode !== 200) {
    http_response_code(502);
    die(json_encode([
        'error' => "API请求失败，状态码: {$httpCode}",
        'curl_error' => $curlError
    ]));
}

if ($response === false) {
    http_response_code(502);
    die(json_encode([
        'error' => 'CURL请求失败',
        'curl_error' => $curlError
    ]));
}

// 解析JSON
$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    die(json_encode([
        'error' => 'JSON解析失败: ' . json_last_error_msg(),
        'raw_response' => substr($response, 0, 200)
    ]));
}

// 查找频道
$targetChannel = null;
foreach ($data['list'] ?? [] as $listItem) {
    if (isset($listItem['data']['title']) && $listItem['data']['title'] === $channelName) {
        $targetChannel = $listItem['data'];
        break;
    }
}

// 如果未找到频道
if ($targetChannel === null) {
    http_response_code(404);
    
    // 提取可用频道列表
    $availableChannels = [];
    foreach ($data['list'] ?? [] as $listItem) {
        if (isset($listItem['data']['title'])) {
            $availableChannels[] = [
                'title' => $listItem['data']['title'],
                'id' => $listItem['data']['id'] ?? null
            ];
        }
    }
    
    die(json_encode([
        'error' => "未找到频道: {$channelName}",
        'input_param' => $inputParam,
        'available_channels' => $availableChannels
    ], JSON_UNESCAPED_UNICODE));
}

// 获取播放地址 (优先使用 otherUrl)
$playUrl = $targetChannel['otherUrl'] ?? $targetChannel['url'] ?? null;

if (empty($playUrl)) {
    http_response_code(500);
    die(json_encode([
        'error' => "频道 {$channelName} 没有播放地址",
        'channel_info' => [
            'title' => $targetChannel['title'] ?? null,
            'id' => $targetChannel['id'] ?? null
        ]
    ], JSON_UNESCAPED_UNICODE));
}

// 设置跨域头
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: *');

// 重定向到播放地址
header("Location: {$playUrl}");
exit;
?>