<?php
/*
宁波新闻综合,id=nbtv1
宁波社会生活,id=nbtv2
宁波文化娱乐,id=nbtv3
宁波影视剧,id=nbtv4
*/
$id = $_GET['id'];
$url = "https://cms.nj.nbtv.cn?task=get-live&channelName={$id}";
$response = file_get_contents($url);
$data = json_decode($response, true);
header('Access-Control-Allow-Origin: *');
header("Location: " . $data['data']['liveUrl']);