<?php

$url = "http://0.0.0.0:1024/frontend/updateClient";
$post_data = array("client_pwd" => "rexun123123");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$output = curl_exec($ch);
curl_close($ch);

//打印获得的数据
print_r($output);
