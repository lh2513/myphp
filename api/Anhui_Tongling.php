<?php
$id = $_GET['id'];
$u = "http://appx.tlbts.com/mag/tv/v1/tv/tvList";
$c = file_get_contents($u);
$j = json_decode($c);
foreach ($j->list as $i) {
    if ($i->id == $id) {
        header('Access-Control-Allow-Origin: *');
        header('Location: ' . $i->link);
        break;
    }
}

/*
新闻频道,tl.php?id=10
教科频道,tl.php?id=8
*/