<?php

// 需要将本地播放器的User-Agent设置为okHttp/Mod-1.5.0.0,才能正常收看!

header("Content-Type: application/json; charset=utf-8");
$id = $_GET["id"] ?? "cctv1";
$channelMap = [
    'cctv1' => 'CCTV1综合',
    'cctv2' => 'CCTV2财经',
    'cctv3' => 'CCTV3综艺',
    'cctv4' => 'CCTV4中文国际',
    'cctv5' => 'CCTV5体育',
    'cctv5p' => 'CCTV5+体育赛事',
    'cctv6' => 'CCTV6电影',
    'cctv7' => 'CCTV7国防军事',
    'cctv8' => 'CCTV8电视剧',
    'cctv9' => 'CCTV9纪录',
    'cctv10' => 'CCTV10科教',
    'cctv11' => 'CCTV11戏曲',
    'cctv12' => 'CCTV12社会与法',
    'cctv13' => 'CCTV13新闻',
    'cctv14' => 'CCTV14少儿',
    'cctv15' => 'CCTV15音乐',
    'cctv17' => 'CCTV17农业农村',
    'cctv4e' => 'CCTV4欧洲',
    'cctv4a' => 'CCTV4美洲',
    'cgtn' => 'CGTN',
    'cgtnar' => 'CGTN阿拉伯语',
    'cgtnsp' => 'CGTN西班牙语',
    'cgtnfr' => 'CGTN法语',
    'cgtnru' => 'CGTN俄语',
    'cgtndoc' => 'CGTN外语纪录',
    'lgs' => '老故事',
    'fxzl' => '发现之旅',
    'zxs' => '中学生',
    'cctv1mcp' => 'cctv1-MCP',
    'cctv2mcp' => 'cctv2-MCP',
    'cctv3mcp' => 'cctv3-MCP',
    'cctv4mcp' => 'cctv4-MCP',
    'cctv5mcp' => 'cctv5-MCP',
    'cctv5pmcp' => 'cctv5p-MCP',
    'cctv6mcp' => 'cctv6-MCP',
    'cctv7mcp' => 'cctv7-MCP',
    'cctv8mcp' => 'cctv8-MCP',
    'cctv9mcp' => 'cctv9-MCP',
    'cctv10mcp' => 'cctv10-MCP',
    'cctv11mcp' => 'cctv11-MCP',
    'cctv12mcp' => 'cctv12-MCP',
    'cctv13mcp' => 'cctv13-MCP',
    'cctv14mcp' => 'cctv14-MCP',
    'cctv15mcp' => 'cctv15-MCP',
    'cctv16mcp' => 'cctv16-MCP',
    'cctv17mcp' => 'cctv17-MCP',
    'cctv4kmcp' => 'cctv4k-MCP',
    'cctv8kmcp' => 'cctv8k-MCP',
    'dycjmcp' => '第一剧场-MCP',
    'fycjmcp' => '风云剧场-MCP',
    'hjjcmcp' => '怀旧剧场-MCP',
    'cgtnarmcp' => 'cgtnar-MCP',
    'cgtndocmcp' => 'cgtndoc-MCP',
    'cgtnmcp' => 'cgtn-MCP',
    'cgtnfrmcp' => 'cgtnfr-MCP',
    'cgtnrumcp' => 'cgtnru-MCP',
    'cgtnspmcp' => 'cgtnsp-MCP',
    'cetv1' => 'CETV1',
    'cetv2' => 'CETV2',
    'cetv4' => 'CETV4',
    'cetvmcp' => '中国教育电视台-MCP',
    'cwjd' => '重温经典',
    'wxty' => '五星体育',
    'dfws' => '东方卫视',
    'jsws' => '江苏卫视',
    'gdws' => '广东卫视',
    'bjws' => '北京卫视',
    'lnws' => '辽宁卫视',
    'hbws' => '河北卫视',
    'jxws' => '江西卫视',
    'hnws' => '河南卫视',
    'shxws' => '陕西卫视',
    'dwqws' => '大湾区卫视',
    'hubws' => '湖北卫视',
    'jlws' => '吉林卫视',
    'qhws' => '青海卫视',
    'dnws' => '东南卫视',
    'hainws' => '海南卫视',
    'hxws' => '海峡卫视',
    'nlws' => '中国农林卫视',
    'btws' => '兵团卫视',
    'nxws' => '宁夏卫视',
    'cqws' => '重庆卫视',
    'ssws' => '三沙卫视',
    'bjwsmcp' => '北京卫视-MCP',
    'hunwsmcp' => '湖南卫视-MCP',
    'jswsmcp' => '江苏卫视-MCP',
    'dfwsmcp' => '东方卫视-MCP',
    'zjwsmcp' => '浙江卫视-MCP',
    'hubwsmcp' => '湖北卫视-MCP',
    'tjwsmcp' => '天津卫视-MCP',
    'sdwsmcp' => '山东卫视-MCP',
    'lnwsmcp' => '辽宁卫视-MCP',
    'ahwsmcp' => '安徽卫视-MCP',
    'hljwsmcp' => '黑龙江卫视-MCP',
    'gzwsmcp' => '贵州卫视-MCP',
    'dnwsmcp' => '东南卫视-MCP',
    'cqwsmcp' => '重庆卫视-MCP',
    'jxwsmcp' => '江西卫视-MCP',
    'gdwsmcp' => '广东卫视-MCP',
    'hbwsmcp' => '河北卫视-MCP',
    'szwsmcp' => '深圳卫视-MCP',
    'jlwsmcp' => '吉林卫视-MCP',
    'hnwsmcp' => '河南卫视-MCP',
    'scwsmcp' => '四川卫视-MCP',
    'gxwsmcp' => '广西卫视-MCP',
    'shxwsmcp' => '陕西卫视-MCP',
    'sxwsmcp' => '山西卫视-MCP',
    'nmgwsmcp' => '内蒙古卫视-MCP',
    'qhwsmcp' => '青海卫视-MCP',
    'hainwsmcp' => '海南卫视-MCP',
    'nxwcmcp' => '宁夏卫视-MCP',
    'xzwsmcp' => '西藏卫视-MCP',
    'xjwsmcp' => '新疆卫视-MCP',
    'gswsmcp' => '甘肃卫视-MCP',
    'ynwsmcp' => '云南卫视-MCP',
    'btwsmcp' => '兵团卫视-MCP',
    'shxwzh' => '上海新闻综合',
    'shdfys' => '上视东方影视',
    'shdycj' => '上海第一财经',
    'njxwzh' => '南京新闻综合频道',
    'njjk' => '南京教科频道',
    'njsb' => '南京十八频道',
    'jstyxx' => '体育休闲频道',
    'jscs' => '江苏城市频道',
    'jsgj' => '江苏国际',
    'jsjy' => '江苏教育',
    'jsys' => '江苏影视频道',
    'jszy' => '江苏综艺频道',
    'jsggxw' => '公共新闻频道',
    'ycxwzh' => '盐城新闻综合',
    'haxwzh' => '淮安新闻综合',
    'tzxwzh' => '泰州新闻综合',
    'lygxwzh' => '连云港新闻综合',
    'sqxwzh' => '宿迁新闻综合',
    'xzxwzh' => '徐州新闻综合',
    'jsymkt' => '优漫卡通频道',
    'jyxwzh' => '江阴新闻综合',
    'ntxwzh' => '南通新闻综合',
    'yxxwzh' => '宜兴新闻综合',
    'lsxwzh' => '溧水新闻综合',
    'shxyl' => '陕西银龄频道',
    'shxdsqc' => '陕西都市青春频道',
    'shxtyxx' => '陕西体育休闲频道',
    'shxqq' => '陕西秦腔频道',
    'shxxwzx' => '陕西新闻资讯频道',
    'jscftx' => '财富天下',
    'zjxwzh' => '镇江新闻综合',
    'lngg' => '辽宁广播电视台公共频道',
    'lnsh' => '辽宁广播电视台生活频道',
    'lntyxx' => '辽宁广播电视台体育休闲频道',
    'lnysj' => '辽宁广播电视台影视剧频道',
    'nxwl' => '宁夏广播电视台文旅频道',
    'nxjj' => '宁夏广播电视台经济频道',
    'jdxgdy' => '经典香港电影',
    'kzjdyp' => '抗战经典影片',
    'xpfyt' => '新片放映厅',
    'chcymdy' => 'CHC影迷电影',
    'chcdzdy' => 'CHC动作电影',
    'chcjtyy' => 'CHC家庭影院',
    'hmxt' => '和美乡途轮播台',
    'gdys' => '南方影视',
    'zgtq' => '中国天气',
    'xm01' => '熊猫频道01高清',
    'xm1' => '熊猫频道1',
    'xm2' => '熊猫频道2',
    'xm3' => '熊猫频道3',
    'xm4' => '熊猫频道4',
    'xm5' => '熊猫频道5',
    'xm6' => '熊猫频道6',
    'xm7' => '熊猫频道7',
    'xm8' => '熊猫频道8',
    'xm9' => '熊猫频道9',
    'xm10' => '熊猫频道10',
    'zqzyp' => '最强综艺趴',
    'gdjjkt' => '嘉佳卡通',
    'jddh' => '经典动画大集合',
    'lnxdm' => '新动漫',
    'xdll' => '新动力量创一流',
    'zhtc' => '中华特产',
    'hqly' => '环球旅游',
    'tea' => '茶',
    'sszjd' => '赛事最经典',
    'ttmlh' => '体坛名栏汇',
    'shdy' => '四海钓鱼',
    'hnwssj' => '武术世界',
    'klcd' => '快乐垂钓',
    'gxmcp' => '国学频道-MCP',
    'sdjyws' => '山东教育'
];
$target = $channelMap[$id] ?? "CCTV1综合";
$m3uUrl = "想知道API地址?请发送请求邮件到baron0037@protonmail.com,并支付10USDT才给你看!";
$headers = [
    "User-Agent: okHttp/Mod-1.5.0.0",
    "Accept: */*",
    "Referer: https://live.ottiptv.cc/",
    "Origin: https://live.ottiptv.cc"
];
$ch = curl_init($m3uUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$m3u = curl_exec($ch);
curl_close($ch);
if (!$m3u) {
	echo json_encode(["code" => 500, "message" => "无法获取M3U8播放列表"]);
	exit;
}
$lines = preg_split("/\r\n|\r|\n/", $m3u);
$liveUrl = "";
for ($i = 0; $i < count($lines); $i++) {
	$line = trim($lines[$i]);
	if (strpos($line, "#EXTINF:") === 0 && strpos($line, $target) !== false) {
		if ($i + 1 < count($lines)) {
			$liveUrl = trim($lines[$i + 1]);
			break;
		}
	}
}
if (!$liveUrl) {
	echo json_encode([
	        "code" => 404,
	        "message" => "未找到频道播放地址",
	        "channel" => $target,
	        "channel_id" => $id
	    ]);
	exit;
}
echo json_encode([
    "code" => 200,
    "message" => "获取成功",
    "channel" => $target,
    "channel_id" => $id,
    "url" => $liveUrl,
    "headers" => [
        "User-Agent" => "okHttp/Mod-1.5.0.0",
        "Referer" => "https://live.ottiptv.cc"
    ]
]);

header('Location:'.$liveUrl);
?>