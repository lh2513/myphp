<?php
/*
四川卫视,sc.php?id=1
四川新闻,sc.php?id=2
四川经济,sc.php?id=3
四川文化旅游,sc.php?id=4
四川影视文艺,sc.php?id=5
四川妇女儿童,sc.php?id=6
四川星空购物,sc.php?id=7
四川乡村,sc.php?id=8
康巴卫视,sc.php?id=9
*/

//https://www.sctv.com/watchTV
//https://iptv.cc/forum.php?mod=viewthread&tid=5856&highlight=%E5%9B%9B%E5%B7%9D
//https://www.utao.tv/
//https://bgithub.xyz/VonChange/utao/releases

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
    $u = 'http://api.vonchange.com/utao/sctv?tag='.$id;
    $r = file_get_contents($u);
    return $r;
}

function send_request($url, &$content_type)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.sctv.com/');
    $ua = 'Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; BLA-AL00 Build/HUAWEIBLA-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.132 MQQBrowser/8.9 Mobile Safari/537.36';
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    $res = curl_exec($ch);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $res;
}

function replace_ts_urls($m3u8_url, $m3u8_content)
{
    //$m3u8_content = ltrim($m3u8_content, "\xEF\xBB\xBF");
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