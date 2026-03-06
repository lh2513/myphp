<?php
// 获取频道ID参数
$channelId = $_GET['id'] ?? 'TNT_Sports_1';

// 构建流URL
$streamUrl = "https://smartstream.lioncdn.net/{$channelId}/index.m3u8";

// 设置Referer并重定向
header('Referer: https://p.lioncdn.net/');
header('Location: ' . $streamUrl);
exit;
?>