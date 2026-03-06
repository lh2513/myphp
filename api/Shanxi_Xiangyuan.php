<?php
error_reporting(0);
$m3u8 = json_decode(file_get_contents("https://app.xyxrmt.com/apiv4.2/api/m3u8_notoken?channelid=2MAgsgb"),1)['data']['address'];
header("location:".$m3u8);
//echo $m3u8;
?>