<?php
require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');
define('LINE_API',"https://notify-api.line.me/api/notify"); 
$token = "JbgK8y3vRxZfbW0byyaoL0Ba7QZ13LTYGjABbkRAGa6";    //Line notify
$access_token = '8SbJvTLOsNAtBmcWCPLMLA6bJuFPqOW39YfYDSuwIscDKjGGUt28RzD3RUns/khrcXxbSz6bL2rDJ2mRnszhJxg0psMNOuZwp200CzoWUhT+neIGL5Uqsez+Q4ru666yn+bO0PY363gSh06itF7G9QdB04t89/1O/w1cDnyilFU=';
$channelSecret = '6f3512faf08bf2a78999ac0a2e34be6d';
$idPush = 'U09793a2f585d3ca2c2e7fdbe41acea8e';
$content = file_get_contents('php://input');
$events = json_decode($content, true);
$com = substr($content, 274, -55);
$ar_new1 = str_replace($ar_word1,$ar_word3,$com);
$ar_new2 = str_replace($ar_word2,$ar_word3,$com);
$ar_textnum1 = array("");
$ar_textnum2 = array("");
$n = 0;$max = 10;
for($i = $n;$i < $max;$i++){
	for($n = $i;$n <= $max;$n++){
		if($ar_new1[$i] != $ar_new1[$n]){
			$ar_textnum1[$i] = $ar_new1[$n];
			$i=$n;
		}		
	}
}
$ar_textnum2[0] = $ar_new1[0];
for($i = 0;$i < $max;$i++){
	$ar_textnum2[$i+1] = $ar_textnum1[$i];	
}
///////////////////////////////////////////////////
$test3 = implode("",$ar_textnum2);
$test4 = strlen($test3);
if($test4 > 1){
	$newstr = str_replace($at_word0,$ar_word3,$ar_new2,$mcon);
	$test5 = strlen($newstr);
}
//////////////////////////////////////////////////
$roomnumber = implode("-",$ar_textnum2);
for($i = 0;$i < 20;$i++){
 if($roomnumber[$i] > 0){
    $D = 1;
 }else if($D == 1 && $roomnumber[$i+1] <= 0){
     $roomnumber[$i] = " ";
  }
}

$h1 = (($w1*$roomnumber)+( $w3*$newstr)) /100  ;
$h1 =$exp((double) -$h1);
$h1 = 1 / (1 + $h1);
$h2 = (($w2*$roomnumber)+( $w4*$newstr)) /100;
$h2 = $exp((double) -$h2);
$h2 = 1 / (1 +$ h2);
$h=$h1+$h2;

$y1 = (($w5*$h1)+( $w7*$h2));
$y2 = (($w6*$h1)+( $w8*$h2));
$y=$y1+$y2;
$e1 = 0.5*$pow((0-$y1),2);
$e2 = 0.5*$pow((1-$y2),2);
$eet = e1+e2;

//notify_message($test5);
if($eet <= 9){
	if (!is_null($events['events'])) {
	 foreach ($events['events'] as $event) {
	  if ($event['type'] == 'message' && $event['message']['type'] == 'text'){		
		 if($test4 > 1){
			date_default_timezone_set("Asia/Bangkok");
 			if(date("H:i") >= "16:30" || date("H:i") <= "8:30"){		
			notify_message("เวลา 16.30น. มโนยกเลิกการทำงาน\nและเปิดใช้งานอีกครั้ง 8.30น.",$token);		
 			} else {
				for($i = 0;$i <= 5;$i++){
			if($roomnumber[$i] >= 5){
			notify_message("หม้ายเลขห้องผิดพลาดกรุณาส่งคำสั่งมาใหม่\nหม้ายเลขห้องคือ 1 2 3 4",$token);
			}else{						
				$Topic = "NodeMCU1";
				$lineMsg = "codeA".$roomnumber;
				getMqttfromlineMsg($Topic,$lineMsg);				
			 
			  }
		       }
		    }
		  }
		else if($test4 < 1 ){
		  if($com == "ใช่" || $com == "ใช"){
			$Topic = "NodeMCU1";
			$lineMsg = "codeY";
			getMqttfromlineMsg($Topic,$lineMsg);	
		  }
		else if($com == "ไม่ใช่"){
			$Topic = "NodeMCU1";
			$lineMsg = "codeN";
			getMqttfromlineMsg($Topic,$lineMsg);	
		 }
		else if($com == "หลัง"){
			$Topic = "NodeMCU1";
			$lineMsg = "codeB";
			getMqttfromlineMsg($Topic,$lineMsg);	
		 }
		else if($com == "หน้า"){
			$Topic = "NodeMCU1";
			$lineMsg = "codeF";
			getMqttfromlineMsg($Topic,$lineMsg);	
		 }
		else if($com == "กดปุ่ม"){
			$Topic = "NodeMCU1";
			$lineMsg = "codeP";
			getMqttfromlineMsg($Topic,$lineMsg);	
			  }}
	}
      }
    }
  } 
/////////////////////////////////////////////////////
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
function notify_message($message,$token){
 $queryData = array('message' => $message);
 $queryData = http_build_query($queryData,'','&');
 $headerOptions = array( 
         'http'=>array(
            'method'=>'POST',
            'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                      ."Authorization: Bearer ".$token."\r\n"
                      ."Content-Length: ".strlen($queryData)."\r\n",
            'content' => $queryData
         ),
 );
 $context = stream_context_create($headerOptions);
 $result = file_get_contents(LINE_API,FALSE,$context);
 $res = json_decode($result);
 return $res;
}
 ?>



