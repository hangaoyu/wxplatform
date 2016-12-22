<?php
/**
 * Created by PhpStorm.
 * User: hangaoyu
 * Date: 16/12/1
 * Time: 下午5:02
 */
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://xike.jojin.com:8081/wechat?".$_SERVER['QUERY_STRING']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$output = curl_exec($ch);
curl_close($ch);
echo $output;