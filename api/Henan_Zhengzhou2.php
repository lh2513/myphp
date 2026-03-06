<?php
$ts = isset($_GET['ts'])?$_GET['ts']:'';
if(empty($ts)) {
    $id = isset($_GET['id'])?$_GET['id']:'zzxwzh';
    $n = array(
        'zzxwzh' => 595660085175275520,//郑州新闻综合
        'zzsd' => 595659997191360512,//郑州商都频道
        'zzwtly' => 595659904266555392,//郑州文体旅游
        'zzyj' => 595659784129105920,//郑州豫剧频道
        'zzfnet' => 595659666227220480,//郑州妇女儿童
        'zzdssh' => 595659527848742913,//郑州都市生活  
    );
    $d = file_get_contents("http://mapi-new.zztv.tv/cloudlive-manage-mapi/api/topic/detail?preview=&id=".$n[$id]
        ."&app_secret=dc302b5bb65d2bb4ad2fa45d282d7763&tenant_id=0&company_id=1015002");
    $live = json_decode($d,1)['topic_camera'][0]['streams'][0]['hls'];
    $burl = "http://live2-new.zztv.tv";
    $php = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/vnd.apple.mpegurl');
    print_r(preg_replace("/(.*?\.ts)/i",$php."?ts=$burl$1",get($live)));
} else {
    $data = get($ts);
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: video/MP2T');
    echo $data;
}

function get($url){
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_REFERER, 'https://h5-new.zztv.tv/');
     $res = curl_exec($ch);
     curl_close($ch);
     return $res;
     }
?>