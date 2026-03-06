<?php
//http://www.sjzntv.cn/wzsy/pd/
$id = $_GET['id'];
//$id = 4;
$u = 'http://mapi.sjzntv.cn/api/v1/channel.php?node_id=1';
$c = file_get_contents($u);
$j = json_decode($c);
foreach ($j as $i) {
    if ($i->id == $id) {
        $p = $i->m3u8;
        break;
    }
}
if (!isset($p))
    die('not found');
header('Access-Control-Allow-Origin: *');
header('Location: '.$p);
