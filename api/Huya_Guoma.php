<?php
error_reporting(0);
$id = $_GET['id'];//房间号(lProfileRoom)
if (empty($_GET['id'])) $id ="11342412";

$roomurl = "https://mp.huya.com/cache.php?m=Live&do=profileRoom&roomid=$id";
$json = json_decode(file_get_contents($roomurl),1);
$data = $json["data"];
$uid = $data['profileInfo']['uid'];
$streamname = $data['stream']['baseSteamInfoList'][0]['sStreamName'];
$url = $data['stream']['hls']['multiLine'][1]['url'];
$burl = explode('?', $url)[0];
$seqid = strval(intval($uid) + intval(microtime(true) * 1000));

$ctype = 'tars_wap';
$t = '102';    
$ss = md5("{$seqid}|{$ctype}|{$t}");
$wsTime = dechex(time() + 21600);
$wsSecret = md5("DWq8BcJ3h6DJt6TY_{$uid}_{$streamname}_{$ss}_{$wsTime}");
$s = [];
$s["wsSecret"] = $wsSecret;
$s["wsTime"] = $wsTime;
$s["ctype"] = $ctype;
$s["seqid"] = $seqid;
$s["uid"] = $uid;
$s["ver"] = "1";
$s["t"] = $t;

$playurl = $burl.'?'.http_build_query($s);
$burl = dirname($playurl)."/";
$mediaurl = preg_replace("/(.*?.ts)/i","$burl$1",get($playurl));
$mediaurl = str_replace(".ts?",".ts?wsSecret=".md5(time()).'&&wsTime='.dechex(time()).'&', $mediaurl);
header('Content-Type: application/vnd.apple.mpegurl');
print_r($mediaurl);

function get($url){
    $ip = $_SERVER['SERVER_ADDR'];//'127.0.0.1';
    $h = [
        'User-Agent: Mozilla/5.0',
        "CLIENT-IP: ".$ip,
        "X-FORWARDED-FOR: ".$ip,
        ];
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch,CURLOPT_HTTPHEADER,$h);
     $res = curl_exec($ch);
     curl_close($ch);
     return $res;
     }
?>