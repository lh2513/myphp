<?php  

$id = isset($_GET['id'])?$_GET['id']:'bjyq';
$n = [
    'ywxwzh' => ["a6edb25e0b944b449f26c881e8c68b85", "www.media.xinhuamm.net/", "b496650d5db94435a690db7326131dc9"],// 浙江-义乌新闻综合
    'ywsmpd' => ["dbc6a686692249379d912943bcec99f1", "www.media.xinhuamm.net", "b496650d5db94435a690db7326131dc9"],// 浙江-义乌商贸频道
    'ywwtpd' => ["6219f5d02db840e7b2f7ccda32bc99d3", "www.media.xinhuamm.net", "b496650d5db94435a690db7326131dc9"],// 浙江-义乌文艺频道
  
];


$header = array(

    'Referer: https://'.$n[$id][1].'/html/rft/live.html?channelId='.$n[$id][0].'&type=1&siteId='.$n[$id][2],
    'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
    // 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
  );

// 设置请求的 URL  
$url = "https://".$n[$id][1]."/rftapi/api/rft/getLiveChannelInfo";  
// 设置 POST 请求的数据  
$data = array(  
    'platform' => 'h5',  
    'channelId' => $n[$id][0],  
    'siteId' => $n[$id][2], 
    'runType' => 'test',  
);  

$response = get_data($url, $data, $header);
echo $response;
// 检查请求是否成功  
if ($response === false) {  
  $error = curl_error($ch);  
  echo "请求失败: " . $error;  
} else {  
  // 处理响应数据  
  echo "响应: " . $response;  
  $data = json_decode($response, true);
  $playurl = $data['url'];
  echo $playurl;
  header('Location:'.$playurl);
} 

function get_data($url, $data, $header){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
?>