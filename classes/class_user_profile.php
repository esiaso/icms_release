<?php

/**
*
* Project:     Natinal Intergrated CPD
* File:        class_user_profile.php
* Purpose:     Edit Users Profiles database
*
*
*
*
*
*/

class UserProfile
{
	 	private $showMessage;
		private $user_id;
	 	public $title;
		public $lastname;
		public $firstname;
		public $institution;
		public $institutiontype;
		public $speciality;
		public $country;
		public $city;
		public $profession;
		public $usermail;
		public $portrait;
		public $mobile1;
		public $mobile2;
		public $fixedline;
		public $skype;
		public $twitter;
		public $email;
		public $email2;
		public $dob;
		public $natId;	
		public $fullname;	
		public $userlevel;
		
		function __construct() {
		/* SET CHECKING TO false */
		$this->parameter_check=false;
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
		
		public function SetParameters($user_id) {
			require_once('class_error_messages.php');
			$errors = new ErrorMessage();
			/* CHECKS */
			if (!is_numeric($user_id)) { $errors->getMessage("Invalid user id",'error'); return false; }
	
			/* SET SOME COMMON VARIABLES */
			$this->user_id=$user_id;
	
			/* CALL THE INFORMATION METHOD */
			$this->Info();
	
			/* PARAMETER CHECK SUCCESSFUL */
			$this->parameter_check=true;
	
			return true;
		}
		
		private function Info() {
			require_once('class_system.php');
			$systems = new System();
		
		$sql="SELECT user_mail,first_name,city,last_name,country_residence
					FROM cme_users
					WHERE user_id = '".$this->user_id."'
					";
		//echo $sql."<br>";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_array($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$systems->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
		else {
			return false;
		}
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
		
		
		
		public function getUserProfile($userid)
		{
			
			$sqlu = sprintf('SELECT cme_user_title.usertitle , cme_countries.country , cme_countries.country , cme_users.user_level,
					 cme_users.first_name, cme_users.last_name , cme_users.reg_no,cme_users.mobile1,
					cme_users.user_mail , cme_users.city , cme_users.portrait
			 	FROM cme_users 
					LEFT JOIN cme_user_title ON (cme_users.title = cme_user_title.ttid)					 
					LEFT JOIN cme_countries ON (cme_users.country_residence = cme_countries.cid) 
					
									
				WHERE cme_users.user_id=%s',$this->setEscape($userid));
								
				
				$res = mysql_query($sqlu);
				if($r=mysql_fetch_array($res))
				{
					$this->title = $r['usertitle'];
					$this->lastname = $r['last_name'];					
					$this->firstname = $r['first_name'];
					$this->usermail = $r['user_mail'];
					$this->country = $r['country'];
					$this->userlevel = $r['user_level'];
					$this->mobile1 = $r['mobile1'];
					$this->city = $r['city'];					
					$this->portrait = $r['portrait'];
					$this->regnumber = $r['reg_no'];
					$this->get_default_user_facility($userid);
					$this->get_default_user_speciality($userid);				
					$this->fullname = $this->title.' '.$this->lastname.' '.$this->firstname;
				}
		}
		
	public function get_default_user_facility($userid)
	{
		$SQLs= sprintf('SELECT med_facilities.fname 
						FROM cme_user_facilities					
						LEFT JOIN med_facilities ON cme_user_facilities.facility_id= med_facilities.idmed_facilities
						WHERE cme_user_facilities.user_id = %s AND cme_user_facilities.default_fac="Yes"',$userid);
		$res = mysql_query($SQLs);
		if (mysql_num_rows($res) > 0) {
			if($r=mysql_fetch_array($res))
			{
				$this->institution  = $r['fname'];
			}
		}
		else
		{
			return '';
		}
	}
	public function get_default_user_speciality($userid)
	{
		$SQLs= sprintf('SELECT cme_profession.pro_name , cme_specialities.sp_name 
					FROM  cme_user_specialities
					LEFT JOIN cme_specialities ON (cme_user_specialities.spid = cme_specialities.sp_id)
			 		LEFT JOIN cme_profession ON (cme_user_specialities.profession_id = cme_profession.pro_id)					
					WHERE cme_user_specialities.usrid = %s AND cme_user_specialities.speciality_default="Yes"',$userid);
		
		$res = mysql_query($SQLs);
		if (mysql_num_rows($res) > 0) {
			if($r=mysql_fetch_array($res))
			{
				$this->speciality = $r['sp_name'];
				$this->profession = $r['pro_name'];
			}
		}
		else
		{
			return '';
		}
	}
		
	public function get_user_address($usr)
	{
	
			$Query = sprintf("SELECT * FROM cme_users u
							 JOIN cme_countries c ON u.country_residence = c.cid
							 WHERE user_id =%s",$this->setEscape($usr));	
		
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$address = '';
			$address .= '<strong>Physical Address :</strong>'.$this->setEscape($r['physical_address']).' <br />';
			$address .= '<strong>Postal Address: </strong>'.$this->setEscape($r['postal_address']).'<br />';
			$address .= '<strong>City: </strong>'.$r['city'].'<br />';
			//$address .= '<strong>Zip: </strong>'.$r['zip_code'].' <br />';
			$address .= '<strong>Country: </strong>'.$r['country'].'<br /> ';		
			$address .= '<strong>Phone: </strong>'.$r['phone_number'].'<br /> ';
			$address .= '<strong>Email: </strong>'.$r['user_mail'].'<br /> ';
			$address .= '';
			
			echo $address;			
		}
		return;
		
	}
	
	public function getUserVCard($userid)
		{
				$sqlu = sprintf('SELECT  *
								FROM cme_users
									LEFT JOIN cme_user_title ON (cme_users.title = cme_user_title.ttid)									
						 		WHERE user_id=%s',$this->setEscape($userid));
				$res = mysql_query($sqlu);
				if($r=mysql_fetch_array($res))
				{
					$this->title = $r['usertitle'];
					$this->lastname = $r['last_name'];					
					$this->firstname = $r['first_name'];
					$this->usermail = $r['user_mail'];
					$this->othernames = $r['other_names'];
					$this->mobile1 = $r['mobile1'];
					$this->mobile2 = $r['mobile2'];
					$this->fixedline = $r['fixedline'];
					$this->skype = $r['skype'];
					$this->twitter = $r['twitter'];
					$this->email  = $r['user_mail'];
					$this->email2  = $r['user_mail2'];
					$this->natId  = $r['natID'];
					$this->userlevel = $r['user_level'];
					$this->regnumber = $r['reg_no'];
					$this->dob = $r['dob'];
					$this->fullname = $this->title.' '.$this->lastname.' '.$this->firstname;	 			
				
				}
		}
		
	public function GetUserID($login ,$variable='') {
		if($variable=='')
			$variable = 'user_mail';
			else
				$variable = $variable;
				
		$sql="SELECT user_id
					FROM cme_users
					WHERE ".$variable." = '".$login."'
					";
		//echo $sql."<br>";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_array($result)) {
				return $row['user_id'];
			}
		}
		else {
			return false;
		}
	}
	public function UserPproviderID($user_id) {
		$sql="SELECT provider_id FROM cme_provider_coordinators WHERE user_id = '".$user_id."'";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_array($result)) {
				return $row['provider_id'];
			}
		}
		else {
			return false;
		}
	}
	
	public function ChangePassword($password,$password_repeat) {

		require_once('class_error_messages.php');
		$errors = new ErrorMessage();
			
		if ($password==$password_repeat) {
			$this->password = $this->setCrypt($this->setEscape($password));
			$sql="UPDATE cme_users
						SET user_password ='".$this->password."'
						WHERE user_id = ".$this->user_id."
						";
			
			if(mysql_query($sql))
			{			
				return true;			
			}
		}
		else {
			$errors->getMessage("Failed to change password. Passwords equal?",'error');
			return false;
		}
	}
	
	public function PasswordRecoveryExists() {
	
		$sql="SELECT *
					FROM cme_user_password_recovery
					WHERE user_id = '".$this->user_id."'
					AND date_requested > DATE_ADD(now(),interval -6 hour)
					";
		//echo $sql."<br>";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function AddPasswordRecovery($user_id,$secret_string) {
		
		$sql="INSERT INTO cme_user_password_recovery
					(user_id,date_requested,secret_string)
					VALUES (
					'".$user_id."',
					now(),
					'".$secret_string."'
					)";
		//echo $sql."<br>";
		if(mysql_query($sql))
		{
			if (mysql_affected_rows() == 0) {
				return false;
			}
			else {
				return true;
			}
		}
	}
	
	public function get_user_rolename($usrlvl)
	{
		$Query = sprintf("SELECT * FROM cme_role WHERE roleid=%s",$usrlvl);	
		//die($Query);		
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res)) 
			$role = $r['rolename'];
			else 
			$role='';
		return $role;
		
	}
}