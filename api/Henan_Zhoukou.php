<?php
$id = isset($_GET['id'])?$_GET['id']:'zkxwzh';
$n = array(
   'zkxwzh' => 1,//周口新闻综合
   'zkgg' => 2,//周口公共频道
   'zkjy' => 3,//周口教育频道
   'zkch' => 4,//周口川汇频道
);

$d=file_get_contents('http://mms.yszkapp.cn:18080/mms4.6.3/videoPlayInterface/getChannelInfo.jspa?token=pmqhukcpyipvqmoz&channelId='.$n[$id]);
//print_r(json_decode($d)->streamList[0]->streamURL);
header('location:'. json_decode($d)->streamList[0]->streamURL);
?>