<?php
error_reporting(E_ALL);
$liveId = $_GET['id'];
/*
永康新闻综合,1625061424267104
华溪频道,1625062755227231
磐安新闻综合,1722331404917192
磐安文化旅游,1722333472632211
银川公共(暂无直播),1731639141671136
银川生活(暂无直播),1731638744888001
银川文体(暂无直播),1728443786945132
余姚新闻综合,1718767387926372
余姚姚江文化,1728440422705076
钱江源国家公园,1721296664884226
瑞安新闻综合,1681269183807252
平湖新闻综合,1690167908295069
普陀电视台,1679466742638079
开化新闻综合,1721295786810240
东阳新闻综合,1681113308206040
东阳影视生活,1681113292195007
嘉兴新闻综合,1675942165226154
嘉兴文化影视,1675149625220101
嘉兴公共,1675149601192103
舟山新闻综合,1699001836208185
舟山公共,1699002430299200
和合频道,1617072208961931
玉环新闻综合,1728627049847070
景宁电视台,1644806967279134
常山新闻综合,1621306190044902
江山电视台,1623117710666335
怀远新闻综合（已下架）,1734491351341308
萧山综合,1678438003663132
象山新闻综合,1702360350551252
苍南新闻综合,1726543353849105
永嘉新闻综合（已下架）,1683366786327093
洞头电视台,1735612228127376
柯桥新闻综合,1640945211435051
嵊州新闻综合(暂无直播),1626935015913208
龙泉新闻综合,1721354859785237
松阳综合,1718675414025228
云和新闻综合（已下架）,1686705257187275
庆元电视台,1733812606413392
武义新闻综合,1707437639053211
青田电视台,1692755062081192
遂昌电视台,1708426592499070
龙游新闻综合,1703570877483349
衢江综合,1700720505174325
*/
$sign = md5($liveId.'NoFeelings');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.qukanvideo.com/h5/channel/view/item/AntiTheft/playUrl');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'origin: https://www.qukanvideo.com',
    'referer: https://www.qukanvideo.com/cloud/h5/'.$liveId,
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, "source=web&liveId=$liveId&sign=$sign");
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$playURL = $data['value']['url'];
//echo $playURL;
header("Location: {$playURL}");