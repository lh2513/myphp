<?php
/*
肥东新闻综合,fd.php?id=752
肥东经济生活,fd.php?id=753
*/
$id = $_GET['id'];
$u = "http://wxfx.feidongtv.com/mag/livevideo/v1/video/wapVideoView?id=".$id;
$c = file_get_contents($u);
preg_match('/video .+?src="(.+?)"/', $c, $m);
header("Access-Control-Allow-Origin: *");
header("Location: {$m[1]}");
