<?php

/* A CLASS TO LOGIN THE USER */

class Login {
	
 private $showMessage;
 public $user_level;
/* private $username;
 private $password;*/

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
		/*if($message_type=='success') $class='notification_success';
		elseif($message_type=='info') $class='notification_info';
		elseif($message_type=='warning') $class='notification_warning';
		elseif($message_type=='error') $class='notification_error';*/
        if($this->showMessage)
        {
           $c='';
            	/*if ($row['alert_type']=="info") { $button="messagebox_info"; $bg="background-color: #BDE5F8; color: #00529B;"; }
				elseif ($row['alert_type']=="error") { $button="error"; $bg="color: #D8000C;background-color: #FFBABA;"; }
				elseif ($row['alert_type']=="success") { $button="button_accept"; $bg="color: #4F8A10;background-color: #DFF2BF;"; }*/
				if($message_type=='success') $class='notification_success';
				elseif($message_type=='info') $class='notification_info';
				elseif($message_type=='warning') $class='notification_warning';
				elseif($message_type=='error') $class='notification_error';
				$style='border: 1px solid #ccc;-moz-border-radius:5px;border-radius:5px;background-repeat: no-repeat;background-position: 10px center;';
				
				$c.="<div id='alertbox' name='alertbox'  style='".$style." position:absolute;left:200;top:100;z-index:2;display:block;' >\n";
				$c.="<table class='alert' onClick=\"javascript:document.getElementById('alertbox').style.display='none';\">\n"; 
					$c.="<tr>\n";
						$c.="<td valign='top' background='images/".$class.".png'>".$message_text."</td>\n";
					$c.="</tr>\n";
				$c.="</table>\n";
				$c.="</div>\n";
			
			if($message_die) die($c);			
            else echo $message_html_tag_open . $c . $message_html_tag_close;//die($message_text);
        }
    }
	
	
	public function SetParameters($username,$password,$remember_me="n") {

		//$this->getMessage('Got Here'.$wgroup.'+'.$password);
		
		/* SET CHECKING TO false */
		$this->parameter_check=false;

		/* CHECK FOR TAINTED DATA */
		if (empty($username)) { $this->getMessage("Username is required to login!",'error'); return false; }
		if (empty($password)) { $this->getMessage("Password is required to login!",'error'); return false; }

		/* CHECK THAT THE REQUIRED FUNCTIONS EXIST */
		//if (!function_exists("GetColumnValue")) { $this->getMessage("Could not determine your user ID!"); return false; }
		//if (!function_exists("EscapeData")) { $this->getMessage("Could not login you in. Data cleansing failed!"); return false; }

		/* STORE THE VARIABLES LOCALLY */
		$this->username=$this->setEscape($username);
		$this->password=$this->setCrypt($this->setEscape($password));
		//$this->workgroup=$this->setEscape($wgroup);
		if ($remember_me!="n") {
			$this->SetRememberMeCookie();
		}

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=true;

		/* RETURN SUCCESS */
		return true;
	}
		
		
	public function UserVerify()
	{
		$sql="SELECT 'x'
	      FROM cme_users u		  
	      WHERE u.user_mail = '".$this->username."'
	      AND u.user_password = '".$this->password."'";
		
		//die($sql);
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0) 
		{
			//$this->getMessage($this->username);
			if($this->user_activated($this->username))
			{
				$this->GetUserID();
				$this->SetPublicSession();
				$this->UpdateSessionID();
				
				return true;
			}
			else
			{
				
				//$GLOBALS['msg'] = "Please activate your account before you continue!";
				//die($GLOBALS['msg']);
				$this->getMessage("Please activate your account before you continue!",'error');
				return false;
			}
		}
		else
		{
			
			return false;
		}
	}

	public function user_activated($username)
	{
	
		$sql="SELECT 'x' FROM cme_users u WHERE u.user_mail = '".$username."' AND u.activated = '1'";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0) 
			return true;
			else
				return false;
	}
	
	public function Verify() {
		if (AUTHENTICATIONTYPE == "mysql") {
			return $this->VerifyUserLocaDatabase();
		}
		elseif (AUTHENTICATIONTYPE == "imap") {
			return $this->VerifyUserIMAP();
		}

	}
	

	public function providerVerify() {

		$sql="SELECT 'x'
			  FROM cme_providers u		  	  
			  WHERE u.email = '".$this->username."'
			  AND u.pr_password = '".$this->password."'";
		
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0) 
		{
			$this->providerGetUserID();
			$this->providerSetSession();
			$this->UpdateProviderSessionID();
			return true;
		}
		else
		{
			return false;
		}
	}

	private function providerGetUserID() {
		
		$sql= sprintf('SELECT pr_id,email,pr_name FROM cme_providers WHERE email ="%s"',$this->username);
		$res= mysql_query($sql);
		if($r= mysql_fetch_array($res))
		{
			$this->provider_id=$r['pr_id'];
			$this->provider_mail=$r['email'];
			
		}
	}

	private function providerSetSession() {
		/* REGISTER THE SESSION NAMES */
		//session_register('sid');
		//session_register('user_id');
		$sid="";
		/* SET VALUES FOR THE SESSION NAMES */
		$_SESSION['sid'] = md5($this->provider_id.microtime());		
		$_SESSION['provider_id'] = $this->provider_id;	
		$_SESSION['email'] =$this->provider_mail;
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		$_SESSION['LAST_ACTIVITY'] = date(time());
		$_SESSION['providerSession']= md5($this->provider_id);
		//$this->SetWorkgroupSession();
		
	}
	private function VerifyUserIMAP() {

		$mbox = imap_open("{".$GLOBALS['auth_imap_server']."}INBOX",$this->username,$this->password);
		if ($mbox) {
			$this->GetUserID();
			$this->SetSession();
			$this->UpdateSessionID();
			
			return true;
		}
		else {
			return false;
		}
	}

	private function GetUserID() {
		
		$sql= sprintf('SELECT u.user_id,u.user_mail,u.username,r.roleid FROM cme_users u 
					LEFT JOIN cme_user_role r ON (u.user_id = r.user_id)
		 			WHERE u.user_mail="%s"',$this->username);
		$res= mysql_query($sql);
		if($r= mysql_fetch_array($res))
		{
			$this->user_id=$r['user_id'];
			$this->user_mail=$r['user_mail'];
			$this->username=$r['username'];
			$this->user_level = $r['roleid'];
		}
	}

	private function SetSession() {
		/* REGISTER THE SESSION NAMES */
		$sid="";
		$user_id="";
		/* SET VALUES FOR THE SESSION NAMES */
		$_SESSION['sid'] = md5($this->user_id.microtime());		
		$_SESSION['user_id'] = $this->user_id;	
		$_SESSION['user_level'] = $this->user_level;
		//$_SESSION['providerSession'] = md5('provider');	
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		$_SESSION['LAST_ACTIVITY'] = date(time());
		//$this->SetWorkgroupSession();
		
		require_once "class_roles.php";
		require_once "class_privileged_user_old.php";
		$u = PrivilegedUser::getByUsername($_SESSION["email"]);
		
		if ($u->hasPrivilege("Public")) {
		  $_SESSION['wkgroup'] = "public"; 
		}
		elseif ($u->hasPrivilege("Provider")) {
		  $_SESSION['wkgroup'] = "provider"; 
		}
		elseif ($u->hasPrivilege("Board")) {
		  $_SESSION['wkgroup'] = "board"; 
		}
		
		//die($_SESSION['wkgroup']);
		
	}
	private function SetPublicSession() {
		/* REGISTER THE SESSION NAMES */
		
		$sid="";
		$user_id="";
		/* SET VALUES FOR THE SESSION NAMES */
		$_SESSION['sid'] = md5($this->user_id.microtime());
		$_SESSION['user_sessionid']= md5($this->user_id.microtime());
		$_SESSION['signature'] = md5(uniqid(rand(), true) + $this->username);
		$_SESSION['signature_timestamp'] = time();
		$_SESSION['user'] =$this->username;
		$_SESSION['email'] =$this->user_mail;
		$_SESSION['user_level'] = $this->user_level;
		$_SESSION['testtakerlogged'] =$this->user_id;		
		$_SESSION['user_id'] = $this->user_id;		
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		$_SESSION['LAST_ACTIVITY'] = date(time());
		$this->SetWorkgroupSession();					
	}
	
	public function SetWorkgroupSession()
	{
		/* SET INITIAL WORKGROUP (BOARD,PUBLIC,PROVIDER) */
		
		require_once "class_roles.php";
		require_once "class_privileged_user_old.php";
		$u = PrivilegedUser::getByUsername($_SESSION["email"]);
		if ($u->hasPrivilege("Public")) {
		  $_SESSION['wkgroup'] = "public"; 
		}
		elseif ($u->hasPrivilege("Provider")) {
		  $_SESSION['wkgroup'] = "provider"; 
		}
		elseif ($u->hasPrivilege("Board")) {
		  $_SESSION['wkgroup'] = "board"; 
		}
		else
		{
			 $_SESSION['wkgroup'] = "guest"; 
		}
	}
	private function UpdateSessionID() {
		
		$ipaddress = getenv('REMOTE_ADDR');
		
		$logins = $this->getlogins()  ;
		$numlogings  = $logins + 1;
		$sql="UPDATE cme_users SET session_id = '".$_SESSION['sid']."',signature = '".$_SESSION['signature']."',signature_timestamp = '".$_SESSION['signature_timestamp']."', logged_in = 'y', logins = ".$numlogings." , login_ip='".$ipaddress."' WHERE user_mail = '".$this->user_mail."'";
		mysql_query($sql);
	}
	private function  UpdateProviderSessionID()
	{
		$sql="UPDATE cme_providers SET pr_session = '".$_SESSION['sid']."', logged_in = 'y' , login_ip=".@$REMOTE_ADDR."  WHERE email = '".$this->username."'";
		
		mysql_query($sql);
	}
	
	public function SetRememberMeCookie() {
		$result=setcookie("mvh_username",$this->username,time()+60*60*24*999);
	}
	
	public function getlogins()
	{
		$sql_query = sprintf("SELECT logins FROM cme_users WHERE user_mail='%s'", $this->user_mail);
		$result_query = @mysql_query($sql_query);           
		if($row_query = @mysql_fetch_array($result_query))
		{
			return $row_query['logins'] ;
		}
			  
				   
	}
	
	public function Get_User_ID($username) {
		
		$sql= sprintf('SELECT u.user_id FROM cme_users u WHERE u.user_mail="%s"',$username);
		$res= mysql_query($sql);
		if($r= mysql_fetch_array($res))
		{
			return $r['user_id'];
		}
	}

}
?>