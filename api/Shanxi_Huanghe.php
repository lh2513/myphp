<?php

/*
山西卫视,id=q8RVWgs
山西影视,id=Md571Kv
山西经济与科技,id=4j01KWX
山西文体生活,id=Y00Xezi
山西社会与法治,id=p4y5do9
黄河电视台,id=lce1mC4
*/

//https://apphhplushttps.sxrtv.com//television/live_wap_650.html
$id = $_GET['id'];
//$id = 'q8RVWgs';
$u = 'https://dyhhplus.sxrtv.com/apiv4.5/api/m3u8_notoken?channelid='.$id.'&site=53';
$c = file_get_contents($u);
$j = json_decode($c);
$p = $j->data->address;
header('Access-Control-Allow-Origin: *');
header('Location: '.$p);