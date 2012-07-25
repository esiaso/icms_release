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
			$sql= "SELECT idmed_facilities,fname,fcode,postcode,address,town,location, street, building,country
					FROM
							med_facilities
					WHERE  fname LIKE '%$queryString%' ORDER BY fname ASC LIMIT 10 ";
			
			$latlng ='';
			$query = mysql_query($sql);	
			if($query) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($result = mysql_fetch_array($query)) 
					{
						$id = $result['idmed_facilities'];
						$latlng.='<li abbr="'.$id.'" code="'.$result['fcode'].'" attrname="'.$result['fname'].'" postcode="'.$result['postcode'].'"  postaladdress="'.$result['address'].'" town="'.$result['town'].'" location="'.$result['location'].'" street = "'.$result['street'].'" building="'.$result['building'].'" country="'.$result['country'].'" >'.$result['fname'].'</li>';
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