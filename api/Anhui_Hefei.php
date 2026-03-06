<?php
/*
合肥新闻,hefei.php?id=1
合肥生活,hefei.php?id=2
合肥科创,hefei.php?id=3
合肥教育法制,hefei.php?id=4
*/
if (need_m3u8($id, $ts_url)) {
    $u = get_m3u8_url($id);
    $c = send_request($u, $ct);
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

    $r = get_m3u8_url_from_web($id);

    save_to_cache($id, $r);
    return $r;
}

function get_m3u8_url_from_web($id) {
    $u = "https://app.hfbtc.cn/shows/2/$id.html";
    $c = send_request($u);
    preg_match('/source src="(.+?)"/', $c, $m);
    $p = $m[1];
    return $p;
}

function save_to_cache($id, $url)
{
    $a[$id]['expire_time'] = time() + 60 * 60 * 0.5;
    $a[$id]['url'] = $url;
    array_to_file($a, 'HeFei_cache.txt');
}

function load_from_cache($id)
{
    file_to_array('HeFei_cache.txt', $a);
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

function replace_ts_urls($m3u8_url, $m3u8_content)
{
    $dest_ts_path = dirname($m3u8_url)."/";
    $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    $self_part = "$protocol://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    return preg_replace_callback("/^((?!#).+)$/im",
        function ($matches) use ($self_part, $dest_ts_path) {
            if (!is_absolute_url($matches[1]))
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