<?php
/**
*
* Project:     Natinal Intergrated CPD
* File:        class_activities_accreditaion.php
* Purpose:    Activity Accreditation 
*
*
*
*
*
*/

class Activities
{
	 	private $showMessage;
	 	private $activityId;
		private $activityType;
		private $id;
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
	
	
	
	
	public function applyAccreditation()
	{
		require_once('class_error_messages.php');
		$errors = new ErrorMessage();
		
	}
	
	public function accreditActivity()
	{
		
	}
	
	public function denyAccreditation()
	{
	}
	
	
	
}
?>