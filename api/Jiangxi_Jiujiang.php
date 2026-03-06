<?php

/*
新闻综合频道,id=jjtv1
公共频道,id=jjtv2
教育频道,id=jjfz
*/

$id = $_GET['id'];
$u = get_m3u8_url($id);
$c = send_request($u);
$c = replace_ts_urls($u, $c);
echo_content($c);



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
    $url = 'https://www.jjntv.cn/live';
    $data = send_request($url);
    preg_match("/{$id}:[\s\S]+?stream:\s*?'([^']+?)'/", $data, $stream);
    return $stream[1];
}

function save_to_cache($id, $url)
{
    $a[$id]['expire_time'] = time() + 60 * 60;
    $a[$id]['url'] = $url;
    array_to_file($a, 'jj_cache.txt');
}

function load_from_cache($id)
{
    file_to_array('jj_cache.txt', $a);
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

function replace_ts_urls($m3u8_url, $m3u8_content)
{
    $dest_ts_path = dirname($m3u8_url)."/";
    return preg_replace_callback("/^((?!#).+)$/im",
        function ($matches) use ($dest_ts_path) {
            if (!is_absolute_url($matches[1]))
                $ts = $dest_ts_path.$matches[1];
            else
                $ts = $matches[1];
            return $ts;
        },
        $m3u8_content
    );
}

function is_absolute_url($url) {
    return stripos($url, 'http:') === 0 || stripos($url, 'https:') === 0;
}

function echo_content($content)
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/vnd.apple.mpegurl");
    echo $content;
}

function send_request($url) {
    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Referer: https://www.jjntv.cn/',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
        )
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}