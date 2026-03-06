<?php
$need_request_ts = false;//是否代理ts。不能播放时置为true，并换个服务器。
$need_refresh_play_token = false;//是否刷新播放令牌。不刷新则播放链接是固定的，无需缓存。
$need_cache = false;//是否需要缓存。需要则缓存文件夹bfgd_cache会自动创建，若无权限则手工创建。
main();



function main() {
    global $need_cache, $need_refresh_play_token;
    if ($need_cache && !$need_refresh_play_token)
        $need_cache = false;

    global $need_request_ts;
    if (!$need_request_ts)//代理m3u8
        request_m3u8();
    else//代理m3u8和ts
        request_m3u8_and_ts();
}

function get_base_url() {
    return 'http://httplive.slave.bfgd.com.cn:14311';
}

function get_access_token()
{
    return 'R621C86FCU319FA04BK783FB5EBIFA29A0DEP2BF4M340CAC5V0Z339C9W16D7E5AFCA1ADFD1';
}

function request_m3u8()
{
    $id = $_GET['id'];
    $u = get_m3u8_url($id);
    $c = send_request($u, $ct);
    $c = replace_ts_urls($c);
    echo_content($ct, $c);
}

function request_m3u8_and_ts()
{
    if (need_m3u8($ts_url)) {
        request_m3u8();
    } else {
        $c = send_request($ts_url, $ct);
        echo_content($ct, $c);
    }
}

function need_m3u8(&$ts_url)
{
    $q = $_SERVER['QUERY_STRING'];
    $r = stripos($q, 'id=') === 0;
    if (!$r)
        $ts_url = $q;
    return $r;
}

function get_m3u8_url($id) {
    global $need_cache;
    if ($need_cache) {
        $r = load_from_cache($id);
        if ($r)
            return $r;
    }

    $r = get_m3u8_url_core($id);

    if ($need_cache)
        save_to_cache($id, $r);
    return $r;
}

function get_m3u8_url_core($id)
{
    return get_base_url().'/playurl'
        .'?playtype=live'
        .'&protocol=hls'
        .'&accesstoken='.get_access_token()
        .'&programid=4200000'.$id
        .'&playtoken='.get_play_token($id);
}

function get_play_token($id)
{
    $default = 'ABCDEFGHI';
    global $need_refresh_play_token;
    if ($need_refresh_play_token) {
        $u = 'http://slave.bfgd.com.cn/media/channel/get_info'
            .'?chnlid=4200000'.$id
            .'&accesstoken='.get_access_token();
        $c = send_request($u);
        $j = json_decode($c);
        $r = isset($j->play_token) ? $j->play_token : $default;
    } else {
        $r = $default;
    }
    return $r;
}

function save_to_cache($id, $m3u8_url)
{
    $a['m3u8_url'] = $m3u8_url;
    array_to_file($a, "bfgd_cache/$id.txt");
}

function load_from_cache($id)
{
    $cacheFile = "bfgd_cache/$id.txt";
    $expireSeconds = 60 * 60;
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expireSeconds)) {
        file_to_array($cacheFile, $a);
        return (isset($a) && isset($a['m3u8_url'])) ? $a['m3u8_url'] : '';
    } else {
        return '';
    }
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
    $dir = dirname($filename);
    if (!is_dir($dir) && is_writable(dirname($dir))) {
        if (!@mkdir($dir, 0755, true))
            return false;
    }
    $data = serialize($array);
    file_put_contents($filename, $data, LOCK_EX);
    return true;
}

function send_request($url, &$content_type = null)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    if ($res === false) {
        $error_code = curl_errno($ch);
        $error_msg = curl_error($ch);
        curl_close($ch);
        echo "cURL 执行失败：\n";
        echo "网址：$url\n";
        echo "错误代码：$error_code\n";
        echo "错误信息：$error_msg\n";
        die;
    }
    if (func_num_args() > 1)
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $res;
}

function replace_ts_urls($m3u8_content)
{
    global $need_request_ts;
    $r = get_base_url();
    if ($need_request_ts)
        $r = $_SERVER['PHP_SELF'].'?'.$r;
    return preg_replace('/https?:\/\/[^\/]+/i', $r, $m3u8_content);
}

function echo_content($content_type, $content)
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: $content_type");
    echo $content;
}