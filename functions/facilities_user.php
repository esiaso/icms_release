<?php
	/*$dbname	=	'medexchange';
	$dbusername	=	'synergyadmin';
	$dbpassword	=	'3MySQL.DBSynergy@@';
	$dbhost	=	'97.74.82.245';
	$dbtype	=	'mysql';*/


	include("../config.php");
	
	// Opens a connection to a mySQL server
	$connection=mysql_connect (DBHOST, DBUSERNAME, DBPASS);
	if (!$connection) {
	  die("Not connected : " . mysql_error());
	}
	
	// Set the active mySQL database
	$db_selected = mysql_select_db(DBNAME, $connection);
	if (!$db_selected) {
	  die ("Can\'t use db : " . mysql_error());
	}

		if(isset($_POST['partial_state']) && isset($_POST['facility'])) {
			$queryString = mysql_escape_string($_POST['partial_state']);	
			$facility = mysql_escape_string($_POST['facility']);			
			// Is the string length greater than 0?			
			if(strlen($queryString) >0) {
			$sql= "SELECT DISTINCT(cme_users.user_id),CONCAT(`first_name`,' ',`last_name`)  as primaryname
					FROM cme_provider_users
    				INNER JOIN cme_users ON (cme_provider_users.user_id = cme_users.user_id)
					WHERE (`first_name` LIKE '%$queryString%' OR `last_name` LIKE '%$queryString%' OR `reg_no` LIKE '%$queryString%') AND provider_id='$facility' ORDER BY `first_name` ASC LIMIT 10 ";
			
			$query = mysql_query($sql);	
			$userslist ='';
			if($query) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($result = mysql_fetch_array($query)) 
					{
						$code = $result['user_id'];
						$userslist.='<li abbr="'.$code.'" attrname="'.$result['primaryname'].'">'.$result['primaryname'].'</li>';
	         		}
				echo $userslist;
				} 
			} else {
				// Dont do anything.
			} // There is a queryString.
		} elseif(isset($_POST['partial_state']) && isset($_POST['provider'])) {
			$queryString = mysql_escape_string($_POST['partial_state']);	
			$provider = mysql_escape_string($_POST['provider']);			
			// Is the string length greater than 0?			
			if(strlen($queryString) >0) {
			$sql= "SELECT DISTINCT(cme_users.user_id),CONCAT(`first_name`,' ',`last_name`)  as primaryname
					FROM cme_provider_users
    				INNER JOIN cme_users ON (cme_provider_users.user_id = cme_users.user_id)
					WHERE (`first_name` LIKE '%$queryString%' OR `last_name` LIKE '%$queryString%' OR `reg_no` LIKE '%$queryString%') AND cme_provider_users.provider_id='$provider' AND cme_users.user_id NOT IN (SELECT user_id FROM cme_provider_coordinators WHERE provider_id='$provider') ORDER BY `first_name` ASC LIMIT 10 ";
			
			$query = mysql_query($sql);	
			$userslist ='';
			if($query) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($result = mysql_fetch_array($query)) 
					{
						$code = $result['user_id'];
						$userslist.='<li abbr="'.$code.'" attrname="'.$result['primaryname'].'">'.$result['primaryname'].'</li>';
	         		}
				echo $userslist;
				} 
			} else {
				// Dont do anything.
			} // There is a queryString.
		} else {
			echo 'There should be no direct access to this script!';
		}
	//}
?>