<?php
// tongshan.php
error_reporting(0);

$id = $_GET['id'] ?? 'tsxwzh';

$map = [
    'tsxwzh' => 10,
    'tssn'   => 9,
    '4g'     => 12,
    '4g1'    => 13,
    'tstv2'  => 14,
    'qht'    => 15,
    'llzb'   => 16,
];

if (!isset($map[$id])) {
    die(json_encode(['error' => '频道ID不存在']));
}

$url = "http://mapi.tstvxmt.com/api/v1/channel.php?channel_id=" . $map[$id];

$resp = file_get_contents($url);
if (!$resp) {
    die(json_encode(['error' => '获取数据失败']));
}

$data = json_decode($resp, true);

if (isset($data[0]['m3u8'])) {
    header("Location: " . $data[0]['m3u8']);
    exit;
}

echo json_encode(['error' => '未找到 m3u8 地址']);
