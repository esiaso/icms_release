<?php
//Question Manager
class OnlineActivities
{
	
	
	
	private $activityName;
	private $artID;
	private $seriesDesc;
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
	
	public function get_article_title($artID)
	{
		
		$Query = sprintf("SELECT * FROM cme_articles WHERE art_id ='%s' ",$this->setEscape($artID));	
		
		$res= mysql_query($Query);
		if(mysql_num_rows($res)>0)
		{
			if($r = mysql_fetch_array($res))
			{
				$this->activityName = $r['art_name'];
				return $this->activityName;
			}
			
		}
	}
	
	public function get_article_series($artID)
	{
		
		$Query = sprintf("SELECT * FROM cme_article_series t
						 JOIN cme_article_info a ON t.seriesId = a.series
						 WHERE a.artcleid ='%s' ",$this->setEscape($artID));		
		
		
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$this->seriesDesc = $r['seriesDesc'];
				return $this->seriesDesc;
		}
		
	}
	
	public function getarticlebody($artID)
	{
		require_once('class_system.php');
		$separator = new System;
		$s = $separator->get_separator();
		
		$Query = sprintf("SELECT * FROM cme_article_body t
						 JOIN cme_articles a ON t.art_id = a.art_id
						 WHERE a.art_id ='%s' ",$this->setEscape($artID));	
		
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$artname = $r['art_name'];
			$artbody = $r['bdtext'];
			
			echo '
				<tr>
					<td colspan="2" align="right">  <p><button type="button" onclick="javascript:history.back()" >Back</button>';
					
					if($this->check_if_article_has_questions($artID))
					{
						echo '<a  class="more" href="index.php?k='.base64_encode('activities'.$s.'q'.$s.''.$s.'item').'&d='.base64_encode($artID).'"><button type="button">Proceed to the Activity</button></a>';
					}
					
				echo ' </p>
				   </td>
				</tr>  
				<tr>
					<td valign="top" style="font-size:11px;">'.$artbody.'</td>
				</tr>';
				
		}
		return;
	}
	
	public function check_questions($artID)
	{
		
		$Query = sprintf("SELECT * FROM cme_articles_questions WHERE art_id ='%s' AND display='Yes' ",$this->setEscape($artID));	
	
		$res= mysql_query($Query);
		$counts = mysql_num_rows($res);
		return $counts;
		
	}
	
	
	public function getquestions($artID,$lim)
	{
		
		$Query = sprintf("SELECT * FROM cme_articles_questions WHERE art_id ='%s' AND display='Yes' ORDER BY qrank ASC LIMIT %s, 1",$this->setEscape($artID),$this->setEscape($lim));	
	
	
		$res= mysql_query($Query);
		while($r = mysql_fetch_array($res))
		{
			$question = $r['qtext'];
			$qID = $r['qid'];
			$optType = $r['opttype'];
			$qrank = $r['qrank'];
			
			echo '
				
				<tr>
					<td valign="top">'.$qrank.'</td>
					<td>'.$question.'
					<input type="hidden" name="question['.$qID.']" id="question[]" value="'.$qID.'">
					</td>
				</tr>
				<tr>
					<td></td>
					<td>';	
			echo '<table with="100%">';		
					$this->getquestionans($qID,$optType);
			echo '</table>';
			
			echo '</td>
				</tr>
			';
		}
		return;
		
	}
	public function getnumquestion($artID)
	{
		global $db;
		$counts =0;
		$qnum =0;
		
		$Query = sprintf("SELECT * FROM cme_articles_questions WHERE art_id ='%s'",$this->setEscape($artID));	
	
		$res= mysql_query($Query);
		$counts = mysql_num_rows($res);
		if($counts<=0)
		{
			$qnum =0;
		}
		else
		{
			$qnum =$counts;
		}
		return $counts;
		
	}
	
	public function getarticletitle($artID)
	{
		
		$Query = sprintf("SELECT * FROM cme_articles WHERE art_id ='%s' ",$this->setEscape($artID));	
	
		$res= mysql_query($Query);
		while($r = mysql_fetch_array($res))
		{
			$title = $r['art_name'];		
			
			echo $title;
		}
		return;
		
	}
    public	function getquestionans($qid,$opttype)
	{
		
		$Query = sprintf("SELECT * FROM cme_articles_questions_ans WHERE qid ='%s' ORDER BY optrank ASC ",$this->setEscape($qid));	
	
		$res= mysql_query($Query);
		while($r = mysql_fetch_array($res))
		{
			$anstext = $r['ans_text'];
			$ansId = $r['ans_id'];
			$optrank = $r['optrank'];
			
			if($opttype=='Multiple')
			{
				echo '
					<tr>
						<td>
							<input type="checkbox" name="ans['.$ansId.']" value="'.$optrank.'" id="ans[]"  onclick="submit()">
							<input type="hidden" name="ansID['.$ansId.']" id="ansID[]" value="'.$ansId.'" >
						</td>
						<td>'.$anstext.'</td>
					</tr>
				';
			}
			elseif($opttype=='Single')
			{
				echo '
					<tr>
					  <td>
					  <input type="radio" name="ans['.$ansId.']" value="'.$optrank.'" id="ans[]"  onclick="submit()">
					  <input type="hidden" name="ansID['.$ansId.']" id="ansID[]" value="'.$ansId.'">
					  </td>
					  <td>'.$anstext.'</td>
					</tr>
				
				';
			}
			
		}
		return;
	}
	
	public function getreference($artID)
	{
		
		$Query = sprintf("SELECT * FROM cme_article_references WHERE art_id ='%s' ",$this->setEscape($artID));	
	
		$res= mysql_query($Query);
		$c=1;
		while($r = mysql_fetch_array($res))
		{
			$ref = $r['reference'];
			$refID = $r['refId'];
			$reflink = $r['reflink'];
			if(isset($reflink))
			{
				$link='<a href="'.$reflink.'" target="_blank" title="Go to '.$ref.'"><img style="border: 0;"  src="images/arrow.gif" width="16" height="14" alt="Go to" /></a>';
			}
			else
			{
				$link='';
			}
			echo '
						
						<tr>
							<td valign="top">'.$c.'</td>
							<td >'.$ref.''.$link.'</td>
							<td align="left"></td>
						</tr>
						';
		$c++;
		}
		return;
	}

	public function check_if_article_has_questions($article)
	{
		
		$Query = sprintf("SELECT cme_articles_questions.qid
						FROM
							cme_articles_questions
						INNER JOIN cme_articles ON (cme_articles_questions.art_id = cme_articles.art_id) WHERE cme_articles.art_id='%s'",$article);
			
		$res= mysql_query($Query);
		if(mysql_num_rows($res)>1)
		{	
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function check_if_has_attempted($article,$user)
	{
	
		$Query = sprintf("SELECT * FROM cme_user_certs WHERE activity_id='%s' AND usrid=%s",$article,$user);	
		
		$res= mysql_query($Query);
		if(mysql_num_rows($res)>0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function post_cert_details($article,$user)
	{
		
		$credits = $this->get_article_credits($article);
		$certno = $this->create_attemp_certificate_number($article,$user);
		$runnum = $this->get_cert_next_num();
		
		$Query = sprintf("INSERT INTO cme_user_certs (certno,runnumber,usrid,articleid,dateattemp,credits) VALUES ('%s',%s,%s,'%s',NOW(),%s)",$certno,$runnum,$user,$article,$credits);	
		$res= mysql_query($Query);
		/*if($r = $res->FetchRow())
		{
			
		}*/
		
		return ;
	}

	public function get_article_credits($article)
	{
		global $db;
		
		$credits=0;
		
		$Query = sprintf("SELECT credits FROM cme_article_info WHERE artcleid ='%s'",$article);	
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$credits= $r['credits'];
		}
			
		return $credits;
	}

	public function get_cert_next_num()
	{
		global $db;
		
		$Query = sprintf("SELECT MAX(runnumber) as runx FROM cme_user_certs ");	
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$nextnum= $r['runx'] +1;
		}
		else
		{
			$nextnum =1;
		}
		
		return $nextnum;
	}

	public function create_attemp_certificate_number($article,$user)
	{
		global $db;
		
		$Query = sprintf("SELECT MAX(runnumber) as runx FROM cme_user_certs");	
		$res= mysql_query($Query);
		if($r = mysql_fetch_array($res))
		{
			$runnum= $r['runx'] +1;
		}
		else
		{
			$runnum =1;
		}
		
		$certno = $article.$user.'_'.$runnum;
		return $certno;
	}

}


?>

