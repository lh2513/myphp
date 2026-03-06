<?php
$ts = isset($_GET['ts']) ? $_GET['ts'] : null;
if ($ts === null) {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $n = [
        '7'=>'7_7c833e',//桂阳新闻综合
        //'13'=>'13_46d84a',//邵东电视台
        '18'=>'18_3aa230',//中方县电视台
        '20'=>'20_68acda',//芷江电视台
        //'21'=>'21_f71392',//东安电视
        '23'=>'23_5d556a',//新晃综合
        //'24'=>'24_6c8648',//洪江区电视台
        //'26'=>'26_370917',//蓝山电视台,切片卡顿
        '27'=>'27_e9e1e5',//耒阳电视台
        '31'=>'31_226756',//汉寿综合
        //'33'=>'33_eb14e5',//祁阳电视
        '34'=>'34_5156ed',//靖州综合
        '38'=>'38_6f397d',//麻阳电视台
        '40'=>'40_1d0ed0',//永兴新闻综合
        '131'=>'131_acfb72',//双峰县电视台
        '134'=>'134_180adf',//花垣综合
        '143'=>'143_70175b',//吉首综合
        '144'=>'144_4efb38',//古丈电视台
        //'146'=>'146_f067fe',//龙山综合,无画面只有声音
        '148'=>'148_feda2f',//临武综合
        '149'=>'149_a8efd8',//汝城电视台
        '157'=>'157_66df9e',//桃源综合
        '158'=>'158_423c80',//攸县新闻综合
        '163'=>'163_2c7011',//沅江融媒
        '165'=>'165_13506b',//湘阴综合
        '166'=>'166_a4ad1b',//汨罗综合
        '168'=>'168_e04f1e',//平江综合
        '169'=>'169_b4d7a4',//城步电视
        '170'=>'170_49c556',//新化电视台
        //'171'=>'171_daca67',//新邵新闻综合
        '174'=>'174_6ab9f8',//桂东融媒
        //'180'=>'180_60f888',//衡东县电视台
        '182'=>'182_3c0dc6',//绥宁电视台
        '183'=>'183_554704',//衡阳县电视台
        '184'=>'184_e3af1a',//嘉禾新闻综合
        '185'=>'185_938292',//桑植新闻综合
        //'203'=>'203_10cdf5',//长沙县新闻综合
        //'204'=>'204_16cb0f',//安化综合
    ];
    $playUrl = "https://liveplay-srs.voc.com.cn/hls/tv/{$n[$id]}.m3u8";
    $m3u8Content = getdata($playUrl);
    if ($m3u8Content === false) {
        exit;
    }
    $scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") ? "https" : "http";
    $host = $_SERVER["HTTP_HOST"];
    $self = $_SERVER["PHP_SELF"]; 
    $updatedContent = preg_replace_callback(
        "/(.*?\.ts)/i",
        function ($matches) use ($scheme, $host, $self) {
            return $scheme . "://" . $host . $self . "?ts=https://liveplay-srs.voc.com.cn/hls/tv/" . $matches[1];
        },
        $m3u8Content
    );
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/vnd.apple.mpegurl');
    echo $updatedContent;
} else {
    $tsContent = getdata($ts);
    if ($tsContent === false) {
        exit;
    }
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: video/mp2t');
    echo $tsContent;
}



function getdata($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $headers = [
        'Referer: https://xhncloud.voc.com.cn/',
        'User-Agent: Mozilla/5.0 (Linux; Android 13; PGZ110 Build/TP1A.220905.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/103.0.5060.129 Mobile Safari/537.36'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return false;
    }
    curl_close($ch);
    return $result;
}