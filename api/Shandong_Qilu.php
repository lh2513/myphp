<?php
//https://v.iqilu.com/
error_reporting(0);
$n = [
     'sdws' => "24581", //山东卫视
     'sdql' => "24584", //山东齐鲁
     'sdxw' => "24602", //山东新闻
     'sdty' => "24587", //山东体育休闲
     'sdsh' => "24596", //山东生活
     'sdzy' => "24593", //山东综艺
     'sdnk' => "24599", //山东农科
     'sdwl' => "24590", //山东文旅
     'sdse' => "24605", //山东少儿
     ];
$id = isset($_GET['id'])?$_GET['id']:'sdws';
$salt = getsalt();
$t = getMillisecond();
$s = md5($n[$id].$t.$salt);
$uri = "https://feiying.litenews.cn/api/v1/auth/exchange?t=$t&s=$s";
$data = base64_encode(aesencrypt('{"channelMark":"'.$n[$id].'"}'));
$str = post($uri,$data);
$live = json_decode(aesdecrypt($str),1)['data'];
header('Access-Control-Allow-Origin: *');
header('location:'.$live);
//echo $live;

function getsalt() {
        $d = get("https://v.iqilu.com/live/sdtv/");
        preg_match("/mxpx = '(.*?)'/",$d,$s);
        $salt = $s[1];
        return $salt;
        }

function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
        }
function post($url,$data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_REFERER, 'https://v.iqilu.com/');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/plain','Origin: https://v.iqilu.com','User-Agent: Mozilla/5.0 (Windows NT 6.1)']);
        $str = curl_exec($ch);
        curl_close($ch);
        return $str;
        }
function getkey() {
        $d = get("https://v.iqilu.com/live/sdtv/");
        preg_match("/aly = '(.*?)'/",$d,$k);
        $key = $k[1];
        return $key;
        }
function aesencrypt($str) {
//        $d = get("https://v.iqilu.com/live/sdtv/");
        $cipher = "AES-128-CBC";
        $key = getkey();
        $iv = "0000000000000000";
        $encryptedText = openssl_encrypt($str, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        return $encryptedText;
}
function aesdecrypt($str) {
        $cipher = "AES-128-CBC";
        $key = getkey();
        $iv = "0000000000000000";
        $decryptedText = openssl_decrypt(base64_decode($str), $cipher, $key, OPENSSL_RAW_DATA, $iv);
        return $decryptedText;
        }
function get($url) {
        if (isset($GLOBALS[$url]))
            return $GLOBALS[$url];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_REFERER, 'https://v.iqilu.com/');
        $res = curl_exec($ch);
        curl_close($ch);
        $GLOBALS[$url] = $res;
        return $res;
        }
?>