<?php

error_reporting(E_ALL);

// 获取$id参数
$id = isset($_GET['id']) ? $_GET['id'] : 'sdws';

$n = [
    'sdws' => [1, ''], // 山东卫视
    'xwpd' => [3, ''], // 新闻频道
    'qlpd' => [5, ''], // 齐鲁频道
    'txyx' => [7, ''], // 体育休闲频道
    'shpd' => [9, ''], // 生活频道
    'zypd' => [11, ''], // 综艺频道
    'wlpd' => [13, ''], // 文旅频道
    'nkpd' => [15, ''], // 农科频道
    'sepd' => [17, ''], // 少儿频道
    'jndst' => [29883, '/region'], // 济南电视台
    'zbdst' => [100010, '/region'], // 淄博电视台
    'zzdst' => [100023, '/region'], // 枣庄电视台
    'dydst' => [100011, '/region'], // 东营电视台
    'ytdst' => [100012, '/region'], // 烟台电视台
    'wfdst' => [100013, '/region'], // 潍坊电视台
    'tadst' => [100015, '/region'], // 泰安电视台
    'whdst' => [100016, '/region'], // 威海电视台
    'rzdst' => [100017, '/region'], // 日照电视台
    'lydst' => [100019, '/region'], // 临沂电视台
    'dzdst' => [100020, '/region'], // 德州电视台
    'lcdst' => [100021, '/region'], // 聊城电视台
    'bzdst' => [100022, '/region'], // 滨州电视台
    'hzdst' => [100024, '/region'], // 菏泽电视台
];

$timestamp = time();
$sign = md5('huangye' . $timestamp . '211f68ea4aeb687a6561707b6e3523c84e');

$url = "https://sdxw.iqilu.com/v1/app/play/tv{$n[$id][1]}/live?e=1&e=1";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => [
        'version: 10.1.1',
        'orgid: 21',
        'platform: android' . $timestamp,
        'imei: 7f918d21082ed7eb',
        'CQ-AGENT: {os:android,imei:7f918d21082ed7eb,osversion:7.1.1,network:wifi,device_model:OPPO R9s,version:10.1.1,brand:oppo,core:2.0.0}',
        'timestamp: ',
        'noncestr: huangye',
        'sign: ' . $sign,
        'User-Agent: chuangqi.o.21.com.iqilu.ksd/10.1.1',
        'Host: sdxw.iqilu.com',
        'Connection: Keep-Alive',
//        'Accept-Encoding: gzip',
    ],
]);

$response = curl_exec($ch);
curl_close($ch);

//$decodedData = gzdecode($response);
$decodedData = $response;
$decrypted_text = decryptAesCbc($decodedData);
$data = json_decode($decrypted_text, true);

if (empty($id)) {
    echo "请输入id值";
} else {
    foreach ($data['data'] as $channel) {
        if ($channel['id'] == $n[$id][0]) {
            header("Location: " . $channel['stream']);
            exit;
        }
    }
}

function decryptAesCbc($encryptedData) {
    $key = hex2bin("6262393735383763666138356563653535343961336432353766373931396633");
    $iv = hex2bin("30303030303030303030303030303030");
    return openssl_decrypt(base64_decode($encryptedData), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
}
?>