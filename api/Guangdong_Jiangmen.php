<?php
$id = isset($_GET['id'])?$_GET['id']:'jmzh';
$n = [
     'jmzh' => '4x3T0dO',//江门综合
     'jmqxsh' => 'qzohscw',//江门侨乡生活
     ];
//$p = file_get_contents("http://www.dsk.cc/php/pname.php?id={$n[$id]}");
$p = get_name($n[$id]);
$m3u8 = "http://tidelive.jmtv.cn/lsdream/{$n[$id]}/993/{$p}.m3u8";
header("Access-Control-Allow-Origin: *");
header("location:".$m3u8);



function get_name($kt, $t = 1, $e = 'live') {
//http://www.jmtv.cn/js/pc/tidePlayer2021.js
// w = function(t, e)
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
}