<?php
/** ensure this file is being included by a parent file */

class UserInfo {

	function UserInfo($id) {

		$db=$GLOBALS['db'];

		$sql="SELECT *
					FROM leave_user_master um, leave_role_master rm
					WHERE um.user_id = '".$id."'
					AND um.role_id = rm.role_id
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	function Login() {	return $this->login; }
	function Password() { return $this->password;	}
	function StaffID() { return $this->identification_number;}
	function TitleID() {	return $this->title_id; }
	function SessionID() { return $this->session_id; }
	function WorkspaceID() { return $this->workspace_id; }
	function TeamspaceID() { return $this->teamspace_id; }
	function FullName() { return $this->full_name; }
	function Photograph() { return $this->photograph; }
	function Timezone() { return $this->timezone; }
	/* ROLE PRIVILEGES */
	function CreateWorkspace() { return $this->create_workspace; }
	function BrowseWorkspaces() { return $this->browse_workspaces; }
	/* ROLE */
	function RoleID() { return $this->role_id; }
	function RoleName() { return $this->role_name; }
	function CreateMaxWorkspaces() { return $this->create_max_workspaces; }
	function CreateWorkspacesRoles() { return $this->create_workspace_roles; }

	/* THIS NEEDS TO BE CLEANED UP AND REFERENCED FROM HRMS MODULE CLASSES */
	public function SuperiorID() {
		$db=$GLOBALS['db'];
		$sql="SELECT superior_user_id
					FROM hrms_user_reporting
					WHERE subordinate_user_id = '".$_SESSION['user_id']."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['superior_user_id'];
			}
		}
		else {
			return false;
		}
	}
}
?>