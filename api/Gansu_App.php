<?php
$n = $_GET['n'];

$companyId = 'D407CA0C210D49DF';
$userId = '35'
    .mt_rand(1,9).mt_rand(1,9).mt_rand(1,9).mt_rand(1,9)
    .mt_rand(1,9).mt_rand(1,9).mt_rand(1,9).mt_rand(1,9)
    .mt_rand(1,9).mt_rand(1,9).mt_rand(1,9).mt_rand(1,9)
    .mt_rand(1,9)
    .$companyId;
$u = 'https://dazzle.gstv.com.cn/xyapi/api/xy/toc/v1/tvPlusQueryPage'
    .'?appCode=FABU_YUNSHI'
    .'&companyId='.$companyId
    .'&userId='.$userId
    .'&productId=063DD3A8567E4FEC8B11E66128D24764'
    .'&serviceCode=YUNSHI_XSGL'
    .'&currentPage=1'
    .'&pageNum=10';
$c = file_get_contents($u, false, stream_context_create(array(
    'http' => array(
        'header' => "User-Agent: Mozilla/5.0 (Linux; Android 12; SM-A5560 Build/V417IR; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/101.0.4951.61 Mobile Safari/537.36\r\n",
    )
)));
preg_match('/"videoUrl":"([^"]+)",[^}]*?"name":"[^"]*'.$n.'[^"]*"/', $c, $m);
$m3u8_path = parse_url($m[1], PHP_URL_PATH);

$t = time() + 1800;
$uuid = vsprintf('%04x%04x%04x4%03x%04x%04x%04x%04x', [
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), // 前8位
    mt_rand(0, 0xffff), // 中间4位
    mt_rand(0, 0x0fff), // 第13位固定为4，这里生成后3位
    mt_rand(0, 0x3fff) | 0x8000, // 第17位固定为8/9/a/b（0x8000确保最高位为1000）
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff) // 最后12位
]);
$m3u8 = 'https://hlss.gstv.com.cn'.$m3u8_path
    ."?auth_key=$t-$uuid-0-"
    .md5("$m3u8_path-$t-$uuid-0-8f60c8102d29fcd525162d02eed4566b");

header('Access-Control-Allow-Origin: *');
header('Location: '.$m3u8);

/* 不支持国外服务器，报403！
甘肃卫视,stgs.php?n=%E5%8D%AB%E8%A7%86
文化影视,stgs.php?n=%E5%BD%B1%E8%A7%86
公共应急,stgs.php?n=%E5%85%AC%E5%85%B1
少儿频道,stgs.php?n=%E5%B0%91%E5%84%BF
科教频道,stgs.php?n=%E7%A7%91%E6%95%99
移动电视,stgs.php?n=%E7%A7%BB%E5%8A%A8
证券服务,stgs.php?n=%E8%AF%81%E5%88%B8
*/