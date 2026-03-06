<?php
//http://127.0.0.1/huya.php?id=11342412
error_reporting(0);
date_default_timezone_set('PRC');
get_ts();
function get_ts()
{
start:
if(strpos($_GET["id"],"-")!== false){
$id=$_GET["id"];
}else{
$idall=file_get_contents("hyid.txt");
$str=strchr($idall,$_GET["id"]);
if (!empty($str)){
$arr = explode(",", $str);
$id=$arr[1];
}else{
$id=getlid($_GET["id"],"hyid.txt");
}
}
//http://hs.hls.huya.com/src/1394565191-1394565191-5989611887484993536-2789253838-10057-A-0-1.m3u8
$host='http://hs.hls.huya.com/src/';
$url=$host.$id.".m3u8";
$User_Agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/".rand(101,555).".".rand(11,99)." (KHTML, like Gecko) Chrome/".rand(46,109).".0.0.0 Safari/".rand(101,555).".".rand(11,99);
$ch=curl_init(); 
$timeout_ms=900;
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_NOSIGNAL,1);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,$timeout_ms);
curl_setopt($ch,CURLOPT_TIMEOUT_MS, $timeout_ms);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch,CURLOPT_USERAGENT,$User_Agent);
curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-FORWARDED-FOR:'.'127.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255),'CLIENT-IP:'.'127.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255)));
$mediaurl=curl_exec($ch);
$count=mb_substr_count($mediaurl,"EXTINF");//echo $count;
$str=strchr($mediaurl,".ts");
$str1=strchr($mediaurl,"ENDLIST");
if((!empty($str))&&(empty($str1))&&($count>=3)){
$mediaurl=str_replace(",\n",",\n".$host, $mediaurl);
?>