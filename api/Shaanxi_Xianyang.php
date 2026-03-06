<?php
/*
咸阳综合,xyzh
//咸阳公共,xygg
*/
$u = $_GET['url'] ?? '';
$c = $_GET['id'] ?? 'xyzh';
if ($u) {
    $t = strpos($u,'.ts') ? 'video/MP2T' : 'application/vnd.apple.mpegurl';
    header("Content-Type: $t");
    $ctx = stream_context_create(['http'=>['header'=>"Referer: https://www.sxxynews.com.cn/\r\n"]]);
    echo file_get_contents(urldecode($u), false, $ctx);
    exit;
}

$h = file_get_contents("https://www.sxxynews.com.cn/tvradio/{$c}pd.html");
preg_match('/videoUrl\s*=\s*[\'"]([^\'"]+)[\'"]/', $h, $m);
$ctx = stream_context_create(['http'=>['header'=>"Referer: https://www.sxxynews.com.cn/\r\n"]]);
$con = file_get_contents($m[1], false, $ctx);

header('Content-Type: application/vnd.apple.mpegurl');
$p = parse_url($m[1]);
$b = $p['scheme'].'://'.$p['host'];
$d = dirname($p['path']);
$s = $_SERVER['PHP_SELF'];

foreach(explode("\n", $con) as $l) {
    if(!$l || $l[0]=='#') echo "$l\n";
    else {
        if(strpos($l,'http')!==0) $l = $l[0]=='/' ? "$b$l" : "$b$d/$l";
        echo "$s?url=".urlencode($l)."\n";
    }
}