<?php
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) goto ts;
$c = get($id . '/playlist.m3u8');
$c = preg_replace('/^(?=.+\.ts)/m', $_SERVER['PHP_SELF'] . "?$id/", $c);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/vnd.apple.mpegurl');
echo $c;
die;

ts:
$q = $_SERVER['QUERY_STRING'];
$c = get($q);
header('Access-Control-Allow-Origin: *');
header('Content-Type: video/mp2t');
echo $c;
die;



function get($name) {
    $u = 'http://szyplaytv.snxw.com/live/' . $name;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $u,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => array(
            'referer: https://www.snxw.com/',
        )
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}