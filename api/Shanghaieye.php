<?php
$m3u8 = json_decode(file_get_contents("https://api.shanghaieye.com.cn/wpindex/stream/pullUrl"),1)['data']['hlsUrl'];
header("location:".$m3u8);
//echo $m3u8;
?>