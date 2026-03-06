<?php
$n = $_GET['n'];
$u = 'https://www.gstv.com.cn/zxc.jhtml';
$c = file_get_contents($u, false, stream_context_create(array(
    'http' => array(
        'header'  => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36\r\n",
    )
)));
preg_match('/data-url=\'(.+?)\'>.*?'.$n.'/', $c, $m);
$count = 1;
$p = str_replace('hls', 'liveout', $m[1], $count);
header('Access-Control-Allow-Origin: *');
header('Location: '.$p);

/* 不支持国外服务器，报403！
甘肃卫视,gstv.php?n=%E5%8D%AB%E8%A7%86
文化影视,gstv.php?n=%E5%BD%B1%E8%A7%86
公共应急,gstv.php?n=%E5%85%AC%E5%85%B1
少儿频道,gstv.php?n=%E5%B0%91%E5%84%BF
科教频道,gstv.php?n=%E7%A7%91%E6%95%99
移动电视,gstv.php?n=%E7%A7%BB%E5%8A%A8
*/