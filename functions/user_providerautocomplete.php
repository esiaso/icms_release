<?php
	
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
			//echo $queryString;		
			// Is the string length greater than 0?			
			if(strlen($queryString) >0) {
			$sql= "SELECT pr_id,pr_name,regno,postalcode,postaladdress,town,location, street, building
					FROM
							cme_providers
					WHERE  pr_name LIKE '%$queryString%' AND cme_providers.status=4 ORDER BY pr_name ASC LIMIT 10 ";
			
			$latlng ='';
			
			$query = mysql_query($sql);	
			if($query) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($result = mysql_fetch_array($query)) 
					{
						$id = $result['pr_id'];
						$latlng.='<li abbr="'.$id.'" code="'.$result['regno'].'" attrname="'.html_entity_decode($result['pr_name']).'" postcode="'.$result['postalcode'].'"  postaladdress="'.$result['postaladdress'].'" town="'.$result['town'].'" location="'.$result['location'].'" street = "'.$result['street'].'" building="'.$result['building'].'" >'.$result['pr_name'].'</li>';
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