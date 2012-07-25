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

		if(isset($_POST['partial_state'])) {
			$queryString = mysql_escape_string($_POST['partial_state']);			
			// Is the string length greater than 0?			
			if(strlen($queryString) >0) {
			$sql= "SELECT user_id,CONCAT(`first_name`,' ',`last_name`)  as primaryname
					FROM
							cme_users
					WHERE  `first_name` LIKE '%$queryString%' OR `last_name` LIKE '%$queryString%' ORDER BY `first_name` ASC LIMIT 10 ";
			
			$latlng ='';
			$query = mysql_query($sql);	
			if($query) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($result = mysql_fetch_array($query)) 
					{
						$code = $result['user_id'];
						$latlng.='<li abbr="'.$code.'" attrname="'.$result['primaryname'].'">'.$result['primaryname'].'</li>';
	         		}
				echo $latlng;
				} 
			} else {
				// Dont do anything.
			} // There is a queryString.
		} else {
			echo 'There should be no direct access to this script!';
		}
	//}
?>