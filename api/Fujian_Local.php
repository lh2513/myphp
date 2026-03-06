<?php
/*
漳州新闻综合,http://xxx/xxx.php?id=727214415649083392
龙岩综合,http://xxx/xxx.php?id=727212352215093248
三明综合,http://xxx/xxx.php?id=727216678547394560
南平综合,http://xxx/xxx.php?id=727216450918322176
来源http://kaniptv.com
*/
$id = $_GET['id'] ?? "727216450918322176";
$api = "https://mapi-plus.fjtv.net/cloudlive-manage-mapi/api/topic/detail?preview=&id={$id}&tenant_id=0&company_id=468&lang_type=zh";
$data = json_decode(file_get_contents($api), true);
if (isset($data['topic_camera'][0]['streams'][0]['hls'])) {
    header("Location: " . $data['topic_camera'][0]['streams'][0]['hls']);
    exit;
}
?>