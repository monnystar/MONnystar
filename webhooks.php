<?php
require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'Q4Ivpqy+fEijDfwAnIDvN6PprOy69lxLVpnTia1q8Hhwgfz8csZFSVqiQvccWdZKcXxbSz6bL2rDJ2mRnszhJxg0psMNOuZwp200CzoWUhTawVgA3QnjIJ+3z3gYLRHoYGiUApr663b9qQWM93jwmwdB04t89/1O/w1cDnyilFU=';
$channelSecret = '6c6ca6e38c984ba4b649dc6cf0a3c5fe';
$idPush = 'U09793a2f585d3ca2c2e7fdbe41acea8e';

$content = file_get_contents('php://input');
$events = json_decode($content, true);
$com = substr($content, 274, -5);

$tesxt = array(" ","ไป","ห้องหนึ่ง","ห้องสอง","ห้องสาม","ห้องสี่","ห้องห้า","ห้องหก");
$tesxt1 = array("","-","1","2","3","4","5","6");
list($robotorderinput, $missionanalysis) = explode("เอกสาร", $com);
for($i = 0; $i <= 7; $i++){
	$missionanalysis = str_replace($tesxt[$i],$tesxt1[$i],$missionanalysis);
	}

if (!is_null($events['events'])) {
  foreach ($events['events'] as $event) {
    if ($event['type'] == 'message' && $event['message']['type'] == 'text') {  
      if($robotorderinput == "ส่ง"){
         $Topic = "NodeMCU1";
         $lineMsg = "$robotorderinput";
         getMqttfromlineMsg($Topic,$lineMsg);
     }
   } 
  }
 } 

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
