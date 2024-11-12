<?php

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

require_once(dirname(__FILE__).'/config.php');

$link="https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
$json=ms_query($link);

foreach($json['states'] as $k=>$v){
 	echo($v['name']." - ".$v['id']."<BR>");

}
//mysqli_query($db,"update c_dict set dict_value='".base64_encode(json_encode($json))."' where dict_code='CUSTOMERORDER_STATES'");


$link="https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes";
$json=ms_query($link);

foreach($json['rows'] as $k=>$v){
 	echo($v['name']." - ".$v['id']."<BR>");

}
//mysqli_query($db,"update c_dict set dict_value='".base64_encode(json_encode($json))."' where dict_code='CUSTOMERORDER_ATTRIBUTES'");


/*

$link="https://api.moysklad.ru/api/remap/1.2/entity/demand/metadata";
$json=ms_query($link);

foreach($json['states'] as $k=>$v){
 	echo($v['name']." - ".$v['id']."<BR>");

}
mysqli_query($db,"update c_dict set dict_value='".base64_encode(json_encode($json))."' where dict_code='DEMAND_STATES'");


$link="https://api.moysklad.ru/api/remap/1.2/entity/demand/metadata/attributes";
$json=ms_query($link);

foreach($json['rows'] as $k=>$v){
 	echo($v['name']." - ".$v['id']."<BR>");

}
mysqli_query($db,"update c_dict set dict_value='".base64_encode(json_encode($json))."' where dict_code='DEMAND_ATTRIBUTES'");



exit();
echo("<pre>");
$link="https://api.moysklad.ru/api/remap/1.2/entity/webhook";
$json=ms_query($link);
var_dump($json);

*/