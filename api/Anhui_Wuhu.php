<?php
//芜湖新闻综合,id=7
//芜湖生活频道,id=9
$id = $_GET['id'];
if ($id) {
    $url = "https://mapi.wuhunews.cn/api/v1/program.php?&channel_id={$id}";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    foreach ($data as $program) {
        if (!empty($program['m3u8'])) {
            header("Access-Control-Allow-Origin: *");
            header("Location: {$program['m3u8']}");
            exit;
        }
    }
}
