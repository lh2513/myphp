<?php
// 上海移动 PLTV - 咪视界 / 睛彩 / 至臻视界
// 完整 PHP 随机节点脚本
// 调用方式：xxx.php?id=msj1 或 xxx.php?id=jcjj

// ===============================
// 频道数组区域（你要把所有数组贴在这里）
// ===============================

$channels = [
    // ============================
    // 在这里粘贴所有频道数组
    // 例如：
    // "jcjj" => [ "http://...", "http://..." ],
    // "msj1" => [ "http://...", "http://..." ],
    // ...
    // ============================
    // 睛彩竞技 jcjj
    "jcjj" => [
        "http://[2409:8087:1e01:20::42]/PLTV/11/224/3221226116/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::40]/PLTV/11/224/3221226116/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::16]/PLTV/11/224/3221226116/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::31]/PLTV/11/224/3221226116/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221226116/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::30]/PLTV/11/224/3221226116/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221226116/1.m3u8?IASHttpSessionId="
    ],

    // 睛彩篮球 jclq
    "jclq" => [
        "http://[2409:8087:1e01:20::42]/PLTV/11/224/3221226118/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::16]/PLTV/11/224/3221226118/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::40]/PLTV/11/224/3221226118/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::31]/PLTV/11/224/3221226118/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221226118/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::30]/PLTV/11/224/3221226118/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221226118/1.m3u8?IASHttpSessionId="
    ],

    // 睛彩广场舞 jcgcw
    "jcgcw" => [
        "http://[2409:8087:1e01:20::17]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::42]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::41]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::40]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::43]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::44]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::22]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::17]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::23]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::11]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::10]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::16]/PLTV/11/224/3221226139/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-1 msj1
    "msj1" => [
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225679/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225679/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::12]/PLTV/11/224/3221225679/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225679/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::10]/PLTV/11/224/3221225679/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::11]/PLTV/11/224/3221225679/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::18]/PLTV/11/224/3221225679/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::19]/PLTV/11/224/3221225679/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-2 msj2
    "msj2" => [
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225690/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225690/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::32]/PLTV/11/224/3221225690/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225690/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::33]/PLTV/11/224/3221225690/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225690/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-3 msj3
    "msj3" => [
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225676/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::14]/PLTV/11/224/3221225676/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225676/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225676/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225676/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::11]/PLTV/11/224/3221225676/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::10]/PLTV/11/224/3221225676/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-4 msj4
    "msj4" => [
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225683/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225683/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::23]/PLTV/11/224/3221225683/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::10]/PLTV/11/224/3221225683/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::11]/PLTV/11/224/3221225683/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::20]/PLTV/11/224/3221225683/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::22]/PLTV/11/224/3221225683/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::21]/PLTV/11/224/3221225683/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-6 msj6
    "msj6" => [
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225697/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::14]/PLTV/11/224/3221225697/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::12]/PLTV/11/224/3221225697/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::31]/PLTV/11/224/3221225697/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::30]/PLTV/11/224/3221225697/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-7 msj7
    "msj7" => [
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225681/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225681/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-8 msj8
    "msj8" => [
        "http://[2409:8087:1e01:20::18]/PLTV/11/224/3221225646/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225646/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225646/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::21]/PLTV/11/224/3221225646/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::20]/PLTV/11/224/3221225646/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::33]/PLTV/11/224/3221225646/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::32]/PLTV/11/224/3221225646/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-9 msj9
    "msj9" => [
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225695/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225695/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::16]/PLTV/11/224/3221225695/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::17]/PLTV/11/224/3221225695/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::29]/PLTV/11/224/3221225695/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::28]/PLTV/11/224/3221225695/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-10 msj10
    "msj10" => [
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225686/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225686/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225686/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225686/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-11 msj11
    "msj11" => [
        "http://[2409:8087:1e01:20::14]/PLTV/11/224/3221225657/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225657/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225657/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::29]/PLTV/11/224/3221225657/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::28]/PLTV/11/224/3221225657/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-13 msj13
    "msj13" => [
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225678/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225678/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::11]/PLTV/11/224/3221225678/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::10]/PLTV/11/224/3221225678/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::28]/PLTV/11/224/3221225678/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::29]/PLTV/11/224/3221225678/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::17]/PLTV/11/224/3221225678/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::16]/PLTV/11/224/3221225678/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-14 msj14
    "msj14" => [
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225692/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225692/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::31]/PLTV/11/224/3221225692/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::18]/PLTV/11/224/3221225692/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::19]/PLTV/11/224/3221225692/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::30]/PLTV/11/224/3221225692/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-15 msj15
    "msj15" => [
        "http://[2409:8087:1e01:20::14]/PLTV/11/224/3221225699/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225699/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::27]/PLTV/11/224/3221225699/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::26]/PLTV/11/224/3221225699/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-16 msj16
    "msj16" => [
        "http://[2409:8087:1e02:20::11]/PLTV/11/224/3221225674/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225674/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::14]/PLTV/11/224/3221225674/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225674/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::10]/PLTV/11/224/3221225674/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225674/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::16]/PLTV/11/224/3221225674/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::17]/PLTV/11/224/3221225674/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::28]/PLTV/11/224/3221225674/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::29]/PLTV/11/224/3221225674/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-17 msj17
    "msj17" => [
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225693/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::12]/PLTV/11/224/3221225693/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-18 msj18
    "msj18" => [
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225637/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::15]/PLTV/11/224/3221225637/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::12]/PLTV/11/224/3221225637/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::17]/PLTV/11/224/3221225637/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::13]/PLTV/11/224/3221225637/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::31]/PLTV/11/224/3221225637/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::30]/PLTV/11/224/3221225637/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::15]/PLTV/11/224/3221225637/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::14]/PLTV/11/224/3221225637/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::12]/PLTV/11/224/3221225637/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-19 msj19
    "msj19" => [
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225680/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225680/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::12]/PLTV/11/224/3221225680/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::14]/PLTV/11/224/3221225680/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::15]/PLTV/11/224/3221225680/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::31]/PLTV/11/224/3221225680/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::10]/PLTV/11/224/3221225680/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::30]/PLTV/11/224/3221225680/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::14]/PLTV/11/224/3221225680/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::11]/PLTV/11/224/3221225680/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-20 msj20
    "msj20" => [
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225688/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225688/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::14]/PLTV/11/224/3221225688/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::23]/PLTV/11/224/3221225688/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::22]/PLTV/11/224/3221225688/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::15]/PLTV/11/224/3221225688/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-21 msj21
    "msj21" => [
        "http://[2409:8087:1e01:20::42]/PLTV/11/224/3221226125/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::43]/PLTV/11/224/3221226125/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::40]/PLTV/11/224/3221226125/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::44]/PLTV/11/224/3221226125/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221226125/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::23]/PLTV/11/224/3221226125/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::22]/PLTV/11/224/3221226125/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221226125/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-22 msj22
    "msj22" => [
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225639/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::41]/PLTV/11/224/3221225639/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225639/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::33]/PLTV/11/224/3221225639/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::32]/PLTV/11/224/3221225639/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::21]/PLTV/11/224/3221225639/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::20]/PLTV/11/224/3221225639/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225639/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225639/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-23 msj23
    "msj23" => [
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225644/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225644/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225644/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225644/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-24 msj24
    "msj24" => [
        "http://[2409:8087:1e01:20::42]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::23]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::44]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::28]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::12]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::22]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::29]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::21]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::40]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::20]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::43]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::41]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::17]/PLTV/11/224/3221225640/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-25 msj25
    "msj25" => [
        "http://[2409:8087:1e01:20::14]/PLTV/11/224/3221225643/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::12]/PLTV/11/224/3221225643/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::18]/PLTV/11/224/3221225643/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::19]/PLTV/11/224/3221225643/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225643/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225643/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-26 msj26
    "msj26" => [
        "http://[2409:8087:1e01:20::19]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::41]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::16]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::26]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::16]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::14]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::15]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::27]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::17]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225638/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-27 msj27
    "msj27" => [
        "http://[2409:8087:1e01:20::14]/PLTV/11/224/3221225641/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::18]/PLTV/11/224/3221225641/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225641/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::29]/PLTV/11/224/3221225641/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225641/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225641/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::28]/PLTV/11/224/3221225641/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-28 msj28
    "msj28" => [
        "http://[2409:8087:1e01:20::14]/PLTV/11/224/3221225694/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225694/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::20]/PLTV/11/224/3221225694/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225694/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::21]/PLTV/11/224/3221225694/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225694/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-29 msj29
    "msj29" => [
        "http://[2409:8087:1e01:20::12]/PLTV/11/224/3221225636/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225636/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::29]/PLTV/11/224/3221225636/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225636/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::31]/PLTV/11/224/3221225636/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::30]/PLTV/11/224/3221225636/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::28]/PLTV/11/224/3221225636/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225636/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-30 msj30
    "msj30" => [
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225685/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::12]/PLTV/11/224/3221225685/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225685/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::12]/PLTV/11/224/3221225685/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::27]/PLTV/11/224/3221225685/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::26]/PLTV/11/224/3221225685/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::13]/PLTV/11/224/3221225685/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-31 msj31
    "msj31" => [
        "http://[2409:8087:1e01:20::10]/PLTV/11/224/3221225682/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225682/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::13]/PLTV/11/224/3221225682/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::11]/PLTV/11/224/3221225682/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::33]/PLTV/11/224/3221225682/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::32]/PLTV/11/224/3221225682/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::10]/PLTV/11/224/3221225682/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::12]/PLTV/11/224/3221225682/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-32 msj32
    "msj32" => [
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221225684/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221225684/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::17]/PLTV/11/224/3221225684/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::16]/PLTV/11/224/3221225684/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::11]/PLTV/11/224/3221225684/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::10]/PLTV/11/224/3221225684/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-33 msj33
    "msj33" => [
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225689/1.m3u8?IASHttpSessionId=",
		"http://[2409:8087:1e01:20::14]/PLTV/11/224/3221225689/1.m3u8?IASHttpSessionId=",
		"http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225689/1.m3u8?IASHttpSessionId=",
		"http://[2409:8087:1e02:20::27]/PLTV/11/224/3221225689/1.m3u8?IASHttpSessionId=",
		"http://[2409:8087:1e02:20::26]/PLTV/11/224/3221225689/1.m3u8?IASHttpSessionId="
    ],

    // 至臻视界 zzsj
    "zzsj" => [
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221225687/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::13]/PLTV/11/224/3221225687/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::13]/PLTV/11/224/3221225687/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::29]/PLTV/11/224/3221225687/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::12]/PLTV/11/224/3221225687/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::28]/PLTV/11/224/3221225687/1.m3u8?IASHttpSessionId="
    ],

    // 咪视界-4K-1 msj4k1
    "msj4k1" => [
        "http://[2409:8087:1e02:20::15]/PLTV/11/224/3221226080/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::14]/PLTV/11/224/3221226080/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::11]/PLTV/11/224/3221226080/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::10]/PLTV/11/224/3221226080/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::16]/PLTV/11/224/3221226080/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e01:20::18]/PLTV/11/224/3221226080/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::14]/PLTV/11/224/3221226080/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::11]/PLTV/11/224/3221226080/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::25]/PLTV/11/224/3221226080/1.m3u8?IASHttpSessionId=",
        "http://[2409:8087:1e02:20::24]/PLTV/11/224/3221226080/1.m3u8?IASHttpSessionId="
    ]
];

// ===============================
// 随机播放逻辑（无需修改）
// ===============================

// 获取频道ID参数
$channelId = isset($_GET['id']) ? $_GET['id'] : '';

// 检查频道是否存在
if (empty($channelId) || !isset($channels[$channelId])) {
    // 返回错误信息或默认频道
    header('HTTP/1.1 404 Not Found');
    echo "频道不存在或参数错误";
    exit;
}

// 从该频道中随机选择一个
$urls = $channels[$channelId];
$randomIndex = array_rand($urls);
$selectedUrl = $urls[$randomIndex];

// 重定向到选中的播放地址
header('Location: ' . $selectedUrl);
exit;

?>
