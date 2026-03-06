<?php

$vid = $_GET["id"];

$map = [
    "kmxwzh" => 1,
    "kmccms" => 5,
    "kmjjsh" => 2
];

if (!isset($map[$vid])) {
    die("无效频道ID");
}

$t = time();
$n = substr(md5($t), 0, 20);

$appid = "0d9dceb318565bdc";
$key   = "aede663c0d9dceb318565bdca6451456";
$e = md5($appid . $key . $t . $n);

$url = "https://zsccv9-cache.kmzscc.com/page/get_page?obj_id=5004&open_type=1";

$headers = [
    "appid: $appid",
    "timestamp: $t",
    "noncestr: $n",
    "version: 8.2.0",
    "encrypt: $e",
    "User-Agent: Mozilla/5.0"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$res = curl_exec($ch);
curl_close($ch);

$j = json_decode($res, true);

foreach ($j["data"]["recommend_list"][1]["item_list"] as $item) {
    if ($item["item_id"] == $map[$vid]) {
        header("Location: " . $item["resource"][0]["url"]);
        exit;
    }
}

echo "未找到频道流";
