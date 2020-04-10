<?php
//https://asia-east2-newchatbottest02.cloudfunctions.net/webhook
//https://f0e495c8.ngrok.io
//https://siitchatbot.000webhostapp.com
date_default_timezone_set("Asia/Bangkok");
$date = date("Y-m-d");
$time = date("H:i:s");
$json = file_get_contents('php://input');
$request = json_decode($json, true);
$queryText = $request["queryResult"]["queryText"];
$answer = $request["queryResult"]["fulfillmentText"];

$message = $queryText;
$source = $request["originalDetectIntentRequest"]["source"];

$userId = $request['originalDetectIntentRequest']['payload']['data']['source']['userId'];

$opts = [
"http" =>[
    //Channel access token
//"header" => "Content-Type: application/json\r\n"."Authorization: Bearer Dbntf0HKGslTEtzvWg4YHxzPDFG+lqAtCHYRPw883DSi5jhv9HrSDDtqdC/ciYlyoeXn+w1pI670mv51UJPbtvyJtaZn0qRd21mBhZMTno8Ie2otG5yWLgHuq8/jX57Q57Ncd1SLxhXOkSdz2orldQdB04t89/1O/w1cDnyilFU="
"header" => "Content-Type: application/json\r\n"."Authorization: Bearer EfRX4LvlQRftxWawuF8aimRdoVi+Fmc5lVAWiaNO9r2JXlWZRUH0PILJpux95uY3/8xhk2eyupL6IaMAC5MF8dGESsX41MhRo/++nceTqfXiP9EFeY6VCfqJ7uTMIIrSu+KDsycjxNporOa3E66VGwdB04t89/1O/w1cDnyilFU="
]
];

$context = stream_context_create($opts);
$profile_json = file_get_contents('https://api.line.me/v2/bot/profile/'.$userId,false,$context);
$profile_array = json_decode($profile_json,true);
$pic_ = $profile_array['pictureUrl'];
$name_ = $profile_array['displayName'];

$myfile = fopen("log$date.txt", "a") or die("Unable to open file!");
//$log = $date."--".$time."\t".$source."\t".$userId."\t".$name_."\t".$pic_."\t".$queryText."\t".$answer."\n";
$log = $date."--".$time."\t".$source."\t".$userId."\t".$name_."\t".$queryText."\t".$answer."\n";
fwrite($myfile,$log);
fclose($myfile);

//$message_all = “คุณ “.$name.” ถามว่า “.$message;
if($source=='line') { 
$message_all = $source."\n\nจากคุณ: ".$name_."\n\nถามว่า: ".$message."\n\nตอบว่า: ".$answer."\n\nถ้าต้องการตอบเพิ่มเติม: ".'https://1cbfa8db.ngrok.io/push.php?uid='.$userId;}
else { $message_all = $source."\n\nถามว่า: ".$message."\n\nตอบว่า: ".$answer;}

$content = $date.' '.$time.' '.$name_.' '.$userId.' '.$pic_.' '.$message."\n";

/* $myfile = fopen(“log_.txt”, “a”) or die(“Unable to open file!”);
fwrite($myfile,$content);
fclose($myfile);
*/
$chOne = curl_init();
curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
// SSL USE
curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0);
//POST
curl_setopt( $chOne, CURLOPT_POST, 1);
// Message
curl_setopt( $chOne, CURLOPT_POSTFIELDS, $message);
//ถ้าต้องการใส่รุป ให้ใส่ 2 parameter imageThumbnail และimageFullsize
//curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=$message_all&imageThumbnail=$pic_&imageFullsize=$pic_");
curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=$message_all");
// follow redirects
curl_setopt( $chOne, CURLOPT_FOLLOWLOCATION, 1);
//ADD header array
$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer h8d38YzBqQlhWIupJbH7RvKsdwC1sCI2hIkHBFVvBtq', );
curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
//RETURN
curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec( $chOne );
//Check error
if(curl_error($chOne)) { echo 'error:' . curl_error($chOne); }
else { $result_ = json_decode($result, true);
//echo “status : “.$result_[‘status’];
//echo “message : “. $result_[‘message’];
}
//Close connect
curl_close( $chOne );
?>


