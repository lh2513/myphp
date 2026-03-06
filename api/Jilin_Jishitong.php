<?php
//改自：qqincai

/*
吉林卫视,jst.php?id=1531
延边卫视,jst.php?id=812244
吉林都市,jst.php?id=1532
吉林生活,jst.php?id=1534
吉林影视,jst.php?id=1535
吉林乡村,jst.php?id=1536
吉林综艺文化,jst.php?id=1538
长春综合,jst.php?id=812402
吉林市新闻综合,jst.php?id=812373
四平新闻综合,jst.php?id=812374
辽源新闻综合,jst.php?id=812376
通化新闻综合,jst.php?id=812379
白山新闻综合,jst.php?id=812383
白城新闻综合,jst.php?id=812380
松原新闻综合,jst.php?id=812382
*/

jltv($_GET['id']);

function jltv($id) {
    $key = "5b28bae827e651b3";
    $url = 'https://clientapi.jlntv.cn/broadcast/list?page=1&size=10000&type=1';
    $User_Agent='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36';
    $Referer_Url="https://www.jlntv.cn/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch,CURLOPT_USERAGENT,$User_Agent);
    curl_setopt($ch,CURLOPT_REFERER,$Referer_Url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Client-Type: web',
        'Connection: keep-alive',
        'DNT: 1',
        'Host: clientapi.jlntv.cn',
        'Origin: https://www.jlntv.cn'
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $response=str_replace('"','',$response);
    $shuju = xxtea_decrypt(base64_decode($response), $key);
    $obj = json_decode($shuju, true);
    $json = isset($obj['data']) ? $obj['data'] : [];
    foreach ($json as $Index => $item) {
        if (isset($item['id']) && $item['id'] == $id) {
            $m3u8 = $item['data']['streamUrl'];
            header("Access-Control-Allow-Origin: *");
            header("Location: $m3u8");
            exit;
        }
    }
}

function xxtea_decrypt($str, $key) {
    if ($str == "") {
        return "";
    }
    $v = str2long($str, false);
    $k = str2long($key, false);
    if (count($k) < 4) {
        for ($i = count($k); $i < 4; $i++) {
            $k[$i] = 0;
        }
    }
    $n = count($v) - 1;

    $z = $v[$n];
    $y = $v[0];
    $delta = 0x9E3779B9;
    $q = floor(6 + 52 / ($n + 1));
    $sum = int32($q * $delta);
    while ($sum != 0) {
        $e = $sum >> 2 & 3;
        for ($p = $n; $p > 0; $p--) {
            $z = $v[$p - 1];
            $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
            $y = $v[$p] = int32($v[$p] - $mx);
        }
        $z = $v[$n];
        $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
        $y = $v[0] = int32($v[0] - $mx);
        $sum = int32($sum - $delta);
    }
    return long2str($v, true);
}

function int32($n) {
    while ($n >= 2147483648) $n -= 4294967296;
    while ($n <= -2147483649) $n += 4294967296;
    return (int)$n;
}

function str2long($s, $w) {
    $v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
    $v = array_values($v);
    if ($w) {
        $v[count($v)] = strlen($s);
    }
    return $v;
}

function long2str($v, $w) {
    $len = count($v);
    $n = ($len - 1) << 2;
    if ($w) {
        $m = $v[$len - 1];
        if (($m < $n - 3) || ($m > $n)) return false;
        $n = $m;
    }
    $s = array();
    for ($i = 0; $i < $len; $i++) {
        $s[$i] = pack("V", $v[$i]);
    }
    if ($w) {
        return substr(join('', $s), 0, $n);
    }
    else {
        return join('', $s);
    }
}