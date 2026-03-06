<?php
//https://hsynew.hongshannet.cn/application/tvradio/h5/detail.html?type=tv&id=17
$id = $_GET['id'];
$u = 'https://hsynew.hongshannet.cn/tvradio/Tvfront/getTvInfo?tv_id='.$id;
$c = file_get_contents($u);
$j = json_decode($c, true);
$l = $j['data']['m3u8'];
header('Access-Control-Allow-Origin: *');
header('Location: '.$l);



/* 可能不支持国外服务器。
乌鲁木齐电视台汉语综合频道,wlmq.php?id=17
乌鲁木齐电视台维吾尔语综合频道,wlmq.php?id=21
*/