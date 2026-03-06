<?php
error_reporting(0);
$n = [
     'hbws' => 0,//湖北卫视
     'hbjs' => 1, //湖北经视
     'hbzh' => 2, //湖北综合
     'hbxw' => 3, //湖北公共新闻
     'hbys' => 4, //湖北影视
     'hbsh' => 5, //湖北生活
     'hbjy' => 6, //湖北教育
     'hbls' => 7, //垄上频道
];
$id = $_GET['id'] ?? 'hbws';
$c = "https://m.hbtv.com.cn/2023_live_audio";
preg_match_all('/stream: "(.*?)"/',get($c),$m_arrry);
preg_match('/stream: "(.*?)"/',$m_arrry[0][$n[$id]],$m);
$stream = $m[1];
$p = fetch_channel_info($stream)['url'];
$burl = dirname($p)."/";
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$php = $protocol . '://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$ts = $_GET['ts'];
if(empty($ts)) {
	header('Content-Type: application/vnd.apple.mpegurl');
	print_r(preg_replace("/(.*?.ts)/i",$php."?ts=$burl$1",get($p)));
} else {
	$data = get($ts);
	header('Content-Type: video/MP2T');
	echo $data;
}

function fetch_channel_info($stream_url) {
	$client_info = get_cookies(['client-id', 'aa-look', 'client-token', 'acw_tc']);
	send_ws_heartbeat($client_info);
	$cookie_str = build_cookie_str($client_info);
	$api_url = "https://m.hbtv.com.cn/ajax/get_cdn_leech?url=$stream_url&client-id=" . $client_info['client-id'];
	$response = get($api_url);
	$data = json_decode($response, true);
	$client_info['url'] = $data['data'];
	return $client_info;
}
function get($url){
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_REFERER, 'http://m.hbtv.com.cn/');
     $res = curl_exec($ch);
     curl_close($ch);
     return $res;
     }
function get_cookies($cookie_names) {
	$ch = curl_init("https://m.hbtv.com.cn/2023_live_audio");
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
	$ws = new NetTCP('wss://remote-wa.cjyun.org.cn/liveweb', [
	            'Upgrade' => 'websocket',
	            'Origin' => 'https://m.hbtv.com.cn',
	        ]);
	$ws->send(json_encode([
	            'client_id' => $client_info['client-id'],
	            'aa_look' => $client_info['aa-look'],
	            'client_token' => $client_info['client-token'],
	        ]));
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
		$host = $parts['host'];
		$port = $parts['port'] ?? ($parts['scheme'] === 'wss' ? 443 : 80);
		$path = $parts['path'] ?? '/';
		$this->socket = @stream_socket_client(
		            ($parts['scheme'] === 'wss' ? 'ssl://' : 'tcp://') . "{$host}:{$port}",
		            $errno, $errstr, $this->options['timeout'], STREAM_CLIENT_CONNECT);
		$key = base64_encode(random_bytes(16));
		$headers = [
		            'Host' => "{$host}:{$port}",
		            'Connection' => 'Upgrade',
		            'Upgrade' => 'websocket',
		            'Sec-WebSocket-Key' => $key,
		            'Sec-WebSocket-Version' => '13'
		        ];
		$headers = array_merge($headers, $this->options['headers']);
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
?>