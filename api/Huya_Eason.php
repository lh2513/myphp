<?php
/**
 * 虎牙斗鱼直播源PHP版本（标准查询参数格式）
 * 调用方式：
 *   虎牙：xxx.php?huya=11274154
 *   斗鱼：xxx.php?douyu=6863367
 *   可选参数：media=flv|hls（仅虎牙）
 */

error_reporting(0);

class LiveStream {
    private $backupVideos = [
        'errA' => [
            'https://vd3.bdstatic.com/mda-qgaep0yxh1ms6xee/1080p/cae_h264/1720693706111957694/mda-qgaep0yxh1ms6xee.mp4',
            'https://vd3.bdstatic.com/mda-rbja7tein2e9sqc4/1080p/cae_h264/1740091054502692061/mda-rbja7tein2e9sqc4.mp4',
        ],
        'errB' => [
            'https://cdn12.yzzy-tv-cdn.com/20221209/8943_e3bd0850/index.m3u8',
            'https://vip.ffzy-play6.com/20221019/118_1367b8f6/index.m3u8',
        ],
        'errC' => 'https://vd3.bdstatic.com/mda-qeni4xn7nx5kczzd/1080p/cae_h264/1716469219902832085/mda-qeni4xn7nx5kczzd.mp4'
    ];
    
    public function main() {
        // 检测请求头，判断是否来自播放器
        $isPlayer = $this->isMediaPlayerRequest();
        
        // 获取参数 - 使用标准查询参数格式
        $huyaId = isset($_GET['huya']) ? trim($_GET['huya']) : '';
        //$douyuId = isset($_GET['douyu']) ? trim($_GET['douyu']) : '';
        $media = isset($_GET['media']) ? trim($_GET['media']) : 'flv';
        
        // 检查参数冲突
        if ($huyaId && $douyuId) {
            return $this->returnError('参数冲突：不能同时指定虎牙和斗鱼房间号');
        }
        
        // 处理虎牙请求
        if ($huyaId) {
            $result = $this->goHuya($huyaId, $media);
            
            // 如果是播放器请求，直接重定向
            if ($isPlayer && isset($result['url']) && $result['success']) {
                $this->redirectToPlayUrl($result['url']);
                exit;
            }
            
            return $this->returnJson($result);
        }
        
        // 处理斗鱼请求
		/*
        if ($douyuId) {
            $result = $this->goDouyu($douyuId);
            
            // 如果是播放器请求，直接重定向
            if ($isPlayer && isset($result['url']) && $result['success']) {
                $this->redirectToPlayUrl($result['url']);
                exit;
            }
            
            return $this->returnJson($result);
        }
		*/
        
        // 没有提供房间号
        return $this->returnError('请提供房间号，使用格式：?huya=房间号 或 ?douyu=房间号');
    }
    
    private function goHuya($id, $media = 'flv') {
        // 随机选择CDN线路 (0-3)
        $n_rand = mt_rand(0, 3);
        
        $roomUrl = "https://mp.huya.com/cache.php?m=Live&do=profileRoom&roomid=" . urlencode($id);
        
        $response = $this->httpRequest($roomUrl);
        
        if (!$response) {
            return [
                'url' => $this->getRandomVideo($this->backupVideos['errA']),
                'error' => '虎牙API请求失败',
                'success' => false,
                'channel' => '虎牙',
                'room_id' => $id
            ];
        }
        
        $json = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'url' => $this->getRandomVideo($this->backupVideos['errA']),
                'error' => '虎牙API返回数据解析失败',
                'success' => false,
                'channel' => '虎牙',
                'room_id' => $id
            ];
        }
        
        if (!isset($json['status']) || $json['status'] !== 200) {
            return [
                'url' => $this->getRandomVideo($this->backupVideos['errA']),
                'error' => '虎牙房间无效',
                'success' => false,
                'channel' => '虎牙',
                'room_id' => $id
            ];
        }
        
        if (!isset($json['data']['realLiveStatus']) || $json['data']['realLiveStatus'] == 'OFF') {
            return [
                'url' => $this->getRandomVideo($this->backupVideos['errB']),
                'error' => '虎牙房间未直播',
                'success' => false,
                'channel' => '虎牙',
                'room_id' => $id
            ];
        }
        
        $data = $json['data'];
        
        // 获取必要参数
        $uid = isset($data['profileInfo']['uid']) ? $data['profileInfo']['uid'] : "";
        $streamname = isset($data['stream']['baseSteamInfoList'][0]['sStreamName']) ? 
                     $data['stream']['baseSteamInfoList'][0]['sStreamName'] : "";
        
        // 获取播放地址
        $urlObj = null;
        if (isset($data['stream'][$media]['multiLine'][$n_rand]['url'])) {
            $urlObj = $data['stream'][$media]['multiLine'][$n_rand]['url'];
        } elseif (isset($data['stream'][$media]['multiLine'][1]['url'])) {
            $urlObj = $data['stream'][$media]['multiLine'][1]['url'];
        } elseif (isset($data['stream']['flv']['multiLine'][$n_rand]['url'])) {
            $urlObj = $data['stream']['flv']['multiLine'][$n_rand]['url'];
            $media = 'flv';
        }
        
        if (!$urlObj) {
            return [
                'url' => $this->getRandomVideo($this->backupVideos['errA']),
                'error' => '无法获取虎牙播放地址',
                'success' => false,
                'channel' => '虎牙',
                'room_id' => $id
            ];
        }
        
        $burl = explode('?', $urlObj)[0];
        
        // 计算签名参数
        $seqid = (int)$uid + (time() * 1000);
        $seqid = (string)$seqid;
        $ctype = "huya_adr";
        $t = "102";
        $ss = md5($seqid . "|" . $ctype . "|" . $t);
        $wsTime = dechex(time() + 21600);
        $fm = "DWq8BcJ3h6DJt6TY_" . $uid . "_" . $streamname . "_" . $ss . "_" . $wsTime;
        $wsSecret = md5($fm);
        
        // 构建参数
        $params = [
            "wsSecret=" . $wsSecret,
            "wsTime=" . $wsTime,
            "ctype=" . $ctype,
            "seqid=" . $seqid,
            "uid=" . $uid,
            "fs=bgct",
            "ver=1",
            "t=" . $t
        ];
        
        $playUrl = $burl . "?" . implode("&", $params);
        
        return [
            'url' => $playUrl,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer' => 'https://www.huya.com/',
                'Origin' => 'https://www.huya.com'
            ],
            'success' => true,
            'channel' => '虎牙',
            'room_id' => $id,
            'format' => $media,
            'cdn_line' => $n_rand
        ];
    }
   /* 
    private function goDouyu($id) {
        // 尝试多个斗鱼API接口
        $apis = [
            [
                'url' => 'https://wxapp.douyucdn.cn/api/nc/stream/roomPlayer',
                'data' => "room_id=" . urlencode($id) . "&big_ct=cph-androidmpro&did=10000000000000000000000000001501&mt=2&rate=0",
                'method' => 'POST'
            ],
            [
                'url' => 'https://open.douyucdn.cn/api/RoomApi/room/' . urlencode($id),
                'method' => 'GET'
            ]
        ];
        
        foreach ($apis as $api) {
            $url = $api['url'];
            $method = $api['method'];
            $data = isset($api['data']) ? $api['data'] : null;
            $headers = ['User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'];
            
            if ($method == 'POST') {
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            }
            
            $response = $this->httpRequest($url, $method, $data, $headers);
            
            if ($response) {
                $jsonData = json_decode($response, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    // 提取播放地址
                    if (isset($jsonData['error']) && $jsonData['error'] === 0 && isset($jsonData['data']['live_url'])) {
                        return [
                            'url' => $jsonData['data']['live_url'],
                            'headers' => [
                                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                                'Referer' => 'https://www.douyu.com/'
                            ],
                            'success' => true,
                            'channel' => '斗鱼',
                            'room_id' => $id
                        ];
                    }
                }
            }
            
            usleep(200000);
        }
        
        return [
            'url' => $this->backupVideos['errC'],
            'error' => '斗鱼房间无效或未开播',
            'success' => false,
            'channel' => '斗鱼',
            'room_id' => $id
        ];
    }
	*/
    
    private function httpRequest($url, $method = 'GET', $data = null, $headers = []) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        // 默认headers
        $defaultHeaders = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ];
        
        $finalHeaders = array_merge($defaultHeaders, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $finalHeaders);
        
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        if ($httpCode != 200) {
            return false;
        }
        
        return $response;
    }
    
    private function getRandomVideo($videoArray) {
        if (is_array($videoArray)) {
            $index = array_rand($videoArray);
            return $videoArray[$index];
        }
        return $videoArray;
    }
    
    private function isMediaPlayerRequest() {
        // 检测User-Agent判断是否是播放器
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        
        if (empty($userAgent)) {
            return false;
        }
        
        // 常见的媒体播放器标识
        $playerKeywords = [
            'VLC', 'PotPlayer', 'MPC-HC', 'MPC-BE', 'KMPlayer', 'MPlayer',
            'ffmpeg', 'libvlc', 'Lavf', 'iina', 'QuickTime', 'CoreMedia',
            'Windows-Media-Player', 'Kodi', 'mxplayer', 'vlc'
        ];
        
        foreach ($playerKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return true;
            }
        }
        
        // 检查是否有Range请求头（流媒体请求）
        if (isset($_SERVER['HTTP_RANGE']) || isset($_SERVER['HTTP_ACCEPT_RANGES'])) {
            return true;
        }
        
        return false;
    }
    
    private function redirectToPlayUrl($url) {
        // 直接重定向到播放地址
        header('Location: ' . $url);
        exit;
    }
    
    private function returnJson($data) {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
    
    private function returnError($message) {
        return $this->returnJson([
            'error' => $message,
            'success' => false
        ]);
    }
}

// 创建测试页面（当没有参数时显示）
function showTestPage() {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>虎牙斗鱼直播源测试</title>
        <meta charset="utf-8">
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
            .test-case { margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 5px; }
            input { padding: 8px; width: 200px; margin: 5px; }
            button { padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
            button:hover { background: #0056b3; }
            pre { background: #eee; padding: 10px; border-radius: 5px; overflow: auto; }
            .example { background: #e8f4ff; padding: 10px; border-radius: 5px; margin: 10px 0; }
        </style>
    </head>
    <body>
        <h1>虎牙斗鱼直播源测试</h1>
        
        <div class="example">
            <h3>调用示例：</h3>
            <p>虎牙：<code>'.$_SERVER['PHP_SELF'].'?huya=11274154</code></p>
            <p>斗鱼：<code>'.$_SERVER['PHP_SELF'].'?douyu=6863367</code></p>
            <p>虎牙（HLS格式）：<code>'.$_SERVER['PHP_SELF'].'?huya=11274154&media=hls</code></p>
        </div>
        
        <div class="test-case">
            <h3>虎牙测试：</h3>
            <input type="text" id="huya-room" placeholder="输入虎牙房间号" value="11274154">
            <select id="huya-media">
                <option value="flv">FLV格式</option>
                <option value="hls">HLS格式</option>
            </select>
            <button onclick="testHuya()">测试虎牙</button>
            <pre id="huya-result">点击按钮测试</pre>
        </div>
        
        <div class="test-case">
            <h3>斗鱼测试：</h3>
            <input type="text" id="douyu-room" placeholder="输入斗鱼房间号" value="6863367">
            <button onclick="testDouyu()">测试斗鱼</button>
            <pre id="douyu-result">点击按钮测试</pre>
        </div>
        
        <script>
            function testHuya() {
                var roomId = document.getElementById("huya-room").value;
                var media = document.getElementById("huya-media").value;
                fetch("?huya=" + roomId + "&media=" + media)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("huya-result").textContent = JSON.stringify(data, null, 2);
                    })
                    .catch(error => {
                        document.getElementById("huya-result").textContent = "错误: " + error;
                    });
            }
            
            function testDouyu() {
                var roomId = document.getElementById("douyu-room").value;
                fetch("?douyu=" + roomId)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("douyu-result").textContent = JSON.stringify(data, null, 2);
                    })
                    .catch(error => {
                        document.getElementById("douyu-result").textContent = "错误: " + error;
                    });
            }
        </script>
    </body>
    </html>';
}

// 主程序
$live = new LiveStream();

// 如果没有参数，显示测试页面
if (empty($_GET)) {
    showTestPage();
    exit;
}

try {
    echo $live->main();
} catch (Exception $e) {
    echo json_encode([
        'error' => '服务器内部错误: ' . $e->getMessage(),
        'success' => false
    ]);
}