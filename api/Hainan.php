<?php
$id=isset($_GET['id'])?$_GET['id']:'hnws';
$ids=array(
    "hnws"=>"STHaiNan_channel_lywsgq",//海南卫视
    "ssws"=>"STHaiNan_channel_ssws",//三沙卫视
    "xwpd"=>"STHaiNan_channel_xwpd",//海南新闻频道
    "wlpd"=>"wlpd",//海南文旅频道
    "jjpd"=>"jjpd",//海南自贸频道
    "ggpd"=>"ggpd",//海南公共频道
    "sepd"=>"sepd",//海南少儿频道
    );
$api=file_get_contents("http://ps.hnntv.cn/ps/livePlayUrl?appCode=&token=&channelCode=".$ids[$id]);
preg_match('/"url":"(.*?)"/i',$api,$m3u8);
// print_r($m3u8[1]);
header('Location:'.$m3u8[1]);
// $playurl="/player/videojs.php?url=".$m3u8[1];
// header('Location:'.$playurl);
?>