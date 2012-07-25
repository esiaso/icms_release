<?php
	session_start();
	include_once('../config.php');
	include_once('../include/dbcon.php');
	include('../functions/system.php');
	include('../functions/providers.php');
	include('../functions/menu_manager.php');
	include('../include/page_routing.php');
	include('../classes/activity_functions.php');
	include('../classes/class_functions.php');
	
	$Group= 'board';
	$mod='';
	$view='';
	$cat='';
	
	
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
		{
			$opt='';
		}
		if(!isset($cat) || $cat=='')
		{
			$cat='';
		}
		
		if(isset($_GET['d']))
		{
			$d = base64_decode($_GET['d']);
		}
		
	}
	else
	{
		$mod =null;
		$view=null;
	}
	//$mod = isset($_GET['mod']) ? EscapeData($_GET['mod']) : null;
	//$view = isset($_GET['view']) ? EscapeData($_GET['view']) : null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
<script src="../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script> 
<script src="../js/jquery.tools.min.js" type="text/javascript"></script> 
<script type="text/javascript" src="../js/jkmegamenu.js"></script>
<script type="text/javascript" src="../js/tiny_mce/tiny_mce.js"></script>

<link rel="shortcut icon" href="../images/favicon.png"> 
<link rel="stylesheet" type="text/css" media="screen" href="../css/my_css.css"  />
<link rel="stylesheet" href="../css/home_css.css" />
<link rel="stylesheet" href="../css/style.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/homesliderStyle.css" />
<link rel="stylesheet" href="../css/jkmegamenu.css" />
<link rel="stylesheet" href="../css/menu_css.css" />
<link rel="stylesheet" href="../css/menu_css.css" />
<link rel="stylesheet" href="../css/boxes.css" />
<link href="../css/dateinput.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 7]>
<style type="text/css">
html .jquerycssmenu{height: 1%;} /*Holly Hack for IE7 and below*/
</style>
<![endif]-->


<script type="text/javascript">
//jkmegamenu.definemenu("anchorid", "menuid", "mouseover|click")
//jkmegamenu.definemenu("megaanchor", "megamenu1", "mouseover");
jkmegamenu.definemenu("menu_pnel", "providers", "mouseover");
jkmegamenu.definemenu("menu_act", "activities", "mouseover");
jkmegamenu.definemenu("menu_sp", "accreditions", "mouseover");
jkmegamenu.definemenu("menu_rep", "monitoring", "mouseover");

</script>

<script type="text/javascript">
var timeout         = 500;
var closetimer		= 0;
var ddmenuitem      = 0;

function jsddm_open()
{	jsddm_canceltimer();
	jsddm_close();
	ddmenuitem = $(this).find('ul').eq(0).css('visibility', 'visible');}

function jsddm_close()
{	if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');}

function jsddm_timer()
{	closetimer = window.setTimeout(jsddm_close, timeout);}

function jsddm_canceltimer()
{	if(closetimer)
	{	window.clearTimeout(closetimer);
		closetimer = null;}}

$(document).ready(function()
{	$('#jsddm > li').bind('mouseover', jsddm_open);
	$('#jsddm > li').bind('mouseout',  jsddm_timer);});

document.onclick = jsddm_close;


</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Synergy CPD</title>

</head>

<body>
<div id="cyn_wrapper">
<div >
  
<div class="banner_bottom">
  <div class="left_strp"><img src="../images/mid_left_03.png" alt="" border="0" /></div>
      <div class="center_strp">
          <div class="mid_left_logo"><a href="/home"><img src="../images/logo.png" alt="" border="0" /></a></div>
          <div class="mid_right_logo"><a href="http://www.medicalboard.co.ke" target="_blank"><img src="../images/mpdb_logo.png" alt="" border="0" /></a>
          </div>
      </div>
  </div>
 </div> 
 <div style="border:#999999 1px solid;"> 
 <header>
 	<hgroup>
		<h1>Board Administrative Panel</h1> 
	<!--	<h2></h2>-->
	</hgroup>
 </header> 
 
      <div id="mainarea">
	         <div style="height:25px">
               <!--
                 ***************************************************
                 Dynamic Menu  
                 ***************************************************               
                 !-->
                 <?php
                 //$mgroup
				 	my_menu('board');
				 ?>
                 <!--
                 ***************************************************
                 END Menu  
                 ***************************************************               
                 !-->
               </div> 
             <div align="left" style="min-height:350px;">
              <?php
              
               get_board_pages($GLOBALS['mod'],$GLOBALS['view']);
               
              ?>
             </div>    
     	</div>
    
    <div class="clr"></div> 
	<!--<div class="footer-top"></div>  -->
   
	<footer style="border-top:#999999 1px solid; background-color:#D5F4FF;">
    <br />
		<div >
            <div class="floatLeft rightMargin" align="left" style="height:100px; color: #c1c1c1;">
                <span ><h2>Quick Links</h2></span>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Synergy Informatics</a></li>
                    <li><a href="#">Medical Education</a></li>
                    <li><a href="#">Medical Solutions</a></li>
                    <li><a href="#">Research Services</a></li>
                    <li><a href="#">IT Consultancy</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Help</a></li>
                </ul>
            </div>
    	</div>
    	<div class="rootColumn2" style="width:200px;">
             <div class="floatRight rightMargin" align="left" style="height:100px; padding-right:40px; color: #c1c1c1;">
                <span class="containerTitle"><h2>Quick links</h2></span>
                <br />
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Strategic Plan</a></li>
                    <li><a href="http://www.medicalboard.co.ke/index.php?option=com_content&view=article&id=3&Itemid=5">Downloads Forms</a></li>
                    <li><a href="#">Contact us</a></li>
                
                </ul>
             </div>
        </div>
        
        <div class="rootColumn3" style="width:200px; list-style-image:none">
            <div class="leftMargin" align="right" style="height:100px; padding-left:40px;color: #c1c1c1; list-style-image:none;">
                <span class="containerTitle"><h2>Follow us</h2></span>
                <br />
                  <ul>
                    <li><div class="social_icon_img"><a href="#"><img src="../images/fb.png" alt="Facebook" class="fb" /> Facebook</a></div></li>
                 
                    <li><div class="social_icon_img"><a href="#"><img src="../images/tw.png" alt="Twitter" class="twitter" /> Twitter</a></div></li>
                </ul>
            </div>
    	</div>
        
		<div class="rootColumn4" style="width:200px;">
            <div  align="left" style="height:100px; color: #c1c1c1;">
                <span class="containerTitle"><h2>Contact Us</h2></span>
                <br />
                    <div class="query_txt">If you have any query feel free to contact</div>
                    <div class="admin_txt">Adminstrator</div>
                    <div class="letter_img"><img src="../images/main_icon.png" border="0" /></div>
                    <div class="comma">:</div>
                    <div class="query_txt"><a href="mailto:support@medsynergy.info">support@medsynergy.info</a></div>
                    <div class="cell_img"><img src="../images/contact_icon.png" border="0" /></div>
                    <div class="comma_cell_no">:</div>
                    <div class="cell_num">+254 723938007</div>
             </div>
         
    	</div>
		
        <div class="clr"></div>
         <div class="copy_right">
         <div class="cpy_text">&copy; Copyright <?php echo date("Y");?>. Synergy Informatics. All Rights Reserved | Designed & Developed by <a href="http://www.nexgeninc.com/">NexGen Technologies Inc</a>.</div>
         
         </div>
  	</footer>
 </div>
</div>
</body>
</html>
<script src="js/litetabs.jquery.js"></script>
	<script>
      		$('.homebrief').liteTabs({borders: true , rounded: true,  height: 200});
    </script>	
    
 <!--   {borders: true , rounded: true,  height: 200, selectedTab: 2 }-->