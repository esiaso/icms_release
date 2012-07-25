<?php

include("../config.php");
	
	
	// Opens a connection to a mySQL server
	$connection=mysql_connect (DBHOST, DBUSERNAME, DBPASS);
	if (!$connection) {
	  die("Not connected : " . mysql_error());
	}
	
	// Set the active mySQL database
	$db_selected = mysql_select_db(DBNAME, $connection);
	if (!$db_selected) {
	  die ("Can\'t use db : " . mysql_error());
	}

if (@$_REQUEST['action'] == 'check_username' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    echo json_encode(check_username($_REQUEST['coordinator_email']));
    exit; // only print out the json version of the response
}

function check_username($username) {
  $username = trim($username); // strip any white space
  $response = array(); // our response
  
  // if the username is blank
  if (!$username) {
    $response = array(
      'ok' => false, 
      'msg' => '<span class="false">Specify valid Email Address!</span>');
      
  // if the username does not match a-z or '.', '-', '_' then it's not valid
  }elseif (!username_taken($username)) {
    $response = array(
      'ok' => false, 
      'msg' => '<span class="false">Email doesnt exist!!</span>');
      
  // it's all good
  } else {
    $response = array(
      'ok' => true, 
      'msg' => '<span class="true">This Email address exists</span>');
  }

  return $response;        
}

function username_taken($emailexist)
{
	$Query = sprintf('SELECT * FROM cme_users WHERE user_mail="%s"',$emailexist);	
		
		$res= mysql_query($Query);
		if (mysql_num_rows($res) > 0) 
			return true;
			else 
				return false;
}
?>