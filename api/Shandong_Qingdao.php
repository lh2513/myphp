<?php

/*
青岛新闻综合,qingdao.php?id=1
青岛经济生活,qingdao.php?id=2
青岛影视,qingdao.php?id=3
青岛文化娱乐,qingdao.php?id=4
青岛教育,qingdao.php?id=6
*/

$q = $_SERVER['QUERY_STRING'];
if (stripos($q, 'id=') === 0) {
    $id = $_GET['id'];
    $p = "https://video10.qtv.com.cn/drm/qtv{$id}at/";
    $c = file_get_contents($p.'manifest.m3u8');
    $p2 = $_SERVER['PHP_SELF'].'?'.$p;
    $c = preg_replace('/^(?=[^#])/m', $p2, $c);
    $ct = 'application/x-mpegURL';
} else {
    $c = file_get_contents($q);
    $e = $c;
    if (strlen($e) > 44 && strpos($e, 'ARCVIDEO-PROTECTED') === 0) {
        $n = $e;
        $i = ord($n[19]);
        $s = ord($n[20]) << 24 | ord($n[21]) << 16 | ord($n[22]) << 8 | ord($n[23]);
        $o = (ord($n[32]) + 1) * $i;
        $u = min($o, $s);
        $l = substr($e, $u + 28, 16);
        $t = openssl_decrypt($l, 'AES-128-CBC', 'qdxmtottarcvidet', OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
        $i = 16 * floor(($s + 15) / 16);
        if ($o < $i) {
            $u = substr($e, 28, $o);
            $u .= substr($e, 44 + $o, $i - $o);
        } else
            $u = substr($e, 28, $s - 28);
        $e = openssl_decrypt($u, 'AES-128-CBC', $t, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
        $c = $e;
    }
    $ct = 'video/mp2t';
}
header('Access-Control-Allow-Origin: *');
header('Content-Type: '.$ct);
echo $c;