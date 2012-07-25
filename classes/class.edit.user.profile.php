<?php

/**
*
* Project:     Natinal Intergrated CPD
* File:        class.edit.user.profile.php
* Purpose:     Edit Users Profiles database
*
*
*
*
*
*/

class EditUserProfile
{
	 	private $showMessage;
	 	public $title;
		public $lastname;
		public $firstname;
		public $institution;
		public $institutiontype;
		public $speciality;
		public $country;
		public $city;
		public $email;
		public $othernames;
		public $mobile1;
        public $mobile2;
        public $fixedline;
        public $skype;
        public $twitter;
		public $regnumber;
		public $facebook;
		public $paddress;
		public $pcode;
		public $pcity;
	
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
	
	public function updateUserProfile($userid)
    {
		require_once('class_error_messages.php');
		$errors = new ErrorMessage();
		
		$regnumber = isset($_POST['regnumber']) ? $_POST['regnumber'] : '' ;
		$title =  isset($_POST['title']) ? $_POST['title'] : '' ;
		$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '' ;
		$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '' ;
		$othernames = isset($_POST['othernames']) ? $_POST['othernames'] : '' ;
		$mobile1 = isset($_POST['mobile1']) ? $_POST['mobile1'] : '' ;
		$mobile2 = isset($_POST['mobile2']) ? $_POST['mobile2'] : '' ;
		$fixedline = isset($_POST['fixedline']) ? $_POST['fixedline'] : '' ;
		$skype = isset($_POST['skype']) ? $_POST['skype'] : '' ;
		$twitter = isset($_POST['twitter']) ? $_POST['twitter'] : '' ;
		$email = isset($_POST['email']) ? $_POST['email'] : '' ;
		$email2 = isset($_POST['email2']) ? $_POST['email2'] : '' ;
		$natId = isset($_POST['natId']) ? $_POST['natId'] : '' ;
		$facebook = isset($_POST['facebook']) ? $_POST['facebook'] : '' ;
		$paddress = isset($_POST['paddress']) ? $_POST['paddress'] : '' ;
		$pcode = isset($_POST['pcode']) ? $_POST['pcode'] : '' ;
		$pcity = isset($_POST['pcity']) ? $_POST['pcity'] : '' ;
		
		
		$SQLUpdate= sprintf('UPDATE cme_users SET reg_no ="%s",title ="%s",last_name  ="%s",  first_name  ="%s",other_names ="%s", mobile1 ="%s" ,mobile2  ="%s",fixedline="%s",skype="%s",twitter="%s",user_mail="%s",user_mail2="%s",natID ="%s",facebook="%s",postal_address="%s",postalcode="%s",city="%s"  WHERE user_id=%s',$regnumber,$title,$lastname,$firstname,$othernames,$mobile1,$mobile2,$fixedline,$skype,$twitter,$email,$email2,$natId,$facebook,$paddress,$pcode,$pcity,$userid);
		//$this->getMessage($SQLUpdate);
		
		if(mysql_query($SQLUpdate))
		{
			$errors->getMessage('Successfully edited your details.','success');
			//$GLOBALS['msg'] = "Successfully edited your details";
			return true;
		}
		else
		{
			//$this->getMessage(mysql_error());
			$errors->getMessage('Failed to edit your details!!.','error');
			return false;
		}
	}
	
	public function importUserProfile($userid,$tempuserid)
    {
		
		$sqlu = sprintf('SELECT * FROM cme_users_temp WHERE user_id=%s',$tempuserid);
			$res = mysql_query($sqlu);
			if($r=mysql_fetch_array($res))
			{
				$this->regnumber  = $r['reg_no'];
				$this->lastname = $r['last_name'];
				$this->firstname = $r['first_name'];
				$this->othernames = $r['other_names'];
				$this->mobile1 = $r['mobile1'];
				$this->profession = $r['profession'];				
				$this->email  = $_SESSION["email"];
				$paddress = $r['postal_address'];
				$pcode = $r['postalcode'];
				$pcity = $r['city'];
				
				$this->email2  = ($r['user_mail']==$_SESSION["email"]) ? $r['user_mail2'] : $r['user_mail'].','.$r['user_mail2'];
				
				$SQLUpdate= sprintf('UPDATE cme_users SET reg_no ="%s",last_name  ="%s",  first_name  ="%s",other_names ="%s", mobile1 ="%s" ,user_mail="%s",user_mail2="%s",postal_address="%s",city="%s" , postalcode="%s" WHERE user_id=%s',$this->regnumber,$this->lastname,$this->firstname,$this->othernames,$this->mobile1,$this->email,$this->email2,$paddress,$pcity,$pcode,$userid);
				if(mysql_query($SQLUpdate))
				{
					$SQLInsertProfession= sprintf('INSERT INTO cme_user_specialities (usrid,spid) VALUES(%s,"%s")',$userid,$this->profession);
					mysql_query($SQLInsertProfession);
				 return true;
				}
			}
		/*$sqlu = sprintf('SELECT reg_no,last_name,first_name,other_names,mobile1,user_mail INTO cme_users FROM cme_users_temp WHERE user_id=%s',$tempuserid);
		$res = mysql_query($sqlu);*/
	}
	
	public function getUserProfile($userid)
	{
			$sqlu = sprintf('SELECT * FROM cme_users WHERE user_id=%s',$userid);
			$res = mysql_query($sqlu);
			if($r=mysql_fetch_array($res))
			{
				$this->regnumber  = $r['reg_no'];
				$this->title = $r['title'];
				$this->lastname = $r['last_name'];
				$this->firstname = $r['first_name'];
				$this->othernames = $r['other_names'];
				$this->mobile1 = $r['mobile1'];
				$this->mobile2 = $r['mobile2'];
				$this->fixedline = $r['fixedline'];
				$this->skype = $r['skype'];
				$this->twitter = $r['twitter'];
				$this->email  = $r['user_mail'];
				$this->email2  = $r['user_mail2'];
				$this->natId  = $r['natID'];
				$this->facebook  = $r['facebook'];
				$this->paddress = $r['postal_address'];
				$this->pcode = $r['postalcode'];
				$this->pcity = $r['city'];
				
			}
	}
    
	
}