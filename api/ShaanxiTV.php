<?php
//http://live.snrtv.com/star
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'sxyd';
$n = [
'sxws' => 'star', //陕西卫视
'nlws' => 'nl', //农林卫视
'sxxwzx' => 1, //陕西新闻资讯
'sxdsqc' => 2, //陕西都市青春
'sxyl' => 3, //陕西银龄
'sxqq' => 5, //陕西秦腔
'ljgw' => 6, //陕西乐家购物
'sxtyxx' => 7, //陕西体育休闲
'sxyd' => 11, //陕西移动
];
$d = file_get_contents('http://toutiao.cnwest.com/static/v1/stream.js');
preg_match('/sTV="(.*?)";/',$d,$sTV);
preg_match('/sRadio="(.*?)";/',$d,$sRadio);
$k = substr($sTV[1],0,16);
$iv = substr($sRadio[1],0,16);
$data = explode('}}',openssl_decrypt(substr($sTV[1],16),'AES-128-CBC',$k,OPENSSL_ZERO_PADDING,$iv))[0]."}}";
$data = json_decode($data);
$playurl = $data->{$n[$id]}->m3u8;
header('Content-Type: application/vnd.apple.mpegurl');
header('location:'.$playurl);
//print_r($playurl);
?>