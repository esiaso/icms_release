<?php
/**
*
* Project:     Natinal Intergrated CPD
* File:        class_activities.php
* Purpose:     Create, Edit, Delete
*
*
*
*
*
*/

class Activities
{
	 	private $showMessage;
	 	public  $activityId;
		private $activityType;
		private $id;
		private $title;
		private $shorttitle;
		private $description;
		private $releasedate;
		private $activitydirector;
		private $activitycontact;
		private $duration;
		private $userId;
		private $providerId;
		private $dateconvert;
		private $audience;
		private $txtLiveMeet;
		private $token;
		private $activitymoderator;
		private $opttype;
		private $artnum;
		private $qrank;
		private $qText;
		private $display;
		private $artnumber;	
		private $aText;
		private $Qid;
		private $corrAns ;
		private $rank;
		private $aExplaination;
		private $choiceLetter;
		
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
	
	public function createActivities()
	{
		require_once('class_system.php');
		$dateconvert = new System;
		require_once('class_error_messages.php');
		$errors = new ErrorMessage();
		$errors->setShowMessage(true);
		
		
		$this->activitytype =  isset($_POST['activitytype']) ? $_POST['activitytype'] : null ;
		$this->title =  isset($_POST['long_title']) ? $this->setEscape($_POST['long_title']) : '' ;
		$this->shorttitle =  isset($_POST['short_title']) ? $this->setEscape($_POST['short_title']) : '' ;
		$this->venue =  isset($_POST['venue']) ? $this->setEscape($_POST['venue']) : null ;
		$this->city =  isset($_POST['city']) ? $_POST['city'] : '' ;		
		$this->releasedate =  isset($_POST['releasedate']) ? $dateconvert->convert_datetime($_POST['releasedate'],'-','ymd') : null ;
		$this->expirydate =  isset($_POST['enddate']) ? $dateconvert->convert_datetime($_POST['enddate'],'-','ymd') : null;
		$this->activitymoderator =  isset($_POST['moderator']) ? $_POST['moderator'] : '' ;
		$this->activitycontact =  isset($_POST['activitycontact']) ? $this->setEscape($_POST['activitycontact']) : null ;
		$this->primary_audience =  isset($_POST['primary_audience']) ? $_POST['primary_audience'] : null ;
		$this->userId =  $_SESSION['user_id'];
		$this->providerId =  $_SESSION['provider_id'];
		//$this->format = isset($_POST['format']) ? $_POST['format'] : '' ;
		
		if(isset($_POST['activity_id']) && $_POST['activity_id']!='')
		{
			$this->activityId= $_POST['activity_id'];
			$SQL=sprintf('SELECT * FROM cme_activities WHERE id=%s',$this->activityId);	
			$res = mysql_query($SQL);
			if(mysql_num_rows($res)>0)
			{
			
			$SQLInsert = sprintf('UPDATE  cme_activities  SET activity_name ="%s",activity_short_title="%s",activity_description="%s",activity_type="%s",release_date ="%s",expiry_date ="%s",activity_moderator ="%s",primary_audience="%s",user_id="%s",provider_id="%s",objectives="%s",venue="%s" WHERE id=%s',$this->title,$this->shorttitle,$this->description,$this->activitytype,$this->releasedate,$this->expirydate
			,$this->activitymoderator,$this->primary_audience,$this->userId,$this->providerId,$this->objective,$this->venue,$this->activityId);
			}
			else
			{
				$SQLInsert = sprintf('INSERT INTO  cme_activities  (activity_name,activity_short_title,activity_description,activity_type,creationdate,release_date,expiry_date,activity_moderator,primary_audience,user_id,provider_id,objectives,venue) VALUES("%s","%s","%s","%s",Now(),"%s","%s","%s","%s","%s","%s","%s","%s")',$this->title,$this->shorttitle,$this->description,$this->activitytype,$this->releasedate,$this->expirydate
			,$this->activitymoderator,$this->primary_audience,$this->userId,$this->providerId,$this->objective,$this->venue);	
			}
		}
		else
		{
			$SQLInsert = sprintf('INSERT INTO  cme_activities  (activity_name,activity_short_title,activity_description,activity_type,creationdate,release_date,expiry_date,activity_moderator,primary_audience,user_id,provider_id,objectives,venue) VALUES("%s","%s","%s","%s",Now(),"%s","%s","%s","%s","%s","%s","%s","%s")',$this->title,$this->shorttitle,$this->description,$this->activitytype,$this->releasedate,$this->expirydate
			,$this->activitymoderator,$this->primary_audience,$this->userId,$this->providerId,$this->objective,$this->venue);	
		}
		
		//echo $SQLInsert;
		if($res= mysql_query($SQLInsert))
		{
			//$errors->getMessage("Successfully added activity",'success');
			
			$SQL_Query =sprintf('SELECT id FROM cme_activities
							WHERE provider_id=%s  AND activity_name="%s" AND activity_short_title="%s" AND user_id="%s" ',$this->providerId,$this->title,$this->shorttitle,$this->userId);
			$res =mysql_query($SQL_Query);
			if($rs = mysql_fetch_array($res))
			{
				$this->activityId =$rs['id'];
			}
				
				return $this->activityId;
		}
			else
			 die(mysql_error());
	}
	
	public function editOfflineActivities()
	{
		$this->description =  isset($_POST['descriptiontextarea']) ? $this->setEscape($_POST['descriptiontextarea']) : null ;
		$this->objective =  isset($_POST['objective']) ? $this->setEscape($_POST['objective']) : null ;
		$this->venue =  isset($_POST['venue']) ? $this->setEscape($_POST['venue']) : null ;
		$this->city =  isset($_POST['city']) ? $_POST['city'] : '' ;
		$this->activityId= $_POST['activity_id'];
		$this->activitymoderator =  isset($_POST['moderator']) ? $_POST['moderator'] : '' ;
			
		$SQLInsert = sprintf('UPDATE  cme_activities  SET activity_description="%s",activity_moderator ="%s",objectives="%s",venue="%s",city="%s" WHERE id=%s',$this->description,$this->activitymoderator,$this->objective,$this->venue,$this->city,$this->activityId);
		if(mysql_query($SQLInsert))
			return true;
	}
	
	public function addActivityAuthor()
	{
		$this->activity_id =  isset($_POST['activity_id']) ? $_POST['activity_id'] : '' ;
		$this->authorlevel =  isset($_POST['authorlevel']) ? $_POST['authorlevel'] : '1' ;
		$this->title =  isset($_POST['title']) ? $_POST['title'] : '' ;
		$this->firstname =  isset($_POST['firstname']) ? $_POST['firstname'] : '' ;
		$this->lastname =  isset($_POST['lastname']) ? $_POST['lastname'] : '' ;
		$this->primaryAffiliation =  isset($_POST['primaryAffiliation']) ? $_POST['primaryAffiliation'] : '' ;
		$this->otheraffiliation =  isset($_POST['otheraffiliation']) ? $_POST['otheraffiliation'] : '' ;
		
		$SQLInsert = sprintf('INSERT INTO  cme_activity_authors  (author_title,activity_id,author_level,first_name,last_name,primary_affiliation,other_affiliation) VALUES("%s","%s","%s","%s","%s","%s","%s")',$this->title,$this->activity_id,$this->authorlevel,$this->firstname,$this->lastname,$this->primaryAffiliation,$this->otheraffiliation);
		
		
		if(mysql_query($SQLInsert))
			return true;
							
	}
	
	public function addActivityAbstract()
	{
		$this->abstracts =  isset($_POST['abstract']) ? $_POST['abstract'] : '' ;
		$this->activityId =  isset($_POST['activity_id']) ? $_POST['activity_id'] : '' ;
		
		$SQL=sprintf('SELECT * FROM cme_online_activity_abstract WHERE activity_id=%s',$this->activityId);	
		$res = mysql_query($SQL);
		if(mysql_num_rows($res)>0)
		{
			$SQLInsert = sprintf('UPDATE cme_online_activity_abstract SET abstracttext="%s" WHERE activity_id=%s',mysql_escape_string($this->abstracts),$this->activityId);	
		}
		else
		{
			$SQLInsert = sprintf('INSERT INTO cme_online_activity_abstract (activity_id,abstracttext,dateadded) VALUES("%s","%s",Now())',$this->activityId,mysql_escape_string($this->abstracts));	
		}
		
			
		if(mysql_query($SQLInsert))
			return true;
							
	}
	
	public function addActivityKeyMessage()
	{
		$this->messagetext =  isset($_POST['messagetext']) ? $_POST['messagetext'] : '' ;
		$this->activityId =  isset($_POST['activity_id']) ? $_POST['activity_id'] : '' ;
		
		$SQLInsert = sprintf('INSERT INTO cme_online_activity_key_messages (activity_id,messagetext,dateadded) VALUES("%s","%s",Now())',$this->activityId,mysql_escape_string($this->messagetext));	
			
		if(mysql_query($SQLInsert))
			return true;
	}
	
	public function editActivityKeyMessage($messagID)
	{
		$this->messagetext =  isset($_POST['messagetext']) ? $_POST['messagetext'] : '' ;
		
		$SQLInsert = sprintf('UPDATE cme_online_activity_key_messages SET messagetext="%s" WHERE messageid = %s',mysql_escape_string($this->messagetext),$messagID);	
		if(mysql_query($SQLInsert))
			return true;
	}
	
	public function addActivitySections()
	{
		$this->section_body = isset($_POST['section_body']) ? $_POST['section_body'] : '' ;
		$this->sectionName =  isset($_POST['sectionName']) ? $_POST['sectionName'] : '' ;
		$this->activityId =  isset($_POST['activity_id']) ? $_POST['activity_id'] : '' ;
		
		$SQLInsert = sprintf('INSERT INTO cme_online_activity_sections (activity_id,sectionheading,sectiontext) VALUES("%s","%s","%s")',$this->activityId,mysql_escape_string($this->sectionName),mysql_escape_string($this->section_body));	
			
		if(mysql_query($SQLInsert))
			return true;			
	}
	public function editActivitySections($sectionID)
	{
		$this->section_body = isset($_POST['section_body']) ? $_POST['section_body'] : '' ;
		$this->sectionName =  isset($_POST['sectionName']) ? $_POST['sectionName'] : '' ;
		
		$SQLInsert = sprintf('UPDATE cme_online_activity_sections SET sectionheading = "%s",sectiontext="%s" WHERE secId=%s',mysql_escape_string($this->sectionName),mysql_escape_string($this->section_body),$sectionID);	
			
		if(mysql_query($SQLInsert))
			return true;			
	}
	
	public function addActivityReferences()
	{
		$this->refTitle = isset($_POST['refTitle']) ? $_POST['refTitle'] : '' ;
		$this->authors =  isset($_POST['authors']) ? $_POST['authors'] : '' ;
		$this->refsource =  isset($_POST['refsource']) ? $_POST['refsource'] : '' ;
		$this->activityId =  isset($_POST['activity_id']) ? $_POST['activity_id'] : '' ;
		
		$SQLInsert = sprintf('INSERT INTO cme_online_activity_references (activity_id,authors,refTitle,refsource) VALUES("%s","%s","%s","%s")',$this->activityId,mysql_escape_string($this->authors),mysql_escape_string($this->refTitle),mysql_escape_string($this->refsource));	
			
		if(mysql_query($SQLInsert))
			return true;			
	}
	public function editActivityReferences($refID)
	{
		$this->refTitle = isset($_POST['refTitle']) ? $_POST['refTitle'] : '' ;
		$this->authors =  isset($_POST['authors']) ? $_POST['authors'] : '' ;
		$this->refsource =  isset($_POST['refsource']) ? $_POST['refsource'] : '' ;
		
		$SQLInsert = sprintf('UPDATE cme_online_activity_references SET authors ="%s" ,refTitle ="%s",refsource="%s" WHERE refId=%s',mysql_escape_string($this->authors),mysql_escape_string($this->refTitle),mysql_escape_string($this->refsource),$refID);	
			
		if(mysql_query($SQLInsert))
			return true;			
	}
	
	
	public function addActivityQuestion()
	{
		
		$opttype = isset($_POST['opttype']) ? $_POST['opttype'] : '' ;
		$activity_id = isset($_POST['activity_id']) ? $_POST['activity_id'] : '' ;
		//$qrank = isset($_POST['rank']) ? $_POST['rank'] : '' ;$this->setEscape($qrank),
		$qText = isset($_POST['pgDessc']) ? $_POST['pgDessc'] : '' ;
		$display = isset($_POST['publish']) ? $_POST['publish'] : '' ;
	
	$QueryIns = sprintf('INSERT INTO cme_online_activity_questions (activity_id,qtext,opttype,display,userid) 
														   VALUES(%s,"%s","%s","%s","%s")'
						 ,$activity_id,$this->setEscape($qText),$this->setEscape($opttype),$display,$_SESSION['user_id']);			
		
		//echo $QueryIns;
		if(mysql_query($QueryIns))
			return true;			
	}
	
	public function editActivityQuestion($qID)
	{
		$activity_id = isset($_POST['activity_id']) ? $_POST['activity_id'] : '' ;
		//$Qid = $_POST['Qid'];
		$opttype = isset($_POST['opttype']) ? $_POST['opttype'] : '' ;
		$artnum = isset($_POST['artnum']) ? $_POST['artnum'] : '' ;
		//$qrank = isset($_POST['rank']) ? $_POST['rank'] : '' ;
		$qText = isset($_POST['pgDessc']) ? $_POST['pgDessc'] : '' ;
		$display = isset($_POST['publish']) ? $_POST['publish'] : '' ;
	
	$QueryIns = sprintf('UPDATE cme_online_activity_questions SET activity_id =%s ,qtext="%s",opttype="%s",display="%s" WHERE qid =%s',
						 $activity_id,$this->setEscape($qText),$this->setEscape($opttype),$display,$qID);			
		if(mysql_query($QueryIns))
			return true;			
	}
	
	public function addActivityQuestionAnswer()
	{
		
		$aText = isset($_POST['aText']) ? $_POST['aText'] : '' ;
		$Qid = $_POST['Qid'];
		$corrAns = isset($_POST['corrAns']) ? $_POST['corrAns'] : 'No' ;
	//	$rank = $_POST['rank'];$this->setEscape($rank),
		$aExplaination = isset($_POST['aExplaination']) ? $_POST['aExplaination'] : '' ;
		
		$QueryIns = sprintf('INSERT INTO cme_online_activity_questions_ans (qid,ans_text,ans_correct,explanation) 
														   VALUES(%s,"%s","%s","%s")'
							 ,$Qid,$this->setEscape($aText),$this->setEscape($corrAns),$this->setEscape($aExplaination));		
		 if(mysql_query($QueryIns))
			return true;	
	}
	
	public function editActivityQuestionAnswer($ansID)
	{
		
		$aText = isset($_POST['aText']) ? $_POST['aText'] : '' ;
		$corrAns = isset($_POST['corrAns']) ? $_POST['corrAns'] : '' ;
	//	$rank = $_POST['rank'];$this->setEscape($rank),
		$aExplaination = isset($_POST['aExplaination']) ? $_POST['aExplaination'] : '' ;
		
		$QueryIns = sprintf('UPDATE cme_online_activity_questions_ans SET ans_text ="%s",ans_correct="%s",explanation="%s" WHERE ans_id= %s'
							 ,$this->setEscape($aText),$this->setEscape($corrAns),$this->setEscape($aExplaination),$ansID);	
			
		 if(mysql_query($QueryIns))
			return true;	
	}
	
	public function addActivityObjectives()
	{
		$this->objectives =  isset($_POST['objectives']) ? $_POST['objectives'] : '' ;
		$this->activityId =  isset($_POST['activity_id']) ? $_POST['activity_id'] : '' ;
		
		$SQLUpdate = sprintf('UPDATE cme_activities SET objectives="%s" WHERE  id="%s"',mysql_escape_string($this->objectives),$this->activityId);			
		if(mysql_query($SQLUpdate))
			return true;
							
	}
	
	public function editActivities()
	{
	}
	
	public function publishActivity($activityID)
	{
		$SQLUpdate= sprintf("UPDATE cme_activities SET published='1' WHERE id=%s",$activityID);
		if(mysql_query($SQLUpdate))
		{
			return true;
		}
	}
	public function unPublishActivity($activityID)
	{
		$SQLUpdate= sprintf("UPDATE cme_activities SET published='0' WHERE id=%s",$activityID);
		if(mysql_query($SQLUpdate))
		{
			return true;
		}
	}
	public function AddActivityQRCode($activityID,$imageName)
	{
		$SQLUpdate= sprintf('UPDATE cme_activities SET qrcodelnk="%s" WHERE id=%s',$imageName,$activityID);
		if(mysql_query($SQLUpdate))
		{
			return true;
		}
	}
	public function AddActivityClaimQRCode($activityID,$imageName)
	{
		$SQLUpdate= sprintf('UPDATE cme_activities SET qrcodeClaim="%s" WHERE id=%s',$imageName,$activityID);
		if(mysql_query($SQLUpdate))
		{
			return true;
		}
	}
	
	public function get_provider_newest_activities($provider)
	{
		require_once('class_system.php');
		$separator = new System;
		$s = $separator->get_separator();
		require_once('class_system.php');
		$dateconvert = new System;
		
		echo '<table width="100%" id="hor-zebra">
				<thead>
					<tr style="background:#C8D4E0; color:#000000;">
						<th width="55%">Activity Name</th>
						<th width="25%">Mode of Delivery</th>
						<th align="left">Expires</th>
					</tr>
				</thead>				
				<tbody>';
				$SQL_Query =sprintf("SELECT *
									FROM
										cme_activities
										INNER JOIN cme_activity_type 
											ON (cme_activities.activity_type = cme_activity_type.type_id)
										INNER JOIN cme_activity_delivery_format 
											ON (cme_activity_delivery_format.typeId = cme_activity_type.delivery_mode) 
							WHERE provider_id=%s  AND expiry_date > DATE(Now()) ORDER BY release_date DESC limit 0, 5",$provider);
					
						if($res =mysql_query($SQL_Query))
						{
							$c=0;
							while($rs = mysql_fetch_array($res))
							{
								$c++;
								$mod = $c %2  ? 'even' : 'odd';
								$editLink = $rs['typeId']==1 ? 'editactivity' : 'newonlineactivity';
								echo '<tr class="row '.$mod.'">
											<td><a href="index.php?k='.base64_encode('activities'.$s.$editLink.$s.''.$s.'item').'&d='.base64_encode($rs['id']).'">'.$rs['activity_name'].'</a></td>
											<td>'.$rs['typeDesc'].'</td>
											<td align="left">'.$dateconvert->convert_datetime($rs['expiry_date'],'-','dmy').'</td>
										</tr>';
							}
						}
						else
						{
							echo '<tr>
									<td colspan="2">No records!</td>
								</tr>';
						}
			echo '	</tbody>
			</table>';
		
		return;
	}
	
	public function get_provider_expiring_activities($provider)
	{
		require_once('class_system.php');
		$dateconvert = new System;
		$s = $dateconvert->get_separator();
		
		
		echo '<table width="100%" id="hor-zebra">
				<thead>
					<tr style="background:#C8D4E0; color:#000000;">
						<th width="50%">Activity Name</th>
						<th width="25%">Mode of Delivery</th>
						<th width="25%" align="left">Expires</th>
					</tr>
				</thead>				
				<tbody>';
				$SQL_Query =sprintf("SELECT * FROM
										cme_activities
						INNER JOIN cme_activity_type ON (cme_activities.activity_type = cme_activity_type.type_id)
						INNER JOIN cme_activity_delivery_format ON (cme_activity_delivery_format.typeId = cme_activity_type.delivery_mode)  
							WHERE provider_id=%s AND expiry_date > DATE(Now()) ORDER BY expiry_date ASC limit 0, 5",$provider);
												
					
						if($res =mysql_query($SQL_Query))
						{
							$c=0;
							while($rs = mysql_fetch_array($res))
							{
								$c++;
								$mod = $c %2  ? 'odd' : 'even';
								$editLink = $rs['typeId']==1 ? 'editactivity' : 'newonlineactivity';
								echo '<tr class="row '.$mod.'">
											<td><a href="index.php?k='.base64_encode('activities'.$s.$editLink.$s.''.$s.'item').'&d='.base64_encode($rs['id']).'">'.$rs['activity_name'].'</a></td>
											<td>'.$rs['typeDesc'].'</td>
											<td align="left">'.$dateconvert->convert_datetime($rs['expiry_date'],'-','dmy').'</td>
										</tr>';
							}
						}
						else
						{
							echo '<tr>
									<td colspan="3">No records!</td>
								</tr>';
						}
			echo '	</tbody>
			</table>';
		
		return;
	}
	
	public function get_online_activities_all()
	{
		require_once('class_system.php');
		$separator = new System;
		$s = $separator->get_separator();
		
		$SQLQuery =sprintf('SELECT *
							FROM
								cme_activities
								
								INNER JOIN cme_activity_type  ON (cme_activities.activity_type = cme_activity_type.type_id)
								INNER JOIN cme_specialities ON (cme_activities.primary_audience = cme_specialities.sp_id)
								INNER JOIN cme_activity_delivery_format ON (cme_activity_delivery_format.typeId = cme_activity_type.delivery_mode)
								WHERE cme_activity_delivery_format.typeId=2 AND cme_activities.published="1" ');
		
		$c=0;
		$res=mysql_query($SQLQuery);
		while($r=mysql_fetch_array($res))
		{
			$c++;
			echo '
			<li>
				<h2 class="title4"><a href="index.php?k='.base64_encode('activities'.$s.'onlinelst'.$s.''.$s.'item').'&d='.base64_encode($r['id']).'&subview=online">'.$r['activity_name'].'</a></h2>				  
				  <p class="text3"><b>Credits:</b>'.$r['cpd_points'].'</p>
				  <p class="text3"><b>Expires:</b>'.$r['expiry_date'].'</p>
				</li>
			';
		}//<p class="text3"><b>Format:</b>'.$r['typeDesc'].'</p>
	}
	
	public function get_external_activities_all()
	{
		require_once('class_system.php');
		$separator = new System;
		$s = $separator->get_separator();
		
		$SQLQuery =sprintf('SELECT *
							FROM
								cme_activities
								INNER JOIN cme_activity_type  ON (cme_activities.activity_type = cme_activity_type.type_id)
								INNER JOIN cme_specialities ON (cme_activities.primary_audience = cme_specialities.sp_id)
								INNER JOIN cme_activity_delivery_format ON (cme_activity_delivery_format.typeId = cme_activity_type.delivery_mode)
								WHERE cme_activity_delivery_format.typeId=1 AND cme_activities.published="1" ORDER BY release_date DESC');
		
		$c=0;
		$res=mysql_query($SQLQuery);
		while($r=mysql_fetch_array($res))
		{
			$c++;
			//$&subview='.$_GET['subview'].'
			echo '
			<li>
				<h2 class="title4"><a href="index.php?k='.base64_encode('activities'.$s.'extlst'.$s.''.$s.'item').'&d='.base64_encode($r['id']).'&subview=offline">'.$r['activity_name'].'</a></h2>
				   <p class="text3"><b>Credits:</b>'.$r['cpd_points'].'</p>
				  <p class="text3"><b>Expires:</b>'.$r['expiry_date'].'</p>
				</li>
			';
		
		}
	}
	
	public function get_latest_external_activities()
	{
		require_once('class_system.php');
		$separator = new System;
		$s = $separator->get_separator();
		
		$SQLQuery =sprintf('SELECT cme_activities.release_date,cme_activities.id,cme_activities.activity_name
							FROM
								cme_activities
								INNER JOIN cme_activity_type  ON (cme_activities.activity_type = cme_activity_type.type_id)
								INNER JOIN cme_specialities ON (cme_activities.primary_audience = cme_specialities.sp_id)
								INNER JOIN cme_activity_delivery_format ON (cme_activity_delivery_format.typeId = cme_activity_type.delivery_mode)
								WHERE cme_activity_delivery_format.typeId=1 AND cme_activities.published="1" 
								ORDER BY cme_activities.release_date DESC
								');
		
		$c=0;
		$res=mysql_query($SQLQuery);
		while($r=mysql_fetch_array($res))
		{
			$c++;
			
			echo '
			<li>
				<p class="text1">'.$separator->convert_datetime($r['release_date'],'-','dmy').'</p>
				<p><a href="index.php?k='.base64_encode('activities'.$s.'extlst'.$s.''.$s.'item').'&d='.base64_encode($r['id']).'">'.$r['activity_name'].'</a></p>
			</li>
			';
		
		}
	}
	
	public function get_my_cpd_alerts($userid)
	{
		require_once('class_system.php');
		$separator = new System;
		$s = $separator->get_separator();
		
		$SQLQuery =sprintf('SELECT cme_activities.release_date,cme_activities.id,cme_activities.activity_name,cme_activity_delivery_format.typeDesc
							FROM
								cme_activities
								INNER JOIN cme_activity_accreditation ON (cme_activities.id = cme_activity_accreditation.activity)
								INNER JOIN cme_activity_type  ON (cme_activities.activity_type = cme_activity_type.type_id)
								INNER JOIN cme_specialities ON (cme_activities.primary_audience = cme_specialities.sp_id)
								INNER JOIN cme_activity_delivery_format ON (cme_activity_delivery_format.typeId = cme_activity_type.delivery_mode)
								WHERE cme_activity_delivery_format.typeId=1
								ORDER BY cme_activities.release_date 
								');
		
		$c=1;
		$res=mysql_query($SQLQuery);
		while($r=mysql_fetch_array($res))
		{
			
			$class = $c%2 ? 'class="even"' : '';
			echo '
					<tr '.$class.'>
							<td>'.$separator->convert_datetime($r['release_date'],'-','dmy').'</td>							
							<td><a href="index.php?k='.base64_encode('activities'.$s.'extlst'.$s.''.$s.'item').'&d='.base64_encode($r['id']).'">'.$r['activity_name'].'</a></td>
							<td>'.$r['typeDesc'].'</td>
						</tr>';
			
		$c++;
		}
	}
	
	public function get_latest_online_activities()
	{
		require_once('class_system.php');
		$separator = new System;
		$s = $separator->get_separator();
		
		$SQLQuery =sprintf('SELECT cme_activities.release_date,cme_activities.id,cme_activities.activity_name
							FROM
								cme_activities
								INNER JOIN cme_activity_type  ON (cme_activities.activity_type = cme_activity_type.type_id)
								INNER JOIN cme_specialities ON (cme_activities.primary_audience = cme_specialities.sp_id)
								INNER JOIN cme_activity_delivery_format ON (cme_activity_delivery_format.typeId = cme_activity_type.delivery_mode)
						WHERE cme_activity_delivery_format.typeId=2 AND cme_activities.published="1"
						ORDER BY cme_activities.release_date DESC
								');
		
		$c=0;
		$res=mysql_query($SQLQuery);
		while($r=mysql_fetch_array($res))
		{
			$c++;
			
			echo '
			<li>
				<p class="text1">'.$separator->convert_datetime($r['release_date'],'-','dmy').'</p>
				<p><a href="index.php?k='.base64_encode('activities'.$s.'onlinelst'.$s.''.$s.'item').'&d='.base64_encode($r['id']).'">'.$r['activity_name'].'</a></p>
			</li>
			';
		
		}
	}
	
	public function get_external_activities_by_location($audience,$txtLiveMeet)
	{
		require_once('class_system.php');
		$separator = new System;
		$s = $separator->get_separator();
		
		$this->audience  = $audience;
		$this->txtLiveMeet = $txtLiveMeet;
		if($this->audience==0)
		{
		$SQLQuery =sprintf('SELECT *
							FROM
								cme_activities
								INNER JOIN cme_activity_accreditation ON (cme_activities.id = cme_activity_accreditation.activity)
								INNER JOIN cme_activity_type  ON (cme_activities.activity_type = cme_activity_type.type_id)
								INNER JOIN synergycpd.cme_activity_delivery_format ON (cme_activity_delivery_format.typeId = cme_activities.activity_format)
								INNER JOIN cme_specialities ON (cme_activities.primary_audience = cme_specialities.sp_id)
								INNER JOIN cme_activity_events ON (cme_activities.id = cme_activity_events.activity_id)
							WHERE cme_activities.published="1" AND cme_activity_events.event_town ="'.$this->txtLiveMeet.'"');
		}
		else
		{
			$SQLQuery =sprintf('SELECT *
							FROM
								cme_activities
								INNER JOIN cme_activity_accreditation ON (cme_activities.id = cme_activity_accreditation.activity)
								INNER JOIN cme_activity_type  ON (cme_activities.activity_type = cme_activity_type.type_id)
								INNER JOIN cme_specialities ON (cme_activities.primary_audience = cme_specialities.sp_id)
								INNER JOIN synergycpd.cme_activity_delivery_format ON (cme_activity_delivery_format.typeId = cme_activities.activity_format)
								INNER JOIN cme_activity_events ON (cme_activities.id = cme_activity_events.activity_id)
							WHERE (cme_activities.published="1" AND cme_specialities.sp_id ='.$this->audience.'
								AND cme_activity_events.event_town ="'.$this->txtLiveMeet.'")');
		}
		$c=0;
		$res=mysql_query($SQLQuery);
		while($r=mysql_fetch_array($res))
		{
			$c++;
			$class = $c%2 ? 'class="even"' : '';
			echo '
				<tr '.$class.'>
				  <td>
					<a href="index.php?k='.base64_encode('activities'.$s.'extlst'.$s.''.$s.'item').'&d='.base64_encode($r['id']).'">'.$r['activity_name'].'</a>
				  </td>
				  <td>'.$r['typeDesc'].'</td>
				  <td>'.$r['credits'].'</td>
				  <td>'.$r['expiry_date'].'</td>
				</tr>
			';
		}
	}
	
	public function createActivityTokens($tokenNumber,$activity)
	{
		
		require_once('class_system.php');
		require_once('class_functions.php');
		$random = new generals;
		$system = new System;
		
		$validfrom = $system->convert_datetime($_POST['validfrom'],'-','ymd');
		$expirydate = $system->convert_datetime($_POST['expirydate'],'-','ymd');
		$batch = $this->createTokensBatch($activity);
		$pp = 0;
		while ( $pp < $tokenNumber)
		{
			$token= $random->genRandomString();
			
			$SQLINSERT = sprintf('INSERT INTO cme_activity_token (token,activity_id,creationdate,validfrom,expirydate,status,tokenbatch) VALUES("%s","%s",Now(),"%s","%s",2,"%s")',$token,$activity,$validfrom,$expirydate,$batch);
			mysql_query($SQLINSERT);
			/*	return true;
				else
				 return false;*/ 
		$pp++;
		}
		return true;
	}
	
	public function getTotalActivityTokens($activity)
	{
		
		$SQL=sprintf('SELECT * FROM cme_activity_token WHERE activity_id =%s',$activity);
		$res = mysql_query($SQL);
		return mysql_num_rows($res);	
		
	}
	public function getTotalClaimedActivityTokens($activity)
	{
		
		$SQL=sprintf('SELECT *
					FROM
						cme_activity_token
						INNER JOIN cme_activity_token_status ON (cme_activity_token.status = cme_activity_token_status.tokenstatusid) 
					WHERE activity_id =%s AND cme_activity_token_status.tokenstatusid=1',$activity);
		$res = mysql_query($SQL);
		return mysql_num_rows($res);	
		
	}
	public function getTotalUnclaimedActivityTokens($activity)
	{
		
		$SQL=sprintf('SELECT *
					FROM
						cme_activity_token
						INNER JOIN cme_activity_token_status ON (cme_activity_token.status = cme_activity_token_status.tokenstatusid) 
					WHERE activity_id =%s AND cme_activity_token_status.tokenstatusid=2',$activity);
		$res = mysql_query($SQL);
		return mysql_num_rows($res);	
		
	}
	public function createTokensBatch($activity)
	{
		require_once('class_functions.php');
		$random = new generals;
		$batch= $activity.$random->genRandomString();
		return $batch;
	}
	
	public function nonTokenUserInitiated($userid)
	{
		require_once('class_system.php');
		$dateconvert = new System;
		$method_source = 'nonTokenUserInitiated';
		
		$activityname =  isset($_POST['activityname']) ? $this->setEscape($_POST['activityname']) : null ;
		$descriptiontextarea =  isset($_POST['descriptiontextarea']) ? $this->setEscape($_POST['descriptiontextarea']) : null ;
		$activitytype =  isset($_POST['activitytype']) ? $_POST['activitytype'] : '' ;	
		$uploadedFile =  isset($_POST['activitytype']) ? $_POST['activitytype'] : null ;	
		$releasedate =  isset($_POST['begindate']) ? $dateconvert->convert_datetime($_POST['begindate'],'-','ymd') : null ;
		$expirydate =  isset($_POST['enddate']) ? $dateconvert->convert_datetime($_POST['enddate'],'-','ymd') : null;
		
		
		$SQLInsert= sprintf('INSERT INTO cme_non_token_based_claims (user_id,activitytype,activityname,claimdate,begindate,enddate,description,uploadedFile) VALUES("%s","%s","%s",Now(),"%s","%s","%s","%s")',$userid,$activitytype,$activityname,$releasedate,$expirydate,$descriptiontextarea,$uploadedFile);
		if(mysql_query($SQLInsert))
		{	
			$this->setUserCPDPointsNontokenUserInitiated($userid,$activitytype,$method_source,$releasedate,$expirydate);
			return true; 
		}
			else
				return false;
	}
	
	public function claimToken($token,$userid)
	{
		
		require_once('class_error_messages.php');
		$errors = new ErrorMessage();
		$errors->setShowMessage(true);
					
				list($activity_id,$tokenid) =  $this->getCPDActivityID($token);
				
				if($this->checkIfUserAssociatedProvider($userid,$activity_id))
				{
					if($this->checkIfUserNotClaim($userid,$activity_id))
					{
						if($r['status']==1)
							return array('false','The token submitted is has been claimed');
							elseif($r['status']==1)
								return array('false','The token submitted is expired!');
									else
										{
										if($this->checkIfActivityPublished($activity_id)){
											if($this->updateTokens($userid,$tokenid) && 
											$this->setUserCPDPoints($token,$userid,$activity_id))
											{
												$points = $this->getCPDPoints($claimActivity);
												return array('true','says:Successfully claimed '.$points.' points for activity #'.$activity_id);
											}
										}
										else
										return array('false','Token invalid, contact your coordinator');
											
									}
					}
					else
					{
						return array('false','Sorry this Claim is invalid, You had made a claim with a token for this activity!!');
					}
				}
				else
				{
					$provide = $this->getprovider_name($activity_id);
					return array('false','To Claim a point for this activity, You must be associated with  this <br> provider <b>('.$provide.')</b> to claim the points!!');
				}
			
	}
	private function updateTokens($userid,$tokenid)
	{
		require_once('class_error_messages.php');
		$errors = new ErrorMessage();
		$errors->setShowMessage(true);
		
		$SQLUpdate= sprintf('UPDATE cme_activity_token SET claimedby=%s, claimeddate=Now(),status=1 WHERE tokenid=%s',$userid,$tokenid);
		if(mysql_query($SQLUpdate))
		{
			//$errors->getMessage('Token successfully claimed','success');
			return true;
		}
			else
			{
				//$errors->getMessage('Error ocurred','error');
				return array('false','Error ocurred');
			}
				
	}
	
	private function checkIfUserNotClaim($userid,$activity_id)
	{
		$SQL=sprintf('SELECT * FROM cme_activity_token WHERE claimedby=%s AND activity_id=%s',$userid,$activity_id);	
		$res = mysql_query($SQL);
		if(mysql_num_rows($res)>0)
			return false; 
				else
					return true;
	}
	
	private function checkIfActivityPublished($activity_id)
	{
		$SQL=sprintf("SELECT * FROM cme_activities WHERE published='1' AND id=%s",$activity_id);
		$res = mysql_query($SQL);
		if(mysql_num_rows($res)>0)
			return true; 
				else
					return false;
	}
	
	
	private function setUserCPDPoints($token,$userid,$activity_id)
	{
		$cpdpoints= $this->getCPDPoints($activity_id);
		$SQLInsert= sprintf('INSERT INTO cme_activity_token_claims (token,claimMethod,activity_id,claimeddate,user_id,cpd_points) VALUES("%s","token",%s,Now(),%s,"%s")',$token,$activity_id,$userid,$cpdpoints);
		if(mysql_query($SQLInsert))
			return true; 
			else
				return false;
	}
	private function setUserCPDPointsNontoken($userid,$activity_id,$method_source)
	{
		$cpdpoints= $this->getCPDPoints($activity_id);
		$SQLInsert= sprintf('INSERT INTO cme_activity_token_claims (claimMethod,activity_id,claimeddate,user_id,cpd_points,method_source) VALUES("sms",%s,Now(),%s,"%s","%s")',$activity_id,$userid,$cpdpoints,$method_source);
		if(mysql_query($SQLInsert))
			return true; 
			else
				return false;
	}
	private function setUserCPDPointsNontokenUserInitiated($userid,$activity_type,$method_source,$begindate,$enddate)
	{
		$cpdpoints= $this->getCPDPointsType($activity_type);
		$ui_activity_id	 = $this->getNontokenUserInitiatedClaimID($userid,$activity_type,$begindate,$enddate);
		$SQLInsert= sprintf('INSERT INTO cme_activity_token_claims (claimMethod,ui_activity_id,claimeddate,user_id,cpd_points,method_source) VALUES("online","%s",Now(),"%s","%s","%s")',$ui_activity_id,$userid,$cpdpoints,$method_source);
		
		if(mysql_query($SQLInsert))
			return true; 
			else
				return false;
	}
	private function getNontokenUserInitiatedClaimID($userid,$activity_type,$begindate,$enddate)
	{
		$SQLQuery =sprintf('SELECT * FROM  cme_non_token_based_claims
							WHERE user_id ="%s" AND begindate="%s" AND enddate="%s" AND activitytype="%s"',$userid,$begindate,$enddate,$activity_type);
			$res=mysql_query($SQLQuery);
			if($r=mysql_fetch_array($res))
			{
				$claimID = $r['claimID'];
			}	
		return $claimID;
	}
	
	private function checkIfUserAssociatedProvider($userid,$activity_id)
	{
		$SQL = sprintf('SELECT *
						FROM
							cme_activities
							INNER JOIN cme_providers ON (cme_activities.provider_id = cme_providers.pr_id)
							INNER JOIN cme_provider_users ON (cme_provider_users.provider_id = cme_providers.pr_id)
						WHERE (cme_provider_users.user_id =%s AND cme_activities.id =%s)',$userid,$activity_id);
		$res = mysql_query($SQL);
		if(mysql_num_rows($res)>0)
			return true; 
				else
					return false;
	}
	
	private function getprovider_name($activity_id)
	{
		$SQL = sprintf('SELECT *
						FROM
							cme_activities
							INNER JOIN cme_providers ON (cme_activities.provider_id = cme_providers.pr_id)
						WHERE (cme_activities.id =%s)',$activity_id);
		$res = mysql_query($SQL);	
		if(mysql_num_rows($res)>0)
		{	
			if($r=mysql_fetch_array($res))
				{
					$provider = $r['pr_name'];
				}	
			return $provider;
		}
	}
	
	public function getCPDActivityID($token)
	{
		$SQL=sprintf('SELECT * FROM cme_activity_token WHERE token="%s"',$token);
		$res = mysql_query($SQL);
		if(mysql_num_rows($res)>0)
		{
			if($r=mysql_fetch_array($res))
			{
				$tokenid = $r['tokenid'];
				$activity_id = $r['activity_id'];
				return array($activity_id,$tokenid,'');
			}
		}
		else
		{  
			$error= 'The token submitted is invalid!!';
			return array('','',$error);
		}	
		
	}
	
	public function getCPDPoints($activity)
	{
		$SQLQuery =sprintf('SELECT * FROM cme_activities 
								INNER JOIN cme_activity_type ON (cme_activities.activity_type = cme_activity_type.type_id)
							WHERE id=%s',$activity);
			$res=mysql_query($SQLQuery);
			if($r=mysql_fetch_array($res))
			{
				$cpdpoints = $r['cpd_points'];
			}	
		return $cpdpoints;
	}
	public function getCPDPointsType($activity_type)
	{
		$SQLQuery =sprintf('SELECT * FROM  cme_activity_type
							WHERE type_id=%s',$activity_type);
			$res=mysql_query($SQLQuery);
			if($r=mysql_fetch_array($res))
			{
				$cpdpoints = $r['cpd_points'];
			}	
		return $cpdpoints;
	}
	public function assignProviderType($prv_id,$activitytype)
	{
		$SQLInsert= sprintf('INSERT INTO cme_provider_acred_activity_type (activity_type,provider_id,dateadded) VALUES(%s,%s,Now())',$activitytype,$prv_id);
		if(mysql_query($SQLInsert))
			return true; 
			else
				return false;
	}
	public function removeProviderType($prv_id,$activitytype)
	{
		$SQLInsert= sprintf('DELETE FROM cme_provider_acred_activity_type WHERE activity_type=%s AND provider_id=%s',$activitytype,$prv_id);
		if(mysql_query($SQLInsert))
			return true; 
			else
				return false;
	}
	
	public function claimCPDpoints($myreg,$claimActivity,$methodSource)
	{
		require_once('class_user_profile.php');
		require_once ("class_send_sms.php");	
		$sms = new Send_SMS();
		$users = new UserProfile();	
		$userid = $users->GetUserID($myreg ,'reg_no');		
		$activity_id = $claimActivity;
		
		if($this->checkIfUserAssociatedProvider($userid,$activity_id))
				{
					if($this->checkIfUserNotClaim($userid,$activity_id))
					{
						if($this->checkIfActivityPublished($activity_id)){
							if($this->setUserCPDPointsNontoken($userid,$activity_id,$methodSource))
							{
								return true;
							}
						}
						else
						$sms->send_sms_single($methodSource,SYSTEM_NAME.' says: Error; Invalid Claim');
								
					}
					else
					{
						$sms->send_sms_single($methodSource,SYSTEM_NAME.' says: Error; Invalid Claim,You had claimed points for this activity!');
					}
				}
				else
				{
					$provide = $this->getprovider_name($activity_id);
					$sms->send_sms_single($methodSource,SYSTEM_NAME.' says: Error; To Claim a point for this activity, You must be associated with  this provider ('.$provide.')to claim the points!!!');
					
				}
	}
	
	
	public function gettotalsections($activityID)
	{
		$SQL=sprintf("SELECT * FROM cme_online_activity_sections WHERE activity_id=%s",$activityID);
		//$html=	$SQL;
		$res = mysql_query($SQL);
		$html ='';
		//$count = mysql_num_rows($res);
			if(mysql_num_rows($res) >0)
				{
					$SQL=sprintf("SELECT * FROM cme_online_activity_sections WHERE activity_id=%s ORDER  BY actorder ASC ",$activityID);	
					$res = mysql_query($SQL);
					while($r = mysql_fetch_array($res))
					{
						$html .='<h2 class="title7">'.$this->getActivitySectionTitle($r['secId'],$activityID).'</h2>';
						$html .=$this->getActivitySection($r['secId'],$activityID);
					}
				}
		return $html;
	}
	
	
	public function getActivitySection($sectionId,$activityID)
	{
		$SQL=sprintf("SELECT * FROM cme_online_activity_sections WHERE secId=%s AND activity_id=%s",$sectionId,$activityID);	
		$res = mysql_query($SQL);
		if(mysql_num_rows($res)>0)
		{
			if($r=mysql_fetch_array($res))
				{
					return $r['sectiontext']; 
				}
		}
		else
		{			
			$text='Section not found';
			return $text;
		}
	}
	public function getActivitySectionTitle($sectionId,$activityID)
	{
		$SQL=sprintf("SELECT * FROM cme_online_activity_sections WHERE secId=%s AND activity_id=%s",$sectionId,$activityID);	
		$res = mysql_query($SQL);
		if(mysql_num_rows($res)>0)
		{
			if($r=mysql_fetch_array($res))
				{
					return $r['sectionheading']; 
				}
		}
		else
		{			
			$text='';
			return $text;
		}
	}
	public function getActivityObjectives($activityID)
	{
		$SQL=sprintf("SELECT  * FROM cme_activities WHERE id='%s'",$activityID); 	
		$res = mysql_query($SQL);
		$mymessage ='';
		if(mysql_num_rows($res)>0)
		{
			$c=0;
			
			while($r=mysql_fetch_array($res) )
			{
				$objectives = $r['objectives'];			
				
				$lk= $c+1;
				$rowclass= $lk%2 ? 'FFFFFF' : 'FFFFFF';
				$mymessage.='
				<div class="myanchor" id="link'.$lk.'"  style=" min-height:30px; background-color:#'.$rowclass.';">        
						'.$lk.'. '.$objectives.'
					</div><br>
				';
				
				$c++;
			}
			return $mymessage;
		}
		else
		{			
			
			return $mymessage;
		}
		
	}
	public function getActivityKeyMessages($activityID)
	{
		$SQL=sprintf("SELECT  * FROM cme_online_activity_key_messages WHERE activity_id='%s'",$activityID); 	
		$res = mysql_query($SQL);
		$mymessage ='';
		if(mysql_num_rows($res)>0)
		{
			$c=0;
			
			while($r=mysql_fetch_array($res) )
			{
				$messagetext = $r['messagetext'];			
				
				$lk= $c+1;
				$rowclass= $lk%2 ? 'FFFFFF' : 'FFFFFF';
				$mymessage.='
				<div class="myanchor" id="link'.$lk.'"  style=" min-height:30px; background-color:#'.$rowclass.';">        
						'.$lk.'. '.$messagetext.'
					</div><br>
				';
				
				$c++;
			}
			return $mymessage;
		}
		else
		{			
			
			return $mymessage;
		}
		
	}
	public function getActivityAbstract($activityID)
	{
		$SQL=sprintf("SELECT  * FROM cme_online_activity_abstract WHERE activity_id='%s'",$activityID); 	
		$res = mysql_query($SQL);
		$mymessage ='';
		if(mysql_num_rows($res)>0)
		{
			$c=0;
			
			while($r=mysql_fetch_array($res) )
			{
				$messagetext = $r['abstracttext'];			
				
				$lk= $c+1;
				$rowclass= $lk%2 ? 'FFFFFF' : 'FFFFFF';
				$mymessage='
				<div class="myanchor" id="link'.$lk.'"  style=" min-height:30px; background-color:#'.$rowclass.';">        
					'.$messagetext.'
					</div>
				';
				
				$c++;
			}
			return $mymessage;
		}
		else
		{			
			
			return $mymessage;
		}
		
	}
	public function getActivityReferences($activityID)
	{
		$SQL=sprintf("SELECT  * FROM cme_online_activity_references WHERE activity_id='%s'",$activityID); 	
		$res = mysql_query($SQL);
		$mymessage ='';
		if(mysql_num_rows($res)>0)
		{
			$c=0;
			
			while($r=mysql_fetch_array($res) )
			{
				$sectionheading = $r['authors'].'.'.$r['refTitle'].'.'.$r['refsource'];	
				$lk= $c+1;
				$rowclass= $lk%2 ? 'FFFFFF' : 'FFFFFF';
				$mymessage.='
				<p><div class="myanchor" id="link'.$lk.'"  style=" min-height:30px; background-color:#'.$rowclass.';">        
						'.$lk.'. '.$sectionheading.'
					</div></p><br>
				';
				
				$c++;
			}
			return $mymessage;
		}
		else
		{			
			
			return $mymessage;
		}
		
	}
	
	public function check_questions($activityID)
		{
			global $db;
			
			$Query = sprintf("SELECT * FROM cme_online_activity_questions WHERE activity_id ='%s' AND display='Yes' ",mysql_escape_string($activityID));	
		
			$res= mysql_query($Query);
			$counts = mysql_num_rows($res);
			return $counts;
			
		}
	
	public function getActivityQuestionsAnswered($activityID,$lim,$instanceID)
	{
		$Query = sprintf("SELECT * FROM cme_online_activity_questions WHERE activity_id ='%s' AND display='Yes' ",mysql_escape_string($activityID));	
		$c=1;
		$res= mysql_query($Query);
		
		while($r = mysql_fetch_array($res))
		{
			$question = $r['qtext'];
			$qID = $r['qid'];
			$optType = $r['opttype'];	
			
			$yourChoice = $this->getAttemptsAns($qID,$instanceID)!=$this->getCorrectAnswer($qID) ? 'color:#FDB735;' : '';
					
			echo '				
				<tr>
					<td valign="top">'.$c.'</td>
					<td style="'.$yourChoice.'">'.$question.'
					
					</td>
				</tr>
				<tr>
					<td></td>
					<td>';	
			echo '<table with="100%">';		
					$this->getQuestionAnsAnswered($qID,$optType,$instanceID);
			echo '</table>';
			
			echo '</td>
				</tr>
			';
			$c++;
		}
		return;
	}
	
	public	function getQuestionAnsAnswered($qid,$opttype,$instanceID)
	{
		//ORDER BY RAND()
		$Query = sprintf("SELECT
							cme_online_activity_questions_ans.ans_text
							, cme_online_activity_questions_ans.ans_id
							, cme_online_pdf_question_instances.choiceLetter
						FROM
							cme_online_activity_questions_ans
							INNER JOIN cme_online_pdf_question_instances 
								ON (cme_online_activity_questions_ans.ans_id = cme_online_pdf_question_instances.choiceID)
						 WHERE cme_online_activity_questions_ans.qid ='%s' AND 
						 cme_online_pdf_question_instances.instanceID ='%s' 
						 ORDER BY cme_online_pdf_question_instances.choiceLetter ASC",
						 mysql_escape_string($qid),mysql_escape_string($instanceID));	
	
		$res= mysql_query($Query);
		while($r = mysql_fetch_array($res))
		{
			$anstext = $r['ans_text'];
			$ansId = $r['ans_id'];
			$choiceLetter = $r['choiceLetter'];
			//$optrank = $r['optrank'];onclick="submit()
			
			$SQLQ= sprintf("SELECT * FROM cme_online_question_attempts WHERE instanceID='%s' AND question_id='%s'",$instanceID,$qid);
			$req = mysql_query($SQLQ);
			if($rs= mysql_fetch_array($req))
			{
				$ansSelect = $rs['ans_select']==$ansId ? 'checked="checked"' : '';
				
				if($opttype=='Multiple')
				{
					echo '
						<tr>
							<td valign="top">
								<b>'.$choiceLetter.'</b> 
							</td>
							<td>
								<input type="checkbox" name="ans['.$ansId.']" value="'.$ansId.'" id="ans[]" '.$ansSelect.'>
								<input type="hidden" name="ansID['.$ansId.']" id="ansID[]" value="'.$ansId.'" >'.$anstext.'
							</td>
						</tr>
					';
				}
				elseif($opttype=='Single')
				{
					echo '
						<tr>
						  <td valign="top">
							<b>'.$choiceLetter.'</b> 
						  </td>
						  <td>
							  <input type="radio" name="ans['.$qid.']" value="'.$ansId.'" id="ans[]" '.$ansSelect.'>
							  <input type="hidden" name="question['.$qid.']" id="question[]" value="'.$qid.'">
							  <input type="hidden" name="ansID['.$qid.']" id="ansID[]" value="'.$ansId.'">
							  '.$anstext.'
						  </td>
						</tr>
					
					';
				}
			}
		}
		return;
	}
	
	
	public function getActivityQuestions($activityID,$lim,$instanceID)
	{
		$Query = sprintf("SELECT * FROM cme_online_activity_questions WHERE activity_id ='%s' AND display='Yes' ",mysql_escape_string($activityID));	
		$c=1;
		$res= mysql_query($Query);
		
		while($r = mysql_fetch_array($res))
		{
			$question = $r['qtext'];
			$qID = $r['qid'];
			$optType = $r['opttype'];
			//$qrank = $r['qrank'];
			
			echo '				
				<tr>
					<td valign="top">'.$c.'</td>
					<td>'.$question.'
					
					</td>
				</tr>
				<tr>
					<td></td>
					<td>';	
			echo '<table with="100%">';		
					$this->getQuestionAns($qID,$optType,$instanceID);
			echo '</table>';
			
			echo '</td>
				</tr>
			';
			$c++;
		}
		return;
	}
	
	public	function getQuestionAns($qid,$opttype,$instanceID)
	{
		//ORDER BY RAND()
		$Query = sprintf("SELECT
							cme_online_activity_questions_ans.ans_text
							, cme_online_activity_questions_ans.ans_id
							, cme_online_pdf_question_instances.choiceLetter
						FROM
							cme_online_activity_questions_ans
							INNER JOIN cme_online_pdf_question_instances 
								ON (cme_online_activity_questions_ans.ans_id = cme_online_pdf_question_instances.choiceID)
						 WHERE cme_online_activity_questions_ans.qid ='%s' AND 
						 cme_online_pdf_question_instances.instanceID ='%s' 
						 ORDER BY cme_online_pdf_question_instances.choiceLetter ASC",
						 mysql_escape_string($qid),mysql_escape_string($instanceID));	
	
		$res= mysql_query($Query);
		while($r = mysql_fetch_array($res))
		{
			$anstext = $r['ans_text'];
			$ansId = $r['ans_id'];
			$choiceLetter = $r['choiceLetter'];
			//$optrank = $r['optrank'];onclick="submit()
			
			if($opttype=='Multiple')
			{
				echo '
					<tr>
						<td valign="top">
							<b>'.$choiceLetter.'</b> 
						</td>
						<td>
							<input type="checkbox" name="ans['.$ansId.']" value="'.$ansId.'" id="ans[]"  ">
							<input type="hidden" name="ansID['.$ansId.']" id="ansID[]" value="'.$ansId.'" >'.$anstext.'
						</td>
					</tr>
				';
			}
			elseif($opttype=='Single')
			{
				echo '
					<tr>
					  <td valign="top">
					  	<b>'.$choiceLetter.'</b> 
					  </td>
					  <td>
						  <input type="radio" name="ans['.$qid.']" value="'.$ansId.'" id="ans[]" ">
						  <input type="hidden" name="question['.$qid.']" id="question[]" value="'.$qid.'">
						  <input type="hidden" name="ansID['.$qid.']" id="ansID[]" value="'.$ansId.'">
						  '.$anstext.'
					  </td>
					</tr>
				
				';
			}
			
		}
		return;
	}
	
	
	public function getActivityQuestionsPreview($activityID,$lim)
	{
			$Query = sprintf("SELECT * FROM cme_online_activity_questions WHERE activity_id ='%s' AND display='Yes' ",mysql_escape_string($activityID));	
		$c=1;
		$res= mysql_query($Query);
		
		while($r = mysql_fetch_array($res))
		{
			$question = $r['qtext'];
			$qID = $r['qid'];
			$optType = $r['opttype'];
			//$qrank = $r['qrank'];
			
			echo '				
				<tr>
					<td valign="top">'.$c.'</td>
					<td>'.$question.'
					
					</td>
				</tr>
				<tr>
					<td></td>
					<td>';	
			echo '<table with="100%">';		
					$this->getQuestionAnsPreview($qID,$optType);
			echo '</table>';
			
			echo '</td>
				</tr>
			';
			$c++;
		}
		return;
	}
	
	public	function getQuestionAnsPreview($qid,$opttype)
	{
		//ORDER BY RAND()
		$Query = sprintf("SELECT
							cme_online_activity_questions_ans.ans_text
							, cme_online_activity_questions_ans.ans_id
						FROM
							cme_online_activity_questions_ans
						 WHERE cme_online_activity_questions_ans.qid ='%s' ",
						 mysql_escape_string($qid));	
	
		$res= mysql_query($Query);
		while($r = mysql_fetch_array($res))
		{
			$anstext = $r['ans_text'];
			$ansId = $r['ans_id'];
			//$optrank = $r['optrank'];onclick="submit()
			
			if($opttype=='Multiple')
			{
				echo '
					<tr>
						<td valign="top"> 
						</td>
						<td>
							<input type="checkbox" name="ans['.$ansId.']" value="'.$ansId.'" id="ans[]"  ">
							<input type="hidden" name="ansID['.$ansId.']" id="ansID[]" value="'.$ansId.'" >'.$anstext.'
						</td>
					</tr>
				';
			}
			elseif($opttype=='Single')
			{
				echo '
					<tr>
					  <td valign="top"> 
					  </td>
					  <td>
						  <input type="radio" name="ans['.$qid.']" value="'.$ansId.'" id="ans[]" ">
						  <input type="hidden" name="question['.$qid.']" id="question[]" value="'.$qid.'">
						  <input type="hidden" name="ansID['.$qid.']" id="ansID[]" value="'.$ansId.'">
						  '.$anstext.'
					  </td>
					</tr>
				
				';
			}
			
		}
		return;
	}
	
	
	public function getActivityQuestionsPrint($activityID,$links,$userID,$attemptSession)
	{
		$html='';
		if($this->checkIfUserAttemptsQualified($activityID,$userID,$attemptSession))
		{
		
		$Query = sprintf("SELECT * FROM cme_online_activity_questions WHERE activity_id ='%s' AND display='Yes' ",mysql_escape_string($activityID));	
		$c=1;
		$html.='<table with="100%">';	
		$res= mysql_query($Query);
		while($r = mysql_fetch_array($res))
		{
			$question = $r['qtext'];
			$qID = $r['qid'];
			$optType = $r['opttype'];
			//$qrank = $r['qrank'];
			
			$html.= '				
				<tr>
					<td valign="top">'.$c.'</td>
					<td>'.$question.'</td>
				</tr>
				<tr>
					<td></td>
					<td><div style="padding-left:20px;">';
						
			//echo '<ol type="A">';		
					//$Querys = sprintf("SELECT * FROM cme_online_activity_questions_ans WHERE qid ='%s' ",mysql_escape_string($qID));	
					$Querys = sprintf("SELECT
							cme_online_activity_questions_ans.ans_text,cme_online_activity_questions_ans.ans_correct
							, cme_online_activity_questions_ans.ans_id,cme_online_activity_questions_ans.explanation
							, cme_online_pdf_question_instances.choiceLetter
						FROM
							cme_online_activity_questions_ans
							INNER JOIN cme_online_pdf_question_instances 
								ON (cme_online_activity_questions_ans.ans_id = cme_online_pdf_question_instances.choiceID)
						 WHERE cme_online_activity_questions_ans.qid ='%s' AND 
						 cme_online_pdf_question_instances.instanceID ='%s'   AND cme_online_activity_questions_ans.ans_correct='Yes'
						 ORDER BY cme_online_pdf_question_instances.choiceLetter ASC",
						 mysql_escape_string($qID),mysql_escape_string($attemptSession));
	
					$ress= mysql_query($Querys);
					while($rs = mysql_fetch_array($ress))
					{
						$correctAnsw = $rs['ans_correct']=='Yes' ? '(Correct Answer)' : '';
						//$correctAnswColor =$rs['ans_correct']=='Yes' ? 'color:#003300; font-weight:bold; ' : ''; style="'.$correctAnswColor.'
						$choiceLetter = $rs['choiceLetter'];
						//$yourchoice = $this->getAttemptsAns($qID,$attemptSession);
						//$yourChoicetext = $yourchoice==$rs['ans_id'] ? 'Your Choice' : '';<br><p style="color:#FF0000;">'.$yourChoicetext.'</p>
						
						$html.='<span><b>Answer: </b>'.$choiceLetter.'. '.$rs['ans_text'].' '.$correctAnsw.'									
									<div style="padding-left:20px;font-style:italic;color:#999;">"'.$rs['explanation'].'"</div>
								</span>';
					}
			//echo '</ol>';			
			$html.= '</div>
					</td>
						</tr>
			';
			$c++;
		}
		$html.='<tr>
					<td valign="top" colspan="2">
					<br><br>
					<a href="'.$links.'&anch=qcpoints&cl=claims" class="back_button">Claim Points</a></td>
				</tr>';
						
		$html.='</table>';
		return $html;
		
		}
		else
		{
			return array('false','You need to get at least <b>'.ACTIVITYPASS.'%</b> of the Questions correct. Please try again!!');
		}
	}
	
	private function getAttemptsAns($qID,$attemptSession)
	{
		$QRYAct = sprintf("SELECT  * FROM cme_online_question_attempts WHERE question_id='%s' AND instanceID='%s'",$qID,$attemptSession);
		$ress= mysql_query($QRYAct);
		if($rs = mysql_fetch_array($ress))
		{
			return $rs['ans_select']; 
		}
	}
	
	private function getCorrectAnswer($qID)
	{
		$QueryCorr = sprintf("SELECT ans_correct FROM cme_online_activity_questions_ans
						 WHERE qid ='%s' AND ans_correct= 'Yes' ",
						 mysql_escape_string($qID));
	
		$resC= mysql_query($QueryCorr);
		if($rC = mysql_fetch_array($resC))
		{
			return $rC['ans_correct'];
		}
	}
	
	
	public function claimOnlineCPD($activityID,$userID,$method_source,$attemptSession)
	{
		if($this->checkIfUserAttemptsQualified($activityID,$userID,$attemptSession))
		{
			$cpdpoints = $this->getCPDPoints($activityID);
			if($this->checkIfUserClaimedActivity($userID,$activityID)){
				if($this->checkIfActivityPublished($activityID)){
						$SQLInsert= sprintf('INSERT INTO cme_activity_token_claims (claimMethod,activity_id,claimeddate,user_id,cpd_points,
											method_source) VALUES("online",%s,Now(),%s,"%s","%s")',
											$activityID,$userID,$cpdpoints,$method_source);
							if(mysql_query($SQLInsert))
								return true; 
								else
									return array('false','Claim is not successfull!!');
				}
				else
					return array('false','Claim is not valid!!');
												
			}
			else
			{
				return array('false','Sorry this Claim is invalid, You had made a claimed points for this activity!!');
			}
		}
		else
		{
			return array('false','You need to get at least'.ACTIVITYPASS.'% of the Questions correct. Please try again!!');
		}
	}
	
	public function checkIfUserClaimedActivity($userid,$activity_id)
	{
		$SQL=sprintf('SELECT * FROM cme_activity_token_claims WHERE user_id=%s AND activity_id=%s',$userid,$activity_id);	
		$res = mysql_query($SQL);
		if(mysql_num_rows($res)>0)
			return false; 
				else
					return true;
	}
	
	public function checkIfUserAttemptsQualified($activityID,$userID,$attemptSession)
	{
		$correctQ=0;
		$correctQPer=0;
		
		$Querys = sprintf('SELECT * FROM cme_online_question_attempts WHERE instanceID ="%s" AND activity_id=%s AND user_id=%s '
		,mysql_escape_string($attemptSession),mysql_escape_string($activityID),mysql_escape_string($userID));	
		$ress= mysql_query($Querys);
		while($rs = mysql_fetch_array($ress))
		{
			if($this->checkIfAnswerIsCorrect($rs['question_id'],$rs['ans_select']))	
				$correctQ=$correctQ+1;
				else
					$correctQ=$correctQ;
		}
		$totalQuestions= $this->check_questions($activityID);
		$correctQPer =($correctQ/$totalQuestions)*100;
		if($correctQPer >= ACTIVITYPASS)
			return true;
			else
			 return false;
	}
		
	public function checkIfAnswerIsCorrect($question_id,$ans_select)
	{
		$QRYAct = sprintf("SELECT  * FROM cme_online_activity_questions_ans WHERE qid='%s' AND ans_id='%s'",$question_id,$ans_select);
		$ress= mysql_query($QRYAct);
		if($rs = mysql_fetch_array($ress))
		{
			if($rs['ans_correct']=='Yes')
			return true; 
				else
					return false;
		}
	}
	
	public function getQuestionChoices($questionId,$myinstanceID)
	{
		require_once('class_functions.php');
		$random = new generals;
		$ln = $this->getNumberOfChoicesQuestion($questionId);
		$choiceLetters = $random->genRandomChoices($ln);
		if($this->checkIfChoiceLetterValid($questionId,$choiceLetters,$myinstanceID))
				return $choiceLetters;
			else
			{
				$array = array();
				$arrayposted = array();
				$ln=($ln+65);
				for( $i = 65; $i < $ln; $i++){
						$array[] = chr($i);
				}
				
				$QRYQp = sprintf("SELECT * FROM cme_online_pdf_question_instances 
				WHERE questionId='%s' AND instanceID='%s'",$questionId,$myinstanceID);
					$reqp= mysql_query($QRYQp);			
					while($rqp = mysql_fetch_array($reqp))
					{
						$arrayposted[] =$rqp['choiceLetter'];
					}
					
					$result = array_diff($array, $arrayposted);	
					//print_r($result)	;
					for( $i = 0; $i < $ln; $i++){
						if(isset($result[$i]))
						{
							return $result[$i];	
						}
					}		
									
					//print_r($array);
					//print_r($arrayposted);
			}
	}
	
	private function checkIfChoiceLetterValid($questionId,$choiceLetters,$myinstanceID)
	{
		
		$QRYQ = sprintf("SELECT  COUNT(choiceLetter) AS mfound FROM cme_online_pdf_question_instances WHERE questionId='%s' AND choiceLetter ='%s' AND instanceID='%s'",$questionId,$choiceLetters,$myinstanceID);
		$req= mysql_query($QRYQ);			
		if($rq = mysql_fetch_array($req))
		{	
			$mfound=$rq['mfound'];				
			if($mfound==0)
				return true;
				 else
				  return false;
		}
										
	}

	public function getNumberOfChoicesQuestion($question_id)
	{
		$QRYQuest = sprintf("SELECT  count(ans_id) as mycount FROM cme_online_activity_questions_ans WHERE qid='%s'",$question_id);
		$resq= mysql_query($QRYQuest);
		if($rs = mysql_fetch_array($resq))
		{
			return $rs['mycount'];
		}
		
	}
	
	/*
		Create Instances for both pdf and html.
		New instances are created everytime a user has failed to get 65% of the questions right.
		When new instances are created the previous instance is set to 0(isValid) so the user cannot claim withit.
	*/
	
	public function createPDFActivityInstance($userid,$activityID)
	{
		require_once('class_functions.php');
		$random = new generals;
		
		$instanceID= $this->checkIfActivityUserInstance($userid,$activityID);
		if($instanceID==0){
			$SQLInsert= sprintf('INSERT INTO cme_online_pdf_instances (user_id,activity_id,datecreated) VALUES(%s,%s,Now())',
								$userid,$activityID);
					if(mysql_query($SQLInsert))
					{
						$myinstanceID = $this->checkIfActivityUserInstance($userid,$activityID);						
						$QRYInst = sprintf("SELECT  * FROM cme_online_activity_questions WHERE activity_id='%s'",$activityID);
						$ress= mysql_query($QRYInst);
						$count =mysql_num_rows($ress);
						if($count>0)
						{
							$c=0;
							while($rs = mysql_fetch_array($ress))
							{ 
								$c++;
									$questionId = $rs['qid']; 
									//$this->getQuestionChoices($questionId,$myinstanceID);
									$QRYQ = sprintf("SELECT  * FROM cme_online_activity_questions_ans WHERE qid='%s'",$questionId);
									$req= mysql_query($QRYQ);
									//$ln = $this->getNumberOfChoicesQuestion($questionId);	
									while($rq = mysql_fetch_array($req))
									{	
										$choiceID=$rq['ans_id'];
										$correctans=$rq['ans_correct'];	
										//Get randomised choices i.e A B C D.....				
										$this->choiceLetter = $this->getQuestionChoices($questionId,$myinstanceID);
										
											$SQLQ= sprintf('INSERT INTO cme_online_pdf_question_instances 
											(instanceID,questionId,correctans,choiceID,
											choiceLetter)
											 VALUES("%s","%s","%s","%s","%s")',$myinstanceID,$questionId,$correctans,$choiceID,
											 $this->choiceLetter);
											if(mysql_query($SQLQ)){}
										
									}									
							}
							if($c==$count)
								return $myinstanceID; 
						}
					}
					
				}
		else
		{
			/*if($this->checkIfAttempted($instanceID))//Check if the Instance had been attempted
			{*/
				/*
				If the instance had been attempted then check if the user passed,
				otherwise create a new instance for the user.
				*/
				/*if($this->checkIfUserAttemptsQualified($activityID,$userid,$instanceID))
				{
					return $instanceID;
				}
				else
				{*/
					/*
					
						If the user failed the instance, update the instance to set it to NOT VALID
					
					*/
					/*if($this->updateInstance($instanceID))
					{
						$SQLInsert= sprintf('INSERT INTO cme_online_pdf_instances (user_id,activity_id,datecreated) VALUES(%s,%s,Now())',
									$userid,$activityID);
						if(mysql_query($SQLInsert))
						{
							$myinstanceID = $this->checkIfActivityUserInstance($userid,$activityID);						
							$QRYInst = sprintf("SELECT  * FROM cme_online_activity_questions WHERE activity_id='%s'",$activityID);
							$ress= mysql_query($QRYInst);
							$count =mysql_num_rows($ress);
							if($count>0)
							{
								$c=0;
								while($rs = mysql_fetch_array($ress))
								{ 
									$c++;
										$questionId = $rs['qid']; 
										//$this->getQuestionChoices($questionId,$myinstanceID);
										$QRYQ = sprintf("SELECT  * FROM cme_online_activity_questions_ans WHERE qid='%s'",$questionId);
										$req= mysql_query($QRYQ);
										//$ln = $this->getNumberOfChoicesQuestion($questionId);	
										while($rq = mysql_fetch_array($req))
										{	
											$choiceID=$rq['ans_id'];
											$correctans=$rq['ans_correct'];					
											$this->choiceLetter = $this->getQuestionChoices($questionId,$myinstanceID);
											
												$SQLQ= sprintf('INSERT INTO cme_online_pdf_question_instances 
												(instanceID,questionId,correctans,choiceID,
												choiceLetter)
												 VALUES("%s","%s","%s","%s","%s")',$myinstanceID,$questionId,$correctans,$choiceID,
												 $this->choiceLetter);
												if(mysql_query($SQLQ)){}
											
										}									
								}
								if($c==$count)
									return $myinstanceID; 
							}
						}
					}*/
				/*}*/
			/*}
			else
			{*/
				return $instanceID;
			/*}*/
		}
	}
	
	private function checkIfAttempted($instanceID)
	{
		$Querys = sprintf('SELECT * FROM cme_online_question_attempts WHERE instanceID ="%s"',mysql_escape_string($instanceID));	
		$ress= mysql_query($Querys);
		if(mysql_num_rows($ress)>0)
		{
			return true;
		}
		else
			return false;
		
	}
	
	
	private function updateInstance($instanceID)
	{
		$QRYInst = sprintf("UPDATE cme_online_pdf_instances SET isValid='0' WHERE instanceID='%s'",$instanceID);
		if(mysql_query($QRYInst))
		return true;
	}
	
	private function checkIfActivityUserInstance($userid,$activityID)
	{
		$QRYInst = sprintf("SELECT  * FROM cme_online_pdf_instances WHERE user_id='%s' AND activity_id='%s' AND isValid='1'",$userid,$activityID);
		$ress= mysql_query($QRYInst);
		if(mysql_num_rows($ress)>0)
		{
			if($rs = mysql_fetch_array($ress))
				return $rs['instanceID']; 
		}
		else
			return 0;
	}
	
	public function activityQuestionsAttempt($activityID,$user_id,$questionId,$answers,$instanceID)
	{
		$SQLQ= sprintf('INSERT INTO cme_online_question_attempts 
											(instanceID,question_id,user_id,activity_id,ans_select,	attempdate)
											 VALUES("%s","%s","%s","%s","%s",NOW())',$instanceID,$questionId,$user_id,$activityID,
											 $answers);
											if(mysql_query($SQLQ)){}
	}
	
	public function PDFActivityClaimsBadge($userid,$instanceID)
	{
		$html = 'Version # :'.$instanceID.'<br>';
		$html .= 'Your Registration # :'.$userid.'<br><br><br>';
		$html .= 'Text Format : #U<em>UserID</em>#I<em>Version</em>#1A2B3D....#<br>
				<em>Sample: <b>#U01#I20#1A2B3D#</em>';
		return $html;
		
	}
	
	public function myCPDPortfolio($userid)
	{
		require_once('class_system.php');
		$separator = new System;
		$s = $separator->get_separator();
		
		$SQLQuery =sprintf('SELECT	claimeddate , cpd_points , user_id , ui_activity_id , activity_id FROM cme_activity_token_claims
						WHERE user_id=%s ORDER BY claimeddate DESC',$userid);
		$c=1;
		$res=mysql_query($SQLQuery);
		while($r=mysql_fetch_array($res))
		{
			
			if(isset($r['activity_id']) && is_numeric($r['activity_id']) && $r['activity_id']!=0)
			{
				$SQLQueryALL =sprintf('SELECT
											cme_activity_token_claims.claimeddate
											, cme_activity_token_claims.cpd_points
											, cme_activities.activity_name
											, cme_activity_type.delivery_mode
											, cme_activity_token_claims.user_id
										FROM
											cme_activity_token_claims
											INNER JOIN cme_activities 
												ON (cme_activity_token_claims.activity_id = cme_activities.id)
											INNER JOIN cme_activity_type 
												ON (cme_activities.activity_type = cme_activity_type.type_id)
									WHERE cme_activity_token_claims.user_id="%s" AND cme_activities.id="%s"',$userid,$r['activity_id']);
				
				
				$resAll=mysql_query($SQLQueryALL);
				if($rAll=mysql_fetch_array($resAll))
				{
					$class = $c%2 ? 'class="even"' : '';
					if($rAll['delivery_mode']==1)	//Offline				
						$viewmode ='extlst';
						elseif($rAll['delivery_mode']==2)//Online
						$viewmode ='onlinelst';
						
					echo '
						<tr '.$class.'>
							<td>'.$separator->convert_datetime($rAll['claimeddate'],'-','dmy').'</td>							
							<td><a href="index.php?k='.base64_encode('activities'.$s.$viewmode.$s.''.$s.'item').'&d='.base64_encode($r['activity_id']).'">'.$rAll['activity_name'].'</a></td>
							<td>'.$rAll['cpd_points'].'</td>
							<td><a title="Print Certificate" href="index.php?k='.base64_encode('diary'.$s.'printcert'.$s.''.$s.'').'&d='.base64_encode($r['activity_id']).'">Print</a></td>
						</tr>';
				}
			}
			elseif(isset($r['ui_activity_id']) && is_numeric($r['ui_activity_id']) && $r['ui_activity_id']!=0)
			{
				$SQLQueryUI =sprintf('SELECT
										cme_activity_token_claims.claimeddate
										, cme_activity_token_claims.cpd_points
										, cme_activity_token_claims.user_id
										, cme_non_token_based_claims.activityname
									FROM
										cme_activity_token_claims
										INNER JOIN cme_non_token_based_claims 
											ON (cme_activity_token_claims.ui_activity_id = cme_non_token_based_claims.claimID)
									WHERE cme_activity_token_claims.user_id="%s" AND cme_non_token_based_claims.claimID ="%s"',$userid,$r['ui_activity_id']);
				
				$resUI=mysql_query($SQLQueryUI);
				if($rUI=mysql_fetch_array($resUI))
				{
					$class = $c%2 ? 'class="even"' : '';
					echo '
						<tr '.$class.'>
							<td>'.$separator->convert_datetime($rUI['claimeddate'],'-','dmy').'</td>							
							<td>'.$rUI['activityname'].'</td>
							<td>'.$rUI['cpd_points'].'</td>
							<td></td>
						</tr>';
		
				}
			}
			
		$c++;
		}
		
	}

	public function printActivityCerficate($userid,$activityID)
	{
		require_once('class_system.php');
		$separator = new System;
		
		$SQLQueryALL =sprintf('SELECT
											cme_activity_token_claims.claimeddate
											, cme_activity_token_claims.cpd_points
											, cme_activities.activity_name
											, cme_activity_type.delivery_mode
											, cme_activity_token_claims.user_id
										FROM
											cme_activity_token_claims
											INNER JOIN cme_activities 
												ON (cme_activity_token_claims.activity_id = cme_activities.id)
											INNER JOIN cme_activity_type 
												ON (cme_activities.activity_type = cme_activity_type.type_id)
									WHERE cme_activity_token_claims.user_id="%s" AND cme_activities.id="%s"',$userid,$activityID);
				
				
				$resAll=mysql_query($SQLQueryALL);
				if($rAll=mysql_fetch_array($resAll))
				{
					$date =$separator->convert_datetime($rAll['claimeddate'],'-','dmy')	;					
					$activityName= $rAll['activity_name'];
					$cpdPoints =$rAll['cpd_points'];
					return array($date, $activityName, $cpdPoints);
				}
	}
}
?>