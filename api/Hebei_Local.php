<?php
// 获取频道ID参数
$id = isset($_GET['id'])?$_GET['id']:'bdxwzh';

// 频道映射数组 - 拼音缩写 => 频道标识
$n = [
    "bdxwzh" => "jybd",          // 保定新闻综合
    "bdgg" => "bdxw1",           // 保定公共
    "bdshjk" => "bddst",         // 保定生活健康
    "cdxwzh" => "cdsxwzhtv",     // 承德新闻综合
    "cdwhly" => "cdsggshtv",     // 承德旅游文化
    "hdxwzh" => "hdxwzh",        // 邯郸新闻综合
    "hdgg" => "hdgg",            // 邯郸公共
    "hdkjjy" => "hdkj",          // 邯郸科技教育
    "xtcssh" => "xtcsshpd",      // 邢台城市生活
    "xsdst" => "jiyunxushui123", // 徐水电视台
    "xinglongzh" => "xlzh",      // 兴隆综合
    //"xinlezh" => "XLTV",         // 新乐综合
    //"wczh" => "wczhpd",          // 围场综合
    "wdds" => "wddst",           // 望都电视台
    "slxw" => "slxw",            // 双滦新闻
    //"slys" => "slys",            // 双滦影视
    "sxxwzh" => "SXTV1",         // 涉县新闻综合
    "shzh" => "jiyunsanhe3",     // 三河综合
    "rqxwzh" => "rqtv1",         // 任丘新闻综合
    "qyxw" => "qyxwpd",          // 清苑综合
    "qysh" => "qyshpd",          // 清苑综合
    "qhxwzh" => "qinghe",        // 清河新闻综合
    //"qhjjzy" => "qinghe1",       // 清河经济综艺
    "qxxw" => "qxtvlive1",       // 青县新闻
    "pqzh" => "pqzh",            // 平泉综合
    "pqys" => "pqys",            // 平泉影视
    "npzh" => "npdspd",          // 南皮综合
    "nhzh" => "NHTV1",           // 南和综合
    "nhys" => "NHTV2",           // 南和影视
    "ngdst" => "ngtv1",          // 南宫电视台
    "lqzh" => "luquanyi",        // 鹿泉综合
    "lqgbdst" => "luquaner",     // 鹿泉区广播电视台
    "lhzh" => "lhtv",            // 隆化综合
    "lxzh" => "lx1",             // 临西综合
    "lxsh" => "lx2",             // 临西生活
    "lyzh" => "jiyunlaiyuan",    // 涞源综合
    "lsdst" => "lsdst",          // 涞水综合
    "gyrm" => "gaoyiyitao",      // 高邑融媒
    "fnzh" => "fengningzonghe",  // 丰宁综合
    //"fnyl" => "fengningyulepindao", // 丰宁娱乐
    "dzxwzh" => "xxzhpd",        // 定州新闻综合
    //"dzsh" => "shpd",            // 定州生活
    //"dzgxys" => "yspd",          // 定州国学影视
    "cldst" => "clzhpd"          // 昌黎电视台
];

// 检查频道ID是否存在
if (!isset($n[$id])) {
    $id = 'bdxwzh'; // 如果频道ID不存在，默认返回保定新闻综合
}

// 构建直播源URL
$streamUrl = "http://jwcdnqx.hebyun.com.cn/live/{$n[$id]}/1500k/tzwj_video.m3u8";

// 重定向到直播源
header('Location: ' . $streamUrl);
exit;
?>