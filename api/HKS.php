<?php
//http://www.hkatv.com/tv
$url = "https://srv-news.hkatv.vip/TVHandler/GetTV";
$post = '{"Offset":1,"Limit":100,"Conditions":{"Status":2,"Aes":1}}';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$d = json_decode(curl_exec($ch),1);
curl_close($ch);
foreach($d['TVList'] as $v){
   if($v['ID'] == 9)
   $str = $v['SourceURL'];
   }
$k = "4kqvNg8LyIe1WQTs";
//$iv = "0000000000";
$iv = "0000000000000000";
$result = openssl_decrypt(base64_decode($str),'AES-128-CBC', $k,OPENSSL_RAW_DATA, $iv);
$query = parse_url($result)["query"];
$m3u8 = "https://al-pull.hkatv.vip/live/hkstv3.m3u8?".$query;
header("Access-Control-Allow-Origin: *");
header("location:".$m3u8);
//echo $m3u8;
?>