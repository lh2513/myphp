<?php
//error_reporting(0);
$id = isset($_GET['id']) ? $_GET['id'] : 'dfws';
$n = [
    'dfws' => 1, //东方卫视4K
    'shxwzh' => 2, //上海新闻综合
    'shds' => 4, //上海都市
    'dycj' => 5, //第1财 经
    'hhxd' => 9, //哈哈炫动
    'wxty' => 10, //五星体育
    'mdy' => 11, //上海魔都眼
    'jsrw' => 12, //上海新纪实(原纪实人文)
];
$t = time();
$nonce = getnonce(8);
$sign = md5(md5("Api-Version=v1&channel_id={$n[$id]}&nonce={$nonce}&platform=android&timestamp={$t}&version=7.1.14&28c8edde3d61a0411511d3b1866f0636"));
$h = [
    "api-version: v1",
    "nonce:".$nonce,
    "m-uuid: 2317c7cbca1543851bbeff55aed1d77b2",
    "platform:android",
    "version:7.1.14",
    "timestamp:".$t,
    "sign:".$sign,
];
$url = "https://kapi.kankanews.com/content/app/tv/channel/detail?channel_id=".$n[$id];
$encrypted = json_decode(get($url,$h),1)['result']['touping_address'];
$live = decrypt($encrypted);
header('Access-Control-Allow-Origin: *');
header('Location: '.$live);



function get($url,$header){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_REFERER, 'https://live.kankanews.com/');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $d = curl_exec($ch);
    curl_close($ch);
    return $d;
}

function getnonce($length) {
    $base36 = @base_convert(mt_rand()/mt_getrandmax(), 10, 36);
    return substr($base36, -$length);
}

function decrypt($str){
    $public_key =
        "-----BEGIN PUBLIC KEY-----\n".
        "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDP5hzPUW5RFeE2xBT1ERB3hHZI\n".
        "Votn/qatWhgc1eZof09qKjElFN6Nma461ZAwGpX4aezKP8Adh4WJj4u2O54xCXDt\n".
        "wzKRqZO2oNZkuNmF2Va8kLgiEQAAcxYc8JgTN+uQQNpsep4n/o1sArTJooZIF17E\n".
        "tSqSgXDcJ7yDj5rc7wIDAQAB\n".
        "-----END PUBLIC KEY-----";
    $pu_key = openssl_pkey_get_public($public_key);
    $key_len = openssl_pkey_get_details($pu_key)['bits'];
    $decrypted = "";
    $part_len = $key_len / 8;
    $parts = str_split(base64_decode($str), $part_len);
    foreach ($parts as $part) {
        $decrypted_temp = '';
        openssl_public_decrypt($part, $decrypted_temp, $pu_key);
        $decrypted .= $decrypted_temp;
    }
    if (PHP_VERSION_ID < 80000)
        openssl_pkey_free($pu_key);
    return $decrypted;
}