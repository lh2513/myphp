<?php
$id = $_GET['id'];
$tencentLive = getTencentLive();
$txTime = time() + 86400;
$txSecret = md5($tencentLive.$id.$txTime);
$url = "https://litchi-play-encrypted.jstv.com/applive/$id.m3u8?txSecret=$txSecret&txTime=$txTime";
header('Access-Control-Allow-Origin: *');
header('Location: '.$url);



function getTencentLive() {
    $auth = getAuth();
    $u = transformURL('https://publish-lizhi.jstv.com/appSetting?appType=2');
    $c = sendRequest($u, $auth);
    $j = json_decode($c);
    $s = $j->data->appSelfSetting;
    $data = '';
    foreach ($s as $i) {
        if ($i->key == 'TencentLive') {
            $data = $i->value;
            break;
        }
    }
    $key = parseKeyOrIv($j->data->aesKey);
    $iv = parseKeyOrIv($j->data->aesIV);
    $r = openssl_decrypt($data, 'aes-128-cbc', $key, 0, $iv);
    return $r;
}

function getAuth() {
    $uuid = generateUuidV4();
    $d['platform'] = 5;
    $d['credentials'] = $uuid;
    //$d['password'] = '';
    $d['uuid'] = $uuid;
    $d['appId'] = getAppID();
    $f = 'appId'.$d['appId'].'credentials'.$d['credentials'].'platform'.$d['platform'].'uuid'.$d['uuid'];
    $u = transformURL('https://api-auth-lizhi.jstv.com/JwtAuth/Token', $f);
    $c = sendRequest($u, '', json_encode($d));
    $j = json_decode($c);
    $r = $j->data->accessToken;
    return $r;
}

function generateUuidV4() { 
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x', 
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), 
        mt_rand( 0, 0xffff ), 
        mt_rand( 0, 0x0fff ) | 0x4000, 
        mt_rand( 0, 0x3fff ) | 0x8000, 
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) 
    ); 
}

function sendRequest($url, $auth = '', $postData = null) {
    $ch = curl_init();
    $v = '8.40';
    $ua = 'Mozilla/5.0 (Linux; Android 12; SM-A5560 Build/V417IR; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/101.0.4951.61 Mobile Safari/537.36';
    $ua .= ' jsbcApp+convergedmedia+'.'com.jsbc.lznews'.'+'.$v;
    $h = [
        'Authorization: Bearer '.$auth,
        'Accept-Language: ',
        'client: android',
        'App-Version: '.$v,
        'ua: '.$ua,
        'User-Agent: okhttp/3.12.13',
    ];
    if (isset($postData))
        $h[] = 'Content-Type: application/json;charset=utf-8';
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => $h,
    ];
    if (isset($postData)) {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $postData;
    }
    curl_setopt_array($ch, $options);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

function parseKeyOrIv($arr) {
    $r = '';
    for ($i = 1; $i < count($arr); $i += 2) {
        $r = $arr[$i].$r;
    }
    return $r;
}

function transformURL($url, $flatPostData = '') {
    if (strpos($url, '?') === false)
        $s = '?';
    else
        $s = '&';
    $AppID = getAppID();
    $url .= $s."AppID=$AppID";
    $t = time();

    $sb = '9dd4b0400f6e4d558f2b3497d734c2b4';
    $parsed = parse_url($url);
    $sb .= $parsed['path'].'?'.$parsed['query'];
    $sb .= $flatPostData;
    $sb .= $t;
    $Sign = md5($sb);

    $intToBytes = intToBytes($t);
    $bArr = array_fill(0, 4, 0); // 初始化4字节数组
    for ($i2 = 0; $i2 < 4; $i2++) {
        $index = (4 - $i2) - 1; // 反转字节顺序
        $byte = $intToBytes[$i2];
        $bArr[$index] = ((($byte & 0xF0) ^ 0xF0) | ((($byte & 0x0F) + 1) & 0x0F)) & 0xFF;
    }
    $TT = bytesToInt($bArr);

    $r = $url."&Sign=$Sign&TT=$TT";
    return $r;
}

function getAppID() {
    if (!isset($GLOBALS['appID']))
        $GLOBALS['appID'] = '3b93c452b851431c8b3a076789ab1e14';
    return $GLOBALS['appID'];
}

// 将整数转为小端序字节数组（4字节）
function intToBytes($j) {
    return [
        $j & 0xFF,
        ($j >> 8) & 0xFF,
        ($j >> 16) & 0xFF,
        ($j >> 24) & 0xFF
    ];
}

// 将字节数组转为整数（小端序）
function bytesToInt($bArr, $i = 0) {
    $value =
        (($bArr[$i + 3] & 0xFF) << 24) | 
        ($bArr[$i] & 0xFF) | 
        (($bArr[$i + 1] & 0xFF) << 8) | 
        (($bArr[$i + 2] & 0xFF) << 16);
    // 符号扩展：处理最高位为1的情况
    if ($value >= 0x80000000) {
        $value -= 0x100000000;
    }
    return $value;
}