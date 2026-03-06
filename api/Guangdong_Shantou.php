<?php

    date_default_timezone_set('Asia/Shanghai');
    $id = $_GET['id'];
    $id  = strtolower($id);

    $hosts = "http://sttv-hls.strtv.cn";

    $ids = [
        "st1"=>"lKGXIQa",
        "st2"=>"7xjJK9d",
        //"st3"=>"G7Kql7a",
        //"fm1020"=>"L3y6rt8",
        "fm1025"=>"s7k681h",
        "fm1072"=>"Li7mg21"


    ];
    $key = "bf9b2cab35a9c38857b82aabf99874aa96b9ffbb";
    $dectime = dechex(time()+7200);
    $rate = substr($id,0,2)=="st"?"500":"64";
    $path = '/'.$ids[$id].'/'.$rate.'/'.pathname($ids[$id]).'.m3u8';

    $sign = md5($key.$path.$dectime);
    $liveURL = $hosts.$path."?sign={$sign}&t={$dectime}";
    
    header("location: $liveURL");

    function pathname($e)
    {
        
        $o = strtotime('today')*1000;

        
        $a=0;
        $r=0;
        $d=-1;
        $p=0;
        $l=0;
    
        for($a=0;$a<strlen($e);$a++)
        {
            $p = ord($e[$a]);
            $r = $r + $p;
            if($d!=-1)
            {
                $l=$l+($d-$p);
            }
            $d = $p;
        }
    
        $r = $r + $l;
        $s = base_convert($r,10,36);
        $c = base_convert($o,10,36);
        $u = 0;
        for($a=0;$a<strlen($c);$a++)
        {
            $u = $u + ord($c[$a]);
        }
    

        $c = substr($c,5).substr($c,0,5);
        $f = abs($u - $r);
        $c = strrev($s).$c;
        $g = substr($c,0,4);
        $w = substr($c,4);
        $b = date('w') % 2;
  

        $m = [];

        for ($a = 0; $a < strlen($e); $a++) {
            
            if ($a % 2 == $b) {
                $index = $a % strlen($c);
                $m[] = $c[$index];
            } else {
                $hIndex = $a - 1;
                if ($hIndex >= 0 ) {
                    $h = $e[$hIndex];
                    $v = strpos($g, $h);
                    if ($v === false) {
                        $m[] = $h;
                    } else {
                        $m[] = $w[$v];
                    }
                } else {
                    $gIndex = $a % strlen($g);
                    $m[] = $g[$gIndex];
                }
            }
            
        }
        $result = strrev(base_convert($f, 10, 36)) . implode('', $m);
        $result = substr($result, 0, strlen($e));
        return $result;
    }
    
?>