<?php
$com = "AAA";
require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = '8SbJvTLOsNAtBmcWCPLMLA6bJuFPqOW39YfYDSuwIscDKjGGUt28RzD3RUns/khrcXxbSz6bL2rDJ2mRnszhJxg0psMNOuZwp200CzoWUhT+neIGL5Uqsez+Q4ru666yn+bO0PY363gSh06itF7G9QdB04t89/1O/w1cDnyilFU=';
$channelSecret = '6f3512faf08bf2a78999ac0a2e34be6d';
$idPush = 'U09793a2f585d3ca2c2e7fdbe41acea8e';

$content = file_get_contents('php://input');
$events = json_decode($content, true);
$linemessage = array('เปิดไฟ','ปิดไฟ','เปิดไฟปิดไฟ');
$com = substr($content, 274, -5);
$textmessagerobot = str_replace(" ","",$com);
$i = 0;
  $Topic = "NodeMCU1";
  $lineMsg = "sdfsd"+"$com";
   getMqttfromlineMsg($Topic,$lineMsg);
/*
if (!is_null($events['events'])) {
  foreach ($events['events'] as $event) {
    if ($event['type'] == 'message' && $event['message']['type'] == 'text') {  
      for($i = 0; $i <= 3; $i++){
      if($textmessagerobot == $linemessage[$i]){
         $Topic = "NodeMCU1";
         $lineMsg = "$textmessagerobot";
         getMqttfromlineMsg($Topic,$lineMsg);
     }
    }
   } 
  }
 } 
*/
function pubMqtt($topic,$msg){
   $APPID= "samickrock/";
   $KEY = "MC6kLl4SYiDW2qd";
   $SECRET = "ASn4eO61s65RPZ3ujHSHNulOz"; 
   $Topic = "$topic"; 
   put("https://api.netpie.io/microgear/".$APPID.$Topic."?retain&auth=".$KEY.":".$SECRET,$msg);
   
  }
 function getMqttfromlineMsg($Topic,$lineMsg){
 
    $pos = strpos($lineMsg, ":");
    if($pos){
      $splitMsg = explode(":", $lineMsg);
      $topic = $splitMsg[0];
      $msg = $splitMsg[1];
      pubMqtt($topic,$msg);
    }else{
      $topic = $Topic;
      $msg = $lineMsg;
      pubMqtt($topic,$msg);
    }
  }
function put($url,$tmsg)
{ 
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);   
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $tmsg);
    curl_setopt($ch, CURLOPT_USERPWD, "MC6kLl4SYiDW2qd:ASn4eO61s65RPZ3ujHSHNulOz");     
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response . "\r\n";
    return $response;
}  
 ?>
