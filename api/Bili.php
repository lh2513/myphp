<?php
//bili原画代码，打开哔哩直播页面，随机打开一个直播间，比如https://live.bilibili.com/22747736?hotRank=0&session_id=6725cad2895aeaf7407c9b8b6b690ee2_D4B0724F-1080-4BD0-A3B2-8201E7A111F6&live_from=77002&trackid=live_feed_0.router-live-2231394-jr4hq.1762583085049.357，其中https://live.bilibili.com/和?之间的22747736就是id。
//使用方法：http://xxxxxxx/bili.php?id=22747736
$roomId = $_GET['id'] ?? '4053897';
$playResponse = file_get_contents("https://api.live.bilibili.com/room/v1/Room/playUrl?quality=4&cid=$roomId");
$playUrl = json_decode($playResponse, true) ['data']['durl'][0]['url'] ?? '';
header('Location: ' . $playUrl);