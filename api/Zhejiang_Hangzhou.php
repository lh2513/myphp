<?php
//代理了切片，不支持国外服务器
//error_reporting(0);
$ts = isset($_GET['ts']) ? $_GET['ts'] : '';
if (empty($ts)) {
    $id = isset($_GET['id']) ? $_GET['id'] : 'hzzh';
    $n = [
        'hzzh' => 16, //杭州综合
        'hzmz' => 17, //西湖明珠
        'hzsh' => 18, //杭州生活
        'hzys' => 21, //杭州影视
        'hzqsty' => 20, //青少体育
        'hzds' => 22, //杭州导视
        'fyxwzh' => 32, //富阳新闻综合
    ];
    $url = 'https://mapi.hoolo.tv/api/v1/channel_detail.php?channel_id='.$n[$id];
    if ($id == 'fyxwzh')
        $i = 0;
    else
        $i = 1;
    $live = json_decode(get($url),1)[0]['channel_stream'][$i]['m3u8'];
    $php = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $host = parse_url($live)['host'];
    $burl = "https://{$host}";
    $data = get($live);
    $data = preg_replace("/(.*?\.ts)/i", $php."?ts=$burl$1", $data);
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/vnd.apple.mpegurl');
    echo $data;
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
    curl_setopt($ch, CURLOPT_REFERER, 'https://tv.hoolo.tv/');
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}
?>