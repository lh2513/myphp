<?php
$id = isset($_GET['id'])?$_GET['id']:'zzxwzh';
$n = [
  'zzxwzh' => 103,//郑州新闻综合
  'zzsd' => 104,//郑州商都频道
  'zzwtly' => 105,//郑州文体旅游
  'zzyj' => 106,//郑州豫剧频道
  'zzfnet' => 107,//郑州妇女儿童
  'zzdssh' => 108,//郑州都市生活  
  ];
$d = file_get_contents("http://mapi-new.zztv.tv/api/v1/channel.php?channel_id=".$n[$id]);
$m3u8 = json_decode($d,1)[0]['m3u8'];
header("location:".$m3u8);
//print_r($m3u8);
?>