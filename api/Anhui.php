<?php

/*
安徽卫视,ah.php?id=47
经济生活,ah.php?id=71
综艺体育,ah.php?id=73
影视频道,ah.php?id=72
安徽公共,ah.php?id=50
农业科教,ah.php?id=51
安徽国际,ah.php?id=70
移动电视,ah.php?id=68
*/

if (need_m3u8($id, $ts_url)) {
    $u = get_m3u8_url($id);
    $c = send_request($u, $ct, $u);
    $c = replace_ts_urls($u, $c);
} else {
    $c = send_request($ts_url, $ct);
}
echo_content($ct, $c);



function need_m3u8(&$id, &$ts_url)
{
    $q = $_SERVER['QUERY_STRING'];
    $r = stripos($q, 'id=') === 0;
    if ($r)
        $id = $_GET['id'];
    else
        $ts_url = $q;
    return $r;
}

function get_m3u8_url($id)
{
    $r = load_from_cache($id);
    if ($r)
        return $r;

    $p = get_m3u8_url_from_web($id);
    $r = get_actual_m3u8_url($p);

    save_to_cache($id, $r);
    return $r;
}

function get_m3u8_url_from_web($id) {
    $u = "https://mapi.ahtv.cn/api/v1/channel.php?is_audio=0&category_id=1%2C2";
    $c = send_request($u, $ct);
    $j = json_decode($c);
    foreach ($j as $i) {
        if ($i->id == $id) {
            return $i->m3u8;
        }
    }
    return '';
}

function get_actual_m3u8_url($index_m3u8_url) {
    $c = send_request($index_m3u8_url, $ct, $r);
    if (stripos($c, '#EXT-X-STREAM-INF:') !== false) {
        $a = explode("\n", $c);
        foreach ($a as $i) {
            $i = trim($i);
            if (strpos($i, '#') !== 0) {
                if (is_absolute_url($i))
                    $r = $i;
                else
                    $r = dirname($r)."/".$i;
                break;
            }
        }
    }
    return $r;
}

function save_to_cache($id, $url)
{
    $a[$id]['expire_time'] = time() + 60 * 60;
    $a[$id]['url'] = $url;
    array_to_file($a, 'ahtv_cache.txt');
}

function load_from_cache($id)
{
    file_to_array('ahtv_cache.txt', $a);
    if (isset($a) && isset($a[$id]) && $a[$id]['expire_time'] > time())
        return $a[$id]['url'];
    else
        return '';
}

function file_to_array($filename, &$array) {
    $array = [];
    if (file_exists($filename)) {
        $handle = fopen($filename, 'r');
        if (flock($handle, LOCK_SH)) { // 共享锁，允许其他进程读但禁止写
            $data = file_get_contents($filename);
            $array = unserialize($data);
            flock($handle, LOCK_UN);
        }
        fclose($handle);
    }
    return true;
}

function array_to_file($array, $filename) {
    $data = serialize($array);
    file_put_contents($filename, $data, LOCK_EX);
    return true;
}

function send_request($url, &$content_type = null, &$final_url = null)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.ahtv.cn/');
    $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36';
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    $res = curl_exec($ch);
    if (func_num_args() > 1)
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    if (func_num_args() > 2)
        $final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    return $res;
}

function replace_ts_urls($m3u8_url, $m3u8_content)
{
    $dest_ts_path = dirname($m3u8_url)."/";
    $dest_ts_domain_path = implode("/", array_slice(explode("/", $m3u8_url), 0, 3));
    $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    $self_part = "$protocol://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    return preg_replace_callback("/^((?!#).+)$/im",
        function ($matches) use ($self_part, $dest_ts_path, $dest_ts_domain_path) {
            if (!is_absolute_url($matches[1]))
                if ($matches[1][0] == '/')
                    $ts = $dest_ts_domain_path.$matches[1];
                else
                    $ts = $dest_ts_path.$matches[1];
            else
                $ts = $matches[1];
            return "$self_part?$ts";
        },
        $m3u8_content
    );
}

function is_absolute_url($url) {
    return stripos($url, 'http:') === 0 || stripos($url, 'https:') === 0;
}

function echo_content($content_type, $content)
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: $content_type");
    echo $content;
}
