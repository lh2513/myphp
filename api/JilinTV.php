<?php
/*by qqincai 20240628
吉林卫视,http://127.0.0.1/jltv.php?id=jlws
都市频道,http://127.0.0.1/jltv.php?id=jlds
生活频道,http://127.0.0.1/jltv.php?id=jlsh
影视频道,http://127.0.0.1/jltv.php?id=jlys
乡村频道,http://127.0.0.1/jltv.php?id=jlxc
公共频道,http://127.0.0.1/jltv.php?id=jlgg
综艺频道,http://127.0.0.1/jltv.php?id=jlzy
戏曲频道,http://127.0.0.1/jltv.php?id=dbxq
*/
error_reporting(0);
jltv();

function jltv() {
$channel = [
    'jlws' => 1531, // 吉林卫视 http://hls.avap.jilintv.cn/zqvk7vpj/channel/0533b55e42354f4f802bdec78e26b571/1.m3u8
    'jlds' => 1532, // 都市频道 http://hls.avap.jilintv.cn/zqvk7vpj/channel/7e8474e6daea44ccaa5aa2300191439e/1.m3u8
    'jlsh' => 1534, //  生活频道 http://hls.avap.jilintv.cn/zqvk7vpj/channel/0a76740c72b74fabae611845aa21e06a/1.m3u8
    'jlys' => 1535, // 影视频道 http://hls.avap.jilintv.cn/zqvk7vpj/channel/906341e6f19b4c4bacdc89941eb85d12/1.m3u8
    'jlxc' => 1536, // 乡村频道 http://hls.avap.jilintv.cn/zqvk7vpj/channel/3ffc4824dce54b92be185555923ce382/1.m3u8
    'jlgg' => 1537, // 公共·新闻频道 http://hls.avap.jilintv.cn/zqvk7vpj/channel/44dcb20ec810463cb517f1814c1b3ce9/1.m3u8
    'jlzy' => 1538, // 吉视综艺文化频道 http://hls.avap.jilintv.cn/zqvk7vpj/channel/b3925adaf01e41f287f482f0aea3d233/1.m3u8
	'dbxq' => 1539, // 东北戏曲频道 http://hls.avap.jilintv.cn/zqvk7vpj/channel/1ceb537a60664ac49649b639e823678c/1.m3u8
];

$id = 'jlws'; 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$parms = json_encode(['page' => 1,'size' => 1000, 'type' => 1]);
$key = "5b28bae827e651b3";
$encrydata = base64_encode(xxtea_encrypt($parms, $key));

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
$json = $obj['data'] ?? [];
$cid = $channel[$id];
foreach ($json as $Index => $item) {
	
    if (isset($item['id']) && $item['id'] == $cid) {
        $m3u8 = $item['data']['streamUrl'];
        header("Location: $m3u8");exit;
    }
}
}

function xxtea_encrypt($str, $key) {
    if ($str == "") {
        return "";
    }
    $v = str2long($str, true);
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
    $sum = 0;
    while (0 < $q--) {
        $sum = int32($sum + $delta);
        $e = $sum >> 2 & 3;
        for ($p = 0; $p < $n; $p++) {
            $y = $v[$p + 1];
            $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
            $z = $v[$p] = int32($v[$p] + $mx);
        }
        $y = $v[0];
        $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
        $z = $v[$n] = int32($v[$n] + $mx);
    }
    return long2str($v, false);
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

?>