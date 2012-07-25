<?php

/**
*
* Project:     Natinal Intergrated CPD
* File:        class_error_messages.php
* Purpose:     Edit Users Profiles database
*
*
*
*
*
*/

class ErrorMessage
{
	 public $showMessage;
	 	
	
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
		//die('got here');
        //if($this->showMessage)
//        {
			$c='';
            	if($message_type=='success') $class='notification_success';
			elseif($message_type=='info') $class='notification_info';
			elseif($message_type=='warning') $class='notification_warning';
			elseif($message_type=='error') $class='notification_error';
			$link = isset($GLOBALS['Group']) && $GLOBALS['Group']!='public' ? '../' : '';
			$bg='background:url(images/'.$class.'.gif) no-repeat ;';	
			//$bg='background:url(images/'.$class.'.png) no-repeat ;';		
			//die($bg);	
			$c.="<div id='alertbox' name='alertbox'  style='".$bg." position:absolute;left:250;top:100;z-index:2; width:95%; height:65px;' >\n";
			$c.= '<div style="text-align:center; padding:5px 10px 10px 5px; color: #D8000C;" onclick="javascript:document.getElementById(\'alertbox\').style.display=\'none\';">';
			$c.= $message_text;
			$c.='</div>';
			$c.="</div>\n";
			if($message_die) die($c);			
            else echo $message_html_tag_open . $c . $message_html_tag_close;//die($message_text);
       // }
    }
	
}
?>
<!--<td background="../images/notification_success.png"-->