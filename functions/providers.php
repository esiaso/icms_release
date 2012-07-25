<?php
function get_active_providers()
{
	$SQLSELECT = "SELECT * FROM cme_providers WHERE cme_providers.status=4 limit 0, 5";
	$res= mysql_query($SQLSELECT);
	$c=0;
	if(mysql_num_rows($res)>0)
	{
		while($r= mysql_fetch_array($res))
		{
			$c++;
			$rowclass= $c%2 ? 'class="row even"' : 'class="row odd"';
			
			$providername = $r['pr_name'];
			$email = $r['email'];
			$address = $r['postaladdress'];
			$phone = $r['phone'];
			$contact_name = $r['contact_name'];
			
			echo '
				<tr '.$rowclass.'>
					<td><a href="index.php?k='.base64_encode('provider'.get_separator().'provpage'.get_separator().''.get_separator()).'&prid='.$r['pr_id'].'">'.$providername.'</a></td>
					<td>11/01/2011</td>
				</tr>
			';
			
		}
	}
	else
	{
		echo '
			<tr class="row even">
				<td colspan="3"> No records</td>
			</tr>
		';
	}
	return;
}

function get_current_activities()
{
	require_once('classes/class_system.php');
		$dateconvert = new System;
	
	echo '<table width="100%" id="hor-zebra">
				<thead>
					<tr style="background:#C8D4E0; color:#000000;">
						<th width="45%">Activity Name</th>
						<th width="35%">Provider Name</th>
						<th align="left">Activity Date</th>
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
										INNER JOIN cme_providers ON cme_activities.provider_id =  cme_providers.pr_id 
							WHERE expiry_date > DATE(Now()) ORDER BY release_date DESC limit 0, 5");
							
					//$this->getMessage($SQL_Query);
					
						if($res =mysql_query($SQL_Query))
						{
							$c=0;
							while($rs = mysql_fetch_array($res))
							{
								$c++;
								$mod = $c %2  ? 'even' : 'odd';
								echo '<tr class="row '.$mod.'">
											<td><a href="index.php?k='.base64_encode('activities'.$GLOBALS['separator'].'extlst'.$GLOBALS['separator'].''.$GLOBALS['separator'].'item').'&d='.base64_encode($rs['id']).'">'.$rs['activity_name'].'</a></td>
											<td>'.$rs['pr_name'].'</td>
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

function get_provider_statistics()
{
	echo '
			<tr class="row even">
				<td colspan="3"> No records</td>
			</tr>
		';
	return;	
}

function get_monthly_user_activities()
{
	echo '
			<tr class="row even">
				<td colspan="3"> No records</td>
			</tr>
		';
	return;	
}
function get_accreditation_applications()
{
	echo '
			<tr class="row even">
				<td colspan="3"> No records</td>
			</tr>
		';
	return;	
}
function get_most_activities_coordinators()
{
	echo '
			<tr class="row even">
				<td colspan="3"> No records</td>
			</tr>
		';
	return;	
}
function get_provider_coordinators()
{
	$SQLSELECT = "SELECT
						cme_users.first_name
						, cme_users.last_name
						, cme_user_title.usertitle
						, cme_providers.pr_name
						, cme_users.user_id
						, cme_providers.pr_id
					FROM
						cme_providers
						INNER JOIN cme_provider_coordinators ON (cme_providers.pr_id = cme_provider_coordinators.provider_id)
						INNER JOIN cme_users ON (cme_provider_coordinators.user_id = cme_users.user_id)
						INNER JOIN cme_user_title ON (cme_users.title = cme_user_title.ttid)
				WHERE cme_providers.status=4 limit 0, 5";
				
	$res= mysql_query($SQLSELECT);
	$c=0;
	if(mysql_num_rows($res)>0)
	{
		while($r= mysql_fetch_array($res))
		{
			$c++;
			$rowclass= $c%2 ? 'class="row even"' : 'class="row odd"';
			
			$providername = $r['pr_name'];
			$coordinator = $r['usertitle'].' '.$r['first_name'].' '.$r['last_name'];
			
			echo '
				<tr '.$rowclass.'>
					<td><a href="index.php?k='.base64_encode('users'.get_separator().'fullview'.get_separator().''.get_separator()).'&usrid='.$r['user_id'].'">'.$coordinator.'</a></td>
					<td><a href="index.php?k='.base64_encode('provider'.get_separator().'provpage'.get_separator().''.get_separator()).'&prid='.$r['pr_id'].'">'.$providername.'</a></td>
				</tr>
			';
			
		}
	}
	else
	{
	echo '
			<tr class="row even">
				<td colspan="3"> No records</td>
			</tr>
		';
	}
	return;	
}

?>