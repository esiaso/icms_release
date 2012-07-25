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


class EditProviderDocuments
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
			

	public function uploadDocuments($providerid)
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
			$image_name=base64_encode($providerid).''.time().'.'.$extension;
			//the new name will be containing the full path where will be stored (images folder)
			$newname=WEBPHOTOROOT.'provider_documents/'.$image_name;
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
					 	$SQLUpdate= sprintf('INSERT INTO cme_provider_documents (doc_name ,file_name,provider_id, date_added, doc_type,doc_ref) VALUES ("%s","%s","%s",Now(),"%s","%s")',$filename,$image_name, $providerid,$doc_type,$doc_ref);	
						
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
			$SQLUpdate= sprintf('INSERT INTO cme_provider_documents (provider_id, date_added, doc_type,doc_ref) VALUES ("%s",Now(),"%s","%s")', $userid,$doc_type,$doc_ref);	
							
				//echo $SQLUpdate;	
				if(mysql_query($SQLUpdate))
				{
					$error->getMessage('Successfully uploaded the document.','success');
					
				}
		}
	}
	
    
}