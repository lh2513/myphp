<?php
/*
陕西卫视,http://陕西.php?id=1131
新闻资讯,http://陕西.php?id=1127
都市青春,http://陕西.php?id=1128
银龄频道,http://陕西.php?id=1129
秦腔频道,http://陕西.php?id=1130
体育休闲,http://陕西.php?id=1179
农林卫视,http://陕西.php?id=1126
乐家购物,http://陕西.php?id=1241
移动电视,http://陕西.php?id=1242
陕西FM106.6新闻广播,http://陕西.php?id=2134&type=radio
陕西FM89.6汽车调频广播,http://陕西.php?id=2135&type=radio
陕西FM91.6交通广播,http://陕西.php?id=2136&type=radio
陕西FM98.8音乐广播,http://陕西.php?id=2137&type=radio
陕西FM101.8都市广播,http://陕西.php?id=2139&type=radio
陕西FM105.5青少年广播,http://陕西.php?id=2140&type=radio
陕西FM107.8戏曲广播,http://陕西.php?id=2142&type=radio
陕西AM900农村广播,http://陕西.php?id=2143&type=radio
*/

$id = isset($_GET["id"]) ? $_GET["id"] : null;
if (!$id) {
    die("缺少 ID 参数。");
}

$url1 = "https://qidian.sxtvs.com/sxtoutiao/getLiveTvV11?cnwestAppId=3&cnwestLbs=%E5%8D%81%E5%A0%B0%E5%B8%82&deviceId=43b2bfb9-dffa-4c46-a75f-c754a057aba5&deviceInfo=samsung-SM-G9750-12&version=5.2.2&imeiId=f591ccd35e75394c292ea2fcf2b22af814508d0752b0f97d6fa77d1a7ec57b32&typeid=17";
$url2 = "https://qidian.sxtvs.com/sxtoutiao/getLiveRadioV11?cnwestLbs=%E5%8D%81%E5%A0%B0%E5%B8%82&typeid=18&deviceId=43b2bfb9-dffa-4c46-a75f-c754a057aba5&version=5.2.2&deviceInfo=samsung-SM-G9750-12&cnwestAppId=3&imeiId=%E6%9C%AA%E5%88%9D%E5%A7%8B%E5%8C%96";

$url = isset($_GET["type"]) && $_GET["type"] == "radio" ? $url2 : $url1;

$qian = @file_get_contents($url);
if ($qian === false) {
    die("从 URL 获取内容失败。");
}

$data = json_decode($qian, true);
if ($data['code'] !== 101) {
    die("获取数据失败: " . $data['msg']);
}

$found = false;

if ($url == $url1) {
    foreach ($data['data'] as $item) {
        if ($item['id'] == $id) {
            header("Location: " . $item['onlineUrlForandroid']);
            exit;
        }
    }
} elseif ($url == $url2) {
    foreach ($data['data']['radio'] as $item) {
        if ($item['id'] == $id) {
            header("Location: " . $item['radioUrlForandroid']);
            exit;
        }
    }
}

die("未找到对应的流。");
?>
