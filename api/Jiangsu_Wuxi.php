<?php
//放到服务器好像不行，而本机可以。
$id = isset($_GET['id']) ? $_GET['id'] : 'wxtv1';
$n = [
    'wxtv1' => 4,//无锡新闻综合
    'wxtv2' => 8,//无锡娱乐
    'wxtv3' => 9,//无锡都市资讯
    'wxtv4' => 10,//无锡生活
    'wxtv5' => 11,//无锡经济
];
$url = 'http://bb-mapi.wifiwx.com/api/v1/channel.php?channel_id='.$n[$id];
$d = file_get_contents($url);
if ($d === false) {
    print_r($http_response_header);
    die;
}
$playurl = json_decode($d)[0]->m3u8;
header('Access-Control-Allow-Origin: *');
header('Location: '.$playurl);