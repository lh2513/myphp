<?php
// 获取频道ID参数
$id = isset($_GET['id'])?$_GET['id']:'hljws';
$n = [
	"hljws" => "hljws_own",  // 黑龙江卫视
    "hljys" => "hljys_hd", // 黑龙江影视
    "hljwt" => "hljwy_hd",   // 黑龙江文体
	"hljds" => "dushi_hd",  // 黑龙江都市
    "hljxwfz" => "hljxw_hd", // 黑龙江新闻法治
    "hljnykj" => "hljgg_hd",   // 黑龙江农业科教
	"hljse" => "hljse_hd"  // 黑龙江少儿
    ];
	
$streamUrl = "https://idclive.hljtv.com:4430/live/$n[$id].m3u8";

header('Location: ' . $streamUrl);
exit;
?>