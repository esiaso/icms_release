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

class EditProviderProfile
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
	
	public function updateProviderProfile($userid)
    {
		require_once('class_error_messages.php');
		$errors = new ErrorMessage();
		
		$regnumber = isset($_POST['regnumber']) ? $_POST['regnumber'] : '' ;
		$providername = isset($_POST['providername']) ? $_POST['providername'] : '' ;		
		$email = isset($_POST['email']) ? $_POST['email'] : '' ;
		$skype = isset($_POST['skype']) ? $_POST['skype'] : '' ;
		$twitter = isset($_POST['twitter']) ? $_POST['twitter'] : '' ;
		$facebook = isset($_POST['facebook']) ? $_POST['facebook'] : '' ;
		$mobile1 = isset($_POST['mobile1']) ? $_POST['mobile1'] : '' ;
		$fixedline = isset($_POST['fixedline']) ? $_POST['fixedline'] : '' ;
		$paddress = isset($_POST['paddress']) ? $_POST['paddress'] : '' ;
		$pcode = isset($_POST['pcode']) ? $_POST['pcode'] : '' ;
		$pcity = isset($_POST['pcity']) ? $_POST['pcity'] : '' ;
		
		
		$SQLUpdate= sprintf('UPDATE cme_providers SET regno ="%s",pr_name ="%s",phone ="%s" ,fixed_line="%s",skype="%s",twitter="%s",email="%s",facebook="%s" ,postaladdress="%s",postalcode="%s",town="%s"
		 WHERE pr_id=%s',$regnumber,$providername,$mobile1,$fixedline,$skype,$twitter,$email,$facebook,$paddress,$pcode,$pcity,$userid);
	
		
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
	
	public function getProviderProfile($userid)
	{
			$sqlu = sprintf('SELECT * FROM cme_providers WHERE pr_id=%s',$userid);
			$res = mysql_query($sqlu);
			if($r=mysql_fetch_array($res))
			{
				$this->providername = $r['pr_name'];
				$this->mobile1 = $r['phone'];
				$this->fixedline = $r['fixed_line'];
				$this->skype = $r['skype'];
				$this->twitter = $r['twitter'];
				$this->email  = $r['email'];
				$this->regnumber  = $r['regno'];
				$this->facebook  = $r['facebook'];
				$this->paddress= $r['postaladdress'];
				$this->pcode= $r['postalcode'];
				$this->pcity= $r['town'];
				
				
			}
	}
    
	
}