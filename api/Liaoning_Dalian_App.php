<?php
$id = isset($_GET['id'])?$_GET['id']:'dlxwzh';
$idn = array(
"dlxwzh" => "tcb3IB5",//大连新闻综合
"dlsh" => "JzcFkF4",//大连生活
"dlwt" => "hxT7Fc3",//大连文体
//"dlys" => "8cuL6wa",//大连影视
//"dlse" => "q6tZ6Ba",//大连少儿
"dlgw" => "N4S4uAj",//大连乐天购物
);
$url="http://dlyapp.dltv.cn/apiv4.0/m3u8_notoken.php?channelid=".$idn[$id];
$info = file_get_contents($url);
preg_match_all('|"address":"(.*?)"|',$info,$playurl);
header('Location:'.str_replace('\/','/',$playurl[1][0]));
//echo str_replace('\/','/',$playurl[1][0]);  
?>