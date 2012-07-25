<?php

class Authors
{
	public function get_providers_authors()
	{
		echo '<table width="100%" id="mytable">
				<thead>
					<tr style="background:#C8D4E0; color:#000000;">
							<th>Name</th>
							<th>Speciality</th>
							<th>Articles</th>
							<th></th>
					</tr>
				</thead>
				
				<tbody>
				<tr>
					<td><a href="#" title="View Details">Dr. James Kananu</a></td>
					<td>Cardiologist</td>
					<td><a href="#" >5</a></td>
					<td><a href="#" title="View"><img src="../images/icons/view.png" alt="View" /></a> <a href="#" title="Send Mail"><img src="../images/icons/msg_email.gif" alt="Send Mail" /></a> <a href="#" title="Remove"><img src="../images/icons/delete.png" alt="Remove" /></a></td>
				</tr>
				</tbody>
			</table>';
		
		return;
	}
}