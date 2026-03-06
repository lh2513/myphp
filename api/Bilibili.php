<?php
//使用方法     http://127.0.0.1/bili.php?id=1788391626
const INIT_URL = "https://api.live.bilibili.com/room/v1/Room/room_init";
const INFO_URL = "https://api.live.bilibili.com/xlive/web-room/v1/index/getInfoByRoom?room_id=";
const PLAY_URL = "https://api.live.bilibili.com/xlive/web-room/v2/index/getRoomPlayInfo";
const COOKIE = "LIVE_BUVID=AUTO6016498536114225; SESSDATA=3abcdd4f%2C1722328666%2C76f94%2A21CjDi_HRAEz7xhkHGKSdJMGt3p27W4njJY1wxhY02lt6Hkg4MlX_of6nrLHtdsOXqoiwSVktRS3Y0blZLNWllUnUtUU8zV1lLZlhMTEJKQ3JkTjlBR1poYUNDOWxjYlpBLUVZWjJaaHRNejhPYUJYNWwxVWI3dWVacXF4STJJWGhfWGwyMlJjdnlBIIEC; DedeUserID=76533767; DedeUserID__ckMd5=16952989ea20eda4; _uuid=952FFA19-DB106-DF32-8A710-A5FBF10FB8D10381135infoc";
class BiliBiliClient {
    public function get($rid, $headers = null) {
        $response = $this->request(INIT_URL, ['id' => $rid], $headers);
        if (!isset($response['data']['live_status']) || $response['data']['live_status'] != 1) {
            return null;
        }
        $rid = $response['data']['room_id'];
        $streamInfo = $this->getBiliStreamInfo($rid, 10000);
        $max = 0;
        foreach ($streamInfo as $data) {
            $acceptQn = $data['format'][0]['codec'][0]['accept_qn'];
            foreach ($acceptQn as $qn) {
                $max = max($qn, $max);
            }
        }
        if ($max != 10000) {
            $streamInfo = $this->getBiliStreamInfo($rid, $max);
        }
        $urls = [];
        foreach ($streamInfo as $data) {
            foreach ($data['format'] as $format) {
                foreach ($format['codec'] as $codec) {
                    $baseUrl = $codec['base_url'];
                    foreach ($codec['url_info'] as $urlInfo) {
                        $host = $urlInfo['host'];
                        $extra = $urlInfo['extra'];
                        $urls[] = $host . $baseUrl . $extra;
                    }
                }
            }
        }
        $workingUrl = $this->getFirstWorkingUrl($urls);
                if ($workingUrl) {
                        header('location:'.$workingUrl);
                        exit;
                } else {
                        echo "No working URLs found.";
                }
    }
        private function getFirstWorkingUrl($urls) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);  // 设置超时时间为5秒
                curl_setopt($ch, CURLOPT_NOBODY, true);  // 只检查HTTP头部，不下载整个页面
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
                // 添加这两行来忽略 SSL 证书验证
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                // 设置 cookie
                curl_setopt($ch, CURLOPT_COOKIE, COOKIE);
                foreach ($urls as $url) {
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_exec($ch);
                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        if ($httpCode == 0) {
                                echo "cURL error for URL $url: " . curl_error($ch) . "\n";
                        }
                        // 如果 HTTP 状态码为 200 (OK)，则认为此 URL 是可访问的
                        if ($httpCode == 200) {
                                curl_close($ch);
                                return $url;
                        }
                }
                curl_close($ch);
                return null;  // 如果没有可访问的 URL，则返回null
        }
    private function getBiliStreamInfo($rid, $qn) {
        $params = [
            'room_id' => $rid,
            'protocol' => '0,1',
            'format' => '0,1,2',
            'codec' => '0,1',
            'qn' => $qn,
            'platform' => 'h5',
            'ptype' => 8
        ];
        $response = $this->request(PLAY_URL, $params);
        return $response['data']['playurl_info']['playurl']['stream'] ?: [];
    }
    private function request($url, $params = [], $headers = null) {
        $ch = curl_init();
        $url = $url . '?' . http_build_query($params);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // 设置 cookie
                curl_setopt($ch, CURLOPT_COOKIE, COOKIE);
                // 添加这两行来忽略 SSL 证书验证
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
}
$client = new BiliBiliClient();
$result = $client->get($_GET['id'] ?: '23333830');

?>
