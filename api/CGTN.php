<?php
error_reporting(0);
date_default_timezone_set("PRC");
$id = $_GET['id']??'cgtn';
if (preg_match('/(.*?)(?:\?.*|\$.*)/', $id, $matches_id)) $id = $matches_id[1];
$playseek = $_GET['playseek']??'';
if ($playseek){
        $pid_arr = [
                'cgtn'=>['api.cgtn.com/website/api/live/channel',1],//CGTN
                'cgtnd'=>['api.cgtn.com/website/api/live/channel',12],//CGTN纪录
                'cgtne'=>['espanol-api.cgtn.com/api',2],//CGTN西班牙语
                'cgtnf'=>['francais-api.cgtn.com/api',3],//CGTN法语
                'cgtna'=>['arabic-api.cgtn.com/api',4],//CGTN阿拉伯语
                'cgtnr'=>['russian-api.cgtn.com/api',5],//CGTN俄语
        ];
        if (!isset($pid_arr[$id])) $id = 'cgtn';
        $t_arr = explode('-',$playseek);
        $startTime = strtotime($t_arr[0])*1000;
        $endTime = (strtotime($t_arr[1])-1)*1000;
        $list_url = 'https://'.$pid_arr[$id][0].'/epg/list?channelId='.$pid_arr[$id][1].'&startTime='.$startTime.'&endTime='.$endTime;
        $list_d = curl_get($list_url,1);
        if (json_decode($list_d)->status !== 200){//接口第一次请求失败则放宽开始时间模糊匹配前两项
                $startTime -= 3600000;
                $list_url = 'https://'.$pid_arr[$id][0].'/epg/list?channelId='.$pid_arr[$id][1].'&startTime='.$startTime.'&endTime='.$endTime;
                $list_d = curl_get($list_url,3);
                if (json_decode($list_d)->status !== 200){
                        die('epg列表接口请求失败！');
                } else {
                        $list_j = json_decode($list_d,true)['data'];
                        //$i = count($list_j)-1;
                        if (abs($list_j[0]['endTime']-$endTime)>abs($list_j[1]['endTime']-$endTime)){
                                $i = 1;
                        } else {
                                $i = 0;
                        }
                }
        } else {//接口第一次请求成功则匹配最后一项
                $list_j = json_decode($list_d,true)['data'];
                if (!$list_j){//接口第一次请求成功但无数据时放宽开始时间匹配最后一项
                        $startTime -= 900000;
                        $list_url = 'https://'.$pid_arr[$id][0].'/epg/list?channelId='.$pid_arr[$id][1].'&startTime='.$startTime.'&endTime='.$endTime;
                        $list_d = curl_get($list_url,3);
                        $list_j = json_decode($list_d,true)['data'];
                }
                $i = count($list_j)-1;
        }
        $epgId = $list_j[$i]['epgId'];
        $startTime = $list_j[$i]['startTime'];
        $endTime = $list_j[$i]['endTime'];
        if ($pid_arr[$id][1]==1||$pid_arr[$id][1]==12){
                $playback = 'playback';
        } else {
                $playback = 'playBack';
        }
        $playBack_url = 'https://'.$pid_arr[$id][0].'/epg/'.$playback.'?channelId='.$pid_arr[$id][1].'&startTime='.$startTime.'&endTime='.$endTime.'&epgId='.$epgId;
        $playBack_d = curl_get($playBack_url,1);
        if (json_decode($playBack_d)->status !== 200){
                $playBack_d = curl_get($playBack_url,3);
                if (json_decode($playBack_d)->status !== 200) die('epg回看接口请求失败！');
        }
        $url = json_decode($playBack_d)->data;
} else {//直播
        $id_arr = [
                'cgtn'=>['english-livetx.cgtn.com','yypdyyctzb'],//CGTN
                'cgtnd'=>['english-livetx.cgtn.com','yypdjlctzb'],//CGTN纪录
                'cgtne'=>['espanol-livews.cgtn.com','LSveOGBaBw41Ea7ukkVAUdKQ220802LSTexu6xAuFH8VZNBLE1ZNEa220802cd'],//CGTN西班牙语
                'cgtnf'=>['francais-livews.cgtn.com','LSvev95OuFZtKLc6CeKEFYXj220802LSTeV6PO0Ut9r71Uq3k5goCA220802cd'],//CGTN法语
                'cgtna'=>['arabic-livews.cgtn.com','LSveq57bErWLinBnxosqjisZ220802LSTefTAS9zc9mpU08y3np9TH220802cd'],//CGTN阿拉伯语
                'cgtnr'=>['russian-livews.cgtn.com','LSvexABhNipibK5KRuUkvHZ7220802LSTeze9o8tdFXMHsb1VosgoT220802cd'],//CGTN俄语
        ];
        if (!isset($id_arr[$id])) $id = 'cgtn';
        $url = "https://{$id_arr[$id][0]}/hls/{$id_arr[$id][1]}/playlist.m3u8";
}
header('location:'.$url);

function curl_get($url,$timeout){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_REFERER,'https://www.cgtn.com/tv');
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
}
?>
