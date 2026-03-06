<?php
$p = isset($_GET["p"]) ? $_GET["p"] : 2;// 0 rtmp | 1 flv | 2 hls
$u = 'https://euvp.icbtlive.com/player_api/api/live/iframe-enter';
$c = file_get_contents($u, false, stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => '{"id":"r83ee4"}',
    )
)));
$j = json_decode($c);
$l = $j->data->channels[0]->streams[0]->play_url[$p]->addr;
header('Access-Control-Allow-Origin: *');
header('Location: ' . $l);