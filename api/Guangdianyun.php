<?php
include_once 'common.php';
$pid = $_GET['pid'];
$cid = $_GET['cid'];
ensure_heartbeat_running($pid, $cid);
$u = get_m3u8_url($pid, $cid);
$c = send_request($u);
$c = replace_ts_urls($u, $c);
echo_content($c);



function get_m3u8_url($pid, $cid)
{
    $r = load_from_cache($pid, $cid);
    if (isset($r) && isset($r['url']))
        return $r['url'];

    $r['url'] = get_m3u8_url_from_intf($r['uuid'], $pid, $cid);

    save_to_cache($pid, $cid, $r);
    return $r['url'];
}

function replace_ts_urls($m3u8_url, $m3u8_content)
{
    $dest_ts_path = dirname($m3u8_url)."/";
    return preg_replace_callback("/^((?!#).+)$/im",
        function ($matches) use ($dest_ts_path) {
            $ts = $dest_ts_path.$matches[1];
            return $ts;
        },
        $m3u8_content
    );
}

function echo_content($content)
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/vnd.apple.mpegurl");
    echo $content;
}

function get_m3u8_url_from_intf($uuid, $pid, $cid)
{
    $url = "https://1812501212048408.cn-hangzhou.fc.aliyuncs.com/2016-08-15/proxy/node-api.online/node-api/tv/getPlayAddress";
    $params = [
        'id' => $pid,
        'uin' => $cid,
        'clientId' => $uuid,
    ];
    $headers = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
        'Origin: https://web.guangdianyun.tv',
        'Referer: https://web.guangdianyun.tv/',
    ];
    $c = send_request($url, $params, $headers);
    $j = json_decode($c, true);
    $r = $j['data']['hlsUrl'];
    return $r;
}