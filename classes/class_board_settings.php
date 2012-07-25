<?php

class boardSettings
{
	public $deliverymode;
	public $activitylevel;
	public $initiator;
	public $activityname;
	public $description;
	public $cpdpoints;
	
	public function getActivityTypeDetails($typeId)
	{
			$sqlu = sprintf('SELECT * FROM cme_activity_type WHERE type_id=%s',$typeId);
			$res = mysql_query($sqlu);
			if($r=mysql_fetch_array($res))
			{
				$this->code = $r['type_code'];
				$this->deliverymode  = $r['delivery_mode'];
				$this->activitylevel = $r['type_level'];
				$this->initiator = $r['initiator'];
				$this->activityname = $r['type_name'];
				$this->description = $r['type_description'];
				$this->cpdpoints = $r['cpd_points'];
			}
		return;
	}
	
	public function editActivityTypeDetails($typeId)
	{
				require_once('class_error_messages.php');
				$error= new ErrorMessage;
				
				$this->code = $error->setEscape($_POST['code']);
				$this->deliverymode  = $error->setEscape($_POST['deliverymode']);
				$this->activitylevel = $_POST['activitylevel'];
				$this->initiator = $_POST['initiator'];
				$this->activityname = $error->setEscape($_POST['activityname']);
				$this->description = $error->setEscape($_POST['description']);
				$this->cpdpoints = $error->setEscape($_POST['cpdpoints']);
				
				$SQLUpdate = sprintf('UPDATE cme_activity_type SET type_code ="%s",delivery_mode ="%s",type_level ="%s",initiator ="%s"
				,type_name ="%s" ,type_description ="%s",cpd_points ="%s" WHERE type_id=%s',$this->code,$this->deliverymode,$this->activitylevel,$this->initiator,
				$this->activityname,$this->description,$this->cpdpoints,$typeId);
				
				 if(mysql_query($SQLUpdate))
				 	return true;
					else 
					 return false;
				 
	}
	public function addActivityTypeDetails()
	{
				require_once('class_error_messages.php');
				$error= new ErrorMessage;
				
				$this->code = $error->setEscape($_POST['code']);
				$this->deliverymode  = $error->setEscape($_POST['deliverymode']);
				$this->activitylevel = $_POST['activitylevel'];
				$this->initiator = $_POST['initiator'];
				$this->activityname = $error->setEscape($_POST['activityname']);
				$this->description = $error->setEscape($_POST['description']);
				$this->cpdpoints = $error->setEscape($_POST['cpdpoints']);
				
				$SQLInsert = sprintf('INSERT INTO cme_activity_type (type_code,delivery_mode,type_level,initiator,type_name,type_description,cpd_points) VALUES("%s","%s","%s","%s"	, "%s" ,"%s","%s")',$this->code,$this->deliverymode,$this->activitylevel,$this->initiator,$this->activityname,$this->description,$this->cpdpoints);
				
				 if(mysql_query($SQLInsert))
				 	return true;
					else 
					 return false;
				 
	}
	
	public function getActivitylevel($levelID)
	{
			$sqlu = sprintf('SELECT * FROM cme_activity_level WHERE levelid=%s',$levelID);
			$res = mysql_query($sqlu);
			if($r=mysql_fetch_array($res))
			{
				$this->activitylevel = $r['level_desc'];
			}
		return;
	}
	
	public function editActivitylevel($levelID)
	{
		require_once('class_error_messages.php');
		$error= new ErrorMessage;
		$this->activitylevel = $error->setEscape($_POST['activitylevel']);				
		$SQLUpdate = sprintf('UPDATE cme_activity_level SET level_desc ="%s" WHERE levelid=%s',$this->activitylevel,$levelID);
		
		 if(mysql_query($SQLUpdate))
			return true;
			else 
			 return false;
				 
	}
	public function addActivitylevels()
	{
		require_once('class_error_messages.php');
		$error= new ErrorMessage;
		$this->activitylevel = $error->setEscape($_POST['activitylevel']);	
		$SQLInsert = sprintf('INSERT INTO cme_activity_level (level_desc) VALUES("%s")',$this->activitylevel);
	
	 if(mysql_query($SQLInsert))
		return true;
		else 
		 return false;
				 
	}
	
	public function getActivityDeliveryMode($typeId)
	{
			$sqlu = sprintf('SELECT * FROM cme_activity_delivery_format WHERE typeId=%s',$typeId);
			$res = mysql_query($sqlu);
			if($r=mysql_fetch_array($res))
			{
				$this->deliverymode  = $r['typeDesc'];
			}
		return;
	}
	
	public function editActivityDeliveryMode($typeId)
	{
		require_once('class_error_messages.php');
		$error= new ErrorMessage;
		$this->deliverymode  = $error->setEscape($_POST['deliverymode']);		
		$SQLUpdate = sprintf('UPDATE cme_activity_delivery_format SET typeDesc ="%s" WHERE typeId=%s',$this->deliverymode,$typeId);
		
		 if(mysql_query($SQLUpdate))
			return true;
			else 
			 return false;
				 
	}
	public function addActivityDeliveryMode()
	{
		require_once('class_error_messages.php');
		$error= new ErrorMessage;
		
		$this->deliverymode  = $error->setEscape($_POST['deliverymode']);		
		$SQLInsert = sprintf('INSERT INTO cme_activity_delivery_format (typeDesc) VALUES("%s")',$this->deliverymode);
		
		 if(mysql_query($SQLInsert))
			return true;
			else 
			 return false;
				 
	}
}
?>