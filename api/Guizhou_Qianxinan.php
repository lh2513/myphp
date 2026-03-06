<?php
error_reporting(0);
$id = $_GET['id']??'qxnzh';
$n = [
    "qxnzh" => 11,//黔西南综合
    "qxngg" => 14,//黔西南公共 x
    ];
$url = 'http://mapi2.qxndt.com/api/v1/channel.php?channel_id='.$n[$id];
$m3u8 = json_decode(file_get_contents($url),1)[0]['m3u8'];
header("location:".$m3u8);
//echo $m3u8;
?>