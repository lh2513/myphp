<?php
// 获取频道ID参数
$id = isset($_GET['id'])?$_GET['id']:'gzws';
$n = [
	"fhzw" => "cn",  // 凤凰中文
    "fhzx" => "info", // 凤凰资讯
    "fhhk" => "hk"   // 凤凰香港
    ];
	
$token = "id=cn&prefix";
/*
主力号码:prefix=86&phone=13256889895&pwd=Fan2345678
备用号码1:prefix=86&phone=13389247903&pwd=Llxxcc198
备用号码2:prefix=86&phone=13955036885&pwd=make123456MAKE
*/
$streamUrl = "http://tv.groupshare.com.cn/fhtv?id=$n[$id]&token=${token}";

header('Location: ' . $streamUrl);
exit;
?>