<?php
/**
* Author: 1C
* Date  : 2020-9-13
* Blog  : https://www.bugku.net/
*/

echo '
    <title>抖音解析—去水印-闲着没事写的！</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link type="text/css" rel="styleSheet"  href="css.css" />
    <form action="index.php" method="POST">
    <input type="text" name="url" placeholder="直接复制分享链接扔给我">
    <input type="submit" value="Fuck it！">
    <br/>
    <br/>
    <br/>
    ';


function get_content_url($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: Mozilla/5.0 (Linux; U; Android 9; zh-cn; HLK-AL00 Build/HONORHLK-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 MQQBrowser/10.1 Mobile Safari/537.36'
    ));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, False);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, False);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function get_share_url($share_url){
        $regular = '/\s\shttps(.*?)\s复制/';
        preg_match($regular,$share_url,$match);
        return 'https'.$match[1];
}

function get_302_url($url){
        $regular = '/<a.*?href="(.*?)".*?\/?>/i';
        preg_match($regular,$url,$match);
        return $match[1];
}

function get_id($url){
        $regular = '/video\/([0-9]\d+)/';
        preg_match($regular,$url,$match);
        return $match[1];
}

function get_url1($url){
        $regular = '/https\:\/\/aweme\.snssdk\.com\/aweme\/v1\/playwm\/(.*?)0\"\]\}\,\"/';
        preg_match($regular,$url,$match);
        return 'https://aweme.snssdk.com/aweme/v1/play/'.$match[1].'0';
}

function get_image_url($url){
        $regular = '/(https\:\/\/p\d+\-dy\-ipv6\.byteimg\.com\/img\/tos\-cn\-p.*?_large)/';
        preg_match($regular,$url,$match);
        $image = str_replace('_300x400', '', $match[1]);
        return $image;
}

function get_url($url) {
        $url = 'https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids='.get_id(get_302_url(get_content_url($url)));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Referer:'.get_302_url(get_content_url($url)),
        'User-Agent: Mozilla/5.0 (Linux; U; Android 10; zh-cn; ELE-AL00 Build/HUAWEIELE-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 MQQBrowser/10.1 Mobile Safari/537.36'
        ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, False);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, False);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
};

function get_video_url($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: Mozilla/5.0 (Linux; Android 5.1; OPPO A59s Build/LMY47I; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/76.0.3809.89 Mobile Safari/537.36 T7/11.20 SP-engine/2.16.0 baiduboxapp/11.20.0.14 (Baidu; P1 5.1)'
        ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, False);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, False);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
}

$url = $_POST['url'];
if (empty($url)){

}elseif (strstr($url, douyin)) {
    $share_url = get_share_url($url);
    echo '
    <p>解析完成，点击下方按钮进行下载无水印视频或封面</p><br/><br/><br/><a target="_blank" rel="noreferrer" href="'.get_302_url(get_video_url(get_url1(get_url($share_url)))).'">视频下载地址</a><br/><br/><a target="_blank" rel="noreferrer" href="'.get_image_url(get_url($share_url)).'">封面下载地址</a>';
}else{
    echo '<p align="center" style="font-size:20px">链接可能输错了，再试试</p>';
}

