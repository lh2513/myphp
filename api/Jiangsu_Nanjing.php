<?php
$id = $_GET['id'] ?? '109152';
/*
来源http://kaniptv.com
新闻综合频道,?id=109152              
教育科技频道,?id=109153
十八·生活频道,?id=110094
文旅纪录频道?id=110093
少儿频道,?id=110095
*/
$js_code = @file_get_contents('http://www.nbs.cn/js/channel.js');
if (preg_match("/case\s+'{$id}'\s*:\s*(?:\/\/[^\n]*)?\s*videosrc\s*=\s*'([^']+)'/", $js_code, $m)) {
    $url = (strpos($m[1], '//') === 0) ? 'http:' . $m[1] : $m[1];
    header("Location: $url");
    exit;
}
?>
