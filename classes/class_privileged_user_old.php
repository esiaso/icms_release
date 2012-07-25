<?php
class PrivilegedUser extends Login
{
    private $roles;

    public function __construct() {
       // parent::__construct();
    }

    // override User method
    public static function getByUsername($username) {
        $sql = sprintf('SELECT * FROM cme_users WHERE user_mail = "%s"',$username);
      	$rest = @mysql_query($sql);
        if($result = @mysql_fetch_array($rest))
		{
			if (!empty($result)) {
				$privUser = new PrivilegedUser();
				$privUser->user_id = $result["user_id"];
				$privUser->username = $result["user_mail"];
				$privUser->password = $result["user_password"];
				$privUser->email_addr = $result["user_mail"];
				$privUser->initRoles();
				return $privUser;
			} else {
				return false;
			}
		}
    }

    // populate roles with their associated permissions
    protected function initRoles() {
        $this->roles = array();
        $sql = sprintf('SELECT t1.roleid, t2.rolename FROM cme_user_role as t1
                JOIN cme_role as t2 ON t1.roleid = t2.roleid
                WHERE t1.user_id = %s',$this->user_id);
		
		$res = mysql_query($sql);
	    while($row = mysql_fetch_assoc($res)) {
            $this->roles[$row["rolename"]] = Role::getRolePerms($row["roleid"]);
        }
    }

    // check if user has a specific privilege
    public function hasPrivilege($perm) {
		  foreach ($this->roles as $role) {
           if ($role->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }
	
	// check if a user has a specific role
	public function hasRole($role_name) {
		return isset($this->roles[$role_name]);
	}
	
	// insert a new role permission association
	public static function insertPerm($role_id, $perm_id) {
		$sql = sprintf('INSERT INTO cme_role_perm (roleid, perm_id) VALUES (%s, %s)',$role_id, $perm_id);
		return mysql_query($sql);
	}
	
	// delete ALL role permissions
	public static function deletePerms() {
		$sql = "TRUNCATE cme_role_perm";
		return mysql_query($sql);
	}
}