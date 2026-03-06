<?php
$id = $_GET['id'] ?? '3';
$api = "https://live.fjtv.net/m2o/channel/channel_info.php?channel_id={$id}";
$response = file_get_contents($api);
$data = json_decode($response, true);
$m3u8 = $data[0]['m3u8'];
header('location:'.$m3u8);
?>