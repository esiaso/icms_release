<?php
function get_user_expiring_activities()
{
	echo '<table width="100%">
			<thead>
				<tr style="background:#C8D4E0; color:#000000;">
					<th>Activity Name</th>
					<th>Format</th>
					 <th>Expires</th>
				</tr>
			</thead>
			
			<tbody>
			<tr>
				<td><a href="#">Adam Visit 1: 82-year-old male presenting with palpitations, shortness of breath, and dizziness</a></td>
				<td>Patient Case Study</td>
				<td>12/26/2012</td>
			</tr>
			</tbody>
		</table>';
	
	return;
}

function get_user_newest_activities()
{
	echo '<table width="100%">
			<thead>
				<tr style="background:#C8D4E0; color:#000000;">
					<th>Activity Name</th>
					<th>Format</th>
					 <th>Expires</th>
				</tr>
			</thead>
			
			<tbody>
			<tr>
				<td><a href="#">The Promise of HDL Modulation in Cardiovascular Risk Reduction</a></td>
				<td>Grand Round</td>
				<td>01/26/2012</td>
			</tr>
			</tbody>
		</table>';
	
	return;
}
function get_provider_users()
{
	echo '<table width="100%">
			<thead>
				<tr style="background:#C8D4E0; color:#000000;">
					<th>Name</th>
					<th>Speciality</th>
					 <th></th>
				</tr>
			</thead>
			
			<tbody>
			<tr>
				<td><a href="#" title="View Details">Dr. James Kananu</a></td>
				<td>Cardiologist</td>
				<td><a href="#" title="View"><img src="../images/icons/view.png" alt="View" /></a> <a href="#" title="Send Mail"><img src="../images/icons/msg_email.gif" alt="Send Mail" /></a> <a href="#" title="Remove"><img src="../images/icons/delete.png" alt="Remove" /></a></td>
			</tr>
			</tbody>
		</table>';
	
	return;
}

?>
