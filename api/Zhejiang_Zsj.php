<?php
/*调用
http://host/zsj.php?id=zjws&r=1080
*/
$id = isset($_GET['id'])?$_GET['id']:'zjws';
$r = isset($_GET['r'])?$_GET['r']:'1080'; //480,720,1080
$n = [
    'zjws' => '01',  //浙江卫视
    'zjqj' => '02',  //浙江钱江
    'zjjjsh' => '03',  //浙江经济生活
    'zjjkys' => '04',  //浙江教科影视
    'zjmsxx' => '06',  //浙江民生休闲
    'zjxw' => '07',  //浙江新闻
    'zjse' => '08',  //浙江少儿
    'zjgj' => '10',  //浙江国际
    'zjhyg' => '11',  //浙江好易购
    'zjzjjl' => '12',  //浙江之江纪录
    ];

  $path = '/live/channel'.$n[$id].$r.'Pnew.m3u8';
  $e = time();
  $key = 'CHWr9VybUeBZE1VB';
  $s= md5($path.'-'.$e.'-0-0-'.$key);
  $auth_key = $e.'-0-0-'.$s;
  //$arr = ['zwebl02','zwebl04','zwebl06'];
  $arr = ['zwebl02'];
  $ip1 = $arr[array_rand($arr)];
  $playurl = "http://$ip1.cztv.com$path?auth_key=$auth_key";
  header('location:'.$playurl);
  //echo $playurl;
?>