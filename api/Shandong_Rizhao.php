<?php

error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'rzxwzh';
$n = [
'rzxwzh' => 6,
'rzkj' => 12,
'rzgg' => 13,
];
$url = "https://mapi.rzw.com.cn/api/v1/channel.php?channel_id=".$n[$id];
$m3u8 = json_decode(file_get_contents($url),1)[0]['m3u8'];
$burl = dirname($m3u8)."/";
$live = $burl.strstr(file_get_contents($m3u8),"sd");
$php = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$ts = $_GET['ts'];
if(empty($ts)) {
header('Content-Type: application/vnd.apple.mpegurl');
print_r(preg_replace("/(.*?.ts)/i",$php."?ts=$burl$1",get(trim($live))));
} else {
$data = get($ts);
header('Content-Type: video/MP2T');
echo $data;
}
function get($url){
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_REFERER, 'https://www.rzw.com.cn/');
$res = curl_exec($ch);
curl_close($ch);
return $res;
}
?>