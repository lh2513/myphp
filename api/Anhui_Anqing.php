<?php

//电视
//https://www.zsaqnews.cn/html/rft/live.html?channelId=61005770c525480db84eed456cd093c1
//https://www.zsaqnews.cn/html/rft/live.html?channelId=b7287de089c6401f985ae27873fb34f8
//广播
//https://www.zsaqnews.cn/html/rft/live.html?channelId=18379921ab4844c3a0bacf9f5c433395
//https://www.zsaqnews.cn/html/rft/live.html?channelId=24e47353df194a3bb6022aa09de9d272

//$id = '61005770c525480db84eed456cd093c1';
$id = $_GET['id'];
$u = "https://www.zsaqnews.cn/rftapi/api/rft/getLiveChannelInfo?platform=h5&channelId=$id&siteId=&runType=test";
$c = file_get_contents($u);
$p = json_decode($c)->url;
header('Access-Control-Allow-Origin: *');
header('Location: '.$p);
