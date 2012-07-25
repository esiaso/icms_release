<?php

/**
*
* Project:     Natinal Intergrated CPD
* File:        class_user_profile.php
* Purpose:     Edit Provider Profiles database
*
*
*
*
*
*/

class ProviderProfile
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
		public $natId;	
		public $fullname;	
		public $userlevel;
		public $regnumber;
		public $providername;		
		public $pmobile1;
		public $pmobile2;
		public $pfixedline;
		public $pskype;
		public $ptwitter;
		public $provinstitution;
		
		function __construct() {
		/* SET CHECKING TO false */
		$this->parameter_check=false;
		} 
		
		public function SetParameters($user_id) {
			require_once('class_error_messages.php');
			$errors = new ErrorMessage();
			/* CHECKS */
			if (!is_numeric($user_id)) { $errors->getMessage("Invalid user id"); return false; }
	
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
		
		
		
		public function getProviderCoordinatorProfile($provider)
		{
			
			$sqlu = sprintf('SELECT cme_user_title.usertitle , cme_countries.country , cme_countries.country , cme_users.user_level,
					 cme_users.first_name, cme_users.last_name , cme_users.reg_no,
					cme_users.user_mail , cme_users.city , cme_users.portrait, cme_users.user_id
			 	FROM cme_users 
					LEFT JOIN cme_user_title ON (cme_users.title = cme_user_title.ttid)					 
					LEFT JOIN cme_countries ON (cme_users.country_residence = cme_countries.cid) 
					LEFT JOIN cme_provider_users ON (cme_users.user_id = cme_provider_users.user_id)
									
				WHERE cme_provider_users.provider_id=%s',$this->setEscape($provider));
								
				
				$res = mysql_query($sqlu);
				if($r=mysql_fetch_array($res))
				{
					$this->title = $r['usertitle'];
					$this->lastname = $r['last_name'];					
					$this->firstname = $r['first_name'];
					$this->usermail = $r['user_mail'];
					$this->country = $r['country'];
					$this->userlevel = $r['user_level'];
					$this->city = $r['city'];					
					$this->portrait = $r['portrait'];
					$this->regnumber = $r['reg_no'];
					$this->get_default_user_facility($r['user_id']);
					$this->get_default_user_speciality($r['user_id']);				
					
				}
		}
		
	public function get_default_user_facility($userid)
	{
		$SQL= sprintf('
					SELECT med_facilities.fname 
					FROM cme_user_facilities					
					LEFT JOIN med_facilities ON cme_user_facilities.facility_id= med_facilities.idmed_facilities
					WHERE cme_user_facilities.user_id = %s AND cme_user_facilities.default_fac="Yes"',$userid);
		$res = mysql_query($SQL);
		if($r=mysql_fetch_array($res))
		{
			$this->institution  = $r['fname'];
		}
		else
		{
			return '';
		}
	}
	public function get_default_user_speciality($userid)
	{
		$SQL= sprintf('
					SELECT cme_profession.pro_name , cme_specialities.sp_name 
					FROM  cme_user_specialities
					LEFT JOIN cme_specialities ON (cme_user_specialities.spid = cme_specialities.sp_id)
			 		LEFT JOIN cme_profession ON (cme_user_specialities.profession_id = cme_profession.pro_id)
					
					WHERE cme_user_specialities.usrid = %s AND cme_user_specialities.speciality_default="Yes"',$userid);
		$res = mysql_query($SQL);
		if($r=mysql_fetch_array($res))
		{
			$this->speciality = $r['sp_name'];
			$this->profession = $r['pro_name'];
		}
		else
		{
			return '';
		}
	}
	
	public function get_default_provider_facility($providerid)
	{
		$SQL= sprintf('
					SELECT med_facilities.fname 
					FROM cme_provider_facilities					
					LEFT JOIN med_facilities ON cme_provider_facilities.facility_id= med_facilities.idmed_facilities
					WHERE cme_provider_facilities.provider_id = %s AND cme_provider_facilities.default_fac="Yes"',$providerid);
		$res = mysql_query($SQL);
		if($r=mysql_fetch_array($res))
		{
			$this->provinstitution  = $r['fname'];
		}
		else
		{
			return '';
		}
	}
	public function get_default_provider_speciality($providerid)
	{
		$SQL= sprintf('
					SELECT cme_profession.pro_name , cme_specialities.sp_name 
					FROM  cme_user_specialities
					LEFT JOIN cme_specialities ON (cme_user_specialities.spid = cme_specialities.sp_id)
			 		LEFT JOIN cme_profession ON (cme_user_specialities.profession_id = cme_profession.pro_id)
					
					WHERE cme_user_specialities.usrid = %s AND cme_user_specialities.speciality_default="Yes"',$userid);
		$res = mysql_query($SQL);
		if($r=mysql_fetch_array($res))
		{
			$this->speciality = $r['sp_name'];
			$this->profession = $r['pro_name'];
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
	
	public function getProviderVCard($providerid)
		{
				$sqlu = sprintf('SELECT  *
								FROM cme_providers									
						 		WHERE pr_id=%s',$this->setEscape($providerid));
				$res = mysql_query($sqlu);
				if($r=mysql_fetch_array($res))
				{
					$this->regnumber = $r['regno'];
					$this->providername = $r['pr_name'];
					$this->logo = $r['logo'];
					$this->pemail = $r['email'];
					$this->postalAddress = $r['postaladdress'].'-'.$r['postalcode'].'<br>';
					$this->town = $r['town'];
					$this->pfixedline = $r['fixed_line'];					
					$this->pmobile1 = $r['phone'];
					$this->pskype = $r['skype'];
					$this->ptwitter = $r['twitter'];
					$this->get_default_provider_facility($providerid);
				}
		}
		
	public function GetUserID($login) {
		$sql="SELECT user_id
					FROM cme_users
					WHERE user_mail = '".$login."'
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
	
	public function ChangePassword($password,$password_repeat)
	 {
		require_once('class_error_messages.php');
		$errors = new ErrorMessage();
		
		if ($this->parameter_check && $password==$password_repeat) {
			$sql="UPDATE cme_users
						SET password =SHA1('".$password."')
						WHERE user_id = ".$this->user_id."
						";

			if(mysql_query($sql))
			{
				if (mysql_affected_rows() > 0) {
					return true;
				}
				else {
					$errors->getMessage("Failed to change password.",'error');
					return false;
				}
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
}