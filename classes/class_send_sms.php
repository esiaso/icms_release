<?php

class Send_SMS
{
	public function send_sms_single($number,$textmessage)
	{
		
		$user = "isahopondo";
		$password = "HaLVFeDEHCIXYD";
		$api_id = "3378062";
		$baseurl ="http://api.clickatell.com";
	 
		$text = urlencode($textmessage);
		$to = trim($number);
	 
		// auth call
		$url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";
	 
		// do auth call
		$ret = file($url);
	 
		// explode our response. return string is on first line of the data returned
		$sess = explode(":",$ret[0]);
		if ($sess[0] == "OK") {
	 
			$sess_id = trim($sess[1]); // remove any whitespace
			$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text";
	// die($url);
			// do sendmsg call
			$ret = file($url);
			$send = explode(":",$ret[0]);
	 
			if ($send[0] == "ID") {
				echo "successnmessage ID: ". $send[1];
			} else {
				echo "Send message failed";
			}
		} else {
			echo "Authentication failure: ". $ret[0];
		}
		
	}
	
	/*public function send_sms_single($number,$message)
	{
		  $gatewayURL  =   'http://197.176.124.79:9333/ozeki?'; 
		  $request = 'login=admin'; 
		  $request .= '&password=abc123'; 
		  $request .= '&action=sendMessage'; 
		  $request .= '&messageType=SMS:TEXT'; 
		  $request .= '&recepient='.urlencode($number); 
		  $request .= '&messageData='.urlencode($message); 
		
		  $url =  $gatewayURL . $request;  
		
		  //Open the URL to send the message 
		   file($url); 
	}*/
}
?>