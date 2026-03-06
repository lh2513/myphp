<?php

//https://app.jy0838.cn/application/tenma01ds_tvradio/view/h5/#/?id=1&time=1746456593101&type=1
//$id = $_GET['id'];
$id = 1;
go_home_intf($m3u8_url, $id);
go_getAuth_intf($m3u8_url_with_sign, $m3u8_url);
locate($m3u8_url_with_sign);





function locate($m3u8_url_with_sign)
{
    header("Access-Control-Allow-Origin: *");
    header("Location: $m3u8_url_with_sign");
}

function go_getAuth_intf(&$m3u8_url_with_sign, $m3u8_url)
{
    $u = 'https://app.jy0838.cn/tenma01ds_tvradio/Apphome/getAuth';
    $h = build_getAuth_request_header($tmtimestamp, $tmrandomnum);
    $d = build_getAuth_request_data($m3u8_url, $tmtimestamp, $tmrandomnum);
    $r = send_request($u, $h, $d);
    $m3u8_url_with_sign = decrypt_getAuth_response($r, $tmtimestamp, $tmrandomnum);
}

function decrypt_getAuth_response($response, $tmtimestamp, $tmrandomnum)
{
    $j = json_decode($response);
    $data = $j->data;
    $key = substr(md5(base64_encode($tmtimestamp).md5($tmtimestamp)), 0, 16);
    $iv = substr(md5($tmrandomnum), 0, 16);
    $r = openssl_decrypt($data, "aes-128-cbc", $key, 0, $iv);
    $j = json_decode($r);
    return $j->auth;
}

function build_getAuth_request_data($m3u8_url, $tmtimestamp, $tmrandomnum)
{
    $a = ['url' => $m3u8_url];
    $data = json_encode($a, JSON_UNESCAPED_SLASHES);
    $key = substr(md5(base64_encode($tmtimestamp).md5($tmrandomnum)), 0, 16);
    $iv = substr(md5(base64_encode($tmrandomnum).md5($tmtimestamp)), 0, 16);
    $r = openssl_encrypt($data, 'aes-128-cbc', $key, 0, $iv);
    $r = 'tm_encrypt_data='.rawurlencode($r);
    return $r;
}

function build_getAuth_request_header(&$tmtimestamp, &$tmrandomnum)
{
    $tmencrypt = 1;
    $tmtimestamp = round(microtime(true) * 1000);
    $tmrandomnum = randomString(16);
    $tmencryptkey = md5(base64_encode(md5($tmtimestamp).$tmrandomnum).$tmrandomnum);
    $a = '';
    $i = 'zh-cn';
    return [
        "Content-Type: application/x-www-form-urlencoded",
        "token: $a",
        "lang: $i",
        "tmencrypt: $tmencrypt",
        "tmtimestamp: $tmtimestamp",
        "tmrandomnum: $tmrandomnum",
        "tmencryptkey: $tmencryptkey"
    ];
}

function randomString($length) {
    $chars = 'abcdefhijkmnprstwxyz2345678';

    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $result;
}

function go_home_intf(&$m3u8_url, $id)
{
    $u = 'https://app.jy0838.cn/tenma01ds_tvradio/Apphome/home?channel_id='.$id;
    $r = send_request($u);
    $j = json_decode($r);
    $c = $j->data->channel_info;
    foreach ($c as $i) {
        if ($i->id == $id) {
            $m3u8_url = $i->m3u8;
            break;
        }
    }
}

function send_request($url, $header = null, $post_data = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    if (!empty($header))
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    if (!empty($post_data)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}