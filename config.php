<?php
	
	define('SYSTEM_NAME','iCPDKenya');
	//define('WEBPHOTOROOT', '/home/synergyadmininfo/cpd/images/portraits/');
	define('WEBPHOTOROOT', 'D:/xampp/htdocs/icms/images/portraits/'); //local
	define('ACTIVITIESROOT', 'D:/xampp/htdocs/icms/images/activities/');
	//define('WEBLINK','http://cpd.medsynergy.info/'); // Website link
	define('WEBLINK','http://localhost/icms/'); // Website link	
	define('USERPHOTODIR',WEBLINK.'images/portraits/');
	define('ACTIVITIESDIR',WEBLINK.'images/activities/');
	//$IMAGESIZE = 
	define('MAX_SIZE',4000);
	
	define('SMTPUSER', 'synergy@medsynergy.info'); // sec. smtp username
	define('SMTPPWD', 'Synergy2012##'); // sec. password
	define('SMTPSERVER', 'mail.medsynergy.co.ke'); // sec. smtp server
	
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
	$msg='';
	define('TOKEN_LENGTH',6);
	define('ACTIVITYPASS',65);
	
	
/*
	define('DBNAME', 'synergycpd');
	define('DBUSERNAME', 'synergyadmin');
	define('DBPASS', '3MySQL.DBSynergy@@');
	define('DBHOST', '97.74.82.245');
	define('DBTYPE', 'mysql');
*/	
	
	
	function get_separator()	
	{
		$string = '|';
		$salt = sha1('Synergy2010@@19811234'); 
        $salt = substr($salt, 0, 4); 
        $hash = base64_encode( $string . $salt ); 
		
     	return  $hash;
	}

?>