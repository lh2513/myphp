<?php
$id = $_GET['id'];//吉安综合-19814和吉安公共-19815
    $tenantId = 'a33433adbbe5d17cacaa7fcc97556ebc';
    $apiUrl = "https://www.jarmt.cn/cmsback/api/article/getMyArticleDetail?tenantId={$tenantId}&articleId={$id}";
   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   
    $response = curl_exec($ch);
    curl_close($ch);
   
    $data = json_decode($response, true);
    $play = $data['data']['videoUrl'] ?? null;
//echo $play;
header("Location: ".$play);
?>