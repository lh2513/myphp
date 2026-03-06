<?php
$url='https://api.junhao.mil.cn/cmsback/api/micro/live/seat/findPage?articleId=5234612';
$headers=["Tenantid: b81ab1497ae1133dbc41e584912d77aa"];
$ch=curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response=curl_exec($ch);
if(curl_errno($ch)){
    curl_close($ch);
    die();
}
$httpCode=curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if($httpCode<200 || $httpCode>=300) die();
$data=json_decode($response, true);
if(json_last_error()!==JSON_ERROR_NONE) die();
if(isset($data['data']['pageRecords'][0]['livePath'])){
    $livePath=$data['data']['pageRecords'][0]['livePath'];
    header("Location: $livePath");
    exit;
}
die();
?>