<?php
	ini_set('display_errors',1); 
 	error_reporting(E_ALL);

	session_start();
	include_once('../config.php');
	include_once('../include/dbcon.php');
	include('../functions/system.php');
	include('../functions/providers.php');
	include('../functions/menu_manager.php');
	require("../classes/class.phpmailer.php");	
	include('../functions/function_mail.php');
	include('../include/page_routing.php');
	include('../classes/activity_functions.php');
	include('../classes/class_functions.php');
	require_once("../classes/class_registration_provider.php");
	require_once ("../classes/class_registration.php");
	$registration =new Registration();
	$providerRegistration = new providerRegistration();
	
	require_once ("../classes/class_user_profile.php");
	$userProfile =new UserProfile();
	
	require_once ("../classes/class_system.php");
	$system =new System();
	
	require_once ("../classes/class_error_messages.php");
	$error = new ErrorMessage();
	
	$Group= 'board';
	
	$mod='';
	$view='';
	$cat='';
	$val= '';
	
	
	function get_separator()	
	{
		$string = '|';
		$salt = sha1('Synergy2010@@19811234'); 
        $salt = substr($salt, 0, 4); 
        $hash = base64_encode( $string . $salt ); 
		
     	return  $hash;
	}
	
	if(isset($_GET['k']))
	{
		$separator = get_separator();	
		$clean_link = EscapeData($_GET['k']);
		$link = base64_decode($clean_link);
		//echo $link;
		list($mod,$view,$cat,$opt)= explode($separator, $link);
		
		if(!isset($opt) || $opt=='')
			$opt='';
		if(!isset($cat) || $cat=='')
			$cat='';
		if(!isset($val) || $val=='')
			$val='';
				
		if(isset($_GET['d']))
				$d = base64_decode($_GET['d']);		
			else
				$d = '';	
		
	}
	else
	{
		$mod =null;
		$view=null;
	}
	if(isset($_GET['dk']))
	{
		if ($_GET['dk']=="logout" && isset($_SESSION['user_id'])) {
			
			require_once("../classes/class_logout.php");
			//require_once ("../classes/class_error_messages.php");
			$logout=new Logout;			
			$logout->SetCredentials($_SESSION['user_id']);
			//$result=$logout->ExecuteLogout();
			if ($logout->ExecuteLogout()) 
			{ 
				
			}
			else
			{
				$error = new ErrorMessage();
				$error->setShowMessage(true);
				$error->getMessage('Error: Something wrong happened!!','error');
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="msvalidate.01" content="9CEE6B922342E2A00F1D0FF85D3BEB22" />
<meta name="google-site-verification" content="GR_1G0d0_Hprsrh2j5ebcpziF4aHhXmaI1-Tucluut8" />
<link rel="shortcut icon" href="../favicon.ico">
<script src="../js/jquery.tools.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery-1.7.1.js"></script>
<script src="../js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/dropmenu.js"></script> 
<script>
$(document).ready(function(){
	$("#nav-one").dropmenu();
});

function __doPostBack(eventTarget, eventArgument) {
	var url = document.location ;
	if(eventTarget!=url)
	{
		window.location = eventTarget;
	}	
}

</script>
<link rel="stylesheet" id="smthemenewprint-css"  href="../css/menu.css" type="text/css" />
<link href="../css/dateinput.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/home_page.css" />
<link rel="stylesheet" href="../css/form.css" />
<link rel="stylesheet" href="../css/contents.css" />
<link href="../css/form.import.css" rel="stylesheet" type="text/css" />
<link href="../css/css.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/my_grid.css" />
<link rel="stylesheet" href="../css/buttons.css" />
<title>iCMS</title>
</head>

<body>
<div id="outerWrap">
<!--<div id="background_grey">&nbsp;</div>-->
<div id="layout">
    <div id="topNavi">
        <ul id="multi-ddm">
                <li class="first"><a title="Home" href="../index.php">Home</a></li>
                <li><a title="About Integrated National CPD Program" href="index.php?dk=about">About iCMS</a></li>				
                <li><a title="" href="index.php?dk=contactus">Contact Us </a>
            
                <li><a title="Resource Centre | Blog, FAQs and Help" href="index.php?dk=resources">Resource Centre</a>
                    <ul>
                        <li><a title="Blog" href="#">Blog</a></li>	
                        <li><a title="Faqs" href="index.php?dk=faqs">White Papers</a></li>
                        <li><a title="Help" href="#">Links</a></li>
                    </ul>		
                </li>
            	         
                <?php 
				if(isset($_SESSION['user_sessionid']))
				{
					$GLOBALS['userProfile']->getUserVCard($_SESSION['user_id']);
					$user_name = $GLOBALS['userProfile']->fullname;
					$userDetail = isset($user_name) ? $user_name : $_SESSION['email'];
					echo '<li style="font-size:10px;"><a>Welcome,'.$userDetail.'</a></li>';
					echo '<li><a href="index.php?dk=logout">Sign Out</a></li>';
				}
				
				?>
        </ul> 
        <div class="cyn_search">
            <form action="" method="post">
                <div class="search">
                    <input name="searchword" id="mod_search_searchword" maxlength="20" alt="Search" class="inputbox" type="text" size="20" value="search..." onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value=='search...') this.value='';">
                </div>
                <input type="hidden" name="task" value="search">
                <input type="hidden" name="option" value="com_search">
                <input type="hidden" name="Itemid" value="1">
            </form>
         </div>
    </div>
 <!-- End topNavi-->
<div class="header"></div>
<?php
if(isset($_SESSION['wkgroup']) && $_SESSION['wkgroup']=='board')
{
	?>
<div id="banner">
<div id="header" class="">
	<ul id="nav-one" class="dropmenu"> 
        <li><a href="index.php">Dashboard</a></li> 
        <li><a href="#">Applications <small><?php echo $registration->get_new_application_request().' New';?></small></a>
        	<ul>      
                <li><a href="index.php?k=<?php echo base64_encode('admin'.get_separator().'new_registrations'.get_separator().''.get_separator());?>">New </a></li>  		
                <li><a href="index.php?k=<?php echo base64_encode('admin'.get_separator().'received'.get_separator().''.get_separator());?>">Received</a></li>
                <li><a href="index.php?k=<?php echo base64_encode('admin'.get_separator().'processing'.get_separator().''.get_separator());?>">In Processing</a></li>
                <!--<li><a href="index.php?k=<?php //echo base64_encode('admin'.get_separator().'approve'.get_separator().''.get_separator());?>">Approve/Reject</a></li>-->
            </ul> 
        </li>
        <li><a href="#">Manage Users</a>
             <ul>
             	<li><a href="index.php?k=<?php echo base64_encode('admin'.get_separator().'usr_create'.get_separator().''.get_separator());?>">Create New User</a></li>       		
        		<li><a href="index.php?k=<?php echo base64_encode('admin'.get_separator().'usr_list'.get_separator().''.get_separator());?>">Users List</a></li>
            	
             </ul>
        </li>
        <li><a href="#">Manage Providers</a>
             <ul>
             	<li><a href="index.php?k=<?php echo base64_encode('provider'.get_separator().'newprovider'.get_separator().''.get_separator());?>">Add New Provider</a></li>
                <li><a href="index.php?k=<?php echo base64_encode('provider'.get_separator().'provderlist'.get_separator().''.get_separator());?>">Providers List</a></li>
                <li><a href="index.php?k=<?php echo base64_encode('admin'.get_separator().'coordinators'.get_separator().''.get_separator());?>">Manage Coordinators</a></li>
             </ul>
        </li>
        <li><a href="#">Monitor Activities</a>
            <ul>      
                <li><a href="#">Create New</a></li>  		
                <li><a href="#">List</a></li>
                <li><a href="#">Expiring</a></li>
            </ul>      
    	</li>
    	<li><a href="#">Activities Settings</a>
        	       <ul > 
                    <li><a href="index.php?k=<?php echo base64_encode('admin'.get_separator().'activity_type'.get_separator().''.get_separator());?>">Activity Types</a></li>
                    <li><a href="index.php?k=<?php echo base64_encode('admin'.get_separator().'activity_type_add'.get_separator().''.get_separator());?>">New Activity Types</a></li>
                    <li><a href="index.php?k=<?php echo base64_encode('admin'.get_separator().'delivery_mode'.get_separator().''.get_separator());?>">Delivery Modes</a></li>
                    <li><a href="index.php?k=<?php echo base64_encode('admin'.get_separator().'activity_lvl'.get_separator().''.get_separator());?>">Activity Levels</a></li>
                </ul>           
    	</li>
  		<li><a href="#">Help</a></li>
  </ul>     
</div>
</div>

<div id="white_box">
    <div id="shade">&nbsp;</div>
    <div id="content">

    
    <?php
               get_board_pages($GLOBALS['mod'],$GLOBALS['view']);
	  ?>
   
    </div>
</div>
<?php
}
else
{
	echo '<div id="white_box">
			<div id="shade">&nbsp;</div>
			<div id="content">';
			include('../modules/mod_login/board_login.php');
	echo ' </div>
		</div>';
}
?>
<div id="white_box_bott"></div><!-- /content -->			
<div id="footer_spacer">
    &nbsp;
</div><!-- /footer -->
</div>
<!-- End of Layout-->

</div>

<!--End of OuterWrap--> 

 <div id="footer_full">
   <div id="footer_content">
			<div id="footer_left">
           	 <span style="float:left"><h2>Quick Links</h2></span>
             <br />
             <br />
                <a href="http://www.medsynergy.info" target="_blank">Home</a>
                <a href="http://www.medsynergy.info/home/index.php?option=com_content&amp;view=article&amp;id=2&amp;Itemid=59" target="_blank">About Synergy Informatics</a>
                <a href="http://www.medsynergy.info" target="_blank">Medical Education</a>
                <a href="http://www.medsynergy.info" target="_blank">Medical Solutions</a>
                <a href="http://www.medsynergy.info" target="_blank">Research Services</a>
                <a href="http://www.medsynergy.info" target="_blank">IT Consultancy</a>
                <a href="http://www.medsynergy.info/home/index.php?option=com_contact&amp;view=contact&amp;id=1&amp;Itemid=60" target="_blank">Contact Us</a>
                <a href="http://www.medsynergy.infohttp://www.medsynergy.info/home/index.php?option=com_content&amp;view=article&amp;id=6&amp;Itemid=61" target="_blank">Help</a>
                <span class="copyright">iCMS  copyright © 2012</span>
			</div>
			<div id="footer_middle">
				
			</div>
			<div id="footer_right">
      		</div>
		</div>
</div>


<!--[if IE]>
<div class="ie_cont">
<div id="iesix">
<h1>iCPDKenya</h1>
<br>
<p class="large">
Sorry, Internet Explorer doesn't support the technology used on
<br>
the iCMS/iCPDKenya web interface.
</p>
<p>
iCMS/iCPDKenya has been built using the latest web based technology, allowing you to participate in the CPD activities with simplicity and ease. The web interface requires that extra kick that is only available on the browsers listed below. You will need to use one of the browsers listed here to participate in the CPD activities.
<br>
<br>


Browsers we recommend:
</p>
<div class="iesix_browser">
<a class="safari" href="http://www.apple.com/safari/download/">
Safari
</a>
<a class="firefox" href="http://www.mozilla.com/firefox/">
Firefox
</a>
<a class="chrome" href="http://www.google.com/chrome/">
Chrome
</a>
</div>
</div>
</div>
<![endif]-->
</body>
</html>