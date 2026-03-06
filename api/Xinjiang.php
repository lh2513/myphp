<?php
// 添加错误报告
error_reporting(0); // 保持原样

$n = [
     'xjws' => 1,   // 新疆卫视
     'xjwyzh' => 3,   // 新疆维语新闻综合
     'xjhyzh' => 4,   // 新疆哈语新闻综合
     'xjzy' => 16,  // 新疆综艺
     'xjwyys' => 17,  // 新疆维语影视
     'xjjjsh' => 18,  // 新疆经济生活
     'xjhyzy' => 19,  // 新疆哈语综艺
     'xjwyjjsh' => 20,  // 新疆维语经济生活
     'xjtyjk' => 21,  // 新疆体育健康
     'xjxxfw' => 22,  // 新疆信息服务
     'xjse' => 23   // 新疆少儿频道
     ];
$id = $_GET["id"] ?? "xjws";

// 添加简单的错误检查
if (!isset($n[$id])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => "频道ID '{$id}' 不存在"]);
    exit;
}

// 修复时间戳问题 - 使用intval避免PHP8.1警告
$t = intval(microtime(true) * 1000);

$sign = md5('@#@$AXdm123%)(ds'.$t.'api/TVLiveV100/TVChannelList');
$url = "https://slstapi.xjtvs.com.cn/api/TVLiveV100/TVChannelList?type=1&stamp={$t}&sign={$sign}";

// 设置请求上下文
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
        'timeout' => 10
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
]);

// 获取数据，添加错误检查
$response = @file_get_contents($url, false, $context);

if ($response === FALSE) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'API请求失败']);
    exit;
}

// 解析JSON，添加错误检查
$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'API返回数据解析失败']);
    exit;
}

// 检查API返回的错误
if (isset($data['message']) && $data['message'] == '签名不通过') {
    header('Content-Type: application/json');
    echo json_encode([
        'error' => '签名验证失败',
        'debug' => [
            'timestamp' => $t,
            'url' => $url
        ]
    ]);
    exit;
}

// 检查是否有data字段
if (!isset($data['data']) || !is_array($data['data'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'API返回数据格式错误']);
    exit;
}

// 查找播放地址
$playurl = null;
foreach($data['data'] as $v){
   if(isset($v['Id']) && $n[$id]==$v['Id'] && isset($v['PlayStreamUrl'])) {
       $playurl = $v['PlayStreamUrl'];
       break;
   }
}

if (!$playurl) {
    header('Content-Type: application/json');
    echo json_encode(['error' => "未找到频道 '{$id}' 的播放地址"]);
    exit;
}

// 成功则重定向
header('location:'.$playurl);
?>