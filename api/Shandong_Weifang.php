<?php
error_reporting(0);

//http://127.0.0.1/test.php?id=1,5,7,9
$apiUrl = 'https://app.litenews.cn/v1/app/play/tv/live?_orgid_=635';
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$jsonData = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    exit;
}
curl_close($ch);
$data = json_decode($jsonData, true);
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$redirectUrl = '';
if (isset($data['data'])) {
    foreach ($data['data'] as $channel) {
        if ($channel['id'] === $id) {
            $redirectUrl = $channel['stream'];
            break;
        }
    }
}
if ($redirectUrl) {
    header("Location: $redirectUrl");
    exit;
}
?>