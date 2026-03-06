<?php
/*
齐齐哈尔新闻综合,id=4
齐齐哈尔经济法治,id=6
齐齐哈尔综合广播,id=9
齐齐哈尔交通广播,id=8
齐齐哈尔乡村广播,id=7
*/
$id = $_GET['id'];
$u = 'https://qqhrnews.com/addons/jianlive/api?id='.$id;
$c = file_get_contents($u);
$j = json_decode($c);
$p = $j->data->MediaUrl;
header('Access-Control-Allow-Origin: *');
header('Location: '.$p);
?>