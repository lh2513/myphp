<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'nmws';
$t = isset($_GET['t'])?$_GET['t']:'hls';//m3u8 flv
$n = [
     //内蒙古
     'nmws' => 262, //内蒙古卫视
     'nmmyws' => 126, //内蒙古蒙古语卫视
     'nmxwzh' => 127, //内蒙古新闻综合
     'nmjjsh' => 128, //内蒙古经济生活
     'nmse' => 129, //内蒙古少儿频道
     'nmwtyl' => 130, //内蒙古文体娱乐
     'nmnm' => 131, //内蒙古农牧频道
     'nmwh' => 132, //内蒙古蒙古语文化
     //地市
     'hhht1' => 141, //呼和浩特新闻综合
     'xlgl1' => 156, //锡林郭勒
     'als1' => 157, //阿拉善新闻综合
     'byle1' => 158, //巴彦淖尔
     'erds1' => 159, //鄂尔多斯
     'cf1' => 161, //赤峰新闻综合
     'tl1' => 163, //通辽新闻综合
     'wlcb1' => 164, //乌兰察布
     'wh1' => 165, //乌海新闻综合
     'hlbe1' => 166, //呼伦贝尔新闻综合
     'xa1' => 167, //兴安新闻综合
     'bt1' => 168, //包头新闻综合
     ];

$ch = curl_init('https://api-bt.nmtv.cn/broadcast/list?size=100&type=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$res = curl_exec($ch);
curl_close($ch);
$res = str_replace('"','',$res);
$str = xxtea_decrypt(base64_decode($res), "5b28bae827e651b3");
$json = json_decode($str, 1);
foreach ($json['data'] as $v) {
        if ($v['data']['id'] == $n[$id]) {
           $m3u8 = $v['data']['streamUrls'][0];
           $flv = $v['data']['streamUrls'][2];
           }
        }
if($t=='hls'||$t==''){
    header("Location: $m3u8");
    //echo $m3u8;
    }
if($t=='flv'){
    header("Location: $flv");
    //echo $flv;
    }
function xxtea_decrypt($str, $key) {
        if ($str == "") return "";
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
        if ($w) $v[count($v)] = strlen($s);
        return $v;
}
function long2str($v, $w) {
        $len = count($v);
        $n = ($len - 1) << 2;
        if ($w) {
                $m = $v[$len - 1];
                if (($m < $n - 3) || ($m > $n))
                          return false;
                $n = $m;
        }
        $s = array();
        for ($i = 0; $i < $len; $i++) {
                $s[$i] = pack("V", $v[$i]);
        }
        if ($w) {
                return substr(join('', $s), 0, $n);
        } else {
                return join('', $s);
        }
}
?>