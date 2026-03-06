<?php
$ws1 = null;
$ws2 = null;

include_once 'heartbeat.php';

function ensure_heartbeat_running($pid, $cid)
{
    if (!is_heartbeat_running($pid, $cid)) {
        $r = start_heartbeat_running($pid, $cid);
        //write_log("r: ".(isset($r) && $r ? 'true' : 'false'));
        if (!$r)
            die;
    }
    update_flag_filemtime($pid, $cid);
}

function create_uuid() {
    return vsprintf('%04x%04x-%04x-4%03x-%04x-%04x%04x%04x', [
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), // 前8位
        mt_rand(0, 0xffff), // 中间4位
        mt_rand(0, 0x0fff), // 第13位固定为4，这里生成后3位
        mt_rand(0, 0x3fff) | 0x8000, // 第17位固定为8/9/a/b（0x8000确保最高位为1000）
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff) // 最后12位
    ]);
}

function start_heartbeat_running($pid, $cid) {
    return run($pid, $cid);
//    $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
//    $u = "$protocol://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
//    $a = explode('/', $u);
//    $a[count($a) - 1] = 'heartbeat.php';
//    $u = implode('/', $a);
//    $u .= "?pid=$pid&cid=$cid";
//    send_request($u, null, null, 500);
//    $n = 0;
//    while (true) {
//        if (is_heartbeat_running($pid, $cid))
//            return true;
//        usleep(1000 * 100);
//        $n += 1000 * 100;
//        if ($n >= 1000 * 1000 * 20)
//            return false;
//    }
}

function is_heartbeat_running($pid, $cid)
{
    return false;
//    return is_flag_file_valid($pid, $cid, 120);
}

function can_continue_heartbeat_running($pid, $cid) {
    return true;
//    return is_heartbeat_running($pid, $cid);
}

function is_flag_file_valid($pid, $cid, $interval) {
    $running_flag_file = get_running_flag_file($pid, $cid);
    $r = file_exists($running_flag_file);
    if ($r) {
        $r = time() - filemtime($running_flag_file) < $interval;
    }
    return $r;
}

function update_flag_filemtime($pid, $cid) {
//    if (!is_flag_file_valid($pid, $cid, 120 - 20)) {
//        $running_flag_file = get_running_flag_file($pid, $cid);
////    file_put_contents($running_flag_file, time(), LOCK_EX);
//        touch($running_flag_file);
//    }
}

function del_flag_file($pid, $cid) {
//    $running_flag_file = get_running_flag_file($pid, $cid);
//    if (file_exists($running_flag_file))
//        unlink($running_flag_file);
}

function get_running_flag_file($pid, $cid)
{
    return __DIR__."/gdy_{$pid}_{$cid}_running";
}

function save_to_cache($pid, $cid, $array)
{
    array_to_file($array, "gdy_cache_{$pid}_{$cid}.txt");
}

function load_from_cache($pid, $cid)
{
    $cacheFile = "gdy_cache_{$pid}_{$cid}.txt";
    $expireSeconds = 60 * 60 * 24;
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expireSeconds)) {
        file_to_array($cacheFile, $a);
        return $a;
    } else {
        return null;
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

function send_request($url, $params = null, $headers = null, $timeout_ms = null) {
    if ($params)
        $url .= '?'.http_build_query($params);
    $ch = curl_init();
    $optArray = array(
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
    );
    if ($headers)
        $optArray[CURLOPT_HTTPHEADER] = $headers;
    if ($timeout_ms)
        $optArray[CURLOPT_TIMEOUT_MS] = $timeout_ms;
    curl_setopt_array($ch, $optArray);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

//function write_log($msg) {
//    $logFile = 'gdy_log.txt';
//    $message = date('[Y-m-d H:i:s]') . " $msg\n";
//    file_put_contents($logFile, $message, FILE_APPEND);
//}