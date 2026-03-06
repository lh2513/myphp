<?php
// luoyang.php
// 洛阳广播电视台 - PHP 版
// 从 API 获取 live_address，并 302 跳转到真实播放地址

error_reporting(0);

// 1. 获取频道 ID，默认 1
$id = isset($_GET['id']) ? $_GET['id'] : 1;

// 2. 构造 API 地址
$apiUrl = "https://www.lytv.com.cn/api/broadcast/index?broadcast_id=" . urlencode($id);

// 3. 使用 curl 请求，带 UA 和 Referer
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "User-Agent: Mozilla/5.0 (Linux; Android 10; Mobile) AppleWebKit/537.36 Chrome/119.0 Safari/537.36",
    "Referer: https://www.lytv.com.cn/"
));

$response = curl_exec($ch);
$err      = curl_error($ch);
curl_close($ch);

if ($response === false || $response === '') {
    die(json_encode(array(
        "error" => "获取数据失败",
        "curlErr" => $err,
        "url" => $apiUrl
    ), JSON_UNESCAPED_UNICODE));
}

// 4. 去掉转义反斜杠（与酷9JS 逻辑一致）
$response = stripslashes($response);

// 5. 正则匹配 "live_address":"xxxx"
if (preg_match('/"live_address":"(.*?)"/', $response, $m)) {
    $playUrl = $m[1];
    header("Location: " . $playUrl);
    exit;
}

// 6. 匹配失败，输出一点调试信息
die(json_encode(array(
    "error" => "未找到 live_address",
    "url"   => $apiUrl,
    "bodySample" => mb_substr($response, 0, 200, 'UTF-8')
), JSON_UNESCAPED_UNICODE));
