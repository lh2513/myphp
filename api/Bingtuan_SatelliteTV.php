<?php
//使用: btws.php
$u = 'https://www.btzx.com.cn/2024new/new_zhibo/index.shtml';
$c = file_get_contents($u);
preg_match('/zbXinhao = "([^"]+)"/', $c, $m);

$u = 'https://api.btzx.com.cn/mobileinf/rest/cctv/videolivelist/dayWeb?json=%7B%27id%27:%27'.$m[1].'%27%7D';
$c = file_get_contents($u);
preg_match('/"url(hd)*":"([^"]+)"/', $c, $m);

header('Access-Control-Allow-Origin:*');
header('Location: '.$m[2]);