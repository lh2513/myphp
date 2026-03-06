<?php
/*
江西卫视,jiangxi.php?id=87
江西都市,jiangxi.php?id=86
江西经济生活,jiangxi.php?id=153
江西影视旅游,jiangxi.php?id=84
江西公共农业,jiangxi.php?id=83
江西少儿,jiangxi.php?id=82
江西新闻,jiangxi.php?id=81
陶瓷,jiangxi.php?id=78
江西风尚购物,jiangxi.php?id=79

南昌新闻综合,jiangxi.php?id=173
九江新闻综合,jiangxi.php?id=174
景德镇新闻综合,jiangxi.php?id=166
萍乡新闻综合,jiangxi.php?id=165
新余新闻综合,jiangxi.php?id=170
鹰潭综合,jiangxi.php?id=167
赣州新闻综合,jiangxi.php?id=172
宜春综合,jiangxi.php?id=175
上饶新闻综合,jiangxi.php?id=169
吉安综合,jiangxi.php?id=168
抚州综合,jiangxi.php?id=171
*/

getChannelDetailAndPlay($_GET['id']);



function getChannelDetailAndPlay($channelId) {
    $i = getChannelInfo($channelId);
    startPlay($i);
}

function startPlay($channelInfo) {
    if ($channelInfo->authType == 0)
        $u = $channelInfo->playUrl;
    else {
        $i = getAuthInfo();
        $t = intval(microtime(true) * 1000);
        $c = getWatchTvAuthentication($channelInfo->authUrl, $t, $i);
        //tryReloadAuth($c);
        $p = parseParams($c, $i);
        $u = $channelInfo->playUrl . $p;
    }
    header('Access-Control-Allow-Origin: *');
    header('Location: '.$u);
}

function parseParams($authResponse, $authInfo) {
    $j = json_decode($authResponse, true);
    $d = $j['data'];
    unset($j['data']);
    $split = explode('-', $authInfo);
    $j2 = json_decode(LiveAuthEncryptUtils_AESCBCDecode($d, $split[0], $split[1]), true);
    $a = array_merge($j, $j2);
    $p = http_build_query($a);
    return '?'.$p;
}

function LiveAuthEncryptUtils_AESCBCDecode($data, $key, $iv) {
    $r = openssl_decrypt($data, 'AES-128-CBC', $key, 0, $iv);
    return $r;
}

function getWatchTvAuthentication($authUrl, $millisecondTimestamp, $authInfo) {
    $d = DeviceId_get();
    $t = intval($millisecondTimestamp / 1000);
    $token = md5('com.sobey.cloud.view.jiangxiandroidjxntv'.$millisecondTimestamp);
    $p = "app_version=5.09.18&device_id=$d&siteid=10001&t=$t&time=$millisecondTimestamp&token=$token&type=android";
    $sb = $p.'&'
        .XorUtils_xor(chr(4).chr(21).chr(21).chr(14).chr(0).chr(28).chr(88), 101)
        .explode('-', $authInfo)[2];
    $sb .= '&timestamp='.$t;
    $p .= '&sign='.md5($sb);
    $u = $authUrl.'?'.$p;
    $defaultUserAgent = 'Mozilla/5.0 (Linux; Android 12; SM-A5560 Build/V417IR; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/101.0.4951.61 Mobile Safari/537.36';
    $h = ["User-Agent: $defaultUserAgent/tvcVersion5.09.18"];
    $c = sendRequest($u, $h);
    return $c;
}

function getAuthInfo() {
    function getInfo() {
        $u = 'https://app.jxgdw.com/api/media/report?device='.DeviceId_get();
        $c = sendRequest($u);
        $j = json_decode($c);
        $r = $j->result;
        return XorUtils_xor(base64_decode($r), 110);
    }
    return getInfo();
}

function XorUtils_xor($bArr, $i) {
    $c = chr($i & 0xFF);
    $result = '';
    for ($j = 0; $j < strlen($bArr); $j++) {
        $result .= $bArr[$j] ^ $c;
    }
    return $result;
}

function getChannelInfo($channelId) {
    $u = 'https://app.jxgdw.com/api/tv/channel/'.$channelId;
    $c = sendRequest($u);
    $j = json_decode($c);
    $r = new stdClass();
    $r->authType = $j->result->authType;
    $r->authUrl = $j->result->authUrl;
    $r->playUrl = $j->result->playUrl;
    return $r;
}

function sendRequest($url, $httpHeader = null) {
    $ch = curl_init();
    if (!isset($httpHeader)) {
        $httpHeader= [
            'appVersion: 5.09.18',
            'channelType: jinshipin',
            'os: Android',
            'device: '.DeviceId_get(),
            'User-Agent: okhttp/4.9.2',
        ];        
    }
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => $httpHeader,
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function DeviceId_get() {
	//不能播的时候,兄弟们把DeviceId_get函数里的值替换成自己抓的值试试
	return 'EDAF095E9E20D0F909CEC043C68B68629C9E9435'; //朕的DeviceId
    //return '8BCF3F234F564831E01D5E0F4FBF278014A6F567';
    if (isset($GLOBALS['DeviceId']))
        return $GLOBALS['DeviceId'];
    $DeviceId = sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
    $GLOBALS['DeviceId'] = $DeviceId;
    return $DeviceId;
}