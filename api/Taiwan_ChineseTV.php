<?php
error_reporting(0);
$id = $_GET['id']??'cctv1';
$n = [
  'cctv1' => 'cctv1', // CCTV1
  'cctv2' => 'cctv2', // CCTV2
  'cctv3' => 'cctv3', // CCTV3
  'cctv4' => 'cctv4', // CCTV4
  'cctv5' => 'cctv-5', // CCTV5
  'cctv5p' => 'cctv5plus', // CCTV5+
  'cctv6' => 'cctv6', // CCTV6
  'cctv7' => 'cctv7', // CCTV7
  'cctv8' => 'cctv8hd', // CCTV8
  'cctv9' => 'cctv9hd', // CCTV9
  'cctv10' => 'cctv10hd', // CCTV10
  'cctv11' => 'cctv11', // CCTV11
  'cctv12' => 'cctv12', // CCTV12
  'cctv13' => 'cctv13', // CCTV13
  'cctv14' => 'cctv14', // CCTV14
  'cctv15' => 'cctv15', // CCTV15
  'cctv16' => 'cctv16', // CCTV16
  'cctv17' => 'cctv17', // CCTV17
  'cgtn' => 'CGTN', // CGTN

  'bjws' => 'beijing', // 北京卫视
  'dfws' => 'dongfangweishi_twn', // 东方卫视
  'hunws' => 'hunan_twn', // 湖南卫视
  'jsws' => 'jiangsu_twn', // 江苏卫视
  'zjws' => 'zhejiang_twn', // 浙江卫视
  'scws' => 'sichuan_twn', // 四川卫视
  'gdws' => 'guangdongweishi_twn', // 广东卫视
  'szws' => 'shenzhen', // 深圳卫视
  'dnws' => 'fujian', // 东南卫视
  'dwqws' => 'nanfang_twn', // 大湾区卫视
  'gxws' => 'guangxi', // 广西卫视

  'gdzj' => 'zhujiang', // 广东珠江

  'fhhk' => 'hkphoenix_twn', // 凤凰香港

  'tvbfc' => 'jade_twn', // TVB翡翠台
  'tvbmzt' => 'pearl_twn', // TVB明珠台
  'tvbwxxw' => 'inews_twn', // TVB无线新闻台
  'tvbj2' => 'tvbplus', // TVB J2
  'tvbxh' => 'Xinhe', // TVB星河台
  'tvbjd' => 'Tvbclassic', // TVB千禧经典台
  'tvbylxw' => 'Tvbentertainment', // TVB娱乐新闻台
  'tvbyz' => 'Tvbasia', // TVB亚洲台
  'ztzh' => 'ctizhonghe', // 中天综合
  'ztxw' => 'ctinews', // 中天新闻
  'ztyl' => 'ctient', // 中天娱乐
  'ztyz' => 'ctiasia_twn', // 中天亚洲
  'ffxw' => 'feifannews_twn', // 非凡新闻
  'tszh' => 'ttvzhonghe', // 台视综合
  'tsxw' => 'ttvnews_twn', // 台视新闻
  'ms' => 'ftvhd_taiwan', // 民视
  'msxw' => 'ftvnew_taiwan', // 民视新闻
  'mstwt' => 'ftvtaiwan_twn', // 民视台湾台
  'hyxw' => 'huanyuxinwen_twn', // 寰宇新闻
  'zs' => 'zhongshihd_twn', // 中视
  'zsxw' => 'zhongshinews_twn', // 中视新闻
  'hs' => 'ctshd_twn', // 华视
  'gsty' => 'ctv2_twn', // 公视台语台
  'dszh' => 'ettvzhonghe', // 东森综合
  'dsxw' => 'ettvnews', // 东森新闻
  'dscjxw' => 'ettvcaijing_twn', // 东森财经新闻
  'dsdy' => 'ettvmovie', // 东森电影台
  'dsxj' => 'ettvdrama', // 东森戏剧台
  'dsyp' => 'ettvwestern', // 东森洋片台
  'dazn1' => 'dazn1_twn', // DAZN1
  'hyzwt' => 'weishichinese_twn', // 华艺中文台
  'tvbs' => 'tvbs', // TVBS
  'tvbsxw' => 'tvbs_n', // TVBS新闻台
  'tvbshl' => 'tvbshuanle_twn', // TVBS欢乐台
  'eyely' => 'eyetvtravel_twn', // EYE TV旅游台
  'eyexj' => 'eyetvxiju_twn', // EYE TV戏剧台
  'jtgh' => 'jingtianintl_twn', // 靖天国会台
  'jtkt' => 'jingtiancartoon_twn', // 靖天卡通台
  'yytv' => 'yoyo_twn', // YoYo TV
  'mydy' => 'meiyamovie_twn', // 美亚电影台
  'tlc' => 'tlc_twn', // TLC

  'arl' => 'arirang_twn', // 阿里郎
  'bsyd1' => 'bosisport1_twn', // 博斯运动台Ⅰ
  'bsyd2' => 'bosisport2_twn', // 博斯运动台Ⅱ
  'bsgqt2' => 'bosigolf2_twn', // 博斯高球台Ⅱ
  'bswqt' => 'bositennis_twn', // 博斯网球台
  'bswxt' => 'bosiunlimited_twn', // 博斯无限台
  'wlzh' => 'videolandzonghe', // 纬来综合台
  'wlyl' => 'videolandmax', // 纬来育乐台
  'wlxj' => 'videolandtv', // 纬来戏剧台
  'wlty' => 'videolandsport', // 纬来体育台
  'wlxj' => 'videolandmovie', // 纬来电影台
  'wljp' => 'videolandjapan', // 纬来日本台
  'wljc' => 'videolandtvn', // 纬来精采台
  'eltasport1'  => 'eltasport1_twn', //爱尔达体育1台
  'eltasport2'  => 'eltasport2_twn', //爱尔达体育2台
  'eltasport3'  => 'eltasport3_twn', //爱尔达体育3台
  'dw' => 'dw_twn', // DW
  'slzh' => 'sanlizhonghe', // 三立综合台
  'sltw' => 'sanlitaiwan', // 三立台湾台
  'slxj' => 'sanlixiju_twn', // 三立戏剧台
  'sldh' => 'sanlidouhui_twn', // 三立都会台
  'ndxw' => 'niandainews_twn', // 年代新闻
  'hbo' => 'hbosignature_twn', // HBO Signature
  'hbohd' => 'hbohd_twn', // HBO HD
  'hlwd' => 'hollywoodmovies_twn', // Hollywood Movies
  'bdzh' => 'badazhonghe', // 八大综合台
  'bddy' => 'badafirst', // 八大第一台
  'bdxj' => 'badadrama', // 八大戏剧台
  'bdyl' => 'badaentertain', // 八大娱乐台
  'viu1' => 'viu1_twn', // Viu TV
  'viu6' => 'viusix_twn', // Viu Six
  'lhxj' => 'lunghuaxiju_twn', // 龙华戏剧台
  'lhox' => 'lunghuaidol_twn', // 龙华偶像台
  'lhjd' => 'lunghuajingdian_twn', // 龙华经典台
  'da' => 'daai_twn', // 大爱
  'good2' => 'good2_twn', // 好消息2台
  'afc' => 'afc_twn', // 亚洲美食台
  'mtv' => 'mtvhd_twn', // MTV Live
  'lxsd' => 'lungxiangtime_twn', // 龙祥时代
  'typd' => 'ctv18_twn', // 天映频道
  'elta' => 'eltadrama_twn', // ELTA综合
  'axn' => 'axn_twn', // AXN Taiwan

  ];
$c = "https://www.kds.tw/tv/china-tv-channels-online/cctv-general-channel/";
preg_match('/"url":"(.*?)"/',file_get_contents($c),$m);
$query = parse_url($m[1], PHP_URL_QUERY);
$live = "https://cdn.inteltelevision.com/4987/{$n[$id]}/playlist.m3u8?".$query;
$burl = dirname($live)."/";
header('Content-Type: application/vnd.apple.mpegurl');
print_r(preg_replace("/(.*?.ts)/i","$burl$1",get($live)));


function get($url){
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_REFERER, 'https://www.kds.tw/');
     $res = curl_exec($ch);
     curl_close($ch);
     return $res;
     }
?>