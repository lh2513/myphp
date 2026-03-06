<?php
/*
防城港新闻综合,id=2
这个目前看不了//防城港公共,id=3
慢直播西港跨海大桥,id=9
*/
$channel_id = $_GET['id'] ?? 2;
$play_data = json_decode(file_get_contents("https://api-cms.fcgtvb.com/v1/mobile/channel/play?channel_id={$channel_id}"), true);
if ($play_data['code'] == 100000 && isset($play_data['data']['channel']['stream'])) {
    $stream_url = $play_data['data']['channel']['stream'];
    $auth_url = "https://api-cms.fcgtvb.com/v1/mobile/channel/play_auth?stream=" . urlencode($stream_url);
    $auth_data = json_decode(file_get_contents($auth_url), true);
    if ($auth_data['code'] == 100000 && isset($auth_data['data']['auth_key'])) {
        $live_url = $stream_url . (strpos($stream_url, '?') !== false ? '&' : '?') . "auth_key=" . $auth_data['data']['auth_key'];
        header("Location: " . $live_url);
        exit;
    }
}
?>