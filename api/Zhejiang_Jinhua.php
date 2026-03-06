<?php
$url = "https://mapi.jcy.jinhua.com.cn/api/hotlive_h5/get_ali_pull_stream_url?channel_id=50&app_id=&noncestr=oFVzaVu8KkYQW4n&timestamp=1741964485&sign=0e2d8a62564f2dc46f6a97ce0718477e";
$m3u8 = json_decode(file_get_contents($url),1)['data']['url'];
header("location:".$m3u8);
//echo $m3u8;
?>