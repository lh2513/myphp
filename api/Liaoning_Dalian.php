<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'dlzh';
$n = [
'dlzh' => 'tcb3IB5',//大连新闻综合
'dlsh' => 'JzcFkF4',//大连生活
'dlwt' => 'hxT7Fc3',//大连文体
'dlgw' => 'N4S4uAj'//大连乐天购物
];
$t = time();
$token=md5($t.$n[$id].'cutvLiveStream|Dream2017');
$bstrURL = "https://hls-api.sztv.com.cn/getCutvHlsLiveKey?t={$t}&token={$token}&id=".$n[$id];
$p = file_get_contents($bstrURL);
$m3u8 = 'http://live.dltv.cn/'.$n[$id].'/500/'.$p.'.m3u8';
header("location:".$m3u8);
//echo $m3u8;
?>