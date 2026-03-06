<?php
//Written by Wheiss
//http://127.0.0.1/ybtv.php?id=ybws
error_reporting(0);
date_default_timezone_set("PRC");
$id = $_GET['id']??'ybws';
$m = [
        "ybws"=>"CYS",//延边卫视
        "ybtv1"=>"ybtv1",//延边朝鲜语综合
        "ybtv2"=>"ybtv2",//延边汉语综合
        "ybjtgb"=>"FM1059",//延边交通文艺广播
        "yblygb"=>"FM1046",//延边旅游广播
        "ybxwgb"=>"FM983",//延边新闻综合广播
];
$n = [
        "iybws"=>"cys",//延边卫视
        "iybtv1"=>"ybtv1",//延边朝鲜语综合
        "iybtv2"=>"ybtv2",//延边汉语综合
        "iybwyshgb"=>"am1206",//延边文艺生活广播
        "iybxwzhgb"=>"fm1023",//延边新闻综合广播
        "iybksgb1"=>"vradio",//延边可视广播*
        "iybksgb2"=>"vraido2",//延边可视广播2*
];
if (isset($m[$id])){
        if (strpos($m[$id],'FM')!==false) {
                $type = 'audio';
        } else {
                $type = 'video';
        }
        $url = "https://srs.iyb983.cn/{$type}/{$m[$id]}/index.m3u8";
} else if (in_array($id,array_keys($n))){
        $html_url = "http://us01.125011.xyz/http.php/https://www.iybtv.com/{$n[$id]}/index.html";
        $html_data = curl_get($html_url);//https://live.ybtvyun.com/video/s10016-5adcf2b11d0d/index.m3u8
        if ($html_data === false){
                die("无法读取html文件内容");
        }
        $pattern = "/a:'(http.+)',\/\//";
        preg_match($pattern,$html_data,$match);
        if (!isset($match[1])){
                die("未匹配到url");
        }
        $url = $match[1];
} else {
        die("id不存在");
}
$playseek = $_GET['playseek']??'';
$starttime = $_GET['starttime']??'';
$endtime = $_GET['endtime']??'';
if (empty($playseek) && empty($starttime)) {
        header("location: {$url}");
} else {
        if ($playseek) {
                $playseekArray = explode('-',$playseek);
                $starttime = strtotime($playseekArray[0]);
                $endtime = strtotime($playseekArray[1]);
        }
        $jsonData = file_get_contents('https://api.wheiss.com/json/ybtv.json');
        $jsonArray = json_decode($jsonData,true);
        $url = $jsonArray['url'];
        $times = $jsonArray['times'];
        $offset = $jsonArray['offset'];
        $path = dirname($url).'/';
        $start = intval($starttime/$times-$offset);
        $end = intval($endtime/$times-$offset);
        $m3u8 = "#EXTM3U".PHP_EOL."#EXT-X-VERSION:3".PHP_EOL."#EXT-X-ALLOW-CACHE:YES".PHP_EOL."#EXT-X-TARGETDURATION:7".PHP_EOL."#EXT-X-MEDIA-SEQUENCE:{$start}".PHP_EOL;//前5行
        for (; $start < $end; $start++) {
                $m3u8 .= "#EXTINF:{$times},".PHP_EOL."{$path}{$start}.ts".PHP_EOL;
        }
        $m3u8 .= "#EXT-X-ENDLIST";//结束标志
        header("Content-Type: application/vnd.apple.mpegurl");
        header("Content-Disposition: inline; filename=index.m3u8");
        echo $m3u8;
}
exit;

function curl_get($url,$origin=''){
        if (!$origin){
                preg_match('/https?:\/\/[^\/\n]+/',$url,$matches);
                $origin = $matches[0];
        }
        $referer = $origin.'/';
        $headers = [
                "Connection: keep-alive",
                "Referer: $referer",
                "Origin: $origin",
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0',
                "Accept-Language: en-US,en;q=0.9"
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch);
        if (curl_error($ch)) {
                return false;
        } else {
                curl_close($ch);
                return $response;
        }
}
?>