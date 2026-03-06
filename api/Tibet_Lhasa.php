<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $u = 'https://www.lasatv.cn/cms/home/content?id='.$id;
    $c = get($u, false);
    $j = json_decode($c);
    $m3u8 = $j->exdata->lives[0]->m3u8_url;
    $l = '?m3u8='.rawurlencode($m3u8);
    header('Access-Control-Allow-Origin: *');
    header('Location: '.$l);
    die;
}

if (isset($_GET['m3u8'])) {
    $m3u8 = $_GET['m3u8'];
    $c = get($m3u8, true);
    $p = dirname($m3u8) . '/';
//    $r = $_SERVER['PHP_SELF'] . '?ts=';
    $r = basename(__FILE__) . '?ts=';
    $c = preg_replace_callback('/^(?!#)/m', function($m) use($p, $r) {
        return $r.rawurlencode($p.$m[0]);
    }, $c);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/vnd.apple.mpegurl');
    echo $c;
    die;
}

if (isset($_GET['ts'])) {
    $ts = $_GET['ts'];
    $c = get($ts, true);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: video/MP2T');
    echo $c;
    die;
}



function get($url, $need_referrer){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    if ($need_referrer)
        curl_setopt($ch, CURLOPT_REFERER, 'https://www.lasatv.cn/');
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}