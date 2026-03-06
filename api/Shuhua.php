<?php
$html = file_get_contents('http://web.shtv.net.cn/MobileWeb/OnlineLive.aspx');
preg_match('/src="(http[^"]+\.m3u8[^"]*)"/i', $html, $m);
header('Location: ' . ($m[1] ?? 'about:blank'));
?>