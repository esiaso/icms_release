<?php
/**
*
* Project:     Natinal Intergrated CPD
* File:        class_activities_events.php
* Purpose:    Activity Events 
*
*
*
*
*
*/

class ActivityEvents
{
	 	private $showMessage;
	 	private $activityID;
		private $eventID;
		private $id;
		private $activityno;
		private $event_title;
		private $event_description;
		private $event_date_from;
		private $event_moderator;
		private $event_time_from;
		private $event_time_to;
		private $event_location;
		private $event_town;
		private $event_cost;
		private $eventid;
		
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
	
		
	public function add_events()
	{
		require_once('class_system.php');
		$dateconvert = new System;
		require_once('class_error_messages.php');
		$error = new ErrorMessage;
		$error->setShowMessage(true);
		
		$this->event_title =  $this->setEscape(isset($_POST['event_title']) ? $_POST['event_title'] : '') ;
		$this->activityno =  isset($_POST['activityno']) ? $_POST['activityno'] : '' ;
		$this->event_description =  $this->setEscape(isset($_POST['event_description']) ? $_POST['event_description'] : '') ;
		$this->event_date_from =  $dateconvert->convert_datetime($_POST['event_date_from'],'/','ymd') ;
		$this->event_date_to =  $dateconvert->convert_datetime($_POST['event_date_to'],'/','ymd') ;
		$this->event_moderator =  $this->setEscape(isset($_POST['event_moderator']) ? $_POST['event_moderator'] : '' );
		$this->event_time_from =  isset($_POST['event_time_from']) ? $_POST['event_time_from'] : '' ;
		$this->event_time_to =  isset($_POST['event_time_to']) ? $_POST['event_time_to'] : '' ;
		$this->event_location =  $this->setEscape(isset($_POST['event_location']) ? $_POST['event_location'] : '') ;
		$this->event_town =  $this->setEscape(isset($_POST['event_town']) ? $_POST['event_town'] : '') ;
		$this->event_contact =  $this->setEscape(isset($_POST['event_contact']) ? $_POST['event_contact'] : '' );
		$this->event_cost =  $this->setEscape(isset($_POST['event_cost']) ? $_POST['event_cost'] : '') ;
		$this->event_credits =  isset($_POST['event_credits']) ? $_POST['event_credits'] : '' ;
		
		$SQLInsert = sprintf('INSERT INTO  cme_activity_events  (activity_id,event_title,event_description,event_date_from,event_date_to,event_time_from,event_time_to,event_town,event_location,event_moderator,event_contact,event_cost,event_credits) VALUES("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")',$this->activityno,$this->event_title,$this->event_description,$this->event_date_from,$this->event_date_to,$this->event_time_from
		,$this->event_time_to,$this->event_town,$this->event_location,$this->event_moderator,$this->event_contact,$this->event_cost,$this->event_credits);
		
		//$error->getMessage($SQLInsert);
		if($res= mysql_query($SQLInsert))
			$error->getMessage("Event successfully added",'success');		
	}
	
	public function update_events()
	{
		require_once('class_system.php');
		$dateconvert = new System;
		require_once('class_error_messages.php');
		$error = new ErrorMessage;
		$error->setShowMessage(true);
		
		
		$this->eventid =  $this->setEscape(isset($_POST['eventid']) ? $_POST['eventid'] : '') ;
		$this->event_title =  $this->setEscape(isset($_POST['event_title']) ? $_POST['event_title'] : '') ;
		$this->activityno =  isset($_POST['activityno']) ? $_POST['activityno'] : '' ;
		$this->event_description =  $this->setEscape(isset($_POST['event_description']) ? $_POST['event_description'] : '') ;
		$this->event_date_from =  $dateconvert->convert_datetime($_POST['event_date_from'],'/','ymd') ;
		$this->event_date_to =  $dateconvert->convert_datetime($_POST['event_date_to'],'/','ymd') ;
		$this->event_moderator =  $this->setEscape(isset($_POST['event_moderator']) ? $_POST['event_moderator'] : '' );
		$this->event_time_from =  isset($_POST['event_time_from']) ? $_POST['event_time_from'] : '' ;
		$this->event_time_to =  isset($_POST['event_time_to']) ? $_POST['event_time_to'] : '' ;
		$this->event_location =  $this->setEscape(isset($_POST['event_location']) ? $_POST['event_location'] : '') ;
		$this->event_town =  $this->setEscape(isset($_POST['event_town']) ? $_POST['event_town'] : '') ;
		$this->event_contact =  $this->setEscape(isset($_POST['event_contact']) ? $_POST['event_contact'] : '' );
		$this->event_cost =  $this->setEscape(isset($_POST['event_cost']) ? $_POST['event_cost'] : '') ;
		$this->event_credits =  isset($_POST['event_credits']) ? $_POST['event_credits'] : '' ;
		
		$SQLUpdate = sprintf('UPDATE  cme_activity_events  SET activity_id ="%s",event_title ="%s",event_description ="%s",event_date_from ="%s",event_date_to ="%s",event_time_from ="%s",event_time_to ="%s",event_town ="%s",event_location ="%s",event_moderator ="%s",event_contact ="%s",event_cost ="%s",event_credits ="%s"',$this->activityno,$this->event_title,$this->event_description,$this->event_date_from,$this->event_date_to,$this->event_time_from
		,$this->event_time_to,$this->event_town,$this->event_location,$this->event_moderator,$this->event_contact,$this->event_cost,$this->event_credits);
		
		//$error->getMessage($SQLInsert);
		if($res= mysql_query($SQLUpdate))
			$error->getMessage("Event successfully updated",'success');		
	}
}