<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:"bjws4k";
$time = time();
$n = [
    'bjws4k' => '5481pu3mib99s696hvtkq65c25n',//'5755n511tbk8flo40l4c71l0sdf',(备用id)
    'bjws' => '573ib1kp5nk92irinpumbo9krlb',
    'bjwy' => '54db6gi5vfj8r8q1e6r89imd64s',
    'bjjs' => '53bn9rlalq08lmb8nf8iadoph0b',
    'bjys' => '50mqo8t4n4e8gtarqr3orj9l93v',
    'bjcj' => '50e335k9dq488lb7jo44olp71f5',
    'bjty' => '54hv0f3pq079d4oiil2k12dkvsc',
    'bjsh' => '50j015rjrei9vmp3h8upblr41jf',
    'bjxw' => '53gpt1ephlp86eor6ahtkg5b2hf',
    'bjkk' => '55skfjq618b9kcq9tfjr5qllb7r',
    ];

$push_id = $token = md5($time.$n[$id]);//可随便填写1个数字或字母。
$body = "browse_mode=1"
       ."&channel=ali"
       ."&id={$n[$id]}"
       ."&net=WIFI"
       ."&os=NOX666999"
       ."&os_type=Android"
       ."&os_ver=33"
       ."&push_id={$push_id}"
       ."&timestamp={$time}"
       ."&token={$token}"
       ."&ver=100600";
$sign = substr(md5($body."shi!@#$%^&*[xian!@#]*"), 3, 7);
$url = "https://app.api.btime.com/video/play?{$body}&sign=".$sign;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: bjtime 100600','Referer: android-app.btime.com'));
$data = curl_exec($ch);
curl_close($ch);
$playurl = json_decode($data)->data->video_stream[0]->stream_url;
header("location:".$playurl);
//echo $playurl;
?>