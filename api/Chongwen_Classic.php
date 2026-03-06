<?php
date_default_timezone_set('Asia/Shanghai');

$cacheFile = __DIR__ . '/cwjdHD.txt';

function getCommonHeaders(): array
{
    $deviceId = md5(time());
    return [
        'charset: UTF-8',
        'channelId: cbn',
        'deviceType: 2048',
        'releaseVersion: 2.0.3',
        'releaseVersionCode: 203',
        'uId: ',
        'os: Android',
        "deviceId: {$deviceId}",
        'API-VERSION: 2',
        'orgCode: ',
        'token: ',
        'isGd: ',
        'User-Agent: okhttp/3.14.9',
        'Accept: */*',
        'Connection: keep-alive',
    ];
}

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 600)) {
    $cachedUrl = trim(file_get_contents($cacheFile));
    header('Location: ' . $cachedUrl);
    exit;
}

function generateSign(string $input, bool $flag): string
{
    $ts = time();
    srand($ts);

    $charset = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $maxIdx = strlen($charset) - 1;

    $randLen = rand(0, 7);
    if ($randLen < 4) {
        $randLen += 4;
    }

    $randStr = '';
    for ($i = 0; $i < $randLen; $i++) {
        $randStr .= $charset[rand(0, $maxIdx)];
    }

    $suffix = $flag
        ? '01234ibcp9'
        : '0123456789';

    $s1 = sprintf('%s-%d-%s-%s', $input, $ts, $randStr, $suffix);
    $hexMd5 = md5($s1);

    return sprintf('%d-%s-%s', $ts, $randStr, $hexMd5);
}

function httpRequest($url, $method, $payload, $headers)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
    ]);

    if (strtoupper($method) === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $headers[] = 'Content-Type: application/json';
    }

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $resp = curl_exec($ch);
    if ($resp === false) {
        throw new \RuntimeException('cURL error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $resp;
}

$firstSign = generateSign('/v1/resourceProductRightsAuth', true);
$resp1 = httpRequest(
    'https://saleservice.5gtv.com.cn/v1/resourceProductRightsAuth',
    'POST',
    ['resId' => '30167', 'resourceStreamId' => '30167'],
    array_merge(
        getCommonHeaders(),
        [
            'sign: ' . $firstSign,
            'Host: saleservice.5gtv.com.cn',
        ]
    )
);
$json1 = json_decode($resp1, true);
$firstUrl = $json1['data']['url'] . '&t=1&v=203';
$parts = parse_url($firstUrl);
$uri = $parts['path'] . (isset($parts['query']) ? '?' . $parts['query'] : '');

$secondSign = generateSign($uri, true);
$resp2 = httpRequest(
    $firstUrl,
    'GET',
    null,
    array_merge(
        getCommonHeaders(),
        [
            'sign: ' . $secondSign,
            'Host: live-dispatcher.5gtv.com.cn',
        ]
    )
);
$json2 = json_decode($resp2, true);

$playUrl = $json2['data']['url'];

file_put_contents($cacheFile, $playUrl);

header('Location: ' . $playUrl);
exit;