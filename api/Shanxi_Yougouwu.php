<?php
$url = "https://www.huimai.com.cn/flow/index/getLiveStream?goodsId=779359&channelKey=UGO1";
$m3u8 = json_decode(file_get_contents($url),1)['data'];
header("location:".$m3u8);
//echo $m3u8;
?>