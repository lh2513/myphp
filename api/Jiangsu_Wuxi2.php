<?php
/*无锡明珠宽频
id=2 无锡新闻综合
id=3 无锡电视娱乐
id=4 无锡都市资讯
id=5 无锡电视生活
id=6 无锡电视5频道
id=8 无锡新闻广播
id=9 无锡交通频率
id=10无锡新闻综合广播
id=12无锡汽车音乐广播
id=13无锡梁溪之声广播
id=15无锡经济频率
id=17无锡都市生活广播
*/
$id = empty($_GET['id']) ? "2" : trim($_GET['id']);
$data = json_decode(file_get_contents("https://v2.thmz.com/m2o/channel/channel_info.php?id=$id"));
header('location:https:'.$data[0]->m3u8);

?>