<?php

/**
 * Project:     Registration PHP Class
 * File:        class_registration.php
 * Purpose:     Register users in a mysql database For CPD
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

class Registration
{
    private $databaseUsersTable;
    //private $cryptMethod;
    private $showMessage;

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
    
    /**
     * Prints the class messages with a customized style if html tags are defined
     *
     * @param string $message_text - the message text
     * @param string $message_html_tag_open - the html tag placed before the text
     * @param string $message_html_tag_close - the html tag placed after the text
     * @param boolean $message_die - if on true die($message_text);
	 * @param string $message_type the type of message (Error, Info, Successfull)
     */
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
    public function setUserRegistration()
    {
		require_once('class_functions.php');
		$random = new generals;
		require_once('class_roles.php');
		//$roleUser = new Role();
		
        if(!$this->databaseUsersTable) $this->getMessage('Users table in the database is not specified. Please specify it before any other operation using the method setDatabaseUserTable();','','','','true');
        //$user_name=$this->setEscape($_POST['user_name']);
        $fname=$_POST['fname'];
        $lname=$_POST['lname'];
		$user_pass=$_POST['pin'];
        $user_confirm_pass=$_POST['pinconfirm'];
        $user_mail=$_POST['user_mail'];
		$activation_code = $random->genRandomString();
        $user_confirm_mail=$_POST['user_confirm_mail'];
        $user_crypted_pass=$this->setCrypt($user_pass);
       // $result_user_name=mysql_query("SELECT * FROM"." ".$this->databaseUsersTable." "."WHERE user_name='$user_name'");
        $result_user_mail=mysql_query("SELECT * FROM"." ".$this->databaseUsersTable." "."WHERE user_mail='$user_mail'");
		//$this->getMessage("SELECT * FROM"." ".$this->databaseUsersTable." "."WHERE user_mail='$user_mail'");
        //if((strlen($user_name)<6) or (strlen($user_name)>16)) $this->getMessage('Entered username length must be of 6 to 16 characters.');
       // elseif(mysql_num_rows($result_user_name)) $this->getMessage('Entered username already exists in the database.');
        if((strlen($user_pass)<8) or (strlen($user_pass)>16)) $this->getMessage('Entered password length must be of 8 to 16 characters.','error');
        elseif($user_pass!=$user_confirm_pass) $this->getMessage('Passwords entered do not match.','error');
        elseif(mysql_num_rows($result_user_mail)) $this->getMessage('Entered email already exists in the database.','error');
        elseif($user_mail!=$user_confirm_mail) $this->getMessage('Email addresses entered do not match.','error');
       // elseif(!preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-]{4,})+\.)+([a-zA-Z0-9]{2,})+$/", $user_mail)) $this->getMessage('Email address entered is not valid.');
        else
        {
			$Query= "INSERT INTO"." ".$this->databaseUsersTable." "."(user_password, user_mail,first_name,last_name,dateadded,addedby,activation_code) VALUES ('$user_crypted_pass', '$user_mail','$fname','$lname',Now(),'self','$activation_code')";
			//$this->getMessage($Query);
            if(mysql_query($Query))
				{
					$user_id = $this->get_user_id($user_mail);
					Role::insertUserRoles($user_id, 2);
					
					$body = '<div style="padding:10px 15px; border:1px solid rgb(204,204,204); border-radius:5px 5px 5px 5px; background:-moz-linear-gradient(100% 100% 90deg, rgb(242,242,242),rgb(255,255,255)) repeat scroll 0% 0% transparent;font-family:Arial, Helvetica,sans-serif;">';	
					$body .='<img src="'.WEBLINK.'/images/emailheader.png"';	
					$body .= '<br>';
					$body .= '<br>';
					$body .= '<br>';			
					$body .='Dear,'.$lname.' '.$fname;
					$body .= '<br>';
					$body .= '<br>';
					$body .= '<p>Congratulations for your successful registration in the integrated CPD Management System (iCMS) platform.</p>';
					$body .= '<br>';
					$body .= '<p>Your username and passwords are:</p>';
					$body .= '<p>USERNAME: <em>'.$user_mail.'</em></p>';
					$body .= '<p>PASSWORD: <em>'.$user_pass.'</em></p>';
					$body .= '<br> ';
					$body .= '<br>';
					$body .= 'Click <a href="'.WEBLINK.'index.php?k='.base64_encode('registration'.get_separator().'activate'.get_separator().''.get_separator()).'&usrn='.$user_mail.'&code='.$activation_code.'"> Here</a> to continue to activate your account';
					$body .= '<br>';
					$body .= '<br>';
					$body .= 'Alternatively, you can copy this link to your browser to access the website.';
					$body .= WEBLINK.'index.php?k='.base64_encode('registration'.get_separator().'activate'.get_separator().''.get_separator()).'&usrn='.$user_mail.'&code='.$activation_code;
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
					if(smtpmailer($user_mail, NOREPLY, 'National CPD Platform', 'User Registration', $body))
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
	
	public function comparePassword($curr_pass,$my_pass)
	{
		if($curr_pass!=$my_pass)
			return false;
			else
				return true;
	}
	
	
	public function updateChangePassword($userid)
    {
		//$user_name=$this->setEscape($_POST['user_name']);
        $user_pass=$_POST['newpass'];
        $user_confirm_pass=$_POST['confpass'];
        $currpass=$_POST['currpass'];
		
        //$user_confirm_mail=$_POST['user_confirm_mail'];
        $user_crypted_pass=$this->setCrypt($user_pass);
		$user_crypted_currpass=$this->setCrypt($currpass);
		//die($user_crypted_currpass);
        $result_user_name=mysql_query("SELECT * FROM cme_users WHERE user_id='$userid'");
		if($r=mysql_fetch_array($result_user_name))
			$my_pass = $r['user_password'];
			//die($my_pass);
      
	  	if($user_crypted_currpass!=$my_pass)$this->getMessage('Current password is wrong.','error');        
        elseif((strlen($user_pass)<8) or (strlen($user_pass)>16)) $this->getMessage('Entered password length must be of 8 to 16 characters.','error');
        elseif($user_pass!=$user_confirm_pass) $this->getMessage('Passwords entered do not match.','error');
		else
		{
			$SQLUpdate= sprintf('UPDATE cme_users SET user_password ="%s" WHERE user_id=%s',$user_crypted_pass,$userid);		
			if(mysql_query($SQLUpdate))
			{
				$this->getMessage('Successfully changed your password.','success');
				//$GLOBALS['msg'] = "Successfully edited your details";
			}
		}
	}
	public function create_newAccount()
	{
		require_once('class_functions.php');
		$random = new generals;
		require_once('class_roles.php');
		//$roleUser = new Role();
		
		//$this->getMessage($_SESSION['user_id']);
		$fname=$_POST['fname'];
        $lname=$_POST['lname'];
		$user_pass=$_POST['pin'];
        $facility=$_POST['company'];
        $user_mail=$_POST['user_mail'];
		$usergroup=$_POST['usergroup'];	
		$user = $_SESSION['user_id'];
		$facilitydefault = "Yes";		
		$activation_code = $random->genRandomString();
        $user_crypted_pass=$this->setCrypt($user_pass);
        $result_user_mail=mysql_query("SELECT * FROM cme_users WHERE user_mail='$user_mail'");
		if(mysql_num_rows($result_user_mail )> 0)
		{
			$this->getMessage('This Email address is already in use','error');
		}
		else
		{
			$Query= "INSERT INTO cme_users (user_password, user_mail,first_name,last_name,dateadded,addedby,activation_code) VALUES ('$user_crypted_pass', '$user_mail','$fname','$lname',Now(),'$user','$activation_code')";
			$res = mysql_query($Query);
            if($res){
					$user_id = $this->get_user_id($user_mail);
					Role::insertUserRoles($user_id, 2);
				$body = '<div style="padding:10px 15px; border:1px solid rgb(204,204,204); border-radius:5px 5px 5px 5px; background:-moz-linear-gradient(100% 100% 90deg, rgb(242,242,242),rgb(255,255,255)) repeat scroll 0% 0% transparent;font-family:Arial, Helvetica,sans-serif;">';	
					$body .='<img src="'.WEBLINK.'/images/emailheader.png"';	
					$body .= '<br>';
					$body .= '<br>';
					$body .= '<br>';			
					$body .='Dear,'.$lname.' '.$fname;
					$body .= '<br>';
					$body .= '<br>';
					$body .= '<p>Congratulations for your successful registration in the integrated CPD Management System (iCMS) platform.</p>';
					$body .= '<br>';
					$body .= '<p>Your username and passwords are:</p>';
					$body .= '<p>USERNAME: <em>'.$user_mail.'</em></p>';
					$body .= '<p>PASSWORD: <em>'.$user_pass.'</em></p>';
					$body .= '<br> ';
					$body .= '<br>';
					$body .= 'Click <a href="'.WEBLINK.'index.php?k='.base64_encode('registration'.get_separator().'activate'.get_separator().''.get_separator()).'&usrn='.$user_mail.'&code='.$activation_code.'"> Here</a> to continue to activate your profile';
					$body .= '<br>';
					$body .= '<br>';
					$body .= 'Alternatively, you can copy this link to your browser to access the website.';
					$body .= WEBLINK.'index.php?k='.base64_encode('registration'.get_separator().'activate'.get_separator().''.get_separator()).'&usrn='.$user_mail.'&code='.$activation_code;
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
					//Associate the user with facility
					$result = mysql_query("SELECT * FROM cme_users WHERE user_mail='$user_mail'");
					if($r= mysql_fetch_array($result));					
					require_once("class_affiliate_faciliities.php");
					$userfacility= new userFacilitiesAffiliation();
					$userfacility->associate_user_facility($r['user_id'],$facility,$facilitydefault);
					//Send mail
					if(smtpmailer($user_mail, NOREPLY, 'National CPD Platform', 'User Registration', $body))
					{
						return true;
					}
					
			}
			else
			{
				//$this->getMessage(mysql_error());
				//mysql_err
			}
		}
		
		
	}
	
	public function submitRegistration($userid)
	{
		$SQLInsert= sprintf('INSERT INTO cme_registration_application (user_id,app_date,status) VALUES(%s,Now(),1)',$userid);
		if(mysql_query($SQLInsert))
			{
				$SQLInsert= sprintf('UPDATE cme_users SET status=1 WHERE user_id=%s',$userid);
				if(mysql_query($SQLInsert));
				//$this->sendApprovalMail($userid);
			}
			else
				return false;
	}
	
	public function receiveApplication($userid)
	{
		$SQLInsert= sprintf('UPDATE cme_registration_application SET received_date=Now(),status=2 WHERE user_id=%s',$userid);
		if(mysql_query($SQLInsert))
		{
				$SQLInsert= sprintf('UPDATE cme_users SET status=2 WHERE user_id=%s',$userid);
				if(mysql_query($SQLInsert))
				return true;
		}
			else
				return false;
	}
	public function startProcessingApplication($userid)
	{
		$SQLInsert= sprintf('UPDATE cme_registration_application SET processed_date=Now(),status=3 WHERE user_id=%s',$userid);
		if(mysql_query($SQLInsert))
			{
				$SQLInsert= sprintf('UPDATE cme_users SET status=3 WHERE user_id=%s',$userid);
				if(mysql_query($SQLInsert))
				return true;
		}
			else
				return false;
	}
	public function ApprovedApplication($userid)
	{  
	require_once('class_roles.php');
		//$roleUser = new Role();
		$SQLInsert= sprintf('UPDATE cme_registration_application SET approved_date=Now(),status=4 WHERE user_id=%s',$userid);
		if(mysql_query($SQLInsert))
			{
				$SQLInsert= sprintf('UPDATE cme_users SET status=4 WHERE user_id=%s',$userid);
				if(mysql_query($SQLInsert))
				{
					$this->sendApprovalMail($userid);
					Role::insertUserRoles($userid, 3);
					return true;
				}
			}
			else
				return false;
	}
	
	public function approveUserProviderApplication($provider_id,$userid)
	{
		
		
		$SQLInsert= sprintf('INSERT INTO cme_provider_users (provider_id,user_id,date_added) VALUES(%s,%s,Now())',$provider_id,$userid);
			if(mysql_query($SQLInsert))
				{
					
					return true;
				}
				 else 
				 	return false;
	}
	
	
	public function rejectApplication($userid)
	{
		$SQLInsert= sprintf('INSERT INTO cme_registration_application (user_id,rejected_date,status) VALUES(%s,Now(),5)',$userid);
		if(mysql_query($SQLInsert))
			{
				$SQLInsert= sprintf('UPDATE cme_users SET status=5 WHERE user_id=%s',$userid);
				if(mysql_query($SQLInsert))
				return true;		
			}	
			else
				return false;
	}
	
	public function  checkRegistrationApproved($userid)
	{
		$SQLInsert= sprintf('SELECT
									*
								FROM
									cme_registration_application
								WHERE user_id=%s AND status=4',$userid);
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)		
			return true;
			else
				return false;
	}
	
	public function checkRegistrationStatus($userid)
	{
		$SQLInsert= sprintf('SELECT
									cme_status_master.status_name
								FROM
									cme_registration_application
									INNER JOIN cme_status_master 
										ON (cme_registration_application.status = cme_status_master.status_id)
								WHERE cme_registration_application.user_id=%s',$userid);
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
									cme_users
									INNER JOIN cme_status_master ON (cme_users.status = cme_status_master.status_id)
								WHERE cme_users.user_id=%s',$userid);
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
	public function checkRegistrationSubmitted($userid)
	{
		$SQLInsert= sprintf('SELECT * FROM cme_registration_application WHERE user_id=%s',$userid);
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
			return true;
			else
				return false;
	}
	
	public function sendApprovalMail($userid)
	{
		$result_user_name=mysql_query("SELECT * FROM cme_users WHERE user_id='$userid'");
		if($r=mysql_fetch_array($result_user_name))
			$fname = $r['first_name'];
			$lname = $r['last_name'];
			$user_mail = $r['user_mail'];
			
			$body = '<div style="padding:10px 15px; border:1px solid rgb(204,204,204); border-radius:5px 5px 5px 5px; background:-moz-linear-gradient(100% 100% 90deg, rgb(242,242,242),rgb(255,255,255)) repeat scroll 0% 0% transparent;font-family:Arial, Helvetica,sans-serif;">';	
			$body .='<img src="'.WEBLINK.'/images/emailheader.png"';	
			$body .= '<br>';
			$body .= '<br>';
			$body .= '<br>';			
			$body .='Dear,'.$lname.' '.$fname;
			$body .= '<br>';
			$body .= '<br>';
			$body .= '<p>Congratulations for your Registration application to the Kenya Medical Practitioners and Dentist Board has been approved.</p>';
			$body .= '<br> ';
			$body .= '<br>';
			$body .= 'Click <a href="'.WEBLINK.'index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'&usrn='.$user_mail.'"> Here</a> to continue to your profile';
			$body .= '<br>';
			$body .= '<br>';
			$body .= 'Alternatively, you can copy this link to your browser to access the website.';
			$body .= WEBLINK.'index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'&usrn='.$user_mail;
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
			if(smtpmailer($user_mail, NOREPLY, 'National CPD Platform', 'Application Appoved', $body))
			{
				return true;
			}
	}
	
	public function get_newly_submitted_application()
	{
		$SQLCount= sprintf('SELECT * FROM cme_registration_application WHERE status=1');
		$res= mysql_query($SQLCount);
		$count = mysql_num_rows($res);
		return $count;
	}
	public function get_newly_received_application()
	{
		$SQLCount= sprintf('SELECT * FROM cme_registration_application WHERE status=2');
		$res= mysql_query($SQLCount);
		$count = mysql_num_rows($res);
		return $count;
	}
	public function get_newly_processing_application()
	{
		$SQLCount= sprintf('SELECT * FROM cme_registration_application WHERE status=3');
		$res= mysql_query($SQLCount);
		$count = mysql_num_rows($res);
		return $count;
	}
	public function get_newly_approved_application()
	{
		$SQLCount= sprintf('SELECT * FROM cme_registration_application WHERE status=4');
		$res= mysql_query($SQLCount);
		$count = mysql_num_rows($res);
		return $count;
	}
	
	public function get_newly_submitted_re_application()
	{
		$SQLCount= sprintf('SELECT * FROM cme_renewal_application WHERE status=1');
		$res= mysql_query($SQLCount);
		$count = mysql_num_rows($res);
		return $count;
	}
	
	public function get_new_application_request()
	{
		$newapp = $this->get_newly_submitted_application();
		$repplication = $this->get_newly_submitted_re_application();
		$count = $newapp + $repplication;
		return $count;
	}
	
	public function checkRegistrationCompleted($userid)
	{
		
		if(!$this->checkAffiliatedHospital($userid)) return false;
		elseif(!$this->checkContacts($userid)) return false;
		elseif(!$this->checkDocuments($userid)) return false;
		elseif(!$this->checkIdentity($userid)) return false;
		elseif(!$this->checkAcademic($userid)) return false;
		else return true;
		
	}
	public function checkRegistrationCompleteness($userid)
	{
		$complete=0;
		if($this->checkAffiliatedHospital($userid)) $complete=$complete+20 ;
			else $complete=$complete+0;
		if($this->checkContacts($userid))  $complete=$complete+20 ;
			else $complete=$complete+0;
		if($this->checkDocuments($userid))  $complete=$complete+20 ;
			else $complete=$complete+0;
		if($this->checkIdentity($userid))  $complete=$complete+15 ;
			else $complete=$complete+0;
		if($this->checkAcademic($userid))  $complete=$complete+25 ;
			else $complete=$complete+0;
		return $complete;
	}
	public function checkRegistrationProcessing($userid)
	{
		$status = $this->checkRegistrationStatus($userid);
		$complete=0;
		if($status=='Pending') $complete=0 ;
		elseif($status=='Received') $complete=25 ;
		elseif($status=='In_processing')  $complete=75 ;
		elseif($status=='Approved')  $complete=100 ;
		elseif($status=='Rejected')  $complete=0 ;
		return $complete;
	}
	
	public function checkRegistrationCompletedMessages($userid)
	{
		$message='';		
		if(!$this->checkAffiliatedHospital($userid))  $message.='Provide information about your affiliated facility.';
		elseif(!$this->checkContacts($userid))  $message.='Provide your contacts information.';
		elseif(!$this->checkDocuments($userid))  $message.='Provide registration application documents.';
		elseif(!$this->checkIdentity($userid))  $message.='Provide your ID/Passport information.';
		elseif(!$this->checkAcademic($userid))  $message.='Provide information about your academic qualification.';
		//else 
		return $message;
		
	}
	
	private function checkAffiliatedHospital($userid)
	{
		$SQLInsert= sprintf('SELECT * FROM cme_user_facilities WHERE user_id=%s',$userid);		
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
			return true;
			else
				return false;
	}
	private function checkDocuments($userid)
	{
		$SQLInsert= sprintf('SELECT * FROM cme_user_documents WHERE user_id=%s',$userid);		
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
			return true;
			else
				return false;
	}
	
	private function checkAcademic($userid)
	{
		$SQLInsert= sprintf('SELECT * FROM cme_user_accademics WHERE user_id=%s',$userid);		
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
			return true;
			else
				return false;
	}
	
	private function checkIdentity($userid)
	{
		$SQLInsert= sprintf('SELECT natID FROM cme_users WHERE user_id=%s',$userid);		
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
			return true;
			else
				return false;
	}
	
	private function checkContacts($userid)
	{
		$SQLInsert= sprintf('SELECT postal_address,city,postalcode,place,street,building FROM cme_users WHERE user_id=%s',$userid);		
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
			return true;
			else
				return false;
	}
	
	public function get_newly_submitted_application_to_provider($provider_id)
	{
		$SQLCount= sprintf('SELECT * FROM cme_provider_user_application WHERE provider_id=%s AND status = 7 AND user_id NOT IN (SELECT user_id FROM cme_provider_users WHERE provider_id=%s)',$provider_id,$provider_id);
		$res= mysql_query($SQLCount);
		$count = mysql_num_rows($res);
		return $count;
	}
	
	public function activateUser($username,$code)
	{
		require_once('class_roles.php');
		//$roleUser = new Role();

		if(Registration::checkIfactivateUser($username,$code))
		{
			$SQLInsert= sprintf('UPDATE cme_users SET activated="1" WHERE user_mail="%s"',$username);
			if(mysql_query($SQLInsert))
				{	
					$user_id = Registration::get_user_id($username);
					Role::insertUserRoles($user_id, 2);
					return true;
				}
				else
					return false;
		}
		else
		{
			return false;
		}
	}
	
	public function checkIfactivateUser($username,$code)
	{
		$SQLInsert= sprintf('SELECT * FROM cme_users WHERE user_mail="%s" AND activation_code="%s"',$username,$code);
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
			return true;
			else
				return false;
	}
	
	private function get_user_id($username)
	{
		$SQLInsert= sprintf('SELECT user_id FROM cme_users WHERE user_mail="%s"',$username);		
		$res= mysql_query($SQLInsert);
		if($r= mysql_fetch_array($res))
			return $r['user_id'];
		
	}
}
	
?>