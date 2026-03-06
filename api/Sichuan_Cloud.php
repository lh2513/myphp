<?php
error_reporting(0);
$n = [
    "cd_jy1" => "jypull.jianyangrongmei.cn&fb9eea7d019310009e23a40200000000", //成都简阳新闻综合

    "my1" => "fjgcl.myxwcm.cn&9ff5a9b001971000b986a10c00000000", //绵阳新闻综合
    "my2" => "fjgcl.myxwcm.cn&9ff6671801971000c0df254f00000000", //绵阳科教生活
//    "my_jy1" => "jypull.jyxrmtzx.xyz&9dfe6ab1018e1000f4bb01a600000000", //绵阳江油新闻综合
//    "my_bc1" => "bcpull.bcrongmei.cn&63820f8101941000036a690c00000000", //绵阳北川综合
    "my_az1" => "azpull.myaztv.cn&2cd42a81018f10007b6e374500000000", //绵阳安州综合
    "my_yt1" => "ytpull.ytxrmt.cn&73957476019110000fa2d07e00000000", //绵阳盐亭综合
    "my_zt1" => "ztpull.ztrmfb.cn&b98e28f7019310006d65e1b500000000", //绵阳梓潼综合

    "ab1" => "apppull.aba-news.com.cn&be5325350190100009f6751d00000000", //阿坝新闻综合
    "ab2" => "apppull.aba-news.com.cn&be57fb5401901000b611a82100000000", //阿坝藏语综合
    "ab_reg1" => "regpull.regrmt.cn&fba4bdf0019310007748ce6c00000000", //阿坝若尔盖综合

    "nj_dx1" => "dxpull.njdxrm.cn&93f6de420192100084d4e49300000000", //内江东兴新闻
    "nj_zz1" => "zzpull.zzrmtzx.cn&75c3758a0193100092e8cea100000000", //内江资中综合

    "dy_gh1" => "dcpull.ghrmt.cn&5c2bdb1a018f100098165ffc00000000", //德阳广汉综合

    "ls_nn1" => "nnpull.ningnan.gov.cn&bd3e289e018f100042d46e9300000000", //凉山宁南综合
    "ls_zj1" => "dcpull.sctvcloud.com&4bb3c2bd01881000e843b73a00000000", //凉山昭觉综合
    "ls_gl1" => "glpull.glxrmtzx.cn&2a1603a1019410001fedc3cd00000000", //凉山甘洛综合
    "ls_mn1" => "mnpull.sichuanmianning.cn&1a5459a9019410003d361ee300000000", //凉山冕宁电视

//    "ga_ls1" => "pgpull.lspuge.cn&7414eab70192100000f07f6e00000000", //广安邻水综合
    "ga_ws1" => "wspull.wsxrmt.com.cn&fb899c55019310000893a7cd00000000", //广安武胜综合

    "ms1" => "mspull.scmstv.cn&ce36a16001931000ae80bca700000000", //眉山综合
    "ms2" => "mspull.scmstv.cn&ce37b20c019310008a26359200000000", //眉山文旅乡村
    "ms_dl1" => "dlpull.2289869471.com&08f432a6018f10004816cbcd00000000", //眉山丹棱综合

    "sn_px1" => "pxpull.pengxiyun.cn&03c29776018f10002512b07f00000000", //遂宁蓬溪新闻综合

    'nc1' => "ncrmpull.cnncw.cn&8f7b908601931000dd6ee79a00000000", //南充综合
    'nc2' => "ncrmpull.cnncw.cn&8f7f8d3101931000ffbe3ba700000000", //南充科教生活
];
$id = isset($_GET['id'])?$_GET['id']:'cd_jy1';
$fmt = isset($_GET['t'])?$_GET['t']:'hls'; //hls flv
$ar = explode("&", $n[$id]);
$host = $ar[0];
$id = $ar[1];

$key = "5df6d8b743257e0e38b869a07d8819d2";
$wsTime = time()+60000;

if($fmt=="hls"||$fmt==""){
    $hlsurl = "http://{$host}/live/{$id}/playlist.m3u8";
    $hlsuri = "/live/{$id}/playlist.m3u8";
    $hlswsSecret = md5($key.$hlsuri.$wsTime);
    $m3u8 = $hlsurl."?wsSecret={$hlswsSecret}&wsTime=".$wsTime;
    header("location:".$m3u8);
    //echo $m3u8;
}
if($fmt=="flv"){
    $flvurl = "http://{$host}/live/{$id}.flv";
    $flvuri = "/live/{$id}.flv";
    $flvwsSecret = md5($key.$flvuri.$wsTime);
    $flv = $flvurl."?wsSecret={$flvwsSecret}&wsTime=".$wsTime;
    header("location:".$flv);
    //echo $flv;
}
?>