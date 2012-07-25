<?php



class cpdDiaryManagement
{
	public function getMaxCPDPoints()
	{
		$cpdpoint=50;
		return $cpdpoint;
	}
	
	public function getMyCPDPoints($userID)
	{
		//To be redefined later interms of date, year, or period
		$year = date('Y');
		$SQLSELECT = sprintf('SELECT SUM(cpd_points) as points FROM cme_activity_token_claims WHERE user_id=%s AND year(claimeddate)=%s',$userID,$year);		
		$res=mysql_query($SQLSELECT);
			if($r=mysql_fetch_array($res))
			{
				$cpdpoints = $r['points'];
			}	
		return $cpdpoints;
	}
	
	public function getAverageCPDPoints()
	{
		$cpdpoint=20;
		return $cpdpoint;
	}
}
?>
