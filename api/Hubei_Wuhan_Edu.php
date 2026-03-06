<?php
$u = 'https://ronghehao.whjyapp.com/v3/media_channel_program/programList';
$c = file_get_contents($u);
$j = json_decode($c, true);
$p = $j['data']['program_list'];
for ($i = count($p)-1; $i >= 0; $i--) {
    if ($p[$i]['play_url'] != '') {
        header('Access-Control-Allow-Origin: *');
        header('Location: '.$p[$i]['play_url']);
        break;
    }
}



/*
使用：
a.php
*/