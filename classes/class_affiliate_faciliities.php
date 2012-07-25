<?php

class userFacilitiesAffiliation
{
	public function check_if_new_facility($new)
	{
		if($new=='no')
			return 'disabled="disabled"';
			else
				return '';
	}
	
	public function check_facility_exist($facilityname)
	{
		$SQL= sprintf("SELECT FROM med_facilities WHERE fname LIKE '%%s%'",$facilityname);
		$res= mysql_query($SQL);
		if(mysql_num_rows($res) > 0)
			return true;
				else
				return false;
	}
	
	public function add_new_temporary_facility()
	{
	}
	
	public function associate_user_facility($user_id,$facility_id,$default='')
	{
		require_once('class_error_messages.php');
		$error = new ErrorMessage();
		$error->setShowMessage(true);
		
					
		if(!$this->check_if_user_associated($user_id,$facility_id))
		{	
			if($this->check_if_user_facility_default($user_id))
			{
				$SQLUpdates = sprintf('UPDATE cme_user_facilities SET default_fac="No" WHERE user_id=%s',$user_id);
				if(mysql_query($SQLUpdates))
				{				
				$SQLInsert= sprintf('INSERT INTO cme_user_facilities (user_id,facility_id,fac_registered,default_fac) VALUES("%s","%s","Yes","%s")',$user_id,$facility_id,$default);
				//$error->getMessage($SQLUpdate);
				if(mysql_query($SQLInsert))
					return true;
					else
						return false;
				}
			}
			else
			{
				$SQLUpdate= sprintf('INSERT INTO cme_user_facilities (user_id,facility_id,fac_registered,default_fac) VALUES("%s","%s","Yes","%s")',$user_id,$facility_id,$default);
				//$error->getMessage($SQLUpdate);
				if(mysql_query($SQLUpdate))
					return true;
					else
						return false;
			}
		}
	}
	
	private function check_if_user_facility_default($user_id)
	{
		$SQLCheck =sprintf('SELECT * FROM cme_user_facilities WHERE user_id="%s" AND default_fac="Yes"',$user_id);
		$res = mysql_query($SQLCheck);
		
		if(mysql_num_rows($res) > 0)
			return true;
			else
			 return false;
	}
	
	public function check_if_user_associated($user_id,$facility_id)
	{
		$SQLCheck =sprintf('SELECT * FROM cme_user_facilities WHERE user_id="%s" AND facility_id="%s"',$user_id,$facility_id);
		$res = mysql_query($SQLCheck);
		
		if(mysql_num_rows($res) > 0)
			return true;
				else
				return false;
	}
	
	public function associate_provider_facility($provider_id,$facility_id,$default='')
	{
		require_once('class_error_messages.php');
		$error = new ErrorMessage();
		$error->setShowMessage(true);
		
					
		if(!$this->check_if_provider_associated($provider_id,$facility_id))
		{	
			if($this->check_if_provider_facility_default($provider_id))
			{
				$SQLUpdates = sprintf('UPDATE cme_provider_facilities SET default_fac="No" WHERE provider_id=%s',$provider_id);
				if(mysql_query($SQLUpdates))
				{				
				$SQLInsert= sprintf('INSERT INTO cme_provider_facilities (provider_id,facility_id,fac_registered,default_fac) VALUES("%s","%s","Yes","%s")',$provider_id,$facility_id,$default);
				//$error->getMessage($SQLUpdate);
				if(mysql_query($SQLInsert))
					return true;
					else
						return false;
				}
			}
			else
			{
				$SQLUpdate= sprintf('INSERT INTO cme_provider_facilities (provider_id,facility_id,fac_registered,default_fac) VALUES("%s","%s","Yes","%s")',$provider_id,$facility_id,$default);
				//$error->getMessage($SQLUpdate);
				if(mysql_query($SQLUpdate))
					return true;
					else
						return false;
			}
		}
	}
	
	private function check_if_provider_facility_default($provider_id)
	{
		$SQLCheck =sprintf('SELECT * FROM cme_provider_facilities WHERE provider_id="%s" AND default_fac="Yes"',$provider_id);
		$res = mysql_query($SQLCheck);
		
		if(mysql_num_rows($res) > 0)
			return true;
			else
			 return false;
	}
	
	public function check_if_provider_associated($provider_id,$facility_id)
	{
		$SQLCheck =sprintf('SELECT * FROM cme_provider_facilities WHERE provider_id="%s" AND facility_id="%s"',$provider_id,$facility_id);
		$res = mysql_query($SQLCheck);
		
		if(mysql_num_rows($res) > 0)
			return true;
				else
				return false;
	}
	
	
	public function application_user_provider($provider_id,$user_id,$default='')
	{
		require_once('class_error_messages.php');
		$error = new ErrorMessage();
		$error->setShowMessage(true);
		
		
		if(!$this->check_if_application_user_provider($provider_id,$user_id))
		{	
			
			if($this->check_if_user_provider_default($provider_id))
			{
				$SQLUpdates = sprintf('UPDATE cme_provider_facilities SET default_fac="No" WHERE user_id=%s',$user_id);
				if(mysql_query($SQLUpdates))
				{				
				$SQLInsert= sprintf('INSERT INTO cme_provider_user_application (provider_id,user_id,app_date) VALUES("%s","%s",Now())',$provider_id,$user_id);
				
				if(mysql_query($SQLInsert))
					return true;
					else
						return false;
				}
			}
			else
			{
				$SQLUpdate= sprintf('INSERT INTO cme_provider_user_application (provider_id,user_id,app_date) VALUES("%s","%s",Now())',$provider_id,$user_id);
				
				if(mysql_query($SQLUpdate))
					return true;
					else
						return false;
			}
		}
	}
	
	private function check_if_user_provider_default($user_id)
	{
		$SQLCheck =sprintf('SELECT * FROM cme_provider_users WHERE user_id="%s" AND default_fac="Yes"',$user_id);
		
		$res = mysql_query($SQLCheck);
		
		if(mysql_num_rows($res) > 0)
			return true;
			else
			 return false;
	}
	
	public function check_if_application_user_provider($provider_id,$user_id)
	{
		$SQLCheck =sprintf('SELECT * FROM cme_provider_user_application WHERE provider_id="%s" AND user_id="%s"',$provider_id,$user_id);
		
		$res = mysql_query($SQLCheck);		
		if(mysql_num_rows($res) > 0)
			return true;
				else
				return false;
	}
	
	public function remove_user_provider_relationship($provider_id,$user_id)
	{
		$SQLDelete =sprintf('DELETE FROM cme_provider_users WHERE provider_id="%s" AND user_id="%s"',$provider_id,$user_id);
		//$res = mysql_query($SQLDelete);
		if(mysql_query($SQLDelete))
			return true;
				else
				return false;
	}
	public function remove_user_affiliation($facility)
	{
		$SQLDelete =sprintf('DELETE FROM cme_user_facilities WHERE user_fac_id ="%s"',$facility);
		if(mysql_query($SQLDelete))
			return true;
				else
				return false;
	}
}
?>

