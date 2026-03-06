<?php
$id = isset($_GET['id'])?$_GET['id']:'1905a';
$n = [
    'cctv6' => 'LIVEOYY31H24H48NE',//CCTV6电影频道
    '1905b' => 'LIVE8J4LTCXPI7QJ5_258',//1905国外电影
    '1905' => 'LIVEOYY31H24H48NE',//1905国内电影
];



$salt = "689d471d9240010534b531f8409c9ac31e0e6521"; 
$url = "https://profile.m1905.com/mvod/liveinfo.php";
$StreamName = $n[$id];
$ts = time();
$playid = substr($ts,-4).'12312345678';
$params = [
    'cid'=> 999999,
    'expiretime'=> 2000000600,
    'nonce'=> 2000000000,
    'page'=> 'https://www.1905.com',
    'playerid'=> $playid,
    'streamname'=> $StreamName,
    'uuid'=> 1
];
$sign = sha1(http_build_query($params).'.'.$salt);
$params['appid'] = 'W0hUwz8D';
$headers = [
    'Authorization: '.$sign,
    'Content-Type: application/json',
    'Origin: https://www.1905.com',
    
];
$data=curl($url,$params,$headers);
$json = json_decode($data,true);
// print_r($data);die;
 $playURL = $json['data']['quality']['hd']['host'].$json['data']['path']['hd']['uri'].$json['data']['sign']['hd']['hashuri'];
header('location:'.$playURL);
print($playURL);

function curl($url,$params,$headers){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_POST,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($params));
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
    }
    
?>
    
    
