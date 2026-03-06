<?php
//Written by Wheiss
error_reporting(0);
date_default_timezone_set("PRC");
$hls = empty($_GET['hls'])?'':base64_decode($_GET['hls']);
$u = empty($_GET['u'])?'':base64_decode($_GET['u']);
if ($u) $hls = $u;
$ua = empty($_GET['ua'])?$_SERVER['HTTP_USER_AGENT']:$_GET['ua'];
$referer = empty($_GET['referer'])?'':$_GET['referer'];
if ($referer) {
    $headers = ['Referer: '.base64_decode($referer),'User-Agent: '.base64_decode($ua)];
    $urle = "referer={$referer}&ua={$ua}";
} else {
    $headers = ['User-Agent: '.base64_decode($ua)];
    $urle = "ua={$ua}";
}
$mode = isset($_GET['mode']) ? $_GET['mode'] : '';//模式：默认/0-只代理请求m3u8，1-同时代理请求m3u8和ts
$ts = empty($_GET['ts'])?'':base64_decode($_GET['ts']);
if ($ts) {
    $d = curl_get($ts,15);
    if (strlen($d)<500) $d = curl_get($ts,15);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: video/MP2T');
    header("Content-Disposition: inline; filename={$ts}.ts");
    print_r($d);
} elseif ($hls) {
    $d = get_m3u8($hls);
    header('Access-Control-Allow-Origin: *');
    header("Content-Type: application/vnd.apple.mpegurl");
    header("Content-Disposition: inline; filename={$hls}.m3u8");
    print_r($d);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {//根据输入参数自动生成代理链接
    $pHls = empty($_POST["pHls"])?die('请输入源链接后再提交数据！'):$_POST["pHls"];
    $pReferer = isset($_POST["pReferer"]) ? $_POST["pReferer"] : '';
    $pUa = empty($_POST["pUa"])?$ua:$_POST["pUa"];
    $urle = "ua=".base64_encode($pUa);
    if ($pReferer) $urle .= "&referer=".base64_encode($pReferer);
    $urle .= empty($_POST["pMode"])?'':"&mode=1";
    $https = isset($_SERVER['HTTPS'])?'https':'http';//当前请求的主机使用的协议。
    $http_host = $_SERVER['HTTP_HOST'];//当前请求的主机名。
    $requestUri = $_SERVER['REQUEST_URI'];//获取当前请求的 URI
    $Uripath = explode('?',$requestUri)[0];
    $urlp = "{$https}://{$http_host}{$Uripath}?{$urle}";
    $pHlsArray = explode("\n",trim($pHls));
    $d = '<center>生成的代理链接为：</center><br/>';
    if (strpos($pHlsArray[0],',http')!==false) {
        foreach ($pHlsArray as $phlsItem) {
            $phlsItemArray = explode(',http',trim($phlsItem));
            $hls = base64_encode('http'.$phlsItemArray[1]);
            $url = "{$urlp}&hls={$hls}";
            $d .= $phlsItemArray[0].',<a href="'.$url.'">'.$url.'</a><br/>';
        }
    } else {
        foreach ($pHlsArray as $phlsItem) {
            $hls = base64_encode(trim($phlsItem));
            $url = "{$urlp}&hls={$hls}";
            $d .= '<a href="'.$url.'">'.$url.'</a><br/>';
        }
    }
    echo $d;
} else {//无参数运行时，显示输入框
    echo <<<'EOT'
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>HlsProxy链接自动生成程序</title>
        <style>
                /* 整体页面通用样式 */
                body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        margin: 0;
                        padding: 0;
                        background-color: #f4f4f4;
                }

                /* 标题样式 */
                h3 {
                        color: #333;
                        text-align: center;
                        margin-top: 20px;
                }

                /* 表单容器样式 */
                .form-container {
                        width: 500px;
                        margin: 20px auto;
                        padding: 20px;
                        background-color: #fff;
                        border-radius: 5px;
                        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
                }

                /* 表单元素通用样式 */
                form {
                        display: flex;
                        flex-direction: column;
                }

                label {
                        margin-bottom: 5px;
                        font-weight: bold;
                }

                textarea {
                        width: 100%;
                        padding: 8px;
                        margin-bottom: 15px;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        resize: vertical;
                }

                button {
                        background-color: #4CAF50;
                        color: white;
                        padding: 10px 15px;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                        transition: background-color 0.3s ease;
                }

                button:hover {
                        background-color: #45a049;
                }

                select {
                        padding: 8px;
                        margin-bottom: 15px;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                }
        </style>
</head>

<body>
        <div class="form-container">
                <h3>请在下方输入需代理的数据</h3>
                <form action="" method="POST">
                        <label for="pHls">m3u8链接:</label>
                        <textarea id="pHls" name="pHls" placeholder="(Referer和UA不变时)支持多条，每行一条(中间不要留空行)；支持带频道名的txt通用格式(不要有分组)"></textarea>
                        <label for="pReferer">Referer:</label>
                        <textarea id="pReferer" name="pReferer" placeholder="如有" maxlength="500"></textarea>
                        <label for="pUa">User-Agent:</label>
                        <textarea id="pUa" name="pUa" placeholder="留空则默认使用当前浏览器的" maxlength="500"></textarea>
                        <label for="pMode">模式:</label>
                        <select id="pMode" name="pMode">
                                <option value="0">只代理请求m3u8</option>
                                <option value="1">同时代理请求m3u8和ts</option>
                        </select>
                        <button type="submit">提交</button>
                </form>
                <hr>
                <h3>仅供测试与学习用途</h3>
        </div>
</body>

</html>
EOT;
}
exit;

function curl_get(&$url,$timeout=3){
    global $headers;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,2);
//    curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
    $data = curl_exec($ch);
    $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    return $data;
}

function get_m3u8($url){
    global $urle;
    global $mode;
    $https = isset($_SERVER['HTTPS'])?'https':'http';//当前请求的主机使用的协议。
    $http_host = $_SERVER['HTTP_HOST'];//当前请求的主机名。
    $requestUri = $_SERVER['REQUEST_URI'];//获取当前请求的 URI
    $Uripath = explode('?',$requestUri)[0];//strstr($requestUri,'?',true);
    $m3u8 = curl_get($url);
    if (!$m3u8) $m3u8 = curl_get($url);
    if ($m3u8) {
        //相对路径前缀
        $urlp = dirname($url).'/';
        //绝对路径前缀
        $position = strpos($url,'/',8);
        $urlp2 = str_split($url,$position)[0];
        if (strpos($m3u8,"\r\n")!==false) {
            $m3u8s = explode("\r\n",trim($m3u8));
        } else {
            $m3u8s = explode("\n",trim($m3u8));
        }
        $d = '';
        if (strpos($m3u8,'#EXT-X-STREAM-INF:')!==false) {//数据为分辨率列表，需要保留该参数
            $proxy = "{$https}://{$http_host}{$Uripath}?{$urle}&mode={$mode}&u=";
        } elseif ($mode) {//ts列表是否需要代理仅根据mode参数判断，ts链接不再需要该参数
            $proxy = "{$https}://{$http_host}{$Uripath}?{$urle}&ts=";
        } else {
            $proxy = '';//后续根据是否为空可判断是否为代理的列表
        }
        if ($proxy) {//可能为m3u8分辨率列表或ts列表
            foreach($m3u8s as $m3u8l){
                if ($m3u8l[0] == '#'){//配置参数行
                    $d .= $m3u8l.PHP_EOL;
                } else if ($m3u8l[0] == '/'){//适用绝对路径
                    $d .= $proxy.base64_encode($urlp2.$m3u8l).PHP_EOL;
                } else if ($m3u8l[0] == 'h'&&strpos($m3u8l,'://')){//适用完整链接
                    $d .= $proxy.base64_encode($m3u8l).PHP_EOL;
                } else if (stripos($m3u8l,'.ts')||stripos($m3u8l,'.m3u8')) {//适用相对路径
                    $d .= $proxy.base64_encode($urlp.$m3u8l).PHP_EOL;
                }
            }
        } else {//只代理请求m3u8
            foreach($m3u8s as $m3u8l){
                if ($m3u8l[0] == '#'){//配置参数行
                    $d .= $m3u8l.PHP_EOL;
                } else if ($m3u8l[0] == '/'){//适用绝对路径
                    $d .= $urlp2.$m3u8l.PHP_EOL;
                } else if ($m3u8l[0] == 'h'&&strpos($m3u8l,'://')){//适用完整链接
                    $d .= $m3u8l.PHP_EOL;
                } else if (stripos($m3u8l,'.ts')) {//适用相对路径
                    $d .= $urlp.$m3u8l.PHP_EOL;
                }
            }
        }
        return $d;
    } else {
        die('m3u8文件数据获取失败！');
    }
}
?>