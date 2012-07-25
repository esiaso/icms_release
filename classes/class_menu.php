<?php
require_once "class_roles.php";
require_once "class_login.php";
require_once "class_privileged_user_old.php";

class UserMenu
{
	private $wkgroup;
	
	public function getUserMenu()
	{		
		if(isset($_SESSION['wkgroup'])){
			$this->wkgroup = $_SESSION['wkgroup'];
			$this->otherMenus();
			}
			else
				$this->defaultMenu();
	}
	
	public function defaultMenu()
	{
		echo '<div id="userbar">
			<div id="usermenu2">
				<ul>
					<li><a href="index.php?k='.base64_encode('activities'.get_separator().'default'.get_separator().''.get_separator()).'">CPD ACTIVITIES</a></li>
					<li><a href="index.php?k='.base64_encode('public'.get_separator().'resources'.get_separator().''.get_separator()).'">RESOURCES</a></li>
					<li><a href="#">REGISTRATION</a>
					<div>
						<ul>
							<li><a href="index.php?k='.base64_encode('registration'.get_separator().'newreg'.get_separator().''.get_separator()).'">AS CPD TAKER</a></li>
							<li><a href="index.php?k='.base64_encode('registration'.get_separator().'newprovider'.get_separator().''.get_separator()).'">AS CPD PROVIDER</a></li>
						 </ul>
						 </div>
					</li>
					<li class="last"><a href="index.php?k='.base64_encode('public'.get_separator().'faq'.get_separator().''.get_separator()).'">FAQs</a></li>
				</ul>
			</div>
			<div id="userprofile">
				<div id="login">
					<script language="javascript">
						function goSubmit()
						{
							document.loginform.submit();
						}
					</script>
					<form id="frmLogin" action="index.php" method="post" name="loginform">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr><td valign="top">
							<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" placeholder="Username" style="font-size:11px;">
						</td><td valign="top">
						 <input id="modlgn_passwd" type="password" name="password" class="inputbox" size="18" alt="password" placeholder="Password" style="font-size:11px;">
						</td><td valign="top">
						<input name="login" value="login" type="hidden">
						<input type="image" src="./images/login.png" width="60" height="25" onclick="goSubmit">
						</td></tr>
					</table>
					</form>
					<div class="loginbottom">
						<h2 class="forgot"><a href="index.php?k='.base64_encode('registration'.get_separator().'newreg'.get_separator().''.get_separator()).'">Create An Account</a> / <a href="index.php?k='.base64_encode('login'.get_separator().'recover'.get_separator().''.get_separator()).'">Forgot Password?</a></h2>
					</div>
				</div>
			</div>
			
		</div>
		<div id="stickyalias"></div>';
	}
	
	private function otherMenus()
	{	
		require_once ("classes/class_user_profile.php");
		$userprofile = new UserProfile;
		$userprofile->getUserVCard($_SESSION['user_id']);
		$user_name = $userprofile->fullname;
		$userDetail = isset($user_name) ? $user_name : $_SESSION['email'];
		$userRole= $userprofile->get_user_rolename($_SESSION['user_level']);
		
		if($this->wkgroup=='guest')
		{
			//'.$GLOBALS['view']=="default" ? "class=\"current\"" : "".'
		$mymenu ='';
		$mymenu.='
		<div id="userbar">
			<div id="usermenu2">
				<ul>
					<li><a href="index.php?k='.base64_encode('activities'.get_separator().'default'.get_separator().''.get_separator()).'" >CPD ACTIVITIES</a></li>
					<li><a href="index.php?k='.base64_encode('public'.get_separator().'resources'.get_separator().''.get_separator()).'">RESOURCES</a></li>
					
					<li class="last"><a href="index.php?k='.base64_encode('public'.get_separator().'faq'.get_separator().''.get_separator()).'">FAQs</a></li>
				</ul>
			</div>
			<div id="foruser">
				<div id="userprofile1">
					<ul>
						<li><a class="profile_name" href="index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'">Welcome, '.$userDetail.'</a> </li>					
						<li><a  href="#" class="drop">Working as <span>'.$userRole.'</span></a></li>
					</ul>
				</div>
				<div id="userprofile2">
					<ul>
						<li><a class="profile_settings" href="index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'">&nbsp;</a></li>
						<li><a class="profile_logout" href="index.php?dk=logout">&nbsp;</a></li>
					</ul>
				</div>
			</div>
			
		</div>
		<div id="stickyalias"></div>
		';
		
		echo $mymenu;
		}
		elseif($this->wkgroup=='public')
		{
			//'.$GLOBALS['view']=="default" ? "class=\"current\"" : "".'
		$mymenu ='';
		$mymenu.='
		<div id="userbar">
			<div id="usermenu2">
				<ul>
					<li><a href="index.php?k='.base64_encode('activities'.get_separator().'default'.get_separator().''.get_separator()).'" >CPD ACTIVITIES</a></li>
					<li><a href="index.php?k='.base64_encode('public'.get_separator().'resources'.get_separator().''.get_separator()).'">RESOURCES</a></li>
					<li ><a href="index.php?k='.base64_encode('diary'.get_separator().'portfolio'.get_separator().''.get_separator()).'">MY CPD PORTFOLIO</a></li>
					<li class="last"><a href="index.php?k='.base64_encode('public'.get_separator().'faq'.get_separator().''.get_separator()).'">FAQs</a></li>
				</ul>
			</div>
			<div id="foruser">
				<div id="userprofile1">
					<ul>
						<li><a class="profile_name" href="index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'">Welcome, '.$userDetail.'</a> </li>					
						<li><a  href="#" class="drop">Working as <span>'.$userRole.'</span></a>
						
							<div class="dropdown_2columns"><!-- Begin 2 columns container -->
    
								<div class="col_2">
									<h2>Switch To</h2>
								</div>
						
								<div class="col_2">';
								if (isset($_SESSION["sid"])) {
									$u = PrivilegedUser::getByUsername($_SESSION["email"]);
									if ($u->hasPrivilege("Public") && $_SESSION['wkgroup']!='public') {
										$mymenu.='<p><a href="index.php?wkg=public">Public Workspace</a></p>';
									}
									if ($u->hasPrivilege("Board") && $_SESSION['wkgroup']!='board') {
										$mymenu.='<p><a href="index.php?wkg=board">Board Workspace</a></p>';
									}
									if ($u->hasPrivilege("Provider") && $_SESSION['wkgroup']!='provider') {
										$mymenu.='<p><a href="index.php?wkg=provider">Provider Workspace</a></p>';
									}
								}	          
								$mymenu.='</div>
						
							</div><!-- End 2 columns container -->
						</li>
					</ul>
				</div>
				<div id="userprofile2">
					<ul>
						<li><a class="profile_settings" href="index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'">&nbsp;</a></li>
						<li><a class="profile_logout" href="index.php?dk=logout">&nbsp;</a></li>
					</ul>
				</div>
			</div>
			
		</div>
		<div id="stickyalias"></div>
		';
		
		echo $mymenu;
		}
		
		elseif($this->wkgroup=='provider')
		{
			$mymenu='';
			$mymenu.='
			<div id="userbar">
			<div id="usermenu2">
				<ul id="nav-one" class="dropmenu">
					<li><a href="./">DASHBOARD</a></li>
					<li><a href="index.php?k='.base64_encode('provider'.get_separator().'usr_application'.get_separator().''.get_separator()).'">REQUESTS <small>'.$GLOBALS['registration']->get_newly_submitted_application_to_provider($_SESSION['provider_id']).'</small></a>
					</li>
					<li><a href="#">USERS</a>
						<div>
						<ul>
							<li><a href="index.php?k='.base64_encode('admin'.get_separator().'usr_create'.get_separator().''.get_separator()).'">Create New User</a></li>
							<li><a href="index.php?k='.base64_encode('provider'.get_separator().'moderators_list'.get_separator().''.get_separator()).'">Moderators</a></li>
							<li><a href="index.php?k='.base64_encode('provider'.get_separator().'users_list'.get_separator().''.get_separator()).'">Users List</a></li>
						 </ul>
						 </div>
					</li>
					<li><a href="#">ACTIVITIES</a>
					<div>
						<ul>      
						<li><a href="index.php?k='.base64_encode('activities'.get_separator().'newext'.get_separator().''.get_separator()).'">New Offline Activity</a></li>  	
						<li><a href="index.php?k='.base64_encode('activities'.get_separator().'newonlineactivity'.get_separator().''.get_separator()).'">New Online Activity</a></li>
						<li><a href="index.php?k='.base64_encode('activities'.get_separator().'activitylist'.get_separator().''.get_separator()).'">Activity List</a></li>
					</ul> 
					</div>
					</li>
					<!--<li><a href="#">REPORTS</a>
						<div>
							<ul> 
								<li><a href="#">Online</a></li>
								<li><a href="#">Offline</a></li>
								<li><a href="#">By Access</a></li>
							</ul>
						</div>
					</li>-->
					<li class="last"><a href="index.php?k='.base64_encode('provider'.get_separator().'profile'.get_separator().''.get_separator()).'">PROFILE</a></li>
				</ul>
			</div>
			<div id="foruser">
				<div id="userprofile1">
					<ul>
						<li><a class="profile_name" href="index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'">Welcome, '.$userDetail.'</a> </li>					
						<li><a  href="#" class="drop">Working as <span>'.$userRole.'</span></a>
						
							<div class="dropdown_2columns"><!-- Begin 2 columns container -->
    
								<div class="col_2">
									<h2>Switch To</h2>
								</div>
						
								<div class="col_2">';
								if (isset($_SESSION["sid"])) {
									$u = PrivilegedUser::getByUsername($_SESSION["email"]);
									if ($u->hasPrivilege("Public") && $_SESSION['wkgroup']!='public') {
										$mymenu.='<p><a href="index.php?wkg=public">Public Workspace</a></p>';
									}
									if ($u->hasPrivilege("Board") && $_SESSION['wkgroup']!='board') {
										$mymenu.='<p><a href="index.php?wkg=board">Board Workspace</a></p>';
									}
									if ($u->hasPrivilege("Provider") && $_SESSION['wkgroup']!='provider') {
										$mymenu.='<p><a href="index.php?wkg=provider">Provider Workspace</a></p>';
									}
								}	          
								$mymenu.='</div>
						
							</div><!-- End 2 columns container -->
						</li>
					</ul>
				</div>
				<div id="userprofile2">
					<ul>
						<li><a class="profile_settings" href="index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'">&nbsp;</a></li>
						<li><a class="profile_logout" href="index.php?dk=logout">&nbsp;</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div id="stickyalias"></div>
			';
			echo $mymenu;
		}
		elseif($this->wkgroup=='board')
		{ 
		
			$mymenu='';
			$mymenu.='
			<div id="userbar">
			<div id="usermenu2">
			<ul >
				<li><a href="./">HOME</a></li>
				<li><a href="#">REQUESTS </a>
				<div>
					<ul>      
						<li><a href="index.php?k='.base64_encode('admin'.get_separator().'new_registrations'.get_separator().''.get_separator()).'">New </a></li>  		
						<li><a href="index.php?k='.base64_encode('admin'.get_separator().'received'.get_separator().''.get_separator()).'">Received</a></li>
						<li><a href="index.php?k='.base64_encode('admin'.get_separator().'processing'.get_separator().''.get_separator()).'">In Processing</a></li>
					</ul> 
					</div>
				</li>
				<li><a href="#">USERS</a>
				<div>
					<ul>
					<li><a href="index.php?k='.base64_encode('admin'.get_separator().'usr_create'.get_separator().''.get_separator()).'">Create New User</a></li>       		
					<li><a href="index.php?k='.base64_encode('admin'.get_separator().'usr_list'.get_separator().''.get_separator()).'">Users List</a></li>
					
				 </ul>
				 <div>
				</li>
				<li><a href="#">PROVIDERS</a>
				<div>
					<ul>
					<li><a href="index.php?k='.base64_encode('provider'.get_separator().'newprovider'.get_separator().''.get_separator()).'">Add New Provider</a></li>
					<li><a href="index.php?k='.base64_encode('provider'.get_separator().'provderlist'.get_separator().''.get_separator()).'">Providers List</a></li>
					<!--<li><a href="index.php?k='.base64_encode('admin'.get_separator().'coordinators'.get_separator().''.get_separator()).'">Manage Coordinators</a></li>-->
				 </ul>
				 <div>
				</li>
				<li><a href="#">REPORTS</a></li>
				<li class="last"><a href="#">SETTINGS</a>
				<div>
				<ul > 
                    <li><a href="index.php?k='.base64_encode('admin'.get_separator().'activity_type'.get_separator().''.get_separator()).'">Activity Types</a></li>
                    <li><a href="index.php?k='.base64_encode('admin'.get_separator().'activity_type_add'.get_separator().''.get_separator()).'">New Activity Types</a></li>
                    <li><a href="index.php?k='.base64_encode('admin'.get_separator().'delivery_mode'.get_separator().''.get_separator()).'">Delivery Modes</a></li>
                    <li><a href="index.php?k='.base64_encode('admin'.get_separator().'activity_lvl'.get_separator().''.get_separator()).'">Activity Levels</a></li>
					<li><a href="index.php?k='.base64_encode('admin'.get_separator().'activity_lvl'.get_separator().''.get_separator()).'">ADVANCED OPTIONS</a></li>
					<li><a href="index.php?k='.base64_encode('users'.get_separator().'roleaccess'.get_separator().''.get_separator()).'">Roles Access</a></li>
                </ul> 
				</div>
				</li>
			</ul>
			</div>
			<div id="foruser">
				<div id="userprofile1">
					<ul>
						<li><a class="profile_name" href="index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'">Welcome, '.$userDetail.'</a> </li>					
						<li><a  href="#" class="drop">Working as <span>'.$userRole.'</span></a>
						
							<div class="dropdown_2columns"><!-- Begin 2 columns container -->
    
								<div class="col_2">
									<h2>Switch To</h2>
								</div>
						
								<div class="col_2">';
									if (isset($_SESSION["sid"])) {
									$u = PrivilegedUser::getByUsername($_SESSION["email"]);
									if ($u->hasPrivilege("Public") && $_SESSION['wkgroup']!='public') {
										$mymenu.='<p><a href="index.php?wkg=public">Public Workspace</a></p>';
									}
									if ($u->hasPrivilege("Board") && $_SESSION['wkgroup']!='board') {
										$mymenu.='<p><a href="index.php?wkg=board">Board Workspace</a></p>';
									}
									if ($u->hasPrivilege("Provider") && $_SESSION['wkgroup']!='provider') {
										$mymenu.='<p><a href="index.php?wkg=provider">Provider Workspace</a></p>';
									}
								}	             
									          
								$mymenu.='</div>
						
							</div><!-- End 2 columns container -->
						</li>
					</ul>
				</div>
				<div id="userprofile2">
					<ul>
						<li><a class="profile_settings" href="index.php?k='.base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator()).'">&nbsp;</a></li>
						<li><a class="profile_logout" href="index.php?dk=logout">&nbsp;</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div id="stickyalias"></div>
			';
			
			echo $mymenu;
		}
	}
	
}