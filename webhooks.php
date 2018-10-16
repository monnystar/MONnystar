<?php
require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');
$access_token = 'k6WZOt6gRL/SN+/8L/3Y2UfQgwd7T9VxIWzl7YsW5CGwMB2OuIczp2zCmVYjmIA5cXxbSz6bL2rDJ2mRnszhJxg0psMNOuZwp200CzoWUhQumTElDznDxAIxoRj6jxf8/CfIoKkkDiAANrI+jRrcbQdB04t89/1O/w1cDnyilFU=';
$channelSecret = '6c6ca6e38c984ba4b649dc6cf0a3c5fe';
$idPush = 'U09793a2f585d3ca2c2e7fdbe41acea8e';
$content = file_get_contents('php://input');
$events = json_decode($content, true);
$com = substr($content, 274, -5);
$ar_word1 = array("!","@","#","$","%","^","&","(",")","_","+","0","-","=",
"Q","W","E","R","T","Y","U","I","O","P","{","}",
"A","S","D","F","G","H","J","K","L",":","'",
"Z","X","C","V","B","N","M","<",">","?",
"q","w","e","r","t","y","u","i","o","p","[","]",
"a","s","d","f","g","h","j","k","l",";",
"z","x","c","v","b","n","m",",",".","/","|",
"ๆ","ไ","ำ","พ","ะ","ั","ี","ร","น","ย","บ","ล",
"ฟ","ห","ก","ด","เ","้","่","า","ส","ว","ง",
"ผ","ป","แ","อ","ิ","ื","ท","ม","ใ","ฝ",
"๐","ฎ","ฑ","ธ","ํ","๊","ณ","ฯ","ญ","ฐ",
"ฤ","ฆ","ฏ","โ","ฌ","็","๋","ษ","ศ","ซ",".",
"ฉ","ฮ","ฺ","์","?","ฒ","ฬ","ฦ",
"ๅ","-","ภ","ถ","ุ","ึ","ค","ต","จ","ข","ช",
"+","๑","๒","๓","๔","ู","฿","๕","๖","๗","๘","๙","๐");
$ar_word2 = array("");
$ar_new = str_replace($ar_word1,$ar_word2,$at);
foreach ($ar_new as $num)
		if (!is_null($events['events'])) {
		 foreach ($events['events'] as $event) {
		 if ($event['type'] == 'message' && $event['message']['type'] == 'text') {  
			 $Topic = "NodeMCU1";
			 $lineMsg = "$num";
			getMqttfromlineMsg($Topic,$lineMsg);	   
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
