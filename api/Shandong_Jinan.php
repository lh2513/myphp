<?php
$id = $_GET['id'];
/*
济南新闻综合频道,1676
济南都市频道,1807
济南文旅体育频道,1842
济南生活频道,1855
济南少儿频道,2234
济南鲁中频道,1857
*/
$timestamp = time();
$url = "https://dlive.guangbocloud.com/api/public/third/channel/tv/page?size=10&page=1";
$query = parse_url($url, PHP_URL_QUERY);
parse_str($query, $queryArray);
$signature = md5(http_build_query(array_reverse($queryArray)) . '&timestamp=' . $timestamp . '&secret=401b38e85b0640b9a6d8f13ad4e1bcc4');
// 来源[url]http://此字符被系统屏蔽[/url]
$headers = [
    "X-DFSX-Timestamp: $timestamp",
    "X-DFSX-mainUsername: jntv",
    "X-DFSX-Signature: $signature"
];
$response = json_decode(makeRequest($url, $headers), true);
foreach ($response['data'] as $channel) {
    if ($channel['id'] == $id && isset($channel['push_play_urls'][1])) {
        header("Location: " . $channel['push_play_urls'][1]);
        exit;
    }
}
function makeRequest($url, $headers) {
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => implode("\r\n", $headers)
        ]
    ];
    $context = stream_context_create($opts);
    return file_get_contents($url, false, $context);
}
?>