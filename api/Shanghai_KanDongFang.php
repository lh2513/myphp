<?php

/*
上海东方卫视4K,id=2030
上海新闻综合,id=20
上海新纪实,id=1600
上海魔都眼,id=1601
上海乐游频道,id=1745
上海第一财经,id=21
上海都市频道,id=18
上海五星体育,id=1605
爱上海,id=2029
*/

//https://summer.bestv.cn/smg_gongzhonghao_h5/#/home
$id = $_GET['id'];
//$id = 22;
$u = 'https://bp-api.bestv.cn/cms/api/live/channels';
$c = file_get_contents($u, false, stream_context_create(array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
    ),
    'http' => array(
        'method' => 'POST',
        'header' =>
            "Content-Type: application/json\r\n",
//            "token: \r\n".
//            "userId:\r\n",
        'content' => '{}'
    )
)));
$j = json_decode($c);
$dt = $j->dt;
foreach ($dt as $i) {
    if ($i->id == $id)
    {
        $p = $i->channelUrl;
        break;
    }
}
if (!isset($p))
    die('not found');
header('Access-Control-Allow-Origin: *');
header('Location: ' . $p);