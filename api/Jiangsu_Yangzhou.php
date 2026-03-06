<?php
//https://share.96189.com/yfs/channel/235/homepage
$id = isset($_GET['id'])?$_GET['id']:'yzxw';
$t = isset($_GET['t'])?$_GET['t']:'hls';//hls flv
$n = [
    'yzxw' => 235, //扬州新闻
    'yzms' => 291, //扬州民生
    'yzjd' => 290, //扬州江都
    'yzhj' => 292, //扬州邗江
];
$url = 'http://vapp.96189.com/setsail/external/externalService';
$post = 'service=getChannelDetail&params={"channelId":"'.$n[$id].'"}';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$d = curl_exec($ch);
curl_close($ch);

$data = json_decode($d,1)['data']['playUrl'];
$flv = explode(",",$data)[0];
$m3u8 = explode(",",$data)[1];

if($t=="hls"||$t==""){
    header("location:".$m3u8);
    //print_r($m3u8);
}
if($t=="flv"){
    header("location:".$flv);
    //print_r($flv);
}
?>