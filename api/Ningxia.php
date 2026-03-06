<?php
$id = $_GET['id'];
$u = 'https://www.nxtv.com.cn/19/19kds/dsp/';
$c = file_get_contents($u);
preg_match('/id="'.$id.'".+?"liveUrl":"(.+?)"/', $c, $m);
$l = str_replace('\\', '', $m[1]);
$h = parse_url($l, PHP_URL_HOST);
$l = str_replace($h, 'livepgc.cmc.ningxiahuangheyun.com', $l);
header('Access-Control-Allow-Origin: *');
header('Location: '.$l);



/*
宁夏卫视频道,nx.php?id=56
宁夏公共频道,nx.php?id=111
宁夏经济频道,nx.php?id=61
宁夏文旅频道,nx.php?id=71
宁夏少儿频道,nx.php?id=67
*/
?>