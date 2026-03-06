<?php
error_reporting(0);
$id = $_GET['id'];
$n = [
    "qhws" => ["786181204964564992"], //青海卫视
    "qhjs" => ["786227316454875136"], //青海经视
    "qhds" => ["786227009616371712"], //青海都市
];

$apiUrl = "https://mapi.qhbtv.com.cn/cloudlive-manage-mapi/api/topic/detail?preview=&id=".$n[$id][0]."&app_secret=32a2c3b4f1b52c58119457d44acdcd49&tenant_id=0&company_id=1075&lang_type=zh";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_REFERER, 'https://mapi.qhbtv.com.cn/');
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$playUrl = $data['topic_camera'][0]['streams'][0]['hls'];
header('Location: '.$playUrl);
?>
