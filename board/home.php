<div id="main_container">
		<div id="main_content2">
			<h2 class="title6">BOARD DASHBOARD</h2>

<table width="100%">
    <tr>
        <td width="50%" valign="top">
            <div style="margin:5px;">
            <div class="box" id="dashboard_diagram_totals">
                <div class="entry-edit">
                <h2 class="title7">Most Active Providers:</h2>           
                <div class="right_articles">
                <table width="100%" border="0" class="nostyle" id="hor-zebra">
                    <thead>
                        <tr style="background:#C8D4E0; color:#000000;">
                            <th width="63%">Provider Name</th>
                            <th width="33%"> Last Activity Date</th>
                        </tr>
                    </thead>
                    <tbody>
                     	<?php
                        	echo get_active_providers();
                    	?>
                    </tbody>
                </table>
                </div>
             </div> 
           <p >  <a  href="index.php?k=<?php echo base64_encode('provider'.get_separator().'newprovider'.get_separator().''.get_separator());?>" title="New"> <img src="images/icons/add.png" alt="New" /> Create New Provider</a> | <a   href="index.php?k=<?php echo base64_encode('provider'.get_separator().'provderlist'.get_separator().''.get_separator());?>" title="View All">View All Providers <img src="images/arrow.gif" alt="View" /></a></p>
                </div>
               
            </div> 
        </td>
        <td width="50%" valign="top">
            <div style="margin:5px;">
            <div class="box" id="dashboard_diagram_totals">
                <div class="entry-edit"> 
                <h2 class="title7">Current Activities:</h2>
                <div class="right_articles">
                  <!--<table width="100%" border="0" class="nostyle" id="hor-zebra">
                    <thead>
                        <tr style="background:#C8D4E0; color:#000000;">
                            <th width="40%">Activity</th>
                            <th width="35%">Provider Name</th>
                            <th width="25%">Activity Date</th>
                        </tr>
                    </thead>
                    <tbody>-->
                     <?php
                        echo get_current_activities();
                    ?>
                    <!--</tbody>
                </table>-->
                </div>
              
                 </div><a href="#" title="View All">View All Activities <img src="images/arrow.gif" alt="View" /></a>
                </div>
                
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div style="margin:5px;">
            <div class="box" id="dashboard_diagram_totals">
                <div class="entry-edit">
                <h2 class="title7">Provider Coordinators</h2>           
                <div class="right_articles">
                  <table width="100%" border="0" class="nostyle" id="hor-zebra">
                    <thead>
                        <tr style="background:#C8D4E0; color:#000000;">
                            <th width="41%">Coordinator Name</th>
                            <th width="59%">Provider Name</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?php
                       echo get_provider_coordinators();
                    ?>
                    </tbody>
                </table>
                </div>
             </div>
             <!--<a href="index.php?k=<?php //echo base64_encode('admin'.get_separator().'coordinators'.get_separator().''.get_separator());?>" title="Add Coordinator"> <img src="images/icons/user-add.png" alt="New" /> Assign Coordinator</a> |--> <a href="#" title="View All">View All <img src="images/arrow.gif" alt="View" /></a>
                </div>
                
            </div> 
        </td>
        <td valign="top">
            <div style="margin:5px;">
            <div class="box" id="dashboard_diagram_totals">
                <div class="entry-edit"> 
                <h2 class="title7">Accreditation Applications</h2>
                <div class="right_articles">
                    <table width="100%" border="0" class="nostyle" id="hor-zebra">
                    <thead>
                        <tr style="background:#C8D4E0; color:#000000;">
                           <th width="40%">Activity</th>
                            <th width="35%">Provider Name</th>
                            <th width="25%">Application Date</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?php
                      echo get_accreditation_applications();
                    ?>
                    </tbody>
                </table>
                </div>
              		
                 </div>
                  <a href="#" title="View All">View All <img src="images/arrow.gif" alt="View" /></a>
                </div>
            </div>
        </td>
    </tr>
     <tr>
        <td>
            <div style="margin:5px;">
            <div class="box" id="dashboard_diagram_totals">
                <div class="entry-edit">
                <h2 class="title7">Users Statistics</h2>           
                <div class="right_articles">
                <table width="100%" border="0" class="nostyle" id="hor-zebra">
                    <thead>
                        <tr style="background:#C8D4E0; color:#000000;">
                           <th width="40%">Month/Year</th>
                            <th width="35%">Count</th>
                            <th width="25%">Activities</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?php
                       echo get_monthly_user_activities();
                    ?>
                    </tbody>
                </table>
                </div>
             </div>
             <a href="#" title="Add User"> <img src="images/icons/user-add.png" alt="New" /> Add New User</a> | <a href="#" title="View All">View All <img src="images/arrow.gif" alt="View" /></a>
                </div>
                
            </div> 
        </td>
        <td>
            <div style="margin:5px;">
            <div class="box" id="dashboard_diagram_totals">
                <div class="entry-edit"> 
                <h2 class="title7">Provider Statictics</h2>
                <div class="right_articles">
                     <table width="100%" border="0" class="nostyle" id="hor-zebra">
                    <thead>
                        <tr style="background:#C8D4E0; color:#000000;">
                           <th width="40%">Month/Year</th>
                            <th width="35%">Count</th>
                            <th width="25%">Activities</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?php
                       echo get_provider_statistics();
                    ?>
                    </tbody>
                </table>
                </div>
              		
                 </div>
                  <a href="#" title="View All">View All <img src="images/arrow.gif" alt="View" /></a>
                </div>
            </div>
        </td>
    </tr>
</table>
</div>
</div>
