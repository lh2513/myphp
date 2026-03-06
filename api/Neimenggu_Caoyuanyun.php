<?php
$id = isset($_GET['id'])?$_GET['id']:'dmzh';
$n = [

  'zgezh' => ['zge','25'], //准格尔旗综合
  'dmzh' => ['yxdm','12'], //达茂综合
  'nmzh' => ['nmqrmt','2'], //奈曼综合
  'ewkzh' => ['ewkrm','12'], //鄂温克综合

];

$url=json_decode(file_get_contents("http://".$n[$id][0].".nmgcyy.com.cn/tvradio/Tv/getTvInfo?tv_id=".$n[$id][1]));
$playurl=$url->data->url;

header('location:'.$playurl);
?>