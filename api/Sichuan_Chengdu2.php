<?php
	/*
	成都,#genre#
	成都新闻综合,cdtv2.php?id=cdtv1
	成都经济资讯,cdtv2.php?id=cdtv2
	成都都市生活,cdtv2.php?id=cdtv3
	成都影视文艺,cdtv2.php?id=cdtv4
	成都公共,cdtv2.php?id=cdtv5
	成都少儿,cdtv2.php?id=cdtv6
	成都蓉城先锋,cdtv2.php?id=cdrcxf

	成华有线,cdtv2.php?id=chyx
	崇州综合,cdtv2.php?id=cztv
	都江堰电视台,cdtv2.php?id=djytv
	大邑综合,cdtv2.php?id=dytv
	高新电视台,cdtv2.php?id=gxtv
	锦江电视台,cdtv2.php?id=jjtv
	金牛电视台,cdtv2.php?id=jntv
	金堂电视台,cdtv2.php?id=jttv
	简阳新闻综合,cdtv2.php?id=jyxwzh
	郫都新闻综合,cdtv2.php?id=pdxwzh
	蒲江电视台,cdtv2.php?id=pjtv
	彭州电视台,cdtv2.php?id=pztv
	青白江电视台,cdtv2.php?id=qbjtv
	双流综合,cdtv2.php?id=slzh
	青羊电视台,cdtv2.php?id=qytv
	武侯电视台,cdtv2.php?id=whtv
	温江电视台,cdtv2.php?id=wjtv
	新都综合,cdtv2.php?id=xdtv
	新津电视台,cdtv2.php?id=xjtv
	*/
error_reporting(0);
header('Content-Type: text/plain; charset=utf-8');

$n = [
    'cdtv1' => 1,
    'cdtv2' => 2,
    'cdtv3' => 3,
    'cdtv4' => 45,
    'cdtv5' => 5,
    'cdtv6' => 6,
    'cdrcxf' => 15,
];

$m = [
    'chyx' => 1319,
    'cztv' => 1257,
    'djytv' => 1314,
    'dytv' => 790,
    'gxtv' => 722,
    'jjtv' => 1541,
    'jntv' => 556,
    'jttv' => 840,
    'jyxwzh' => 1698,
    'lqzh' => 882,
    'pdxwzh' => 845,
    'pjtv' => 828,
    'pztv' => 796,
    'qbjtv' => 966,
    'qltv' => 1427,
    'qytv' => 910,
    'slzh' => 557,
    'whtv' => 1766,
    'wjtv' => 559,
    'xdtv' => 1712,
    'xjtv' => 760,
];


$id = isset($_GET['id']) ? $_GET['id'] : 'cdtv1';
$t = isset($_GET['t']) ? $_GET['t'] : 'hd'; // hd or sd

$url = null;
if (isset($n[$id])) {
    $url = "http://mob.api.cditv.cn/show/192-{$n[$id]}.html";
} elseif (isset($m[$id])) {
    $url = "http://mob.api.cditv.cn/show/192-{$m[$id]}.html";
} else {
    die("Invalid channel ID: $id");
}


$context = stream_context_create(['http' => ['timeout' => 5]]);
$content = @file_get_contents($url, false, $context);
if (!$content) {
    die("Failed to fetch data from API.");
}

$json = json_decode($content);
if (!$json || !isset($json->data)) {
    die("Invalid JSON response from API.");
}


if (isset($n[$id])) {
    $m3u8_sd = isset($json->data->android_url) ? preg_replace('/^http:/i', 'https:', $json->data->android_url) : '';
    $m3u8_hd = isset($json->data->android_HDlive_url) ? preg_replace('/^http:/i', 'https:', $json->data->android_HDlive_url) : '';

    if (!$m3u8_sd && !$m3u8_hd) {
        die("No stream URL available for this channel.");
    }

    $target = ($t === 'hd' && $m3u8_hd) ? $m3u8_hd : $m3u8_sd;

    header('Location: ' . $target);
    exit();

} elseif (isset($m[$id])) {
    $m3u8 = $json->data->android_url ?? '';
    if (empty($m3u8)) {
        die("No stream URL available for this county channel.");
    }


    header('Location: ' . $m3u8);
    exit();
}
?>