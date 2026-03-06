<?php

/**
 * 单文件版：东方蓝（新蓝网）直播流解析工具
 * 转换自Java版本
 */

// -----------------------------------------------------------------------------
// Part 1: URL解析类 (ParsedURL)
// -----------------------------------------------------------------------------
/**
 * URL解析类，用于从URL中提取主机名和路径
 * 模拟Java中的ParsedURL类
 */
class ParsedURL
{
    public string $hostname;
    public string $pathname;

    /**
     * @param string $url 要解析的URL字符串
     * @throws InvalidArgumentException 如果URL无效
     */
    public function __construct(string $url)
    {
        $parsedUrl = parse_url($url);

        if ($parsedUrl === false || !isset($parsedUrl['host']) || !isset($parsedUrl['path'])) {
            throw new InvalidArgumentException("Invalid URL: " . $url);
        }

        $this->hostname = $parsedUrl['host'];
        $this->pathname = $parsedUrl['path'];
    }
}


// -----------------------------------------------------------------------------
// Part 2: 核心逻辑类 (DongFangLan)
// -----------------------------------------------------------------------------
/**
 * 东方蓝（新蓝网）直播流解析工具
 * 转换自Java版本
 */
class DongFangLan
{
    /**
     * 解析直播流URL并生成带鉴权参数的播放地址
     *
     * @param string $args 直播配置文件的JSON URL
     * @return string 带auth_key的播放地址
     * @throws Exception 如果网络请求失败或解析出错
     */
    public static function parseUrl(string $args): string
    {
        // --- 1. 获取JSON内容 ---
        // 优先尝试使用 Guzzle (如果已安装)
        if (class_exists('\GuzzleHttp\Client')) {
            $client = new \GuzzleHttp\Client();
            try {
                $response = $client->get($args);
                $body = $response->getBody()->getContents();
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                throw new Exception("HTTP request failed: " . $e->getMessage());
            }
        } else {
            // 备用方案：使用 file_get_contents
            // 注意：此方法可能不适用于所有服务器环境（如需要特殊代理或证书）
            $body = @file_get_contents($args);
            if ($body === false) {
                throw new Exception("Failed to get content from URL using file_get_contents. URL might be unreachable or allow_url_fopen is disabled.");
            }
        }

        // --- 2. 解析JSON并提取播放URL ---
        $jsonObject1 = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Failed to decode JSON: " . json_last_error_msg());
        }

        $playurl = $jsonObject1['playurl'];
        $dispatch = $playurl['dispatch'][0];
        $videoUrl = $dispatch['url'][0];
        $ali = $videoUrl['ali'][0];

        // 默认URL
        $url = "https://zwebl02.cztv.com/live/channel041080Pnew.m3u8";

        // 按优先级选择清晰度
        if (isset($ali['蓝光'])) {
            $url = $ali['蓝光'];
        } elseif (isset($ali['超清'])) {
            $url = $ali['超清'];
        } elseif (isset($ali['标清'])) {
            $url = $ali['标清'];
        } elseif (isset($ali['音频'])) {
            $url = $ali['音频'];
        }

        // --- 3. 生成鉴权参数 ---
        $e = time();
        $parsedURL = new ParsedURL($url);
        $hostname = $parsedURL->hostname;
        $pathname = $parsedURL->pathname;

        // 域名和密钥映射
        $keys = [
            "zappl01.cztv.com" => "F7nW84dyAfswpyB0",
            "zwebl01.cztv.com" => "CHWr9VybUeBZE1VB",
            "zhfivel01.cztv.com" => "9T08yiAoqM4eeCwV",
            "zappl02.cztv.com" => "F7nW84dyAfswpyB0",
            "zwebl02.cztv.com" => "CHWr9VybUeBZE1VB",
            "zhfivel02.cztv.com" => "9T08yiAoqM4eeCwV",
            "zwebl03.cztv.com" => "CHWr9VybUeBZE1VB",
            "zwebl04.cztv.com" => "CHWr9VybUeBZE1VB",
            "zwebl05.cztv.com" => "CHWr9VybUeBZE1VB",
            "zwebl06.cztv.com" => "CHWr9VybUeBZE1VB",
            "zwebl07.cztv.com" => "CHWr9VybUeBZE1VB",
            "zhfivel03.cztv.com" => "9T08yiAoqM4eeCwV",
            "zhfivel04.cztv.com" => "9T08yiAoqM4eeCwV",
            "zhfivel05.cztv.com" => "9T08yiAoqM4eeCwV",
            "zhfivel06.cztv.com" => "9T08yiAoqM4eeCwV",
            "zhfivel07.cztv.com" => "9T08yiAoqM4eeCwV",
        ];

        if (!isset($keys[$hostname])) {
            throw new Exception("Key not found for hostname: " . $hostname);
        }
        $key = $keys[$hostname];

        // 生成随机字符串 (替代 UUID)
        $rand = md5(uniqid(mt_rand(), true));
        $uid = "0";

        // 生成MD5签名
        $signString = $pathname . "-" . $e . "-" . $rand . "-" . $uid . "-" . $key;
        $javaMD5 = md5($signString);

        // 组装auth_key
        $authKey = $e . "-" . $rand . "-" . $uid . "-" . $javaMD5;

        // 返回最终的URL
        $finalUrl = $url . "?auth_key=" . $authKey;
        
        echo $finalUrl . PHP_EOL;

        return $finalUrl;
    }
}
# 浙江卫视-101,钱江都市-102,经济生活-103,教科影视-104,民生休闲-106,浙江新闻-107,浙江少儿频道-108,浙江国际-110,浙江好易购-111,之江纪录-112.json
$id = $argv[1] ?? $_GET['id'] ?? '101';
$irl = DongFangLan::parseUrl("https://streamlive.cztv.com/newplayer/live/pc/config/$id.json");
header('location:'.$irl);

