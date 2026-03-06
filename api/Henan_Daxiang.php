<?php
$id = isset($_GET['id'])?$_GET['id']:'hnws';
$n = [
    'hnws' => 145, //河南卫视
    'hnds' => 141, //河南都市
    'hnms' => 146, //河南民生
    'hnfz' => 147, //河南法治
    'hndsj' => 148, //河南电视剧
    'hnxw' => 149, //河南新闻
    'htgw' => 150, //欢腾购物
    'hngg' => 151, //河南公共
    'hnxc' => 152, //河南乡村
//    'hngj' => 153, //河南国际
    'hnly' => 154, //河南梨园
    'wwbk' => 155, //文物宝库
    'wspd' => 156, //武术世界
    'jczy' => 157, //睛彩中原
    'ydxq' => 163, //移动戏曲
    'xsj' => 183, //象视界
    'gxpd' => 194, //国学频道

    'zzxw' => 197, //郑州新闻综合
    'ayxw' => 206, //安阳新闻综合
    'lhxw' => 221, //漯河新闻综合
    'kfxw' => 198, //开封新闻综合
    'lyxw' => 204, //洛阳新闻综合
    'pdsxw' => 205, //平顶山新闻综合
    'pyxw' => 219, //濮阳新闻综合
    'sqxw' => 224, //商丘新闻综合
    'hbxw' => 207, //鹤壁新闻综合
    'jy1' => 228, //济源一套
    'jzzh' => 209, //焦作综合
    'nyxw' => 223, //南阳新闻综合
    'smxxw' => 222, //三门峡新闻综合
    'xczh' => 220, //许昌综合
    'xxxw' => 208, //新乡新闻综合
    'xyxw' => 225, //信阳新闻综合
    'zkxw' => 226, //周口新闻综合
    'zmdxw' => 227, //驻马店新闻综合
];
$t = time();
$sign = hash('sha256', '6ca114a836ac7d73' . $t);
$header = [
    'timestamp:' . $t,
    'sign:' . $sign,
    'Referer: https://static.hntv.tv/',
];
$url = 'https://pubmod.hntv.tv/program/getAuth/channel/channelIds/1/'.$n[$id];
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_HTTPHEADER => $header,
]);
$data = curl_exec($ch);
curl_close($ch);
$d = json_decode($data, true);
$m3u8 = $d[0]['video_streams'][0];
$m3u8 = str_replace('http:', 'https:', $m3u8);
header('Access-Control-Allow-Origin: *');
header("Location: $m3u8");
header("Location: $m3u8");