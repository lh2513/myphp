<?php

/*
广西卫视,http://192.168.1.200/myphp/test/guangxi.php?id=1
广西综艺旅游,http://192.168.1.200/myphp/test/guangxi.php?id=2
广西都市,http://192.168.1.200/myphp/test/guangxi.php?id=3
广西影视,http://192.168.1.200/myphp/test/guangxi.php?id=4
广西新闻,http://192.168.1.200/myphp/test/guangxi.php?id=5
广西国际,http://192.168.1.200/myphp/test/guangxi.php?id=6
广西乐思购,http://192.168.1.200/myphp/test/guangxi.php?id=7
广西移动,http://192.168.1.200/myphp/test/guangxi.php?id=8
CETV-1,http://192.168.1.200/myphp/test/guangxi.php?id=9
CETV-2,http://192.168.1.200/myphp/test/guangxi.php?id=10
CETV-4,http://192.168.1.200/myphp/test/guangxi.php?id=11
*/

if (need_m3u8($id, $ts_url, $channel)) {
    $u = get_m3u8_url($channel);
    $c = send_request($u, $ct);
    $c = replace_ts_urls($id, $c);
} else {
    $k = get_key_and_blocks($channel);
    $u = get_m3u8_url($channel);
    $ts_url = dirname($u).'/'.$ts_url;
    $c = send_request($ts_url, $ct);
    decrypt_ts($k, $c);
}
echo_content($ct, $c);



function get_channel($id) {
    return [
        1 => ['https://hlscdn.liangtv.cn/live/0c4ef3a44b934cacb8b47121dfada66c/d7e04258157b480dae53883cc6f8123b-1.m3u8', 'aa390855e94889d26ccf2c5a0c342e73', 18], //广西卫视
        2 => ['https://hlscdn.liangtv.cn/live/de0f97348eb84f62aa6b7d8cf0430770/dd505d87880c478f901f38560ca4d4e6-1.m3u8', '59ccd582591a61e35a2434df51d8e697', 26], //综艺旅游频道
        3 => ['https://hlscdn.liangtv.cn/live/b8f4e500a4024fd2bf189b46f490359f/b04d249044fb4d0887b88aa9c2cc8f6c-1.m3u8', '3d4a0a74ee9af217ff63c7bf7bfa4f91', 17], //都市频道
        4 => ['https://hlscdn.liangtv.cn/live/a84182dabc5147afbd3d90ddbb5a9404/d097f6c24c53463e897de496b32c7d2b-1.m3u8', '328d87240d5c25c1f412a08be81a1649', 4], //影视频道
        5 => ['https://hlscdn.liangtv.cn/live/a48635e37ac84afa82c0d0edc4bfabf9/dbc9a18971294257bad7c75b7f3f0c20-1.m3u8', 'c21df4dd9cbd339b20b6e435a62f10e3', 4], //新闻频道
        6 => ['https://hlscdn.liangtv.cn/live/0234c48e0bc24fe1b41b9999a253e581/1075ee38e04f490690f6a36a16e09c79-1.m3u8', '4297eb2b6d538f7bee595e70b35289fb', 24], //国际频道
        7 => ['https://hlscdn.liangtv.cn/live/2cb851292fd14014a6558343872899e6/0820054f3fcc4ee5b4d17198bd7eddd6-1.m3u8', '1ec32c4e7960167d4d9679d2ef5f7265', 16], //乐思购频道
        8 => ['https://hlscdn.liangtv.cn/live/b6cea70bfad24970aaa2256a3c340ad4/0a79a8e5f94641e583d1872ef7bed2bf-1.m3u8', '5c1d834a84f3ff24720622105b5cddfe', 9], //移动数字电视频道
        9 => ['https://hlscdn.liangtv.cn/live/ddb2ee1aa1134ac591230352a121aa22/bc359bd2e13b4cb9a3096effa77d1bc0-1.m3u8', 'b324a5b0682ea911d1ccc18ebc1c0cba', 11], //中国教育电视台CETV-1频道
        10 => ['https://hlscdn.liangtv.cn/live/3f29b81206fe4d229e1522d59aae8e75/15a4a13dbf624ab9ac7cca5df100e985-1.m3u8', '7a63f3adc0ebbf30eab427a9846cf8be', 7], //中国教育电视台CETV-2频道
        11 => ['https://hlscdn.liangtv.cn/live/63f3fd7d8cf44a3e9719eec310c86fa5/b96f8fd6d5424ad4862d054172f616e4-1.m3u8', '86556718d2add26ff9136a1af241b3db', 11], //中国教育电视台CETV-4频道
    ][$id];
}

function get_m3u8_url($channel) {
    return $channel[0];
}

function get_key_and_blocks($channel) {
    return [hex2bin($channel[1]), $channel[2]];
}

function need_m3u8(&$id, &$ts_url, &$channel)
{
    $id = $_GET['id'];
    $ts_url = isset($_GET['ts']) ? $_GET['ts'] : null;
    $channel = get_channel($id);
    return $ts_url === null;
}

function send_request($url, &$content_type = null)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    if (func_num_args() > 1)
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $res;
}

function replace_ts_urls($id, $m3u8_content)
{
    $p = sprintf('%s?id=%d&ts=', $_SERVER['PHP_SELF'], $id); 
    return preg_replace('/^(?=[^#])/m', $p, $m3u8_content);
}

function echo_content($content_type, $content)
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: $content_type");
    echo $content;
}

function decrypt_ts($k, &$c) {
    append($k, $c);
}

function append($k, &$e) {
    $c = strlen($e);
    $f = $g = !1;
    $y = $E = $A = -1;
    $b = -1;
    $T = $X = $S = null;
    $P = 0;
    for ($c -= ($c + $P) % 188,
         $n = $P; $n < $c; $n += 188)
        if (71 === ord($e[$n])) {
            $o = !!(64 & ord($e[$n + 1]));
            $l = ((31 & ord($e[$n + 1])) << 8) + ord($e[$n + 2]);
            $d = (48 & ord($e[$n + 3])) >> 4;
            if ($d > 1) {
                $h = $n + 5 + ord($e[$n + 4]);
                if ($h === $n + 188)
                    continue;
            } else
                $h = $n + 4;
            switch ($l) {
                case $y:
                    $o && ($T && parsePES($T, $e, $k));
                    $o && $T = [
                        'data' => [],
                        'size' => 0,
                        'offset' => [],
                    ];
                    if ($T) {
                        $T['data'][] = substr($e, $h, $n + 188 - $h);
                        $T['size'] += $n + 188 - $h;
                        $T['offset'][] = $h;
                    }
                    break;
                case $E:
                    $o && ($X && parsePES($X, $e, $k));
                    $o && $X = [
                        'data' => [],
                        'size' => 0,
                        'offset' => [],
                    ];
                    if ($X) {
                        $X['data'][] = substr($e, $h, $n + 188 - $h);
                        $X['size'] += $n + 188 - $h;
                        $X['offset'][] = $h;
                    }
                    break;
                case $A:
                    $o && ($S && parsePES($S, $e, $k));
                    $o && $S = [
                        'data' => [],
                        'size' => 0,
                        'offset' => [],
                    ];
                    if ($S) {
                        $S['data'][] = substr($e, $h, $n + 188 - $h);
                        $S['size'] += $n + 188 - $h;
                        $S['offset'][] = $h;
                    }
                    break;
                case 0:
                    $o && (($h += ord($e[$h]) + 1) &&
                        $b = parsePAT($e, $h)
                    );
                    break;
                case $b:
                    {
                        $o && ($h += ord($e[$h]) + 1);
                        $t = parsePMT($e, $h);
                        $y = $t['avc'];
                        $E = $t['audio'];
                        $A = $t['id3'];
                        $f && !$g && (
                        $f = !1 ||
                            $n = $P - 188
                        );
                        $g = !0;
                    }
                    break;
                case 17:
                case 8191:
                    break;
                default:
                    $f = !0;
            }
        } else
            die("TS packet did not start with 0x47");
    $T && parsePES($T, $e, $k);
    $X && parsePES($X, $e, $k);
    $S && parsePES($S, $e, $k);
}

function parsePAT($e, $t) {
    return (31 & ord($e[$t + 10])) << 8 | ord($e[$t + 11]);
}

function parsePMT($e, $t, $i = true, $r = false) {
    $l = [
        'audio' => -1,
        'avc' => -1,
        'id3' => -1,
        'isAAC' => !0
    ];
    for ($s = $t + 3 + ($a = (15 & ord($e[$t + 1])) << 8 | ord($e[$t + 2])) - 4,
         $t += 12 + ($n = (15 & ord($e[$t + 10])) << 8 | ord($e[$t + 11])); $t < $s; ) {
        $o = (31 & ord($e[$t + 1])) << 8 | ord($e[$t + 2]);
        switch (ord($e[$t])) {
            case 207:
                if (!$r) {
                    die("unkown stream type:" . ord($e[$t]));
                }
                break;
            case 15:
                -1 === $l['audio'] && ($l['audio'] = $o);
                break;
            case 21:
                -1 === $l['id3'] && ($l['id3'] = $o);
                break;
            case 219:
                if (!$r) {
                    die("unkown stream type:" . ord($e[$t]));
                }
                break;
            case 27:
                -1 === $l['avc'] && ($l['avc'] = $o);
                break;
            case 3:
            case 4:
                die("mpeg audio found, but it's support had been removed");
            case 36:
                die("HEVC stream type found, not supported for now");
                break;
            default:
                die("unkown stream type:" . ord($e[$t]));
        }
        $t += 5 + ((15 & ord($e[$t + 3])) << 8 | ord($e[$t + 4]));
    }
    return $l;
}

function parsePES($e, &$s, $k) {
    $c = null;
    $f = 0;
    $g = $e['data'];
    $a = $e['offset'];
    $O = array_fill(0, $e['size'], 0);
    $k2 = 0;
    for ($i = 0; $i < count($a); $i++) {
        for ($j = 0; $j < strlen($g[$i]); $j++) {
            $O[$k2] = $a[$i] + $j;
            $k2++;
        }
    }
    if (!$e || 0 === $e['size'])
        return null;
    for (; strlen($g[0]) < 19 && count($g) > 1; ) {
        $e2 = $g[0].$g[1];
        $g[0] = $e2;
        array_splice($g, 1, 1);
    }
    $r = $g[0];
    if (1 === ($s2 = (ord($r[0]) << 16) + (ord($r[1]) << 8) + ord($r[2]))) {
        if (($n = (ord($r[4]) << 8) + ord($r[5])) && $n > $e['size'] - 6)
            return null;
        $a = ord($r[7]);
        $u = ($o = ord($r[8])) + 9;
        if (1 & $a) {
            $c = [
                'audio' => true,
                'video' => true,
                'algorithm' => 2
            ];
        }
        $e['size'] -= $u;
        $l = '';
        for ($e2 = 0, $t = count($g); $e2 < $t; $e2++) {
            $r = $g[$e2];
            $t2 = strlen($r);
            if ($u) {
                if ($u > $t2) {
                    $u -= $t2;
                    array_splice($O, 0, $t2);
                    continue;
                }
                $r = substr($r, $u);
                array_splice($O, 0, $u);
                $t2 -= $u;
                $u = 0;
            }
            $l .= $r;
            $f += $t2;
        }
        $n && ($n -= $o + 3);

        return 0 !== strlen($l) && $c && ($c['video'] || $c['audio']) && decrypt($k, $l, $s, $O);
    }
    return null;
}

function decrypt($k, $e, &$s, $o) {
    $t = intval(strlen($e) / $k[1]);
    decryptV2($k[0], $e, $t);
    swapBlocks($e, $t, $k[1]);

    for ($i = 0; $i < count($o); $i++) {
        $s[$o[$i]] = $e[$i];
    }

    return true;
}

function decryptV2($k, &$e, $t) {
    $t = intval($t / 2);
    $r = intval((strlen($e) - $t - 1) / 16);
    for ($i = 0; $i < $r; $i++) {
        $r2 = substr($e, $t + 16 * $i, 16);
        $d = openssl_decrypt($r2, 'AES-128-ECB', $k, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
        for ($j = 0; $j < 16; $j++) {
            $e[$j + $t + 16 * $i] = $d[$j];
        }
    }
}

function swapBlocks(&$e, $t, $blocks) {
    $i = array_values(unpack('C*', $e)); // 转换为字节数组

// 提取最后一个块
    $r = array_slice($i, ($blocks - 1) * $t, $t);

// 移动中间块
    $middleBlocks = array_slice($i, $t, ($blocks - 2) * $t);
    array_splice($i, 2 * $t, count($middleBlocks), $middleBlocks);

// 放入最后一个块
    array_splice($i, $t, count($r), $r);

    $e = pack('C*', ...$i);
}