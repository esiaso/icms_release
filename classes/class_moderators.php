<?php

class manageModerators
{
	public function get_providers_moderators($provider_id)
	{
		echo '<table width="100%" id="hor-zebra">
				<thead>
					<tr style="background:#C8D4E0; color:#000000;">
							<th>Name</th>
							<th>Speciality</th>
							<th>Activities</th>
							<th></th>
					</tr>
				</thead>
				
				<tbody>';
				$SQLGet =sprintf("SELECT
									DISTINCT(CONCAT(cme_user_title.usertitle,' ', cme_users.first_name,' ', cme_users.last_name,' ', cme_users.other_names)) AS fullname							
									, cme_users.portrait,cme_users.user_id
								FROM
									cme_provider_moderators
									INNER JOIN cme_users ON (cme_provider_moderators.user_id = cme_users.user_id)
									LEFT JOIN cme_user_title ON (cme_users.title = cme_user_title.ttid)
								WHERE cme_provider_moderators.provider_id=%s",$provider_id);
				$res = mysql_query($SQLGet);
				if(mysql_num_rows($res)>0)
				{
					$c=0;
					while($r = mysql_fetch_array($res))
					{
					require_once("class_user_profile.php");
					$UserProfile= new UserProfile;
					$UserProfile->get_default_user_speciality($r['user_id']);
					
					$c++;
					$rowclass= $c%2 ? 'class="even"' : '';
					echo '<tr '.$rowclass.'>
							<td><a href="#" title="View Details">'.$r['fullname'].'</a></td>
							<td>'.$UserProfile->speciality.'</td>
							<td><a href="#" >5</a></td>
							<td><a href="#" title="View"><img src="images/icons/view.png" alt="View" /></a> <a href="#" title="Send Mail"><img src="images/icons/msg_email.gif" alt="Send Mail" /></a> <a href="#" title="Remove"><img src="images/icons/delete.png" alt="Remove" /></a></td>
						</tr>';
					}
				}
				else
				{
					echo '<tr>
							<td colspan="4">No records found</td>							
						</tr>';
				}
				echo '</tbody>
			</table>';
		
		return;
	}
	
	public function check_if_moderator($provider_id,$user_id)
	{
		
		$SQLGet =sprintf("SELECT * FROM cme_provider_moderators
								WHERE provider_id=%s AND user_id=%s",$provider_id,$user_id);
		$res = mysql_query($SQLGet);
				if(mysql_num_rows($res)>0)
					return true;
					else
						return false;
					
	}
	
	public function add_moderator($provider_id,$user_id)
	{
		require_once('class_roles.php');
		$SQLGet =sprintf("INSERT INTO cme_provider_moderators (provider_id,user_id,date_added)
								VALUES(%s,%s,Now())",$provider_id,$user_id);
		if(mysql_query($SQLGet))
			{
				Role::insertUserRoles($user_id, 4);
				return true;
			}
			else
				return false;
					
	}
	public function delete_moderator($provider_id,$user_id)
	{
		require_once('class_roles.php');
		
		$SQLGet =sprintf("DELETE  FROM cme_provider_moderators
								WHERE provider_id=%s AND user_id=%s",$provider_id,$user_id);
		if(mysql_query($SQLGet))				
			{
				Role::deleteUserRoles($user_id);
				return true;
			}
			else
				return false;
					
	}
	
	
}