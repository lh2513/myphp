<?php
//https://www.cbg.cn/web/list/4918/1.html?5DEFC70C1A4AOD5173881C62BD4ACAD0
$u = 'https://rmtapi.cbg.cn/list/4918/1.html?pagesize=20';
$c = file_get_contents($u);
$j = json_decode($c, true);
$u = $j['data']['lists'][0]['android_url'];

$u = 'https://web.cbg.cn/live/getLiveUrl?url='.urlencode($u);
$c = file_get_contents($u);
$j = json_decode($c, true);
$u = $j['data']['url'];

header('Access-Control-Allow-Origin: *');
header('Location: '.$u);