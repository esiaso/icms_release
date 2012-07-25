<?php
class System
{
	private $datestr;
	private $separator;
	private $mysqltouk;
	private $timestamp;
	private $m;
	private $d;
	private $y;
	private $mk;
	private $string;
	private $salt;
	private $hash;
	
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
	
	public function convert_datetime($datestr,$separator,$mysqltouk) 
		{
				
				if(!empty($datestr))
				  {
						if($mysqltouk=='dmy')
						{
							list($y,$m,$d ) = explode($separator, $datestr);
						
							$mk = mktime(0, 0, 0, $m, $d, $y);
							
							$timestamp = strftime('%d-%m-%Y',$mk);
									
						}
						else if ($mysqltouk='ymd')
						{
							list($d, $m, $y) = explode($separator, $datestr);
						
							$mk = mktime(0, 0, 0, $m, $d, $y);
							
							$timestamp = strftime('%Y-%m-%d',$mk);
		
						}
					
				  }
				  else
				  {
					  $timestamp='';
				  }
	
		return $timestamp;
	}
	
	public function get_separator()	
	{
		$string = '|';
		$salt = sha1('Synergy2010@@19811234'); 
        $salt = substr($salt, 0, 4); 
        $hash = base64_encode( $string . $salt ); 
		
     	return  $hash;
	}
	
	public function GetColumns($result) 
	{
		//return mysql_fetch_field($result, $i);
		$i = 0;
		//echo mysql_num_fields($result);
		$fields_arr[]="";
			for ($i=0;$i<mysql_num_fields($result);$i++) {
			$meta= mysql_fetch_field($result, $i);
			array_push($fields_arr,$meta->name);
		}
		return $fields_arr;
  }
	
	public function KeyExists($secret_string) 
	{	

		$sql="SELECT user_id
					FROM cme_user_password_recovery
					WHERE secret_string = '".$this->setEscape($secret_string)."'
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
	public function audit_trail($message,$filename,$details)
		{
			$eventdate = date('dmY');
			$user_id = $_SESSION['user_id'];
			$sessionid = $_SESSION['sid'];
			$ipaddress = getenv('REMOTE_ADDR');
			
			$aQuery= sprintf('INSERT INTO cme_audit_trail (message,filename,eventdate,user_id,sessionid,otherdetails,ipaddress)
													  VALUES ("%s", "%s",NOW(),%s,"%s","%s","%s")',$message,$filename,$user_id,$sessionid,$details,$ipaddress);	
			if($res= mysql_query($aQuery))
			{
				
			}
			return;
		}
}
?>