<?php
//韶关新闻综合
$html = @file_get_contents('https://www.sgmsw.cn/mobile/tvinfo?id=SB05RIYZOU8JR418AUQOF62CAJQ08D0E');
if ($html && preg_match('/src:\s*"(\/videos\/tv\/[^"]+\.m3u8)"/', $html, $match)) {
    header('Location: https://www.sgmsw.cn' . $match[1]);
}
?>