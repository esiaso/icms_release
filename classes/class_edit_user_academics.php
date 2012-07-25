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


class EditUserAcademics
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
			
	public function setUserAcademics($userid)
	{
		require_once('class_system.php');
		require_once('class_error_messages.php');
		$error = new ErrorMessage();
		$error->setShowMessage(true);
		
		$system	= new System();
		
		$qualification = $_POST['qualification'];
		$abbreviation = $_POST['abbreviation'];
		$enrolldate = $system->convert_datetime($_POST['enrolldate'],'-','ymd');
		$graddate = $system->convert_datetime($_POST['graddate'],'-','ymd');
		$medschools = $_POST['medschools'];
		$othermedschool = isset($_POST['othermedschool']) ? $_POST['othermedschool'] : '';
		
		/*$portraits = $_FILES['certupload']['name']; 
		if ($portraits) 
		{
			//get the original name of the file from the clients machine
			$filename = stripslashes($_FILES['certupload']['name']);
			//$error->getMessage($filename);
			
			//get the extension of the file in a lower case format
			$signaturefiletype= $_FILES["certupload"]["type"];
			$extension = $this->getExtension($filename);
			$extension = strtolower($extension);
			$image_name=base64_encode($userid).''.time().'.'.$extension;
			//the new name will be containing the full path where will be stored (images folder)
			$newname=WEBPHOTOROOT.'user_documents/'.$image_name;
			//$newname2=USERPHOTODIR."po_".$image_name;
			//we verify if the image has been uploaded, and print error instead
			$size=filesize($_FILES['certupload']['tmp_name']);
			
						
			if ($size > MAX_SIZE*1024*4)
				{
					$error->getMessage('You have exceeded the size limit of 4MB!','error');				
				}
				else
				{ //copy($_FILES['portraits']['tmp_name'], $newname)
					 if(copy($_FILES['certupload']['tmp_name'], $newname))
					 {		
					 }
					 else
					 {
						 $error->getMessage('Something wrong happened try later.','success');
					 }
				}			
		}
		else
		{*/
			//$image_name='';
			$SQLUpdate= sprintf('INSERT INTO cme_user_accademics (degree,abbreviation,user_id,medschool,medschoolothers,dateenrolled,yeargraduated) VALUES("%s","%s","%s","%s","%s","%s","%s")',$qualification,$abbreviation, $userid,$medschools,$othermedschool,$enrolldate,$graddate);
	 
	  //$error->getMessage($SQLUpdate);
				
		if(mysql_query($SQLUpdate))
			$error->getMessage('Successfully uploaded your Certificate.','success');
				else
					$error->getMessage(mysql_error(),'error');
		//}
		
		
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
	
	public function uploadDocuments($userid)
	{
		require_once('class_error_messages.php');
		$error = new ErrorMessage();
		$error->setShowMessage(true);	
		
		//$doc_name = $_POST['docname'];
		$doc_type = $_POST['doc_type'];
		$doc_ref = isset($_POST['doc_ref']) ? $_POST['doc_ref'] : '';
		
				
		$portraits = $_FILES['certupload']['name']; 
		if ($portraits) 
		{
			//get the original name of the file from the clients machine
			$filename = stripslashes($_FILES['certupload']['name']);
			//$error->getMessage($filename);
			
			//get the extension of the file in a lower case format
			$signaturefiletype= $_FILES["certupload"]["type"];
			$extension = $this->getExtension($filename);
			$extension = strtolower($extension);
			$image_name=base64_encode($userid).''.time().'.'.$extension;
			//the new name will be containing the full path where will be stored (images folder)
			$newname=WEBPHOTOROOT.'user_documents/'.$image_name;
			//$newname2=USERPHOTODIR."po_".$image_name;
			//we verify if the image has been uploaded, and print error instead
			$size=filesize($_FILES['certupload']['tmp_name']);
			
						
			if ($size > MAX_SIZE*1024*4)
				{
					$error->getMessage('You have exceeded the size limit of 4MB!','error');				
				}
				else
				{ //copy($_FILES['portraits']['tmp_name'], $newname)
					 if(copy($_FILES['certupload']['tmp_name'], $newname))
					 {	
					 	$SQLUpdate= sprintf('INSERT INTO cme_user_documents (doc_name ,file_name,user_id, date_added, doc_type,doc_ref) VALUES ("%s","%s","%s",Now(),"%s","%s")',$filename,$image_name, $userid,$doc_type,$doc_ref);	
						
						//echo $SQLUpdate;	
						if(mysql_query($SQLUpdate))
						{
							$error->getMessage('Successfully uploaded the document.','success');
							
						}
					 }
					 else
					 {
						 $error->getMessage('Something wrong happened try later.','error');
					 }
				}
			
		}
		else
		{
			$SQLUpdate= sprintf('INSERT INTO cme_user_documents (user_id, date_added, doc_type,doc_ref) VALUES ("%s",Now(),"%s","%s")', $userid,$doc_type,$doc_ref);	
							
				//echo $SQLUpdate;	
				if(mysql_query($SQLUpdate))
				{
					$error->getMessage('Successfully uploaded the document.','success');
					
				}
		}
	}
	
    
}