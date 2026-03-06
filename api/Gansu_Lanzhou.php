<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'lzxwzh';
$ts = $_GET['ts'];
$n = [
'lzxwzh' => ['xwzh','tv'],//新闻综合
'lzwl' => ['wlpd','tv'],//文旅
'lzzhgb' => ['aac_zhgb','gb'], //兰州新闻综合广播
'lzyygb' => ['aac_jtyy','gb'], //兰州音乐广播
'lzwygb' => ['aac_shwy','gb'], //兰州文艺广播
];
$m3u8 = "http://liveplus.lzr.com.cn/{$n[$id][0]}/HD/live.m3u8";
$php = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$burl = "http://liveplus.lzr.com.cn/";

if($n[$id][1]=="tv"){
if(!$ts) {
header('Content-Type: application/vnd.apple.mpegurl');
print_r(preg_replace("/(.*?.ts)/i", $php."?ts=$burl$1",get($m3u8)));
} else {
$data = get($ts);
header('Content-Type: video/MP2T');
echo $data;
}
} 

if($n[$id][1]=="gb"){
if(!$ts) {
$a = preg_replace("/aac_/", "aab_", get($m3u8));
$b = preg_replace("/(.*?.aac)/i", $php."?ts=$burl$1", $a);
header('Content-Type: application/vnd.apple.mpegurl');
print_r(preg_replace("/aab_/", "aac_", $b));
} else {
$d = get($ts);
header('Content-Type: video/MP2T');
echo $d;
}
}
function get($url){
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_REFERER, 'http://lanzhoubcnew.zainanjing365.com/');
curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: Mozilla/5.0 (Windows NT 6.1)"]);
$res = curl_exec($ch);
curl_close($ch);
return $res;
}
?>