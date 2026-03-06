<?php
error_reporting(0);

$id = $_GET['id'] ?? 1;

$url = "https://rmtzx.fysrmt.com/api/media/channelDetail?siteId=1&channelId=" . $id;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: Mozilla/5.0 (Linux; Android 10)",
    "Accept: application/json, text/plain, */*",
    "Origin: https://rmtzx.fysrmt.com",
    "Referer: https://rmtzx.fysrmt.com/",
    "Host: rmtzx.fysrmt.com",
]);

$json = curl_exec($ch);
curl_close($ch);

if (!$json) {
    die(json_encode(["error" => "获取数据失败"], JSON_UNESCAPED_UNICODE));
}

$data = json_decode($json, true);

if (isset($data["channelLiveUrl"])) {
    header("Location: " . $data["channelLiveUrl"]);
    exit;
}

// 兼容数组结构
if (isset($data["data"]["channelLiveUrl"])) {
    header("Location: " . $data["data"]["channelLiveUrl"]);
    exit;
}

echo json_encode(["error" => "未找到播放地址"], JSON_UNESCAPED_UNICODE);
