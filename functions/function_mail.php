<?php

	

function smtpmailer($to, $from, $from_name, $subject, $body, $is_gmail = true) { 
		
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = SMTPSERVER;
		$mail->SMTPAuth = true;
		$mail->Username = SMTPUSER;
		$mail->Password = SMTPPWD;
		
		$mail->From=$from;
		$mail->FromName=$from_name;
		$mail->Sender="mailer@medsynergy.info";
		$mail->AddReplyTo("noreply@medsynergy.info", "Replies for my site");
		
		$mail->AddAddress($to);
		$mail->AddBCC(SMTPUSER);
		$mail->Subject = $subject;
		
		$mail->IsHTML(true);
		$mail->Body = $body;
		$mail->AltBody="This is text only alternative body.";
		
		if(!$mail->Send())
		{
		  // echo "Error sending: " . $mail->ErrorInfo;;
		}
		else
		{
			return true;
		   //echo "Confirmation mail has been sent to ".$to.". Please check your inbox.";
		}

}

?>