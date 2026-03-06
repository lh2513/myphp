<?php
$id = isset($_GET['id'])?$_GET['id']:'zh';
$n = [
    "zh" => '1375684745699328', //综合频道
    "xw" => '1375684808818688', //新闻频道
    "fz" => '1375684882210816', //法治频道
    "ngds" => '1375684847116288', //4K南国都市频道
    ];
$url= 'https://gzbn.gztv.com:7443/plus-cloud-manage-app/liveChannel/queryLiveChannelList?type=1';
$d = json_decode(file_get_contents($url),1)['data'];
      foreach($d as $v){
         if($n[$id]==$v['id']) {
             $m3u8 = $v['httpUrl'];
             break;
         }}
header("location:".$m3u8);
//print_r($m3u8);
?>