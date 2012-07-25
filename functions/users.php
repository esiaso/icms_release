<?php

function get_user_potriat($usr)
{

		$Query = sprintf("SELECT portrait FROM cme_users WHERE us_id ='%s'",EscapeData($usr));	
	
	$res= mysql_query($Query);
	if(mysql_num_rows($res)>1)
	   {
			if($r = mysql_fetch_array($res))
			{
				$portrait = $r['portrait'];
				echo '<img src="images/portraits/'.$portrait.'" border="0" />';			
			}
	   }
   else
	{
		echo '<img src="images/portraits/avatar.jpg" border="0" />';	
	}
	return;
	
}

function get_user_authored_articles($usr)
{

		$Query = sprintf("SELECT cme_articles.art_id, cme_articles.art_name , cme_articles.creationdate
					  FROM cme_articles_authors 
						JOIN cme_articles ON cme_articles.art_id = cme_articles_authors.art_id WHERE `user` =%s  ORDER BY creationdate DESC LIMIT 0, 5",EscapeData($usr));
	
		$res= mysql_query($Query);
		$address = '<table>';
			while($r = mysql_fetch_array($res))
			{		
				$address .= '<tr><td><a href="?com=contents&pid=art&art=vw&a='.$r['art_id'].'">'.$r['art_name'].'</a></td><tr> ';
			}
		$address .= '</table>';
							
	return $address;
	
}

function get_user_address($usr)
{

		$Query = sprintf("SELECT * FROM cme_users u
						 JOIN countries c ON u.country_residence = c.cid
						 WHERE us_id =%s",EscapeData($usr));	
	
	$res= mysql_query($Query);
	if($r = mysql_fetch_array($res))
	{
		$address = '<table>';
		$address .= '<tr><td><strong>Physical Address :</strong></td><td>'.$r['physical_address'].'</td></tr> ';
		$address .= '<tr><td><strong>Postal Address: </strong></td><td>'.$r['postal_address'].'</td></tr> ';
		$address .= '<tr><td><strong>City: </strong></td><td>'.$r['city'].'</td></tr> ';
		$address .= '<tr><td><strong>Zip: </strong></td><td>'.$r['zip_code'].'</td></tr> ';
		$address .= '<tr><td><strong>Country: </strong></td><td>'.$r['country'].'</td></tr> ';		
		$address .= '<tr><td><strong>Phone: </strong></td><td>'.$r['phone_number'].'</td></tr> ';
		$address .= '<tr><td><strong>Email: </strong></td><td>'.$r['email'].'</td></tr> ';
		$address .= '</table>';
		
		echo $address;			
	}
	return;
	
}

function get_user_specialities($usr)
{

		$Query = sprintf("SELECT cme_specialities.sp_name , cme_user_specialities.dateadded , cme_user_specialities.uspid
						FROM
							cme_user_specialities INNER JOIN cme_specialities ON (cme_user_specialities.spid = cme_specialities.sp_id)
						WHERE usrid =%s",EscapeData($usr));	
	
	$res= mysql_query($Query);
		$address = '<ul>';
			while($r = mysql_fetch_array($res))
			{		
				$address .= '<li>'.$r['sp_name'].'</li> ';
			}
		$address .= '</ul>';
	echo $address;		
	return;
	
}
function get_user_profcategory($usr)
{

	$profession='';
		$Query = sprintf("SELECT cme_profession.pro_name , cme_profession.pro_id
							FROM cme_users
    						INNER JOIN cme_profession  ON (cme_users.profession = cme_profession.pro_id)    
						WHERE user_id =%s",EscapeData($usr));	
		$res= mysql_query($Query);
		
			if($r = mysql_fetch_array($res))
			{		
				$profession= isset($r['pro_name']) ? $r['pro_name'] : '';
			}
		
	echo $profession;		
	return;
	
}
function get_user_alerts($usr)
{

		
	return;
	
}

function get_user_newest_articles()
{
	
		$Query = sprintf("SELECT cme_articles.art_id, cme_articles.art_name , cme_articles.creationdate
					  FROM  cme_articles   ORDER BY creationdate DESC LIMIT 0, 5");
	
		$res= mysql_query($Query);
		$articles = '<table>';
			while($r = mysql_fetch_array($res))
			{		
				$articles .= '<tr><td><a href="?com=contents&pid=art&art=vw&a='.$r['art_id'].'">'.$r['art_name'].'</a></td><tr> ';
			}
		$articles .= '</table>';
							
	return $articles;
	
	
}

function get_user_expiring_articles()
{

		$Query = sprintf("SELECT cme_articles.art_id, cme_articles.art_name , cme_articles.creationdate
					  FROM  cme_articles   ORDER BY creationdate ASC LIMIT 0, 5");
	
		$res= mysql_query($Query);
		$articles = '<table>';
			while($r = mysql_fetch_array($res))
			{		
				$articles .= '<tr><td><a href="?com=contents&pid=art&art=vw&a='.$r['art_id'].'">'.$r['art_name'].'</a></td><tr> ';
			}
		$articles .= '</table>';
							
	return $articles;
	
	
}
?>