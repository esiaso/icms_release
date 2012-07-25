<?php

/**
*
* Project:     Natinal Intergrated CPD
* File:        class.edit.user.photo.php
* Purpose:     Edit Users Profiles database
*
*
*
*/


class EditUserPhoto
{
	private $error;
	public $portrait;
		
	
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
	*	File extension
	*
	*   @param string $str
	*	@param $ext
	*	@param $i
	*/
	
	public function getExtension($str) 
	{
		 $i = strrpos($str,".");
		 if (!$i) { return ""; }
		 $l = strlen($str) - $i;
		 $ext = substr($str,$i+1,$l);
		return $ext;
	}
			
	public function setUserProfilePhoto($userid)
	{
		require_once('class_error_messages.php');
		require_once('class_image_resizer.php');		
		$error = new ErrorMessage();
		$error->setShowMessage(true);	
		
		$imageresize = new ImageResize();
		
		$portraits = $_FILES['portraits']['name']; 
		if ($portraits) 
		{
			//get the original name of the file from the clients machine
			$filename = stripslashes($_FILES['portraits']['name']);
			//$error->getMessage($filename);
			
			//get the extension of the file in a lower case format
			$signaturefiletype= $_FILES["portraits"]["type"];
			$extension = $this->getExtension($filename);
			$extension = strtolower($extension);
			$image_name=base64_encode($userid).''.time().'.'.$extension;
			//the new name will be containing the full path where will be stored (images folder)
			$newname=WEBPHOTOROOT.$image_name;
			$newname2=USERPHOTODIR."po_".$image_name;
			//we verify if the image has been uploaded, and print error instead
			$size=filesize($_FILES['portraits']['tmp_name']);
			
			$imageresize->load($_FILES['portraits']['tmp_name']);
      		$imageresize->resizeToWidth(150);
	 		//$imageresize->save($newname2);
			
			if ($size > MAX_SIZE*1024)
				{
					$error->getMessage('You have exceeded the size limit!');				
				}
				else
				{ //copy($_FILES['portraits']['tmp_name'], $newname)
					 if($imageresize->save($newname))
					 {	
					 	$SQLUpdate= sprintf('UPDATE cme_users SET portrait ="%s" WHERE user_id=%s',$image_name, $userid);		
						if(mysql_query($SQLUpdate))
						{
							$error->getMessage('Successfully uploaded your photo.','success');
							
						}
					 }
					 else
					 {
						 $error->getMessage('Something wrong happened try later.','error');
					 }
				}
			
		}
	}
	
	public function getUserPhoto($userid)
	{
			$sqlu = sprintf('SELECT * FROM cme_users WHERE user_id=%s',$userid);
			$res = mysql_query($sqlu);
			if($r=mysql_fetch_array($res))
			{
				$this->portrait = $r['portrait'];
			}
	}
    
}