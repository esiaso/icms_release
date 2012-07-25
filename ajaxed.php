<?php
	require("config.php");
	include_once('include/dbcon.php');
	session_start();
	if(!isset($_GET['obtype']))
		$obtype 	=  '';	
		else
			$obtype 	=  $_GET['obtype'] ;	
	
	if(!isset($_GET['mysql']))	
		$mysql		=  '';	
		else
			$mysql		=  $_GET['mysql'];
	
	if($obtype == 'pullmessages')
		{
			$description='';
			$type_name ='';
			$cpd_points ='';
			$typeId =  $_GET['activitytypeid'];
			
			$sqldata = sprintf("SELECT type_description,type_name ,cpd_points FROM cme_activity_type WHERE type_id='%s'",
											mysql_escape_string($typeId));
			$res = mysql_query($sqldata);
			
			if($r = mysql_fetch_array($res))
			{
				$description = $r['type_description'];
				$type_name = $r['type_name'];
				$cpd_points = $r['cpd_points'];
			}
			
			
			echo '<b>'.$type_name.'</b><br><b>Description:</b>'.$description.'<br><b>Points:</b>'.$cpd_points;
		}	
		
	if($obtype == 'pulluserexists')
		{
			$userregn =  $_GET['userregn'];
			
			//edit this to allow logged user to be able to search if his credentials exist. To avoid dublications and security flaws
			
			
			$sqldata = sprintf("SELECT * FROM cme_users_temp WHERE reg_no='%s'",mysql_escape_string($userregn));
			$link = 'index.php?k='.base64_encode('users'.get_separator().'details'.get_separator().''.get_separator()).'&usrid='.$_SESSION['user_id'].'&act=edit';
			$res = mysql_query($sqldata);
			if(mysql_num_rows($res)>0)
			{
				if($r = mysql_fetch_array($res))
				{
				  echo '<div align="center" style="width:170px;border: 1px solid #666666; color: #666666; font-size: 88%;background-color: #ffffe1;padding:3px;">Some of your details already exist in our database.<br> Click on the button below to import.<br>
				  <a href="'.$link.'&tempusr='.$r['user_id'].'&import=1" class="activity_op_edit" name="import" >Import</a></div>';
				}
			}
				else
					echo ''	;
			
			
		}	
		if($obtype == 'pulluserdetails')
		{
			$userid =  $_GET['userregn'];
			
			//edit this to allow logged user to be able to search if his credentials exist. To avoid dublications and security flaws
			
			$sqlu = sprintf('SELECT cme_user_title.usertitle , cme_countries.country , cme_countries.country , cme_users.user_level,
					 cme_users.first_name, cme_users.last_name , cme_users.reg_no,cme_users.mobile1,
					cme_users.user_mail , cme_users.city , cme_users.portrait
			 	FROM cme_users 
					LEFT JOIN cme_user_title ON (cme_users.title = cme_user_title.ttid)					 
					LEFT JOIN cme_countries ON (cme_users.country_residence = cme_countries.cid) 
					
									
				WHERE cme_users.user_id=%s',$userid);
								
				
				$res = mysql_query($sqlu);
				if($r=mysql_fetch_array($res))
				{
					$title = $r['usertitle'];
					$lastname = $r['last_name'];					
					$firstname = $r['first_name'];
					$usermail = $r['user_mail'];
									
					$portrait = $r['portrait'];
					$regnumber = $r['reg_no'];/*
					$this->get_default_user_facility($userid);
					$this->get_default_user_speciality($userid);*/				
					$fullname = $title.' '.$lastname.' '.$firstname;
					echo $fullname;
				}
			
			
			
		}	
		
		
?>