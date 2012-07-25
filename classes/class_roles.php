<?php
class Role
{
    protected $permissions;

    protected function __construct() {
        $this->permissions = array();
    }

    // return a role object with associated permissions
    public static function getRolePerms($role_id) {
        $role = new Role();
        $sql = sprintf('SELECT t2.perm_desc FROM cme_role_perm as t1
                JOIN cme_permissions as t2 ON t1.perm_id = t2.perm_id
                WHERE t1.roleid = %s',$role_id);
			$res = mysql_query($sql);

        while($row = mysql_fetch_assoc($res)) {
            $role->permissions[$row["perm_desc"]] = true;
        }
        return $role;
    }

    // check if a permission is set
    public function hasPerm($permission) {
		 return isset($this->permissions[$permission]);
    }


	// insert a new role
	public static function insertRole($role_name) {
		$sql = sprintf('INSERT INTO cme_role (rolename) VALUES ("%s")',$role_name);
		return mysql_query($sql);
		
		
	}
	
	// insert array of roles for specified user id
	public static function insertUserRoles($user_id, $roles) {
		$SQLInsert= sprintf('SELECT * FROM cme_user_role WHERE user_id=%s',$user_id);	
		$res= mysql_query($SQLInsert);
		if(mysql_num_rows($res) > 0)
		{
			$sql = sprintf('UPDATE cme_user_role SET roleid=%s WHERE user_id=%s', $roles,$user_id);
		}
		else
			$sql = sprintf('INSERT INTO cme_user_role (user_id, roleid) VALUES (%s, %s)',$user_id, $roles);
			
		mysql_query($sql);
		return true;
	}
	
	// delete array of roles, and all associations
	public static function deleteRoles($roles) {
		$sql = sprintf('DELETE t1, t2, t3 FROM cme_role as t1
				JOIN cme_user_role as t2 on t1.roleid = t2.roleid
				JOIN cme_role_perm as t3 on t1.roleid = t3.roleid
				WHERE t1.roleid = "%s"',$roles);
		mysql_query($sql);
		return true;
	}
	
	// delete ALL roles for specified user id
	public static function deleteUserRoles($user_id) {
		$sql = sprintf('DELETE FROM cme_user_role WHERE user_id = %s',$user_id);
		if(mysql_query($sql))
		{
			$this->insertUserRoles($user_id, 3);
			return true;
		}
	}

}
?>