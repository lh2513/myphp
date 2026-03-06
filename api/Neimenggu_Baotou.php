<?php
$id = $_GET['id'];
$u = 'https://iapp.baotounews.com.cn/share/'.$id.'.html';
$c = file_get_contents($u);
preg_match('/source src="([^"]+)"/', $c, $m);
header('Access-Control-Allow-Origin: *');
header('Location: '.$m[1]);



/*
包头新闻综合,baotou.php?id=dHZsLTY4OS0xMQ
包头经济,baotou.php?id=dHZsLTY4OS0zNQ
*/