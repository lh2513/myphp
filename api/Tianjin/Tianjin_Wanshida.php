<?php
//die(send_request("https://api.wisetv.com.cn:8684/6/tvpage/29"));
//die(send_request("https://api.wisetv.com.cn:8684/v5/fdl/crypt/getCDNList",
//    "type=live&channelId=30001110000000000000000000000698"));
$id = $_GET['id'];
$cdn = isset($_GET['cdn']) ? $_GET['cdn'] : "tx";// ws | tx | bd
$playseek = isset($_GET['playseek']) ? $_GET['playseek'] : "";

$u = "https://api.wisetv.com.cn:8684/v8/fdl/android/getUrl";
$p = "para=".get_para($id, $cdn, $playseek);
$c = send_request($u, $p);
$j = json_decode($c, true);
$l = decrypt_url($j["url"]);
header('Access-Control-Allow-Origin: *');
header('Location: '.$l);



function decrypt_url($url)
{
    $d = strrev($url);
    $d = hex2bin($d);
    $r = call_openssl_func('decrypt', $d);
    return $r;
}

function get_para($id, $cdn, $playseek)
{
    if (!empty($playseek))
        $playseek = str_replace("-", "|", $playseek) . "|";
    $d = "com.whizen.iptv.activity" . "|" . $id . "|" . $playseek . $cdn . "|" . create_uuid();
    $r = call_openssl_func('encrypt', $d);
    $r = bin2hex($r);
    $r = strrev($r);
    return $r;
}

function call_openssl_func($name, $data) {
    $f = 'openssl_'.$name;
    return $f($data, 'AES-256-CBC', get_key(), OPENSSL_RAW_DATA, get_iv());
}

function get_key() {
   static $k = null;
   if ($k === null) {
       $k = md5(get_androidID() . '|qVZRY$le7nGqRz$YNd4Ve!*11IcgEjpb');
   }
   return $k;
}

function get_iv() {
    return "c!$5Jh#19.ZK&24=";
}

function create_uuid() {
    return vsprintf('%04x%04x-%04x-4%03x-%04x-%04x%04x%04x', [
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), // 前8位
        mt_rand(0, 0xffff), // 中间4位
        mt_rand(0, 0x0fff), // 第13位固定为4，这里生成后3位
        mt_rand(0, 0x3fff) | 0x8000, // 第17位固定为8/9/a/b（0x8000确保最高位为1000）
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff) // 最后12位
    ]);
}

function send_request($url, $post_data = null) {
    $ch = curl_init();
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSLCERT => realpath('cert.txt'),
        CURLOPT_SSLKEY => realpath('pkey.txt'),
        CURLOPT_HTTPHEADER => [
            "x-bindcp: ".        "",
            "x-appcode: ".       "473",
            "x-bindarea: ".      "",
            "ak: ".              "HyQuy347BBv9En+0VZ8ToA==",
            "x-rec: ".           "on",
            "x-sysver: ".        "12",
            "x-userid: ".        "",
            "x-phonever: ".      "M2011K2C",
            "x-phoneno: ".       "",
            "authorization: ".   "",
            "x-bindid: ".        "",
            "x-deviceid: ".      get_androidID(),
            "sk: ".              "CQNRW8hsdrKKwHr1ofFgdw==",
            "iemi: ".            get_androidID(),
            "x-platform: ".      "Android",
            "x-devicetoken: ".   "",
            "x-appversion: ".    "7.1.9",
            "user-agent: ".      "okhttp/3.12.12",
//            "accept-encoding: ". "gzip",
        ]
    ];
    if (!empty($post_data)) {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $post_data;
    }
    curl_setopt_array($ch, $options);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

function get_androidID() {
    static $id = null;
    if ($id === null) {
        $id = bin2hex(openssl_random_pseudo_bytes(8));
    }
    return $id;
}