<?php

/**
*
* Project:     Natinal Intergrated CPD
* File:        class.provider.php
* Purpose:     Provider Details database
*
*
*
*
*
*/

class Provider
{
	 	private $showMessage;
	 	private $userId;
		private $providerId;
		private $id;
	/**
     * Anti-Mysql-Injection method, escapes a string.
     *
     * @param string $text_to_escape
     */
    static public function setEscape($text_to_escape)
    {
        if(!get_magic_quotes_gpc()) $text_to_escape=mysql_real_escape_string($text_to_escape);
        return $text_to_escape;
    }
	
	/**
     * If on true, displays class messages
     *
     * @param boolean $database_user_table
     */
   	
	public function addUserProvider()
	{
		require_once('class_error_messages.php');
		$errors = new ErrorMessage();
		
		$userId =  isset($_POST['user_id']) ? $_POST['user_id'] : '' ;
		$providerId = isset($_POST['provider_id']) ? $_POST['provider_id'] : '' ;
		$SQLUpdate= sprintf('INSERT INTO cme_provider_users (user_id,provider_id,date_added) VALUES("%s","%s",Now())',$userId,$providerId);
		
		if(mysql_query($SQLUpdate))
		{
			$this->getMessage('Successfully added user details.','success');
			//$GLOBALS['msg'] = "Successfully edited your details";
		}
	}
	public function deleteUserProvider($id)
	{
		require_once('class_error_messages.php');
		$errors = new ErrorMessage();
		
		$SQLDelete = sprintf('DELETE FROM cme_provider_users WHERE id="%s"',$id);
		if(mysql_query($SQLDelete))
		{
			$this->getMessage('Successfully removed user details.','success');
			
		}
		
	}
	public function checkUserProvider($userId,$providerId)
	{
		$sqlu = sprintf('SELECT * FROM cme_provider_users WHERE user_id=%s AND provider_id',$userId,$providerId);
		$res = mysql_query($sqlu);
		if(mysql_num_rows($res)>0)
		 return false;
		 else
		  return true;
	}
	public function getUserProvider($prov)
	{
			 
			$Query = sprintf("SELECT  DISTINCT(cme_users.user_id) AS user_id
									, cme_status_master.status_name
									, cme_users.dateadded , cme_user_title.usertitle
									, cme_users.first_name , cme_provider_users.id
									, cme_users.last_name
								FROM
									cme_users
								LEFT JOIN cme_status_master ON (cme_users.status = cme_status_master.status_id)
								INNER JOIN cme_provider_users ON (cme_users.user_id = cme_provider_users.user_id) 
								INNER JOIN cme_providers ON (cme_providers.pr_id = cme_provider_users.provider_id)
								LEFT JOIN cme_user_title ON (cme_users.title = cme_user_title.ttid)
								WHERE pr_id=%s",$this->setEscape($prov));	
			//echo $Query;
			//$res= mysql_query($Query);
			echo '
					<table width="100%" id="hor-zebra">
					<thead>
						<tr style="background:#C8D4E0; color:#000000;">
							<th align="left">Name</th>
							<th  align="left">Speciality</th>
							<th></th>
						</tr>
					</thead>
				';
				if($res =mysql_query($Query))
					{	
							$c=0;
							while($r = mysql_fetch_array($res))
							{
								require_once("class_user_profile.php");
								$UserProfile= new UserProfile;
								$UserProfile->get_default_user_speciality($r['user_id']);
									$c++;
									$mod = $c %2  ? 'odd' : 'even';
									echo '<tr class="row '.$mod.'">
										<td><a href="index.php?k='.base64_encode('provider'.$GLOBALS['separator'].'fullview'.$GLOBALS['separator'].''.$GLOBALS['separator']).'&userid='.$r['user_id'].'" title="View Details">'.$r['usertitle'].' '.$r['first_name'].'  '.$r['last_name'].'</a></td>						
										<td>'.$UserProfile->speciality.'</td>						
										<td><a href="index.php?k='.base64_encode('provider'.$GLOBALS['separator'].'fullview'.$GLOBALS['separator'].''.$GLOBALS['separator']).'&userid='.$r['user_id'].'" title="View"><img src="images/icons/view.png" alt="View" /></a> <a href="#" title="Send Mail"><img src="images/icons/msg_email.gif" alt="Send Mail" /></a>  <a title="Remove" href="index.php?k='.base64_encode('provider'.$GLOBALS['separator'].'add_users'.$GLOBALS['separator'].''.$GLOBALS['separator'].'del').'&d='.base64_encode($r['id']).'"><img alt="Remove" src=images/icons/cross.png border="0"></a></td>
									</tr>';
								
										
							}
							
					}
					else
					{
						echo '<tr>
								<td colspan="3">No records!</td>
							</tr>';
					}
				echo '</table>';	
			return;
			}
    
	
}