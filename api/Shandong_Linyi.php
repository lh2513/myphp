<?php
/*
临沂综合,lytv.php?id=channel111841
临沂经济,lytv.php?id=channel115062
临沂公共,lytv.php?id=channel113571
*/
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
    $u = 'https://m3u8-channel.lytv.tv/nmip-media/channellive/' . $name;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $u,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => array(
            'referer: https://www.ilinyi.net/',
//            'referer: https://m.lytv.tv/',
        )
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
?>