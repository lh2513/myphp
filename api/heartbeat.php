<?php
include_once 'common.php';
//run($_GET['pid'], $_GET['cid']);



function run($pid, $cid)
{//https://iptv.cc/forum.php?mod=viewthread&tid=5705
//    ignore_user_abort(true); // 忽略客户端断开连接
//    set_time_limit(0); // 取消脚本执行时间限制（谨慎使用）

    $need_save_cache = false;
    $a = load_from_cache($pid, $cid);
    if (isset($a) && isset($a['uuid']))
        $uuid = $a['uuid'];
    else {
        $uuid = create_uuid();
        $a['uuid'] = $uuid;
        $need_save_cache = true;
    }

    $h1 = [
        'Upgrade' => 'websocket',
        'Origin' => 'https://web.guangdianyun.tv',
        'Cache-Control' => 'no-cache',
        'Accept-Language' => 'zh-CN,zh;q=0.9',
        'Pragma' => 'no-cache',
        'Connection' => 'Upgrade',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
        'Sec-Websocket-Extensions' => 'permessage-deflate; client_max_window_bits',
    ];
    $h2 = $h1;
    $h2['Sec-Websocket-Protocol'] = 'mqtt';

    global $ws1, $ws2;
    $ws1 = new NetTCP('wss://bapi.guangdianyun.tv/v1/stats/channel/watchTime', $h1);
    $ws2 = new NetTCP('wss://mqttdms.aodianyun.com:8300/mqtt', $h2);

    $p = get_heartbeat_data($uuid, $pid, $cid, true);
    $ws1->send($p);
    //write_log('ws1.recv: '.bin2hex($ws1->recv()).PHP_EOL);

    if (isset($a) && isset($a['sub_key']))
        $sub_key = $a['sub_key'];
    else {
        $sub_key = get_sub_key($pid, $cid);
        $a['sub_key'] = $sub_key;
        $need_save_cache = true;
    }
    $p = hex2bin('105800044d51545404c2003c0024'.bin2hex($uuid).'00000024'.bin2hex($sub_key));
    $ws2->send($p, 'binary');
    //write_log('ws2.recv: '.bin2hex($ws2->recv()).PHP_EOL);

    $l1 = 24 + strlen($pid);
    $l2 = $l1 - 5;
    $l1_str = dechex($l1);
    $l2_str = dechex($l2);
    $p = hex2bin("82{$l1_str}000100{$l2_str}".bin2hex("program_tv_channel_$pid").'00');
    $ws2->send($p, 'binary');
    //write_log('ws2.recv: '.bin2hex($ws2->recv()).PHP_EOL);

    $l1_str = dechex($l1+5);
    $l2_str = dechex($l2+5);
    $p = hex2bin("82{$l1_str}000200{$l2_str}".bin2hex("program_tv_channel_data_$pid").'00');
    $ws2->send($p, 'binary');
    //write_log('ws2.recv: '.bin2hex($ws2->recv()).PHP_EOL);

    $p = hex2bin('82130003000e7379732f6e6f746966792f6c737300');
    $ws2->send($p, 'binary');
    //write_log('ws2.recv: '.bin2hex($ws2->recv()).PHP_EOL);

    if ($need_save_cache) {
        unset($a['url']);
        save_to_cache($pid, $cid, $a);
    }
    update_flag_filemtime($pid, $cid);
//    $interval1 = 5; // 心跳间隔（秒）
//    $interval2 = 60;
//    $ws2_seconds = 0;
//    while (true) {
        $p = get_heartbeat_data($uuid, $pid, $cid, false);
        $ws1->send($p);
        //write_log('ws1.recv: '.bin2hex($ws1->recv()).PHP_EOL);
//        if ($ws2_seconds >= $interval2) {
            $p = hex2bin('c000');
            $ws2->send($p, 'binary');
            //write_log('ws2.recv: '.bin2hex($ws2->recv()).PHP_EOL);
//            $ws2_seconds = 0;
//        }
//        if (!can_continue_heartbeat_running($pid, $cid)) {
//            del_flag_file($pid, $cid);
//            //write_log('del_flag_file run!');
//            return false;
//        }
        //write_log('heartbeat running...');
//        sleep($interval1);
//        $ws2_seconds += $interval1;
        return true;
//    }
}

function get_sub_key($pid, $cid)
{
    $url = "https://1812501212048408.cn-hangzhou.fc.aliyuncs.com/2016-08-15/proxy/node-api.online/node-api/tv/channelInfo";
    $params = [
        'id' => $pid,
        'uin' => $cid,
    ];
    $headers = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
        'Origin: https://web.guangdianyun.tv',
        'Referer: https://web.guangdianyun.tv/',
        'X-Ca-Stage: ',
        'Token: ',
    ];
    $c = send_request($url, $params, $headers);
    $j = json_decode($c, true);
    $r = $j['data']['sub_key'];
    return $r;
}

function get_heartbeat_data($uuid, $pid, $cid, $first){
    $msg = $first ? 'first' : '';
    return json_encode([
        "msg" => $msg,
        "uuid" => $uuid,
        "ua" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36",
        "time" => time(),
        "userId" => 0,
        "id" => (int)$pid,
        "type" => "tv",
        "uin" => (int)$cid,
        "liveNowStatus" => 1
    ], JSON_UNESCAPED_SLASHES);
}



//https://blog.csdn.net/qq_22183039/article/details/128780018
/**
 * NetTCP 支持ws，wss、ssl、tcp
 * @author 尹雪峰
 * @date 2022年6月6日
 * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
 */
class NetTCP {

    private $socket_uri = "wss://www.xforms.cn";
    private $isRev      = false; //是否开启错误提醒
    private $socket     = null;
    //默认设置参数
    private $options    = [
        'context'       => null,
        'filter'        => ['text', 'binary'],
        'fragment_size' => 4096,
        'headers'       => null,
        'logger'        => null,
        'origin'        => null,
        'persistent'    => false,
        'return_obj'    => false,
        'timeout'       => 5,
    ];
    //默认信息编码设置
    private $opcodes    = [
        'continuation'  => 0,
        'text'          => 1,
        'binary'        => 2,
        'close'         => 8,
        'ping'          => 9,
        'pong'          => 10,
    ];

    /**
     * 构造函数
     * @author 尹雪峰
     * @date 2022年6月6日
     * @param unknown $config
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    public function __construct($socket_uri = null, $headers = null){
        if(!empty($socket_uri)){
            $this->socket_uri = $socket_uri;
        }
        if(!empty($headers)){
            $this->options['headers'] = $headers;
        }
        if(!$this->isConnect()){
            $this->connect();
        }
    }

    /**
     * 析构函数
     * @author 尹雪峰
     * @date 2022年6月7日
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    public function __destruct(){
        if ($this->isConnect() && get_resource_type($this->socket) !== 'persistent stream') {
            fclose($this->socket);
        }
        $this->socket   = null;
    }

    /**
     * 发送信息
     * @author 尹雪峰
     * @date 2022年6月6日
     * @param unknown $payload
     * @param string $opcode
     * @param bool $masked
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    public function send($payload, $opcode = 'text', $masked = true){
        if (!$this->isConnect()){
            $this->connect();
        }
        if (!in_array($opcode, array_keys($this->opcodes))){
            $this->show(-1, "Bad opcode '{$opcode}'.  Try 'text' or 'binary'.");
        }
        $payload_chunks = str_split($payload, $this->options['fragment_size']);
        $frame_opcode   = $opcode;
        for ($index     = 0; $index < count($payload_chunks); ++$index) {
            $chunk      = $payload_chunks[$index];
            $final      = $index == count($payload_chunks) - 1;
            $this->sendFragment($final, $chunk, $frame_opcode, $masked);
            $frame_opcode = 'continuation';
        }
    }

    public function recv(){
        return $this->read();
    }

    /**
     * 发送信息
     * @author 尹雪峰
     * @date 2022年6月6日
     * @param unknown $message
     * @param string $res
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    private function connect(){
        $url_parts          = parse_url($this->socket_uri);
        $scheme             = $url_parts['scheme'];
        $host               = $url_parts['host'];
        $user               = isset($url_parts['user']) ? $url_parts['user'] : '';
        $pass               = isset($url_parts['pass']) ? $url_parts['pass'] : '';
        $port               = isset($url_parts['port']) ? $url_parts['port'] : ($scheme === 'wss' ? 443 : 80);
        $path               = isset($url_parts['path']) ? $url_parts['path'] : '/';
        $query              = isset($url_parts['query'])    ? $url_parts['query'] : '';
        $fragment           = isset($url_parts['fragment']) ? $url_parts['fragment'] : '';

        $path_with_query    = $path;
        if (!empty($query)) {
            $path_with_query .= '?' . $query;
        }
        if (!empty($fragment)) {
            $path_with_query .= '#' . $fragment;
        }

        if (!in_array($scheme, ['ws', 'wss'])) {
            $this->show(-1, "Url should have scheme ws or wss, not '{$scheme}' from URI '{$this->socket_uri}'.");
        }
        $host_uri           = ($scheme === 'wss' ? 'ssl' : 'tcp') . '://' . $host;

        if (isset($this->options['context']) && !empty($this->options['context'])) {
            if (@get_resource_type($this->options['context']) === 'stream-context') {
                $context = $this->options['context'];
            } else {
                $this->show(-1, "Stream context in \$options['context'] isn't a valid context.");
            }
        } else {
            $context    = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
        }

        $persistent     = true;
        $errno          = null;
        $errstr         = null;
        $persistent     = $this->options['persistent'] === true;
        $flags          = STREAM_CLIENT_CONNECT;
        $flags          = $persistent ? $flags | STREAM_CLIENT_PERSISTENT : $flags;

        //取消证书认证，避免https无法正常使用
        stream_context_set_option($context, 'ssl', 'verify_peer_name', false);
        stream_context_set_option($context, 'ssl', 'verify_peer', false);
        stream_context_set_option($context, 'ssl', 'verify_host', false);

        $this->socket = stream_socket_client(
            "{$host_uri}:{$port}",
            $errno,
            $errstr,
            $this->options["timeout"],
            $flags,
            $context
        );

        restore_error_handler();
        if($this->isConnect()){
            if(!$persistent || ftell($this->socket) == 0){
                stream_set_timeout($this->socket, $this->options["timeout"]);
                $key        = $this->generateKey();
                $headers = [
                    'Host'                  => $host . ":" . $port,
                    'User-Agent'            => 'websocket-client-php',
                    'Connection'            => 'Upgrade',
                    'Upgrade'               => 'websocket',
                    'Sec-WebSocket-Key'     => $key,
                    'Sec-WebSocket-Version' => '13',
                ];
                if ($user || $pass) {
                    $headers['authorization'] = 'Basic ' . base64_encode($user . ':' . $pass);
                }
                if (isset($this->options['origin'])) {
                    $headers['origin'] = $this->options['origin'];
                }
                if (isset($this->options['headers'])) {
                    $headers = array_merge($headers, $this->options['headers']);
                }
                $header = "GET " . $path_with_query . " HTTP/1.1\r\n" . implode("\r\n",
                        array_map(function ($key, $value) {
                            return "$key: $value";
                        },
                            array_keys($headers),
                            $headers
                        )
                    )."\r\n\r\n";

                $matches    = array();
                $this->write($header);
                $response   = stream_get_line($this->socket, 1024, "\r\n\r\n");
                $address    = "{$scheme}://{$host}{$path_with_query}";
                if (!preg_match('#Sec-WebSocket-Accept:\s(.*)$#mUi', $response, $matches)) {
                    $this->show(-1, "Connection to '{$address}' failed: Server sent invalid upgrade response: {$response}");
                }
                $keyAccept  = trim($matches[1]);
                $expectedResonse = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
                if ($keyAccept !== $expectedResonse) {
                    $this->show(-1, "Server sent bad upgrade response");
                }
            }else{
                $this->show(-1, "网络连接异常，请稍后重试");
            }
        }else{
            $this->show(-1, "网络连接异常，请稍后重试");
        }
    }

    /**
     * 判断是否连接
     * @author 尹雪峰
     * @date 2022年6月6日
     * @return boolean
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    private function isConnect(){
        return $this->socket &&
            (get_resource_type($this->socket) == 'stream' || get_resource_type($this->socket) == 'persistent stream');
    }

    /**
     * Receive one message.
     * Will continue reading until read message match filter settings.
     * Return Message instance or string according to settings.
     */
    private function sendFragment($final, $payload, $opcode, $masked){
        $data       = '';
        $byte_1     = $final ? 0b10000000 : 0b00000000; // Final fragment marker.
        $byte_1     |= $this->opcodes[$opcode]; // Set opcode.
        $data       .= pack('C', $byte_1);
        $byte_2     = $masked ? 0b10000000 : 0b00000000; // Masking bit marker.
        $payload_length = strlen($payload);
        if ($payload_length > 65535) {
            $data   .= pack('C', $byte_2 | 0b01111111);
            $data   .= pack('J', $payload_length);
        } elseif ($payload_length > 125) {
            $data   .= pack('C', $byte_2 | 0b01111110);
            $data   .= pack('n', $payload_length);
        } else {
            $data   .= pack('C', $byte_2 | $payload_length);
        }
        if ($masked) {
            $mask   = '';
            for ($i = 0; $i < 4; $i++) {
                $mask .= chr(rand(0, 255));
            }
            $data   .= $mask;
            for ($i = 0; $i < $payload_length; $i++) {
                $data .= $payload[$i] ^ $mask[$i % 4];
            }
        } else {
            $data   .= $payload;
        }
        $this->write($data);
    }

    /**
     * 写入数据信息
     * @author 尹雪峰
     * @date 2022年6月6日
     * @param string $data
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    private function write($data){
        @fwrite($this->socket, $data);
    }

    private function read(){
        $r = @fread($this->socket, 4096);
        return $r;
    }

    /**
     * 输入信息
     * @author 尹雪峰
     * @date 2022年6月6日
     * @param unknown $code
     * @param unknown $msg
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    private function show($code, $msg, $data = null){
        $value = array(
            "code"  =>$code,
            "msg"   =>$msg,
        );
        if(!empty($data)){
            $value["data"] = $data;
        }
        if($this->isRev){
            echo json_encode($value, JSON_UNESCAPED_UNICODE);
            die();
        }else{
            return $value;
        }
    }

    /**
     * 获取websocket key值
     * @author 尹雪峰
     * @date 2022年6月6日
     * @return string
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    private function generateKey(){
        $key = '';
        for ($i = 0; $i < 16; $i++) {
            $key .= chr(rand(33, 126));
        }
        return base64_encode($key);
    }
}