<?php
$n = $_GET['n'];
$u = 'https://www.nctv.net.cn/live';
$c = file_get_contents($u, false, stream_context_create(array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
    ),
    'http' => array(
        'header'  => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.6261.95 Safari/537.36\r\n",
    )
)));
preg_match('/data-url="(.+?)".*?'.$n.'/', $c, $m);
$p = htmlspecialchars_decode($m[1]);
header('Access-Control-Allow-Origin: *');
header('Location: '.$p);

/*
新闻综合频道(现为都市频道),nc.php?n=%E6%96%B0%E9%97%BB
文旅频道(现为都市频道),nc.php?n=%E6%96%87%E6%97%85
资讯频道,nc.php?n=%E8%B5%84%E8%AE%AF
新闻综合频率,nc.php?n=%E9%A2%91%E7%8E%87
南昌交通广播,nc.php?n=%E5%B9%BF%E6%92%AD
*/