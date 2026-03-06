<?php
$id=isset($_GET['id'])?$_GET['id']:'tyxwzh';
$n = [
"tyxwzh" => "49VAfrw",//太原新闻综合
"tyjjsh" => "u8BmT6h",//太原经济生活
"tysjfz" => "phsry3e",//太原社教法制
"tyys" => "J4EX72D",//太原影视
"tywt" => "rk8Z088",//太原文体
"tyblg" => "iancgyD",//太原佰乐购
"tycssh" => "i88rmGU",//太原城市
"tyjy" => "g4XtSCF",//太原教育
];
$t = time();
$token = md5($t.$n[$id].'cutvLiveStream|Dream2017');
$bstrURL = "http://hls-api.sxtygdy.com/getCutvHlsLiveKey?t=".$t."&token=".$token."&id=".$n[$id];
$p = file_get_contents($bstrURL);
$m3u8 = 'http://tytv-hls.sxtygdy.com/'.$n[$id].'/500/'.$p.'.m3u8';
header('Location:'.$m3u8);
//echo $m3u8;
?>