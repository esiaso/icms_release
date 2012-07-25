<?php

/* A CLASS TO LOGIN THE USER */

class Logout {

	private $providerid;
	private $user_id;
	
	function SetCredentials($userid) {
		require_once('class_error_messages.php');
			$errors = new ErrorMessage();
			
		if (empty($userid)) { $errors->getMessage("Not logged in!"); return false; }
		$this->user_id=$userid;
	}

	public function ExecuteLogout() {
		/* UPDATE THE USER TABLE */
		
		
		$sql="UPDATE cme_users SET logged_in = 'n',session_id = '',signature = '',signature_timestamp = '' WHERE user_id = '".$this->user_id."'";
		
		if (mysql_query($sql)) {			
			$this->DeleteSession();
			return true;
		}
		else {
			return false;
		}
		
	}

	private function DeleteSession() {
		
		unset($_SESSION['sid']);
		unset($_SESSION['user_id']);
		unset($_SESSION['user_sessionid']);
		unset($_SESSION['wkgroup']);
		unset($_SESSION['email']);
		unset($_SESSION['testtakerlogged']);
		unset($_SESSION['publicSession']);
		unset($_SESSION['boardSession']);
		unset($_SESSION['providerSession']);
		unset($_SESSION['adminSession']);	
		unset($_SESSION['signature']);
		unset($_SESSION['signature_timestamp']);
		session_destroy();
		//echo "ok123";
		//echo $_SESSION['user_id'];
	}


	function providerSetCredentials($providerid) {
		require_once('class_error_messages.php');
			$errors = new ErrorMessage();
			
		if (empty($providerid)) { $errors->getMessage("Not logged in!"); return false; }
		$this->providerid=$providerid;
	}

	public function providerExecuteLogout() {
		/* UPDATE THE USER TABLE */
		
		$this->providerDeleteSession();
		$sql="UPDATE cme_providers SET logged_in = 'n' WHERE pr_id = '".$this->providerid."'";
		mysql_query($sql);
		if (mysql_affected_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
		
	}

	private function providerDeleteSession() {
		
		unset($_SESSION['sid']);
		unset($_SESSION['email']);
		unset($_SESSION['providerSession']);
		unset($_SESSION['provider_id']);
		session_destroy();
	}

}
?>