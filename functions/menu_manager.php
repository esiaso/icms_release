<?php

//Menu Manager
//mgroup= module group
function my_menu($mgroup)
{
	if($mgroup=='public')
	{		
	if(isset($_SESSION['user_sessionid']))
					{
						echo '
							<ul id="jsddm">
								<li><a href="index.php">Home</a></li>
								<li><a href="index.php" id="menu_act">Activities</a></li>								
								<li><a href="index.php" id="menu_diary">My Portfolio</a></li>
								<li><a href="index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'" id="my_account">My Account</a></li>
								<li><a href="index.php?k='.base64_encode('public'.get_separator().'faq'.get_separator().''.get_separator()).'">FAQ</a></li>
					</ul>
					
					<div id="activities" class="megamenu">        
						<div class="column" style="text-align:left;">
							<!--<h3>CME Activities</h3>-->
							<ul>
								<li><a href="index.php?k='.base64_encode('activities'.get_separator().'extlst'.get_separator().''.get_separator()).'">Offline/Live Activities</a></li>
								<li><a href="?k='.base64_encode('activities'.get_separator().'onlinelst'.get_separator().''.get_separator()).'">Online Activities</a></li>
							</ul>
						</div>
					</div>
					
									
					<div id="mydiary" class="megamenu">        
						<div class="column" style="text-align:left;">							
						<ul>
							<li><a href="?k='.base64_encode('diary'.get_separator().'portfolio'.get_separator().''.get_separator()).'">My Activities</a></li>
							<li><a href="?k='.base64_encode('diary'.get_separator().'sdiary'.get_separator().''.get_separator()).'">My Diary</a></li>
							<li><a href="?k='.base64_encode('diary'.get_separator().'cert'.get_separator().''.get_separator()).'">My Certificates</a></li>
							 <li><a href="?k='.base64_encode('diary'.get_separator().'apply'.get_separator().'tpc'.get_separator()).'">Re-Licensure </a></li>
							 <li><a href="?k='.base64_encode('diary'.get_separator().'claim'.get_separator().'tpc'.get_separator()).'">Claim Credits</a></li>
						</ul>
						</div>
					</div>
					';
				}
				else
				{
					echo '<ul id="jsddm">
							<li><a href="index.php">Home</a></li>
							<li><a href="index.php" id="menu_act">Activities</a></li>
							<li><a href="index.php" id="menu_reg">Registration</a></li>
							<li><a href="index.php?k='.base64_encode('public'.get_separator().'faq'.get_separator().''.get_separator()).'">FAQ</a></li>
						</ul>
					
					<div id="activities" class="megamenu">        
						<div class="column" style="text-align:left;">
							<!--<h3>CME Activities</h3>-->
							<ul>
								<li><a href="index.php?k='.base64_encode('activities'.get_separator().'extlst'.get_separator().''.get_separator()).'">Offline/Live Activities</a></li>
								<li><a href="?k='.base64_encode('activities'.get_separator().'onlinelst'.get_separator().''.get_separator()).'">Online Activities</a></li>
							</ul>
						</div>
					</div>
					
					<div id="registration" class="megamenu">        
						<div class="column" style="text-align:left;">
							<ul>
								<li><a href="?k='.base64_encode('registration'.get_separator().'newreg'.get_separator().''.get_separator()).'">New Registration</a></li>
								<li><a href="?k='.base64_encode('registration'.get_separator().'track'.get_separator().''.get_separator()).'">Track Registration</a></li>								 
							</ul>
						</div>
					</div>
					
					';
				}
					
	}
	elseif($mgroup=='provider')
	{
		if(isset($_SESSION['sid']))
		{
		echo '
					<ul id="jsddm">
						<li><a href="index.php">Home</a></li>
						<li><a href="?mod=activities&view=act" id="menu_act">Activities</a></li>
						<li><a href="?mod=contents&view=pnel" id="menu_accre">Accreditation</a></li>
						<li><a href="index.php?k='.base64_encode('provider'.get_separator().'speclst'.get_separator().''.get_separator()).'" id="menu_sp">Specilities</a></li>
					   <!-- <li><a href="?mod=contents&view=art" id="menu_att" >Activities</a></li>-->
					   <li><a href="?mod=contents&view=set">My Profile</a></li>
						<li><a href="?mod=contents&view=faq">FAQ</a></li>
					</ul>
				
					<div id="activities" class="megamenu">        
						<div class="column" style="text-align:left;">
							<!--<h3>CME Activities</h3>-->
							<ul>
								<li><a href="index.php?k='.base64_encode('provider'.get_separator().'provmyext'.get_separator().''.get_separator()).'">Offline/Live Activities</a></li>
								<li><a href="index.php?k='.base64_encode('provider'.get_separator().'provmyonline'.get_separator().''.get_separator()).'">Online Activities</a></li>
								<li><a href="index.php?k='.base64_encode('provider'.get_separator().'events_list'.get_separator().''.get_separator()).'">My Events</a></li>
							</ul>
						</div>
					</div>
					
					<div id="accreditions" class="megamenu">        
						<div class="column" style="text-align:left;">
							<!--<h3>CME Activities</h3>-->
							<ul>
								<li><a href="index.php?k='.base64_encode('provider'.get_separator().'accredlist'.get_separator().''.get_separator()).'">Current Applications</a></li>
								<li><a href="index.php?k='.base64_encode('provider'.get_separator().'accredlistpast'.get_separator().''.get_separator()).'">Past Applications</a></li>
							</ul>
						</div>
					</div>
					
					
					';
	?>				
					<div id="speciality" class="megamenu">

	<?php
    	 $Queryq= sprintf('SELECT * FROM cme_specialities ');
           
            $resq = mysql_query($Queryq);
           
            $count = mysql_num_rows($resq);
			
            $c =0;
            while($count >= $c)
            {
                if($c%5==0)
                {
                    $cc=$c;
                    if($cc%3!=0)
                    {
                    ?>
                    <div class="column" style="text-align:left;">
                    <!--<h3>CME Specialities</h3>-->
                     <hr />
                        <?php
                        $Query= sprintf('SELECT * FROM cme_specialities LIMIT '.$cc.', 5');
                        //echo $Query;
                        $res = mysql_query($Query);
                        while($r = mysql_fetch_array($res))
                        {
                            $speci =$r['sp_name'];
                            $id =$r['sp_id'];
                                ?>						
                                <ul>
                                     <li><a href="index.php?k=<?php echo base64_encode('provider'.get_separator().'specview'.get_separator().''.get_separator());?>&spid=<?php echo $id;?>"><?php echo $speci;?></a></li>
                                </ul>
                                <?php
                            
                        }
                        ?>
                    </div>
                
                <?php
                    }
                    else
                    {
                ?>
            <br style="clear: left" /> 
				<div class="column" style="text-align:left;">
				<!--<h3>CME Specialities</h3>-->
                <hr />
					<?php
					 $Query= sprintf('SELECT * FROM cme_specialities LIMIT '.$cc.', 5');
                        
                        $res = mysql_query($Query);
                        while($r = mysql_fetch_array($res))
                        {
                            $speci =$r['sp_name'];
                            $id =$r['sp_id'];
                                ?>						
                                <ul>
                                     <li><a href="index.php?k=<?php echo base64_encode('provider'.get_separator().'specview'.get_separator().''.get_separator());?>&spid=<?php echo $id;?>"><?php echo $speci;?></a></li>
                                </ul>
							<?php
						
					}
					//$c++;
					?>
				</div>
            
            <?php
				}
            }
			$c++;
		}
			?>
</div>
<?php
		}
	}
	
	elseif($mgroup=='board')
	{
		echo '
					<ul id="jsddm">
						<li><a href="index.php">Home</a></li>
						<li><a href="#" id="menu_act">Activities</a></li>
						<li><a href="#" id="menu_pnel">Providers</a></li>
						<li><a href="#" id="menu_sp">Accreditations</a></li>
					    <li><a href="#" id="menu_rep" >Monitoring</a></li>
						<li><a href="#">FAQ</a></li>
					</ul>
				
					<div id="activities" class="megamenu">        
						<div class="column" style="text-align:left;">
							<!--<h3>CME Activities</h3>-->
							<ul>
								<li><a href="index.php?k='.base64_encode('provider'.get_separator().'prov_ext_activ_board'.get_separator().''.get_separator()).'">External/live Activities</a></li>
								<li><a href="index.php?k='.base64_encode('provider'.get_separator().'prov_online_activ_board'.get_separator().''.get_separator()).'">Online Activities</a></li>
							</ul>
						</div>
					</div>
					
					<div id="providers" class="megamenu">        
						<div class="column" style="text-align:left;">
							<!--<h3>CPD Providers</h3>-->
							<ul>
									<li><a href="index.php?k='.base64_encode('provider'.get_separator().'provderlist'.get_separator().''.get_separator()).'">Accredited Providers</a></li>
									<li><a href="?mod=contents&view=onlinelst">Applications</a></li>
									<li><a href="?mod=contents&view=onlinelst">Manage</a></li>
								 	<li><a href="?mod=contents&view=act&cat=tpc">Activities</a></li>
								 	<li><a href="?mod=contents&view=act&cat=tpc">Co-ordinators</a></li>
							</ul>
						</div>
					</div>
					
					<div id="accreditions" class="megamenu">        
						<div class="column" style="text-align:left;">
							<!--<h3>Accredition Application</h3>-->
							<ul>
								<li><a href="?mod=contents&view=extlst">Pending</a></li>
								<li><a href="?mod=contents&view=onlinelst">Past</a></li>
								<li><a href="?mod=contents&view=act&cat=tpc">Setup</a></li>
							</ul>
						</div>
					</div>
					<div id="monitoring" class="megamenu">        
						<div class="column" style="text-align:left;">
							<!--<h3>Statistics</h3>-->
							<ul>
								<li><a href="?mod=contents&view=extlst">Provider Perfomance</a></li>
								<li><a href="?mod=contents&view=onlinelst">Users Perfomance</a></li>
								<li><a href="?mod=contents&view=act&cat=tpc">Activities Evaluation</a></li>
							</ul>
						</div>
					</div>
					';
	}
	elseif($mgroup=='admin')
	{
		echo '
					<ul id="jsddm">
						<li><a href="index.php">Home</a></li>
						<li><a href="?mod=activities&view=act" id="menu_act">Activities</a></li>
						<li><a href="?mod=contents&view=pnel" id="menu_pnel">Providers</a></li>
						<li><a href="?mod=contents&view=spec" id="menu_sp">Specilities</a></li>
					   <!-- <li><a href="?mod=contents&view=art" id="menu_att" >Activities</a></li>-->
						<li><a href="?mod=contents&view=faq">FAQ</a></li>
					</ul>
				
					<div id="activities" class="megamenu">        
						<div class="column">
							<h3>CME Activities</h3>
							<ul>
								<li><a href="?mod=contents&view=extlst">External/live Activities</a></li>
								<li><a href="?mod=contents&view=onlinelst">Online Activities</a></li>
							</ul>
						</div>
					</div>
					
					
					';
	}
	else
	{
		echo '<ul id="jsddm">
				<li><a href="index.php">Home</a></li>
				<li><a href="?mod=public&view=faq">FAQ</a></li>
			</ul>					
			';
	}
}
?>
		