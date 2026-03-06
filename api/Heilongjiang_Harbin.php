<?php
/*
使用说明：

xxx.php?id=xwzh  //新闻综合频道
xxx.php?id=yspd  //影视频道
xxx.php?id=shpd  //生活频道
*/
header('Access-Control-Allow-Origin: *');
$channels = [
    'xwzh' => '3', // 新闻综合频道
    'yspd' => '5', // 影视频道
    'shpd' => '7', // 生活频道
];
$channel = isset($_GET['id']) ? trim($_GET['id']) : '';
if (empty($channel)) {
    header('Content-Type: text/plain; charset=utf-8');
    die("请指定频道代码：\n" . implode("\n", array_keys($channels)));
}
if (!isset($channels[$channel])) {
    header('Content-Type: text/plain; charset=utf-8');
    die("无效的频道代码，可用：\n" . implode("\n", array_keys($channels)));
}
$channelId = $channels[$channel];
$url = "https://www.hrbtv.net/m2o/channel/channel_info.php?id=" . $channelId;
$json = @file_get_contents($url);
if (!$json) {
    header('Content-Type: text/plain; charset=utf-8');
    die("无法获取频道信息");
}
$data = json_decode($json, true);
if (!$data || !isset($data[0])) {
    header('Content-Type: text/plain; charset=utf-8');
    die("解析数据失败");
}
$info = $data[0];
$m3u8 = '';
if (isset($info['channel_stream']) && is_array($info['channel_stream'])) {
    foreach ($info['channel_stream'] as $stream) {
        if (!empty($stream['m3u8'])) {
            $m3u8 = $stream['m3u8'];
            break;
        }
    }
}
if (empty($m3u8) && !empty($info['m3u8'])) {
    $m3u8 = $info['m3u8'];
}
if (empty($m3u8)) {
    header('Content-Type: text/plain; charset=utf-8');
    die("找不到m3u8地址\n原始数据：" . json_encode($info, JSON_UNESCAPED_UNICODE));
}
header('HTTP/1.1 302 Found');
header('Location: ' . $m3u8);
exit;
?>

