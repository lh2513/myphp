<?php
if (isset($_GET['ts_url'])) {
    handle_ts_proxy();
    exit;
}

$id = $_GET['id'] ?? '';
$use_proxy = isset($_GET['r']) ? (int)$_GET['r'] : 0;

if (!$id) exit;

$info = get_m3u8_info($id);
if (!$info || empty($info['url'])) exit;

$content = get_m3u8($info['url']);
if ($use_proxy) {
    $content = replace_ts_with_proxy($info['url'], $content, $id);
} else {
    $content = fix_ts_paths($info['url'], $content);
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/vnd.apple.mpegurl");
echo $content;

function handle_ts_proxy() {
    $ts_url = urldecode($_GET['ts_url']);
    $id = $_GET['id'] ?? '';
    
    if (!$ts_url || !filter_var($ts_url, FILTER_VALIDATE_URL)) exit;
    
    $url_parts = parse_url($ts_url);
    if (!$url_parts || !isset($url_parts['host'])) exit;
    
    $client_info = get_cache($id);
    if (!$client_info) {
        $client_info = fetch_channel_info($id);
        set_cache($id, $client_info);
    }
    
    header('Content-Type: video/MP2T');
    header('Access-Control-Allow-Origin: *');
    header('Cache-Control: max-age=300');
    
    $ch = curl_init($ts_url);
    
    $headers = [
        'Host: ' . $url_parts['host'],
        'User-Agent: Lavf/58.12.100',
        'Accept: */*',
        'Accept-Encoding: gzip, deflate',
        'Connection: keep-alive',
        'Origin: https://m.hbtv.com.cn',
        'X-Requested-With: com.hiker.youtoo',
        'Sec-Fetch-Site: cross-site',
        'Sec-Fetch-Mode: cors',
        'Sec-Fetch-Dest: empty',
        'Referer: https://m.hbtv.com.cn/',
    ];
    
    $cookies = [];
    foreach (['client-id', 'aa-look', 'client-token', 'acw_tc'] as $key) {
        if (!empty($client_info[$key])) {
            $cookies[] = "$key=" . $client_info[$key];
        }
    }
    if ($cookies) {
        $headers[] = 'Cookie: ' . implode('; ', $cookies);
    }
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_REFERER, 'https://m.hbtv.com.cn/');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
        echo $data;
        return strlen($data);
    });
    
    curl_exec($ch);
    curl_close($ch);
}

function get_m3u8_info($id) {
    $cached = get_cache($id);
    if ($cached) {
        send_ws_heartbeat($cached);
        return $cached;
    }
    
    $info = fetch_channel_info($id);
    if ($info) {
        set_cache($id, $info);
    }
    return $info;
}

function fetch_channel_info($id) {
    $html = http_get('https://m.hbtv.com.cn/9hbfmtv');
    if (!$html) return null;
    
    $client_info = get_cookies(['client-id', 'aa-look', 'client-token', 'acw_tc']);
    
    $stream_url = find_stream($id, $html);
    if (!$stream_url) return null;
    
    send_ws_heartbeat($client_info);
    
    $cookie_str = build_cookie_str($client_info);
    $api_url = "https://m.hbtv.com.cn/get_cdn_9hbfm?url=$stream_url&client-id=" . ($client_info['client-id'] ?? '');
    $response = http_get($api_url, 'https://m.hbtv.com.cn/', ['x-requested-with: XMLHttpRequest'], $cookie_str);
    
    $data = json_decode($response, true);
    if (empty($data['data'])) return null;
    
    $client_info['url'] = $data['data'];
    return $client_info;
}

function find_stream($id, $html) {
    $pattern = '/name:\s*"' . $id . '"\s*[,}\s\w":]+stream\s*:\s*"([^"]+)"/u';
    if (preg_match($pattern, $html, $matches)) {
        return $matches[1];
    }
    return null;
}

function get_m3u8($url) {
    return http_get($url, 'https://m.hbtv.com.cn/');
}

function replace_ts_with_proxy($base_url, $content, $id) {
    $base_path = dirname($base_url) . '/';
    $script_url = get_script_url();
    
    return preg_replace_callback("/^((?!#).+)$/im", function($matches) use ($base_path, $id, $script_url) {
        $ts_url = trim($matches[1]);
        
        if (!is_absolute_url($ts_url)) {
            $ts_url = $base_path . $ts_url;
        }
        
        $params = ['ts_url' => $ts_url, 'id' => $id, 'r' => 1];
        $clean_url = strtok($script_url, '?');
        return $clean_url . '?' . http_build_query($params);
    }, $content);
}

function fix_ts_paths($base_url, $content) {

    $parsed = parse_url($base_url);
    $scheme = $parsed['scheme'] ?? 'http';
    $host = $parsed['host'] ?? '';
    $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
    
    return preg_replace_callback("/^((?!#).+)$/im", function($matches) use ($scheme, $host, $port) {
        $ts_path = trim($matches[1]);
        
        if (!is_absolute_url($ts_path)) {

            if (strpos($ts_path, '/') === 0) {
                $ts_path = $scheme . '://' . $host . $port . $ts_path;
            } else {
                $ts_path = $scheme . '://' . $host . $port . '/' . $ts_path;
            }
        }
        
        return $ts_path;
    }, $content);
}

function is_absolute_url($url) {
    $url = trim($url);
    return stripos($url, 'http:') === 0 || stripos($url, 'https:') === 0;
}

function get_script_url() {
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    return $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
}

function http_get($url, $referer = null, $headers = null, $cookies = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    if ($referer) curl_setopt($ch, CURLOPT_REFERER, $referer);
    if ($headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if ($cookies) curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    
    curl_setopt($ch, CURLOPT_COOKIEFILE, '');
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

function get_cookies($cookie_names) {
    $ch = curl_init('https://m.hbtv.com.cn/9hbfmtv');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_COOKIEFILE, '');
    curl_exec($ch);
    $cookie_list = curl_getinfo($ch, CURLINFO_COOKIELIST);
    curl_close($ch);
    
    $result = [];
    foreach ($cookie_names as $name) {
        foreach ($cookie_list as $cookie) {
            if (strpos($cookie, $name) !== false) {
                preg_match("/$name\s+(.+)/", $cookie, $matches);
                $result[$name] = $matches[1];
                break;
            }
        }
    }
    return $result;
}

function build_cookie_str($cookies) {
    $parts = [];
    foreach ($cookies as $key => $value) {
        $parts[] = "$key=$value";
    }
    return implode('; ', $parts);
}

function send_ws_heartbeat($client_info) {
    if (empty($client_info['client-id']) || empty($client_info['aa-look']) || empty($client_info['client-token'])) {
        return;
    }
    
    try {
        $ws = new NetTCP('wss://remote-wa.cjyun.org.cn/liveweb', [
            'Upgrade' => 'websocket',
            'Origin' => 'https://m.hbtv.com.cn',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);
        
        $ws->send(json_encode([
            'client_id' => $client_info['client-id'],
            'aa_look' => $client_info['aa-look'],
            'client_token' => $client_info['client-token'],
        ]));
    } catch (Exception $e) {
    }
}

function set_cache($id, $data) {
    $filename = "hb_cjy_$id.txt";
    file_put_contents($filename, serialize($data), LOCK_EX);
}

function get_cache($id) {
    $filename = "hb_cjy_$id.txt";
    $expire = 2 * 60 * 60;
    
    if (!file_exists($filename) || (time() - filemtime($filename) > $expire)) {
        return null;
    }
    
    $data = file_get_contents($filename);
    $result = @unserialize($data);
    
    return ($result !== false && is_array($result) && !empty($result['url'])) ? $result : null;
}
class NetTCP {
    private $socket = null;
    private $options = ['timeout' => 5];
    
    public function __construct($socket_uri = null, $headers = null) {
        $this->socket_uri = $socket_uri;
        $this->options['headers'] = $headers;
        $this->connect();
    }
    
    public function __destruct() {
        if ($this->socket) fclose($this->socket);
    }
    
    public function send($payload) {
        if (!$this->socket) return;
        
        $payload_chunks = str_split($payload, 4096);
        $frame_opcode = 'text';
        
        foreach ($payload_chunks as $index => $chunk) {
            $final = $index == count($payload_chunks) - 1;
            $this->sendFragment($final, $chunk, $frame_opcode, true);
            $frame_opcode = 'continuation';
        }
    }
    
    private function connect() {
        $parts = parse_url($this->socket_uri);
        if (!in_array($parts['scheme'], ['ws', 'wss'])) return;
        
        $host = $parts['host'];
        $port = $parts['port'] ?? ($parts['scheme'] === 'wss' ? 443 : 80);
        $path = $parts['path'] ?? '/';
        
        $context = stream_context_create(['ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]]);
        
        $this->socket = @stream_socket_client(
            ($parts['scheme'] === 'wss' ? 'ssl://' : 'tcp://') . "{$host}:{$port}",
            $errno, $errstr, $this->options['timeout'], STREAM_CLIENT_CONNECT, $context
        );
        
        if (!$this->socket) return;
        
        $key = base64_encode(random_bytes(16));
        $headers = [
            'Host' => "{$host}:{$port}",
            'Connection' => 'Upgrade',
            'Upgrade' => 'websocket',
            'Sec-WebSocket-Key' => $key,
            'Sec-WebSocket-Version' => '13'
        ];
        
        if ($this->options['headers']) {
            $headers = array_merge($headers, $this->options['headers']);
        }
        
        $header = "GET {$path} HTTP/1.1\r\n";
        foreach ($headers as $name => $value) {
            $header .= "{$name}: {$value}\r\n";
        }
        $header .= "\r\n";
        
        @fwrite($this->socket, $header);
        @stream_get_line($this->socket, 1024, "\r\n\r\n");
    }
    
    private function sendFragment($final, $payload, $opcode, $masked) {
        $opcodes = [
            'continuation' => 0,
            'text' => 1,
            'binary' => 2,
            'close' => 8,
            'ping' => 9,
            'pong' => 10,
        ];
        
        $byte_1 = $final ? 0b10000000 : 0b00000000;
        $byte_1 |= $opcodes[$opcode];
        $data = pack('C', $byte_1);
        
        $byte_2 = $masked ? 0b10000000 : 0b00000000;
        $payload_length = strlen($payload);
        
        if ($payload_length > 65535) {
            $data .= pack('C', $byte_2 | 0b01111111);
            $data .= pack('J', $payload_length);
        } elseif ($payload_length > 125) {
            $data .= pack('C', $byte_2 | 0b01111110);
            $data .= pack('n', $payload_length);
        } else {
            $data .= pack('C', $byte_2 | $payload_length);
        }
        
        if ($masked) {
            $mask = random_bytes(4);
            $data .= $mask;
            for ($i = 0; $i < $payload_length; $i++) {
                $data .= $payload[$i] ^ $mask[$i % 4];
            }
        } else {
            $data .= $payload;
        }
        
        @fwrite($this->socket, $data);
    }
}

/*
湖北卫视,http://xxx/xxx.php?id=湖北卫视&r=1
湖北综合,http://xxx/xxx.php?id=湖北综合&r=1
湖北经视,http://xxx/xxx.php?id=湖北经视&r=1
湖北教育,http://xxx/xxx.php?id=湖北教育&r=1
湖北影视,http://xxx/xxx.php?id=湖北影视&r=1
湖北生活,http://xxx/xxx.php?id=湖北生活&r=1
垄上频道,http://xxx/xxx.php?id=垄上频道&r=1
武汉新闻综合,http://xxx/xxx.php?id=武汉新闻综合
黄石新闻综合,http://xxx/xxx.php?id=黄石新闻综合&r=1
十堰新闻综合,http://xxx/xxx.php?id=十堰新闻综合
宜昌综合,http://xxx/xxx.php?id=宜昌综合
襄阳综合,http://xxx/xxx.php?id=襄阳综合&r=1
鄂州新闻综合,http://xxx/xxx.php?id=鄂州新闻综合&r=1
荆门新闻综合,http://xxx/xxx.php?id=荆门新闻综合
孝感新闻综合,http://xxx/xxx.php?id=孝感新闻综合&r=1
荆州新闻综合,http://xxx/xxx.php?id=荆州新闻综合&r=1
黄冈新闻综合,http://xxx/xxx.php?id=黄冈新闻综合&r=1
咸宁综合,http://xxx/xxx.php?id=咸宁综合
随州综合,http://xxx/xxx.php?id=随州综合
//恩施新闻综合,http://xxx/xxx.php?id=恩施新闻综合
仙桃综合频道,http://xxx/xxx.php?id=仙桃综合频道&r=1
天门综合,http://xxx/xxx.php?id=天门综合&r=1
潜江新闻综合,http://xxx/xxx.php?id=潜江新闻综合
//神农架综合,http://xxx/xxx.php?id=神农架综合
*/