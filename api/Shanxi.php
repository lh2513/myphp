<?php

/*
//省级频道
山西卫视,shanxi.php?t=1&id=q8RVWgs&r=1000
山西黄河,shanxi.php?t=1&id=lce1mC4&r=800
山西经济与科技,shanxi.php?t=1&id=4j01KWX&r=800
山西影视,shanxi.php?t=1&id=Md571Kv&r=800
山西社会与法治,shanxi.php?t=1&id=p4y5do9&r=500
山西文体生活,shanxi.php?t=1&id=agmpyEk&r=800

//地市频道
太原-1,shanxi.php?t=2&id=11
朔州-1,shanxi.php?t=2&id=6
忻州综合,shanxi.php?t=2&id=3
阳泉新闻综合,shanxi.php?t=2&id=9
吕梁-1,shanxi.php?t=2&id=1
晋中综合,shanxi.php?t=2&id=5
长治-1,shanxi.php?t=2&id=8
晋城新闻综合,shanxi.php?t=2&id=7
临汾-1,shanxi.php?t=2&id=2
运城-1,shanxi.php?t=2&id=4

//广播
山西综合广播,http://radiolive.sxrtv.com/live/xinwen/playlist.m3u8
山西经济广播,http://radiolive.sxrtv.com/live/jingji/playlist.m3u8
山西健康之声广播,http://radiolive.sxrtv.com/live/jiankang/playlist.m3u8
山西交通广播,http://radiolive.sxrtv.com/live/jiaotong/playlist.m3u8
山西农村广播,http://radiolive.sxrtv.com/live/nongcun/playlist.m3u8
山西文艺广播,http://radiolive.sxrtv.com/live/wenyi/playlist.m3u8
山西音乐广播,http://radiolive.sxrtv.com/live/yinyue/playlist.m3u8
山西故事广播,http://radiolive.sxrtv.com/live/gushi/playlist.m3u8
*/

$t = $_GET['t'];
$id = $_GET['id'];
$r = isset($_GET['r']) ? $_GET['r'] : null;

if ($t == 1) {
    $get_name = function($kt, $t = 1, $e = 'live') {
        $n = $kt;
        $timezone = new DateTimeZone('Asia/Shanghai');
        $o = new DateTime('now', $timezone);
        $dateString = $o->format('Y-m-d');
        $newDate = new DateTime($dateString, $timezone);
        $r = $newDate->getTimestamp() * 1000;
        $a = 0;
        $d = 0;

        $g = -1;
        $m = 0;
        for ($a = 0; $a < strlen($n); $a++) {
            $b = ord($n[$a]);
            $d += $b;
            -1 != $g && ($m += $g - $b);
            $g = $b;
        }
        $l = base_convert((string)($d += $m), 10, 36);
        $s = base_convert((string)$r, 10, 36);
        $p = 0;
        for ($a = 0; $a < strlen($s); $a++)
            $p += ord($s[$a]);
        $s = substr($s, 5) . substr($s, 0, 5);
        $c = abs($p - $d);
        $h = substr($s = strrev($l).$s, 0, 4);
        $u = substr($s, 4);
        $v = [];
        $date = new DateTime('@' . floor($r / 1000));
        $date->setTimezone($timezone);
        $day = $date->format('w');
        $f = $day % 2;
        for ($a = 0; $a < strlen($n); $a++) {
            ($a % 2 == $f)
                ? $v[] = $s[$a % strlen($s)]
                : (
            ($w = $a === 0 ? '' : $n[$a - 1])
                ? (
            -1 == ($x = strpos($h, $w) !== false ? strpos($h, $w) : -1)
                ? $v[] = $w
                : $v[] = $u[$x]
            )
                : $v[] = $h[$a]
            );
        }

        return substr(strrev(base_convert((string)$c, 10, 36)) . implode('', $v), 0, strlen($n));
    };
    $n = $get_name($id);
    $l = "https://livehhhttps.sxrtv.com/lsdream/$id/$r/$n.m3u8";
} else {
    $u = 'https://dyhhplus.sxrtv.com/tapi/custom/huawei_live_secret.jsp?itemId='.$id;
    $c = file_get_contents($u);
    $j = json_decode($c, true);
    $l = $j['data']['address'];
}

header('Access-Control-Allow-Origin: *');
header('Location: '.$l);