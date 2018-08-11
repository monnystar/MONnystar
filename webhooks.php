<?php
require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'Q4Ivpqy+fEijDfwAnIDvN6PprOy69lxLVpnTia1q8Hhwgfz8csZFSVqiQvccWdZKcXxbSz6bL2rDJ2mRnszhJxg0psMNOuZwp200CzoWUhTawVgA3QnjIJ+3z3gYLRHoYGiUApr663b9qQWM93jwmwdB04t89/1O/w1cDnyilFU=';
$channelSecret = '6c6ca6e38c984ba4b649dc6cf0a3c5fe';
$idPush = 'U09793a2f585d3ca2c2e7fdbe41acea8e';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);

$com = substr($content, 227, -5);
  
if (!is_null($events['events'])) {
  // Loop through each event
  foreach ($events['events'] as $event) {
    // Reply only when message sent is in 'text' format
    if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
      
         $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
         $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
         $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("$com");
         $response = $bot->pushMessage($idPush, $textMessageBuilder);
      
      if($com == "เปิดไฟ"){      
         $Topic = "NodeMCU1";
         $lineMsg = "1";
         getMqttfromlineMsg($Topic,$lineMsg);
      }      
        else if($com == "ปิดไฟ")         
         $Topic = "NodeMCU1";
         $lineMsg = "0";
         getMqttfromlineMsg($Topic,$lineMsg);
      }
  }else {
     $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
      $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
      $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('1');
      $response = $bot->pushMessage($idPush, $textMessageBuilder);
    }
  }
} 

function pubMqtt($topic,$msg){
   $APPID= "samickrock/"; //enter your appid
   $KEY = "MC6kLl4SYiDW2qd"; //enter your key
   $SECRET = "ASn4eO61s65RPZ3ujHSHNulOz"; //enter your secret
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
    // $Topic = "NodeMCU1";
    //  $lineMsg = "$com";
    //  getMqttfromlineMsg($Topic,$lineMsg);
 
 ?>
