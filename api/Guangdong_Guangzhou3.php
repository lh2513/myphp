<?php
    $id = $_GET['id'] ?? 'zhonghe';
    $programs = [
        'zhonghe' => 0,     //广州综合
        'xinwen' => 1,      //广州新闻
        'fazhi' => 2,     //广州法治
        'jingsai' => 3,     //广州竞赛
        'yingshi' => 4,       //广州影视
        'shenghuo' => 5,    //广州南国都市
    ];
    $url = 'https://gzbn.gztv.com:7443/plus-cloud-manage-app/liveChannel/queryLiveChannelList?type=1';
    $headers = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML,like Gecko) Chrome/99.0.4844.84 Safari/537.36 HBPC/12.1.3.3',
        'Host: gzbn.gztv.com',
        'Origin: gzbn.gztv.com',
        'Referer: gzbn.gztv.com',
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $data = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($data)->data;
    $index = $programs[$id];
    $playUrl = $result[$index]->httpUrl ?? '';
    header('location:'.$playUrl);
?>