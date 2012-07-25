<?php
/**
 * Project:     providerRegistration PHP Class
 * File:        class_registration.php
 * Purpose:     providerRegistration users in a mysql database For CPD
 *
 * For questions, help, comments, discussion, etc, please send
 * e-mail to opondo.isaiah@gmail.com
 *
 * @link http://www.medsynergy.co.ke
 * @author Isaiah Opondo <opondo.isaiah@gmail.com>
 * @package Registration PHP Class
 * @version 1.1
 *
 */

class providerRegistration
{
	
	/**
     * Sets the database users table
     *
     * @param string $database_user_table
     */
    public function setDatabaseUserTable($database_user_table)
    {
        $this->databaseUsersTable=$database_user_table;
    }
    
    /**
     * Sets the crypting method
     *
     * @param string $crypt_method - You can set it as 'md5' or 'sha1' to choose the crypting method for the user password.
     */
    public function setCryptMethod($crypt_method)
    {
        $this->cryptMethod=$crypt_method;
    }

    /**
     * Crypts a string
     *
     * @param string $text_to_crypt -  crypt a string if $this->cryptMethod was defined.
     * If not, the string will be returned uncrypted.
     */
    public function setCrypt($text_to_crypt)
    {
        switch($this->cryptMethod)
        {
            case 'md5': $text_to_crypt=trim(md5($text_to_crypt)); break;
            case 'sha1': $text_to_crypt=trim(sha1($text_to_crypt)); break;
        }
       return $text_to_crypt;
    }
    
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
    public function setShowMessage($registration_show_message)
    {
        if(is_bool($registration_show_message)) $this->showMessage=$registration_show_message;
    }
	
	 public function getMessage($message_text,$message_type, $message_html_tag_open=null, $message_html_tag_close=null, $message_die=false)
    {
        if($this->showMessage)
        {
            $c='';
            	
			if($message_type=='success') $class='notification_success';
			elseif($message_type=='info') $class='notification_info';
			elseif($message_type=='warning') $class='notification_warning';
			elseif($message_type=='error') $class='notification_error';
			$link = isset($GLOBALS['Group']) && $GLOBALS['Group']!='public' ? '../' : '';
			$bg='background:url('.$link.'images/'.$class.'.png) no-repeat ;';			
			$c.="<div id='alertbox' name='alertbox'  style='".$bg." position:absolute;left:250;top:100;z-index:2; width:469px; height:65px;' >\n";
			$c.= '<div style="text-align:center; padding:5px 10px 10px 5px; color: #D8000C;" onclick="javascript:document.getElementById(\'alertbox\').style.display=\'none\';">';
			$c.= $message_text;
			$c.='</div>';
			$c.="</div>\n";
			
			if($message_die) die($c);			
            else echo $message_html_tag_open . $c . $message_html_tag_close;//die($message_text);
        }
    }
    
	
	
	/**
     * Register user in the database
     *
     * The user form data needed is: user_name, user_pass, user_confirm_pass, user_mail, user_confirm_mail
     */
    public function setProviderRegistration()
    {
        if(!$this->databaseUsersTable) $this->getMessage('Users table in the database is not specified. Please specify it before any other operation using the method setDatabaseUserTable();','','','','true');
        //$user_name=$this->setEscape($_POST['user_name']);
        $fname=$_POST['fname'];
       	$provider_pass=$_POST['pin'];
        $provider_confirm_pass=$_POST['pinconfirm'];
        $provider_mail=$_POST['provider_mail'];
		
        $provider_confirm_mail=$_POST['provider_confirm_mail'];
        $provider_crypted_pass=$this->setCrypt($provider_pass);
       // $result_user_name=mysql_query("SELECT * FROM"." ".$this->databaseUsersTable." "."WHERE user_name='$user_name'");
        $result_provider_mail=mysql_query("SELECT * FROM"." ".$this->databaseUsersTable." "."WHERE email='$provider_mail'");
		//$this->getMessage("SELECT * FROM"." ".$this->databaseUsersTable." "."WHERE user_mail='$user_mail'");
        //if((strlen($user_name)<6) or (strlen($user_name)>16)) $this->getMessage('Entered username length must be of 6 to 16 characters.');
       // elseif(mysql_num_rows($result_user_name)) $this->getMessage('Entered username already exists in the database.');
        if((strlen($provider_pass)<8) or (strlen($provider_pass)>16)) $this->getMessage('Entered password length must be of 8 to 16 characters.','error');
        elseif($provider_pass!=$provider_confirm_pass) $this->getMessage('Passwords entered do not match.','error');
        elseif(mysql_num_rows($result_provider_mail)) $this->getMessage('Entered email already exists in the database.','error');
        elseif($provider_mail!=$provider_confirm_mail) $this->getMessage('Email addresses entered do not match.','error');
       // elseif(!preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-]{4,})+\.)+([a-zA-Z0-9]{2,})+$/", $user_mail)) $this->getMessage('Email address entered is not valid.');
        else
        {
			$Query= "INSERT INTO"." ".$this->databaseUsersTable." "."(pr_password, email,pr_name,dateadded,addedby) VALUES ('$provider_crypted_pass', '$provider_mail','$fname',Now(),'self')";
			//$this->getMessage($Query,'error');
            if(mysql_query($Query))
				{
					$body = '<div style="padding:10px 15px; border:1px solid rgb(204,204,204); border-radius:5px 5px 5px 5px; background:-moz-linear-gradient(100% 100% 90deg, rgb(242,242,242),rgb(255,255,255)) repeat scroll 0% 0% transparent;font-family:Arial, Helvetica,sans-serif;">';	
					$body .='<img src="'.WEBLINK.'/images/emailheader.png"';	
					$body .= '<br>';
					$body .= '<br>';
					$body .= '<br>';			
					$body .='Dear,'.$fname;
					$body .= '<br>';
					$body .= '<br>';
					$body .= '<p>Congratulations for your successful registration in the integrated CPD Management System (iCMS) platform.</p>';
					$body .= '<br>';
					$body .= '<p>Your username and passwords are:</p>';
					$body .= '<p>USERNAME: <em>'.$provider_mail.'</em></p>';
					$body .= '<p>PASSWORD: <em>'.$provider_pass.'</em></p>';
					$body .= '<br> ';
					$body .= '<br>';
					$body .= 'Click <a href="'.WEBLINK.'provider/index.php?k='.base64_encode('provider'.get_separator().'profile'.get_separator().''.get_separator()).'&usrn='.$provider_mail.'"> Here</a> to continue to your profile';
					$body .= '<br>';
					$body .= '<br>';
					$body .= 'Alternatively, you can copy this link to your browser to access the website.';
					$body .= WEBLINK.'provider/index.php?k='.base64_encode('provider'.get_separator().'profile'.get_separator().''.get_separator()).'&usrn='.$provider_mail;
					$body .= '<br>';
					$body .= '<br>';
					$body .= '<br>
								Feel free to contact us incase you need assistance.';
					
					$body .= '<p>Yours sincerely,</p>
								CPD administrator<br>
								National CPD Project<br>
								Medical Practitioner and Dentist Board<br>';
					$body .= '<br>';
					$body .= 'admin@icpdkenya.org';
					$body .= '<br>';
					$body .= 'http://www.icpdkenya.org';
					$body .= '<br>';
					$body .= '-------------------------------------';
					$body .= '<br>';
					$body .= 'A project of the Kenya Medical Practitioners and Denstist Board';
					$body .= '<br>';
					$body .= '<br>';					
					
					$body .= '</div>';
					if(smtpmailer($provider_mail, NOREPLY, 'National CPD Platform', 'User Registration', $body))
					{
						return true;
					}
					/*else
					{
						$this->getMessage('Registration was successful. <a href="index.php?k='.base64_encode('registration'.get_separator().'track'.get_separator().''.get_separator()).'"> Continue to login</a>');
					}*/
				}
				else
				{
					return false;
				}
        }
    }
	
	
	
	 public function setUpdateProviderRegistration()
    {
       	require_once "class_login.php";
	   
       	$providerid=$_POST['providerid'];
        $provider_pass=$_POST['password'];
        $provider_mail=$_POST['coordinator_email'];
		$user_id= Login::Get_User_ID($provider_mail);
        $provider_crypted_pass=$this->setCrypt($provider_pass);
		
       $sql="SELECT 'x' FROM cme_users u WHERE u.user_mail = '".$provider_mail."'  AND u.user_password = '".$provider_crypted_pass."'";	
	   
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0)
		{
			$Query= "INSERT INTO cme_provider_coordinators (user_id, provider_id,date_added) VALUES ('$user_id', '$providerid',Now())";
		    if(mysql_query($Query))
				{
					require_once('class_roles.php');
					Role::insertUserRoles($user_id, 6);
					
					$body = '<div style="padding:10px 15px; border:1px solid rgb(204,204,204); border-radius:5px 5px 5px 5px; background:-moz-linear-gradient(100% 100% 90deg, rgb(242,242,242),rgb(255,255,255)) repeat scroll 0% 0% transparent;font-family:Arial, Helvetica,sans-serif;">';	
					$body .='<img src="'.WEBLINK.'/images/emailheader.png"';	
					$body .= '<br>';
					$body .= '<br>';
					$body .= '<br>';			
					$body .='Hi';
					$body .= '<br>';
					$body .= '<br>';
					$body .= '<p>Congratulations for your successful application in the integrated CPD Management System (iCMS) platform.</p>';
					$body .= '<br>';
					$body .= '<br>';
					$body .= '<br>
								Feel free to contact us incase you need assistance.';
					
					$body .= '<p>Yours sincerely,</p>
								CPD administrator<br>
								National CPD Project<br>
								Medical Practitioner and Dentist Board<br>';
					$body .= '<br>';
					$body .= 'admin@icpdkenya.org';
					$body .= '<br>';
					$body .= 'http://www.icpdkenya.org';
					$body .= '<br>';
					$body .= '-------------------------------------';
					$body .= '<br>';
					$body .= 'A project of the Kenya Medical Practitioners and Denstist Board';
					$body .= '<br>';
					$body .= '<br>';					
					
					$body .= '</div>';
					if(smtpmailer($provider_mail, NOREPLY, 'National CPD Platform', 'User Registration', $body))
					{
						return true;
					}
					/*else
					{
						$this->getMessage('Registration was successful. <a href="index.php?k='.base64_encode('registration'.get_separator().'track'.get_separator().''.get_separator()).'"> Continue to login</a>');
					}*/
				}
				else
				{
					return false;
				}
        }
		    
        else
        { 
			$this->getMessage('Your password is wrong.','error');   
		}
    }
	
	public function comparePassword($curr_pass,$my_pass)
	{
		if($curr_pass!=$my_pass)
			return false;
			else
				return true;
	}
	
	public function checkRegistrationSubmitted($providerid)
	{
		$SQLInsert= sprintf('SELECT * FROM cme_provider_application WHERE provider_id=%s',$providerid);
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
			return true;
			else
				return false;
	}
	
	public function checkRegistrationCompleted($providerid)
	{
		
		if(!$this->checkDocuments($providerid)) return false;
		//elseif(!$this->checkDocuments($userid)) return false;
		else return true;
		
	}
	
	private function checkDocuments($providerid)
	{
		$SQLInsert= sprintf('SELECT * FROM cme_provider_documents WHERE provider_id=%s',$providerid);		
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
			return true;
			else
				return false;
	}
	
	private function checkAffiliatedHospital($providerid)
	{
		$SQLInsert= sprintf('SELECT * FROM cme_provider_facilities WHERE provider_id=%s',$providerid);		
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
			return true;
			else
				return false;
	}
	
	public function checkRegistrationCompletedMessages($providerid)
	{
		$message='';		
		if(!$this->checkDocuments($providerid))  $message.='Provide registration application documents.';
		elseif(!$this->checkAffiliatedHospital($providerid)) $message.='Provide information about your affiliated facility.';
		//else 
		return $message;
		
	}
	
	public function submitRegistration($providerID)
	{
		$SQLInsert= sprintf('INSERT INTO cme_provider_application (provider_id,app_date,status) VALUES(%s,Now(),1)',$providerID);
		if(mysql_query($SQLInsert))
			{
				$SQLInsert= sprintf('UPDATE cme_providers SET status=1 WHERE pr_id=%s',$providerID);
				if(mysql_query($SQLInsert));
				//$this->sendApprovalMail($userid);
			}
			else
				return false;
	}
	
	public function receiveApplication($providerID)
	{
		$SQLInsert= sprintf('UPDATE cme_provider_application SET received_date=Now(),status=2 WHERE provider_id=%s',$providerID);
		if(mysql_query($SQLInsert))
		{
				$SQLInsert= sprintf('UPDATE cme_providers SET status=2 WHERE pr_id=%s',$providerID);
				if(mysql_query($SQLInsert))
				return true;
		}
			else
				return false;
	}
	public function startProcessingApplication($providerID)
	{
		$SQLInsert= sprintf('UPDATE cme_provider_application SET processed_date=Now(),status=3 WHERE provider_id=%s',$providerID);
		if(mysql_query($SQLInsert))
			{
				$SQLInsert= sprintf('UPDATE cme_providers SET status=3 WHERE pr_id=%s',$providerID);
				if(mysql_query($SQLInsert))
				return true;
		}
			else
				return false;
	}
	public function ApprovedApplication($providerID)
	{
		$SQLInsert= sprintf('UPDATE cme_provider_application SET approved_date=Now(),status=4 WHERE provider_id=%s',$providerID);
		if(mysql_query($SQLInsert))
			{
				$SQLInsert= sprintf('UPDATE cme_providers SET status=4 WHERE pr_id=%s',$providerID);
				if(mysql_query($SQLInsert))
				{
					//Check if the provider's coordinator is fully approved then change roles
					$this->assign_provider_coordinator_rights($providerID);
					$this->sendApprovalMail($providerID);
				}
				return true;
			}
			else
				return false;
	}
	
	public function get_newly_submitted_application()
	{
		$SQLCount= sprintf('SELECT * FROM cme_provider_application WHERE status=1');
		$res= mysql_query($SQLCount);
		$count = mysql_num_rows($res);
		return $count;
	}
	public function get_newly_received_application()
	{
		$SQLCount= sprintf('SELECT * FROM cme_provider_application WHERE status=2');
		$res= mysql_query($SQLCount);
		$count = mysql_num_rows($res);
		return $count;
	}
	public function get_newly_processing_application()
	{
		$SQLCount= sprintf('SELECT * FROM cme_provider_application WHERE status=3');
		$res= mysql_query($SQLCount);
		$count = mysql_num_rows($res);
		return $count;
	}
	
	public function checkProviderRegistrationStatus($providerid)
	{
		$SQLInsert= sprintf('SELECT
									cme_status_master.status_name
								FROM
									cme_provider_application
									INNER JOIN cme_status_master 
										ON (cme_provider_application.status = cme_status_master.status_id)
								WHERE cme_provider_application.provider_id=%s',$providerid);
		$res= mysql_query($SQLInsert);
		
		if(mysql_num_rows($res) > 0){
			while($r=mysql_fetch_array($res))
			{
				return $r['status_name'];
			}
		}
		else
		{
			$SQLGET= sprintf('SELECT
									cme_status_master.status_name
								FROM
									cme_providers
									INNER JOIN cme_status_master ON (cme_users.status = cme_status_master.status_id)
								WHERE cme_providers.pr_id=%s',$providerid);
			$res= mysql_query($SQLGET);
			
			if(mysql_num_rows($res) > 0){
				while($r=mysql_fetch_array($res))
				{
					return $r['status_name'];
				}
			}
			else
			{
				return 'Not set';
			}
		}
		
	}
	
	public function sendApprovalMail($providerID)
	{
		$result_user_name=mysql_query("SELECT cme_users.user_id,cme_users.first_name ,cme_users.last_name, cme_users.user_mail, cme_providers.pr_name
									FROM
										cme_providers
										INNER JOIN cme_provider_application ON (cme_providers.pr_id = cme_provider_application.provider_id)
										Inner JOIN cme_provider_coordinators ON cme_provider_application.provider_id = cme_provider_coordinators.provider_id
										INNER JOIN cme_users ON (cme_provider_coordinators.user_id = cme_users.user_id)
									WHERE (cme_providers.pr_id ='$providerID')");
		if($r=mysql_fetch_array($result_user_name))
			$fname = isset($r['first_name']) ? $r['first_name'] : '';
			$lname = isset($r['last_name']) ? $r['last_name'] : '';
			$user_mail = $r['user_mail'];
			$providerName = $r['pr_name'];
			
			
			$body = '<div style="padding:10px 15px; border:1px solid rgb(204,204,204); border-radius:5px 5px 5px 5px; background:-moz-linear-gradient(100% 100% 90deg, rgb(242,242,242),rgb(255,255,255)) repeat scroll 0% 0% transparent;font-family:Arial, Helvetica,sans-serif;">';	
			$body .='<img src="'.WEBLINK.'/images/emailheader.png"';	
			$body .= '<br>';
			$body .= '<br>';
			$body .= '<br>';			
			$body .='Dear,'.$lname.' '.$fname;
			$body .= '<br>';
			$body .= '<br>';
			$body .= '<p>Congratulations for your Registration application (for '.$providerName .') to the Kenya Medical Practitioners and Dentist Board has been approved.</p>';
			$body .= '<br> ';
			$body .= '<br>';
			$body .= '<br>
						Feel free to contact us incase you need assistance.';
			
			$body .= '<p>Yours sincerely,</p>
						CPD administrator<br>
						National CPD Project<br>
						Medical Practitioner and Dentist Board<br>';
			$body .= '<br>';
			$body .= 'admin@icpdkenya.org';
			$body .= '<br>';
			$body .= 'http://www.icpdkenya.org';
			$body .= '<br>';
			$body .= '-------------------------------------';
			$body .= '<br>';
			$body .= 'A project of the Kenya Medical Practitioners and Denstist Board';
			$body .= '<br>';
			$body .= '<br>';					
			
			$body .= '</div>';
			if(smtpmailer($user_mail, NOREPLY, 'National CPD Platform', 'Application Appoved', $body))
			{
				return true;
			}
	}
	public function assign_provider_coordinator_rights($providerID)
	{
		require_once('class_roles.php');
		require_once('class_registration.php');
		$Registration = new Registration;
		
		$userID = $this->get_provider_cordinator_id($providerID);
		if($Registration->checkRegistrationApproved()){			
				if(Role::insertUserRoles($userID, 5))
					return true;
		}
		
	}
	
	public function change_add_provider_coordinator()
	{	
			require_once('class_roles.php');
			
			$providerid = $_POST['company'];
			$user_id = $_POST['user_id'];
			$SQLCoor= "SELECT * FROM cme_provider_coordinators WHERE provider_id='$providerid'";
			$res=mysql_query($SQLCoor);
			if(mysql_num_rows($res)>0)
			{
				if($rs = mysql_fetch_array($res))
				{
					if(Role::insertUserRoles($rs['user_id'], 3))
					{
					$Query= "UPDATE cme_provider_coordinators SET user_id='$user_id' WHERE provider_id='$providerid'";
					if(mysql_query($Query))
						{
							
							if(Role::insertUserRoles($user_id, 5))
								return true;
						}
					}
				}
			}
			else
			{
				$Query= "INSERT INTO cme_provider_coordinators (user_id, provider_id,date_added) VALUES ('$user_id', '$providerid',Now())";
				if(mysql_query($Query))
					{
						if(Role::insertUserRoles($user_id, 5))
							return true;
					}
			}
	}
	
	public function get_provider_cordinator($providerID)
	{
		require_once ("class_user_profile.php");
		$userprofile = new UserProfile;
		
		
		$SQLCoor= sprintf("SELECT DISTINCT(cme_users.user_id) as UID,CONCAT(`first_name`,' ',`last_name`)  as coord
					FROM cme_users
    				INNER JOIN cme_provider_coordinators ON (cme_users.user_id = cme_provider_coordinators.user_id)
					WHERE cme_provider_coordinators.provider_id =%s",$providerID);
		$res=mysql_query($SQLCoor);	
		if($rs = mysql_fetch_array($res))
		{
			$userprofile->getUserVCard($rs['UID']);
			$user_name = $userprofile->fullname;
			return $user_name ;
		}
	}
	
	public function get_provider_cordinator_id($providerID)
	{
		require_once ("class_user_profile.php");
		$userprofile = new UserProfile;
		
		
		$SQLCoor= sprintf("SELECT DISTINCT(cme_users.user_id) as UID,CONCAT(`first_name`,' ',`last_name`)  as coord
					FROM cme_users
    				INNER JOIN cme_provider_coordinators ON (cme_users.user_id = cme_provider_coordinators.user_id)
					WHERE cme_provider_coordinators.provider_id =%s",$providerID);
		$res=mysql_query($SQLCoor);	
		if($rs = mysql_fetch_array($res))
		{
			return $rs['UID'] ;
		}
	}
}
?>