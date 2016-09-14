<?php
	
	define('SYSTEM_NAME','iCPDKenya');
	define('WEBPHOTOROOT', '/home/images/portraits/');
	define('ACTIVITIESROOT', '/home/images/activities/');
	//define('WEBPHOTOROOT', 'http://www.icpdkenya.org/images/portraits/');
	//define('WEBPHOTOROOT', 'D:/xampp/htdocs/cpd/images/portraits/'); //local
	define('WEBLINK','http://www.icpdkenya.org/'); // Website link
	//define('WEBLINK','http://localhost/cpd/'); // Website link	
	define('USERPHOTODIR',WEBLINK.'images/portraits/');
	define('ACTIVITIESDIR',WEBLINK.'images/activities/');
	//$IMAGESIZE = 
	define('MAX_SIZE',4000);
	
	define('SMTPUSER', ''); // sec. smtp username
	define('SMTPPWD', ''); // sec. password
	define('SMTPSERVER', ''); // sec. smtp server
	

	define('MailSupport','support@icpdkenya.org');
	define('NOREPLY','noreply@icpdkenya.org');
	
	define('DBNAME', 'synergycpd');
	define('DBUSERNAME', 'admin');
	define('DBPASS', 'password');
	define('DBHOST', 'localhost');
	define('DBTYPE', 'mysql');
	
	define('CLAIM_DELIMITER',' ');
	define('AUTHENTICATIONTYPE','mysql');
	
	$CryptMethod ='sha1';
	
	define('TOKEN_LENGTH',6);
	define('ACTIVITYPASS',65);
	define('MAXQUESTIONS',5);	


	
	
	function get_separator()	
	{
		$string = '|';
		$salt = sha1('Synergy2010##19811234'); 
        $salt = substr($salt, 0, 4); 
        $hash = base64_encode( $string . $salt ); 
		
     	return  $hash;
	}
	

?>
