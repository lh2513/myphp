<?php

$ts = isset($_GET['ts']) ? $_GET['ts'] : '';
if (empty($ts)) {

// IPTV 频道列表
    $tv_list = [
        'szws4k' => ['R77mK1v', '24725', '深圳卫视4K'],
        'szws' => ['AxeFRth', '7867', '深圳卫视'],
        'szds' => ['ZwxzUXr', '7868', '都市频道'],
        'szdsj' => ['4azbkoY', '7880', '电视剧频道'],
//        'szgg' => ['2q76Sw2', '7874', '公共频道'],
        'szcj' => ['3vlcoxP', '7871', '财经频道'],
        'szse' => ['1SIQj6s', '7881', '少儿频道'],
        'szyd' => ['wDF6KJ3', '7869', '移动电视'],
        'szyh' => ['BJ5u5k2', '7878', '宜和购物频道'],
        'szgj' => ['sztvgjpd', '7944', '国际频道'],
    ];
// 处理 IPTV 请求
    $id = isset($_GET['id']) ? $_GET['id'] : 'szds';
    if (isset($tv_list[$id])) {
        header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/vnd.apple.mpegurl');
        echo get_m3u8_content($tv_list[$id][0], $tv_list[$id][1]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['code' => 400, 'message' => 'Invalid request']);
    }
} else {
    $data = curl_get($ts);
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: video/MP2T');
    echo $data;
}

// 通用 curl GET 请求
function curl_get($url, $params = null) {
    if ($params) $url .= '?' . http_build_query($params);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_REFERER,'https://www.sztv.com.cn/');
//    curl_setopt($ch, CURLOPT_REFERER,'https://www.sztv.com.cn/pindao/index.html?id=7868');
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.6261.95 Safari/537.36');
    $data = curl_exec($ch);

    curl_close($ch);
    return $data;
}

// 获取密钥
function get_key($url, $params) {
    return json_decode(curl_get($url, $params), true);
}

// Base64 解码逻辑
function ba($a) {
    return base64_decode(implode('', array_reverse(str_split(substr($a, strlen($a) / 2) . substr($a, 0, strlen($a) / 2)))));
}

// 获取 m3u8 文件内容
function get_m3u8_content($live_id, $cdn_id) {
    $live_key = ba(get_key('https://hls-api.sztv.com.cn/getCutvHlsLiveKey', [
        't' => time(),
        'id' => $live_id,
        'token' => md5(time() . $live_id . 'cutvLiveStream|Dream2017'),
        'at' => '1'
    ]));
//    echo '$live_key: '.$live_key;
//    exit;
   
    $cdn_key = get_key('https://sttv2-api.sztv.com.cn/api/getCDNkey.php', [
        'domain' => 'sztv-live.sztv.com.cn',
        'page' => 'https://www.sztv.com.cn/pindao/index.html?id=' . $cdn_id,
        'token' => md5('iYKkRHlmUanQGaNMIJziWOkNsztv-live.sztv.com.cn' . time() * 1000),
        't' => time() * 1000
    ])['key'];
//    echo 'ck: '.$cdn_key;
//    echo 'shit.';
//    print_r($cdn_key);
//    exit;
    if (!$cdn_key)
        $cdn_key = 'ejow6p6p6hmrm9g96beh2knecdq5kyv9bp0zxyg7';

    $t = dechex(time());
    $sign = md5("$cdn_key/$live_id/500/$live_key.m3u8$t");
    $url = "https://sztv-live.sztv.com.cn/$live_id/500/$live_key.m3u8?sign=$sign&t=$t";

//    return preg_replace_callback('/(\d{13})(\/\d+\.ts)/', function ($matches) use ($live_id) {
//        return "https://sztv-live.sztv.com.cn/$live_id/500/{$matches[1]}{$matches[2]}";
//    }, curl_get($url));

    $php = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $burl = "https://sztv-live.sztv.com.cn/$live_id/500/";
    $left = "$php?ts=$burl";
    $data = curl_get($url);
    $data = preg_replace("/(.+\.ts)/i", $left."$1", $data);
    return $data;
}



/*
深圳卫视4K,sz.php?id=szws4k
深圳卫视,sz.php?id=szws
深圳都市频道,sz.php?id=szds
深圳电视剧频道,sz.php?id=szdsj
深圳财经频道,sz.php?id=szcj
深圳少儿频道,sz.php?id=szse
深圳移动电视,sz.php?id=szyd
深圳宜和购物频道,sz.php?id=szyh
深圳国际频道,sz.php?id=szgj
*/