<?php
        $bstrURL = 'https://www.setv.sh.cn/static/tvshow/overview.json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $bstrURL);       
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($data);
        $m3u8 = $json->data->liveLink;
        header('location:'.$m3u8);
?>
