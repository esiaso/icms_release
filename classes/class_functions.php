<?php
//Class general functions

class generals
{
	
	public function genRandomString() {
		$string ='';
		$length = TOKEN_LENGTH;
		//$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$characters = '01234567890123456789012345678901234567890123456789012345678901234567890123456789';
		    		
		for ($p = 0; $p < $length; $p++)
		{
			$string.= $characters[mt_rand(0, strlen($characters))];
			//$string.=mt_rand(0,strlen($characters));
		}
		return $string;
	}
	
	public function genRandomChoices($ln) {
		$char ='';	
		$char= chr(65 + mt_rand(0, $ln-1));
		return $char;
	}
	
	
	public function escapestring($text_to_escape)
		{
			if(!get_magic_quotes_gpc()) $text_to_escape=mysql_real_escape_string($text_to_escape);
			return $text_to_escape;
		}
	
	public function get_user_name($usr)
	{
		global $db;
			$Query = sprintf("SELECT * FROM cme_users u JOIN cme_user_title c ON u.title=c.ttid WHERE us_id=%s",$this->escapestring($usr));	
		
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res)) 
		{
			$username = $r['first_name'].' '.$r['last_name'].', '.$r['usertitle'];
			echo $username;			
		}
		return;
		
	}
	
	
	public function check_if_user_is_author($usr)
	{
		global $db;
			$Query = sprintf("SELECT * FROM cme_users  WHERE us_id=%s",$this->escapestring($usr));	
		
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$author = $r['author'];
			if($author=='Yes')
			{
				return true;
			}
			else
			{
				return false;
			}
					
		}
		
		
	}
	
	public function get_user_institution($usr)
	{
		global $db;
		$Query = sprintf("SELECT * FROM cme_users WHERE us_id=%s",$this->escapestring($usr));	
		
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$institution = $r['institution'];
			echo $institution;			
		}
		return;
		
	}
	
	public function get_provider_name($prov)
	{
		$Query = sprintf("SELECT * FROM cme_providers WHERE pr_id=%s",$this->escapestring($prov));	
		
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$provider = $r['pr_name'];		
		}
		return $provider;
		
	}
	
	public function get_provider_users_list($prov)
	{
		require_once ("class_moderators.php");
		$moderator= new manageModerators;
		 
		$Query = sprintf("SELECT DISTINCT(CONCAT(cme_users.first_name ,' ', cme_users.last_name)) AS username ,cme_users.user_mail , 
							cme_users.title ,  cme_role_master.role_name, cme_provider_users.id ,cme_users.user_id	,cme_user_title.usertitle 	 
						FROM cme_users 
						INNER JOIN cme_provider_users ON (cme_users.user_id = cme_provider_users.user_id) 
						INNER JOIN cme_providers ON (cme_providers.pr_id = cme_provider_users.provider_id)
						LEFT JOIN cme_role_master ON (cme_role_master.role_id = cme_provider_users.user_level)
						LEFT JOIN cme_user_title ON (cme_users.title = cme_user_title.ttid)
						WHERE pr_id=%s",$this->escapestring($prov));	
		//echo $Query;
		$res= mysql_query($Query);
		echo '
				<table width="100%" id="hor-zebra">
				<thead>
					<tr style="background:#C8D4E0; color:#000000;">
						<th align="left">Name</th>
						<th  align="left">Email</th>
						<th  align="left">Speciality</th>
						<th  align="left">User Level</th>
						<th>Add/Remove As Moderator</th>
					</tr>
				</thead>
			';
			$c=0;
		while($r = mysql_fetch_array($res))
		{
			require_once("class_user_profile.php");
			$UserProfile= new UserProfile;
			$UserProfile->get_default_user_speciality($r['user_id']);
			
			if(!$moderator->check_if_moderator($prov,$r['user_id']))
				$linkadd ='<a href="index.php?k='.base64_encode('provider'.$GLOBALS['separator'].'add_users'.$GLOBALS['separator'].''.$GLOBALS['separator'].'add').'&usr='.$r['user_id'].'&prov='.$prov.'" title="Add As Moderator"><img alt="Add As Moderator" src=images/icons/add.png border="0"></a>';
				
			 else
				$linkadd ='<a href="index.php?k='.base64_encode('provider'.$GLOBALS['separator'].'add_users'.$GLOBALS['separator'].''.$GLOBALS['separator'].'rem').'&usr='.$r['user_id'].'&prov='.$prov.'" title="Remove As Moderator"><img alt="Remove As Moderator" src=images/icons/delete.png border="0"></a>';
				
			$c++;
				$mod = $c %2  ? 'odd' : 'even';
				echo '<tr class="row '.$mod.'">
					<td>'.$r['usertitle'].' '.$r['username'].'</td>
					<td>'.$r['user_mail'].'</td>
					<td>'.$UserProfile->speciality.'</td>
					<td><a href=" ">'.$r['role_name'].'</a></td>
					<td> '.$linkadd.' </td>
				</tr>';
			
					
		}
			echo '</table>';	
		return;
		
		/*<a href="#" title="View"><img alt="View" src=../images/icons/script-add.png border="0"></a>  <a title="Edit" href="#"><img alt="Edit" src=../images/icons/edit.png border="0"></a>  <a title="Remove" href="index.php?k='.base64_encode('provider'.$GLOBALS['separator'].'add_users'.$GLOBALS['separator'].''.$GLOBALS['separator'].'del').'&d='.base64_encode($r['id']).'"><img alt="Remove" src=../images/icons/cross.png border="0"></a>*/
	}
	
	public function get_user_address($usr)
	{
		global $db;
			$Query = sprintf("SELECT * FROM cme_users u
							 JOIN countries c ON u.country_residence = c.cid
							 WHERE us_id =%s",$this->escapestring($usr));	
		
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
	
	public function get_user_specialities($usr)
	{
		global $db;
			$Query = sprintf("SELECT cme_specialities.sp_name , cme_user_specialities.dateadded , cme_user_specialities.uspid
							FROM
								cme_user_specialities INNER JOIN cme_specialities ON (cme_user_specialities.spid = cme_specialities.sp_id)
							WHERE usrid =%s",$this->escapestring($usr));	
		
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
	public function get_user_profcategory($usr)
	{
		global $db;
		$profession='';
			$Query = sprintf("SELECT cme_profession.pro_name , cme_profession.pro_id
								FROM cme_users
								INNER JOIN cme.cme_profession  ON (cme_users.profession = cme_profession.pro_id)    
							WHERE us_id =%s",$this->escapestring($usr));	
			$res= mysql_query($Query);
			
				if($r = mysql_fetch_array($res))
				{		
					$profession= isset($r['pro_name']) ? $r['pro_name'] : '';
				}
			
		echo $profession;		
		return;
		
	}
	public function get_user_alerts($usr)
	{
		global $db;
			
		return;
		
	}
	
	public function get_user_newest_articles()
	{
		global $db;
			$Query = sprintf("SELECT cme_activities.art_id, cme_activities.art_name , cme_activities.creationdate
						  FROM  cme_activities   ORDER BY creationdate DESC LIMIT 0, 5");
		
			$res= mysql_query($Query);
			$articles = '<table>';
				while($r = mysql_fetch_array($res))
				{		
					$articles .= '<tr><td><a href="?com=contents&pid=art&art=vw&a='.$r['art_id'].'">'.$r['art_name'].'</a></td><tr> ';
				}
			$articles .= '</table>';
								
		return $articles;
		
		
	}
	
	public function get_user_expiring_articles()
	{
		global $db;
			$Query = sprintf("SELECT cme_activities.art_id, cme_activities.art_name , cme_activities.creationdate
						  FROM  cme_activities   ORDER BY creationdate ASC LIMIT 0, 5");
		
			$res= mysql_query($Query);
			$articles = '<table>';
				while($r = mysql_fetch_array($res))
				{		
					$articles .= '<tr><td><a href="?com=contents&pid=art&art=vw&a='.$r['art_id'].'">'.$r['art_name'].'</a></td><tr> ';
				}
			$articles .= '</table>';
								
		return $articles;
		
		
	}
	
	public function get_user_authored_articles($usr)
	{
		global $db;
			$Query = sprintf("SELECT cme_activities.art_id, cme_activities.art_name , cme_activities.creationdate
						  FROM cme_activities_authors 
							JOIN cme_activities ON cme_activities.art_id = cme_activities_authors.art_id WHERE `user` =%s  ORDER BY creationdate DESC LIMIT 0, 5",$this->escapestring($usr));
		
			$res= mysql_query($Query);
			$address = '<table>';
				while($r = mysql_fetch_array($res))
				{		
					$address .= '<tr><td><a href="?com=contents&pid=art&art=vw&a='.$r['art_id'].'">'.$r['art_name'].'</a></td><tr> ';
				}
			$address .= '</table>';
								
		return $address;
		
	}
	public function check_if_article_has_questions($article)
	{
		global $db;
		$Query = sprintf("SELECT cme_activities_questions.qid
						FROM
							cme_activities_questions
						INNER JOIN cme_activities ON (cme_activities_questions.art_id = cme_activities.art_id) WHERE cme_activities.art_id='%s'",$article);
			
		$res= mysql_query($Query);
		if($res->RecordCount()>1)
		{	
			return true;
		}
		else
		{
			return false;
		}
						
		
	}
	
	public function get_user_potriat($usr)
	{
		global $db;
			$Query = sprintf("SELECT portrait FROM cme_users WHERE us_id ='%s'",$this->escapestring($usr));	
		
		$res= mysql_query($Query);
		if($res->RecordCount()>1)
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
	
	public function err_code($err)
		{
			global $db;
			
			$errquery = sprintf("SELECT * FROM cme_err WHERE err_code='%s'",$this->escapestring($err));
			
			$res= mysql_query($errquery);
			if($r = mysql_fetch_array($res))
				{
					$errcode= $r['err_code'];
					$errname= $r['err_name'];
					$errdesc= $r['err_desc'];
					$errremmedy= $r['err_remmedy'];
					
			
				}
				
				echo '<fieldset>
						<h2>Error '.$errcode.': '.$errname.'</h2>
						'.$errdesc.'
						'.$errremmedy.'					
						</fieldset>
						';
			return ;
			
		}
		
	public function getdata($table)
	{
		global $db;
		
		$Query = sprintf("SELECT * FROM cme_activity_%s",$this->escapestring($table));	
		$res= mysql_query($Query);
		while($r = mysql_fetch_array($res))
		{
			echo '<option value='.$r[''.$table.'Id'].'>'.$r[''.$table.'Desc'].'</option>';
		}
		
		return;
	}
	
	public function check_if_has_attempted($article,$user)
	{
		global $db;
		
		$Query = sprintf("SELECT * FROM cme_user_certs WHERE articleid='%s' AND usrid=%s",$article,$user);	
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function post_cert_details($article,$user)
	{
		global $db;
		$credits = get_article_credits($article);
		$certno = create_attemp_certificate_number($article,$user);
		$runnum = get_cert_next_num();
		
		$Query = sprintf("INSERT INTO cme_user_certs (certno,runnumber,usrid,articleid,dateattemp,credits) VALUES ('%s',%s,%s,'%s',NOW(),%s)",$certno,$runnum,$user,$article,$credits);	
		$res= mysql_query($Query);
		/*if($r = mysql_fetch_array($res))
		{
			
		}*/
		
		return ;
	}
	
	public function get_article_credits($article)
	{
		global $db;
		
		$credits=0;
		
		$Query = sprintf("SELECT credits FROM cme_article_info WHERE artcleid ='%s'",$article);	
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$credits= $r['credits'];
		}
			
		return $credits;
	}
	
	public function get_article_rank()
	{
		global $db;
		
		$Query = sprintf("SELECT MAX(ranks) as runx FROM cme_activities WHERE creationdate = date(NOW())");	
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$nextnum= $r['runx'] +1;
		}
		else
		{
			$nextnum =1;
		}
		
		return $nextnum;
	}
	public function get_cert_next_num()
	{
		global $db;
		
		$Query = sprintf("SELECT MAX(runnumber) as runx FROM cme_user_certs ");	
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$nextnum= $r['runx'] +1;
		}
		else
		{
			$nextnum =1;
		}
		
		return $nextnum;
	}
	
	public function create_attemp_certificate_number($article,$user)
	{
		global $db;
		
		$Query = sprintf("SELECT MAX(runnumber) as runx FROM cme_user_certs");	
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$runnum= $r['runx'] +1;
		}
		else
		{
			$runnum =1;
		}
		
		$certno = $article.$user.'_'.$runnum;
		return $certno;
	}
	
	public function create_article_num()
	{
		global $db;
		
		$vardate= date('dmY');
		$artnum=0;
		
		$Query = sprintf("SELECT MAX(ranks) as runx FROM cme_activities WHERE creationdate = date(NOW())");	
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$nextnum= $r['runx'] +1;
		}
		else
		{
			$nextnum =1;
		}
		
		
		if($nextnum<=9)
		{
			$nextnum = '00'.$nextnum;
		}
		elseif($nextnum>=10 && $nextnum<=99)
		{
			$nextnum = '0'.$nextnum;
		}
		else
		{
			$nextnum = $nextnum;
		}
		
		//Create the Next number
		$artnum =$vardate.$nextnum;
		//check if locked the number
		
		$artnum_lock = $this->lockref_num('cme_activities',$artnum);
		
		//return the next number
		return $artnum_lock;
	}
	
	public function lockref_num($table,$artnum)
	{
		global $db;
		
		$Query = sprintf("SELECT * FROM cme_lock_ref_num WHERE ref_table = '%s' AND ref_num= %s",$table,$artnum);	
		$res = mysql_query($Query);
		if(mysql_num_rows($res)>1) 
		{
			$artnum_loc= $artnum + 1;		
			$QueryIns = sprintf("INSERT INTO cme_lock_ref_num (ref_table,ref_num,datecreated) VALUES('%s',%s,date(NOW()))",$table,$artnum_loc);			
			if($ress= mysql_query($QueryIns))
			{
				$artnumlocked = $artnum_loc;
				return $artnumlocked;
			}
		}
		else
		{
			$QueryIns = sprintf("INSERT INTO cme_lock_ref_num (ref_table,ref_num,datecreated) VALUES('%s',%s,date(NOW()))",$table,$artnum);	
			if($ress= mysql_query($QueryIns))
			{
				$artnumlocked = $artnum;
				return $artnumlocked;
			}
		}
		
	}
	
	public function gettotalarticles_per_author($authId)
	{
		global $db;
		$Query = sprintf("SELECT * FROM cme_activities_authors WHERE auth_id = %s",$authId);	
		$res = mysql_query($Query);
		$tot_articles = mysql_num_rows($res);
		
		return $tot_articles;
	}
	
	
	public function getarticle_audience($artid)
	{
		global $db;
		$specialty = array();
		$sep =', ';
		$QueryIns = sprintf("SELECT DISTINCT(sp_name) AS aud FROM cme_activities_targetaudience t
						 JOIN cme_specialities s ON s.sp_id =t.aud_id					 
						 WHERE t.art_id = %s",$artid);	
		$res= mysql_query($QueryIns);
		while($r = mysql_fetch_array($res))
			{
				$specialty[] = $r['aud'];			
			}
	
		return ;
	}

}
?>