<?php
error_reporting(0);
//官网 https://jzb123.huajiaedu.com/tvs ---由不播不精彩特约赞助
$n = [
   //"cctv1" => 578, //CCTV1综合
   //"cctv2" => 485+94, //CCTV2财经
   "cctv1" => 578, //CCTV1综合
   "cctv2" => 579, //CCTV2财经
   "cctv3" => 580, //CCTV3综艺
   "cctv4" => 581, //CCTV4中文国际
   "cctv4a" => 595, //CCTV4中文国际-美洲
   "cctv4o" => 596, //CCTV4中文国际-欧洲
   "cctv5" => 582, //CCTV5体育
   "cctv5p" => 583, //CCTV5+体育赛事
   "cctv6" => 584, //CCTV6电影
   "cctv7"=> 585, //CCTV7国防军事
   "cctv8" => 586, //CCTV8电视剧
   "cctv9" => 587, //CCTV9纪录
   "cctv10" => 588, //CCTV10科教
   "cctv11" => 589, //CCTV11戏曲
   "cctv12" => 590, //CCTV12社会与法
   "cctv13" => 591, //CCTV13新闻
   "cctv14" => 592, //CCTV14少儿
   "cctv15" => 593, //CCTV15音乐
   "cctv17" => 594, //CCTV17农业农村

   "bjws" => 608, //北京卫视
   "dfws" => 597, //东方卫视
   "tjws" => 611, //天津卫视
   "cqws" => 607, //重庆卫视
   "hljws" => 621, //黑龙江卫视,暂时无法播放
   "jlws" => 601, //吉林卫视
   "lnws" => 620, //辽宁卫视
   "gsws" => 622, //甘肃卫视
   "qhws" => 605, //青海卫视
   "sxws" => 603, //陕西卫视
   "hbws" => 615, //河北卫视
   "sxiws" => 624, //山西卫视
   "sdws" => 613, //山东卫视,暂时无法播放
   "ahws" => 612, //安徽卫视
   "hnws" => 616, //河南卫视
   "hubws" => 604, //湖北卫视
   "hunws" => 609, //湖南卫视
   "jxws" => 602, //江西卫视
   "jsws" => 599, //江苏卫视
   "zjws" => 617, //浙江卫视
   "dnws" => 618, //东南卫视,暂时无法播放
   "gdws" => 598, //广东卫视
   "szws" => 606, //深圳卫视
   "gxws" => 614, //广西卫视
   "gzws" => 619, //贵州卫视
   "scws" => 610, //四川卫视   
   "xjws" => 623, //新疆卫视,频道内容与实际不符
   "hinws" => 600, //海南卫视
   ];
$id = $_GET['id']??'cctv1';
$fmt = $_GET['fmt']??'hls';//hls,flv

$url = "https://jzb123.huajiaedu.com/prod-api/iptv/getIptvList?liveType&deviceType=1";
$data = json_decode(file_get_contents($url),1)['list'];
foreach($data as $v){
   if($n[$id] == $v['id'])
   $m3u8 = preg_replace('/https/','http',$v['play_source_url']);
   $flv = preg_replace('/.m3u8/','.flv',$m3u8);
   }
if($fmt == 'hls'||$fmt == '') {
   header("location:".$m3u8);
   //echo $m3u8;
   }
if($fmt == 'flv') {
   header("location:".$flv);
   //echo $flv;
   }
?>