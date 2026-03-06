<?php
header('Content-Type: text/plain; charset=utf-8');

class DouyuStream {
    private $roomId;
    private $did;
    private $cookies = [];
    
    public function __construct($roomId) {
        $this->roomId = $roomId;
        $this->getCookies();
        $this->extractDid();
    }
    
    private function getCookies() {
        $ch = curl_init("https://www.douyu.com/" . $this->roomId);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 5,
        ]);
        
        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        curl_close($ch);
        
        $lines = explode("\n", $headers);
        foreach ($lines as $line) {
            if (stripos($line, 'Set-Cookie:') === 0) {
                $cookieLine = substr($line, 12);
                $cookieParts = explode(';', $cookieLine);
                if (count($cookieParts) > 0) {
                    $cookiePair = explode('=', trim($cookieParts[0]), 2);
                    if (count($cookiePair) == 2) {
                        $this->cookies[trim($cookiePair[0])] = trim($cookiePair[1]);
                    }
                }
            }
        }
    }
    
    private function extractDid() {
        if (isset($this->cookies['dy_did'])) {
            $this->did = $this->cookies['dy_did'];
        } else {
            $this->did = substr(md5(microtime() . mt_rand()), 0, 32);
            $this->cookies['dy_did'] = $this->did;
        }
        
        if (!isset($this->cookies['mantine-color-scheme-value'])) {
            $this->cookies['mantine-color-scheme-value'] = 'light';
        }
    }
    
    private function getCookiesString() {
        $result = [];
        foreach ($this->cookies as $name => $value) {
            $result[] = $name . '=' . $value;
        }
        return implode('; ', $result);
    }
    
    private function getEncryptionKey() {
        $url = "https://www.douyu.com/wgapi/livenc/liveweb/websec/getEncryption?did=" . $this->did;
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [
                'authority: www.douyu.com',
                'referer: https://www.douyu.com/' . $this->roomId,
                'origin: https://www.douyu.com',
                'content-type: application/x-www-form-urlencoded',
                'x-requested-with: XMLHttpRequest',
            ],
            CURLOPT_COOKIE => $this->getCookiesString(),
            CURLOPT_TIMEOUT => 5,
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        return ($data && $data['error'] == 0) ? $data['data'] : false;
    }
    
    private function calculateAuth($keyData, $timestamp) {
        $key = $keyData['key'];
        $randStr = $keyData['rand_str'];
        $encTime = $keyData['enc_time'];
        
        $u = $randStr;
        for ($i = 0; $i < $encTime; $i++) {
            $u = md5($u . $key);
        }
        
        return md5($u . $key . $this->roomId . $timestamp);
    }
    
    private function updateDidFromStream($streamData) {
        if (isset($streamData['rtmp_live']) && preg_match('/did=([a-f0-9]{32})/', $streamData['rtmp_live'], $matches)) {
            $newDid = $matches[1];
            if ($newDid !== $this->did) {
                $this->did = $newDid;
                $this->cookies['dy_did'] = $this->did;
                return true;
            }
        }
        return false;
    }
    
    public function getStreamUrl() {
        $keyData = $this->getEncryptionKey();
        if (!$keyData) return false;
        
        $timestamp = time();
        $auth = $this->calculateAuth($keyData, $timestamp);
        
        $postData = [
            'enc_data' => $keyData['enc_data'],
            'tt' => $timestamp,
            'did' => $this->did,
            'auth' => $auth,
            'cdn' => '',
            'rate' => '',
            'hevc' => '0',
            'fa' => '0',
            'ive' => '0',
        ];
        
        $url = "https://www.douyu.com/lapi/live/getH5PlayV1/" . $this->roomId;
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [
                'authority: www.douyu.com',
                'referer: https://www.douyu.com/' . $this->roomId,
                'origin: https://www.douyu.com',
                'content-type: application/x-www-form-urlencoded',
                'x-requested-with: XMLHttpRequest',
            ],
            CURLOPT_COOKIE => $this->getCookiesString(),
            CURLOPT_TIMEOUT => 5,
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        if (!$data || $data['error'] != 0) return false;
        
        $streamData = $data['data'];
        
        if ($this->updateDidFromStream($streamData)) {
            $keyData = $this->getEncryptionKey();
            if ($keyData) {
                $auth = $this->calculateAuth($keyData, $timestamp);
                $postData['did'] = $this->did;
                $postData['auth'] = $auth;
                
                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => http_build_query($postData),
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_HTTPHEADER => [
                        'authority: www.douyu.com',
                        'referer: https://www.douyu.com/' . $this->roomId,
                        'origin: https://www.douyu.com',
                        'content-type: application/x-www-form-urlencoded',
                        'x-requested-with: XMLHttpRequest',
                    ],
                    CURLOPT_COOKIE => $this->getCookiesString(),
                    CURLOPT_TIMEOUT => 5,
                ]);
                
                $response = curl_exec($ch);
                curl_close($ch);
                
                $data = json_decode($response, true);
                if (!$data || $data['error'] != 0) return false;
                $streamData = $data['data'];
            }
        }
        
        if (isset($streamData['rtmp_url'], $streamData['rtmp_live'])) {
            return $streamData['rtmp_url'] . '/' . $streamData['rtmp_live'];
        }
        if (isset($streamData['hls_url']) && $streamData['hls_url']) {
            return $streamData['hls_url'];
        }
        
        return false;
    }
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    set_time_limit(10);
    $douyu = new DouyuStream(intval($_GET['id']));
    $streamUrl = $douyu->getStreamUrl();
    
    if ($streamUrl) {
        header("Location: " . $streamUrl);
        exit();
    } else {
        echo "获取失败\n";
    }
} else {
    echo "使用: ?id=房间号\n";
    echo "示例: ?id=2206405\n";
}
?>