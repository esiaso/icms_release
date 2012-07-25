<?php
//Question Manager

function get_article_title($artID)
{
	global $db;
	$Query = sprintf("SELECT * FROM cme_articles WHERE art_id ='%s' ",escapestring($artID));	
	
	$res= mysql_query($Query); 
	if($r = mysql_fetch_array($res)) 
	{
		$artname = $r['art_name'];
		echo $artname;			
	}
	return;
}

/*function get_article_credits($artID)
{
	global $db;
	$Query = sprintf("SELECT * FROM cme_article_info WHERE artcleid ='%s'",escapestring($artID));	
	
	$res= mysql_query($Query);
	if($r = mysql_fetch_array($res))
	{
		$credits = $r['credits'];
				
	}
	return $credits;
}*/

function get_article_series($artID)
{
	global $db;
	$Query = sprintf("SELECT * FROM cme_article_series t
					 JOIN cme_article_info a ON t.seriesId = a.series
					 WHERE a.artcleid ='%s' ",escapestring($artID));		
	
	
	$res= mysql_query($Query);
	if($r = mysql_fetch_array($res))
	{
		$seriesDesc = $r['seriesDesc'];
		echo $seriesDesc;			
	}
	return;
}

function getarticlebody($artID)
{
	global $db;
	$Query = sprintf("SELECT * FROM cme_article_body t
					 JOIN cme_articles a ON t.art_id = a.art_id
					 WHERE a.art_id ='%s' ",escapestring($artID));	
	
	$res= mysql_query($Query);
	if($r = mysql_fetch_array($res))
	{
		$artname = $r['art_name'];
		$artbody = $r['bdtext'];
		
		echo '<tr>
				<td valign="top"><h2>'.$artname.'</h2><hr></td>
			</tr>
			
			<tr>
				<td valign="top">'.$artbody.'</td>
			</tr>';
			
	}
	return;
}

function check_questions($artID)
{
	global $db;
	
	$Query = sprintf("SELECT * FROM cme_articles_questions WHERE art_id ='%s' AND display='Yes' ",escapestring($artID));	

	$res= mysql_query($Query);
	$counts = mysql_num_rows($res);
	return $counts;
	
}


function getquestions($artID,$lim)
{
	global $db;
	
	$Query = sprintf("SELECT * FROM cme_articles_questions WHERE art_id ='%s' AND display='Yes' ORDER BY qrank ASC LIMIT %s, 1",escapestring($artID),escapestring($lim));	


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
				getquestionans($qID,$optType);
		echo '</table>';
		
		echo '</td>
			</tr>
		';
	}
	return;
	
}
function getnumquestion($artID)
{
	global $db;
	$counts =0;
	$qnum =0;
	
	$Query = sprintf("SELECT * FROM cme_articles_questions WHERE art_id ='%s'",escapestring($artID));	

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

function getarticletitle($artID)
{
	global $db;
	
	$Query = sprintf("SELECT * FROM cme_articles WHERE art_id ='%s' ",escapestring($artID));	

	$res= mysql_query($Query);
	while($r = mysql_fetch_array($res))
	{
		$title = $r['art_name'];		
		
		echo $title;
	}
	return;
	
}
function getquestionans($qid,$opttype)
{
	global $db;
	
	$Query = sprintf("SELECT * FROM cme_articles_questions_ans WHERE qid ='%s' ORDER BY optrank ASC ",escapestring($qid));	

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

function getreference($artID)
{
	global $db;
	$Query = sprintf("SELECT * FROM cme_article_references WHERE art_id ='%s' ",escapestring($artID));	

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



?>

