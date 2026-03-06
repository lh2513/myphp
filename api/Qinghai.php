<?php
error_reporting(0);
$id = $_GET['id'];
$n = [
    "qhws" => ["786181204964564992", "32a2c3b4f1b52c58119457d44acdcd49", 1075], //青海卫视
    "qhjs" => ["786227316454875136", "32a2c3b4f1b52c58119457d44acdcd49", 1075], //青海经视
    "qhds" => ["786227009616371712", "32a2c3b4f1b52c58119457d44acdcd49", 1075], //青海都市
    "adws" => ["824587377543962624", "069486993db4acc22c846557c8880d9a", 1077], //安多卫视
];

$apiUrl = "https://mapi.qhbtv.com.cn/cloudlive-manage-mapi/api/topic/detail?preview=&id=".$n[$id][0]."&app_secret=".$n[$id][1]."&tenant_id=0&company_id=".$n[$id][2]."&lang_type=zh";

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