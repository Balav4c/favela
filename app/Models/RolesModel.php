<?php 
namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function checkRoles($rolename, $fv_id, $role_id) {
		if($role_id) {
			$condition = ' and role_id<>'.$role_id;
		}
		else {
			$condition = '';
		}
		return $this->db->query("select * from user_roles where role_name = '".$rolename."' and fv_id = " . $fv_id . $condition)->getRow();
	}
	public function getThisRoles($rid) {
		return $this->db->query("select * from user_roles where role_id = ".$rid)->getRow();
	}
	public function createRoles($data) {
		return $this->db->table('user_roles')->insert($data);
	}
	public function modifyRoles($rid,$data) {
		return $this->db->table('user_roles')
					->where(["role_id" => $rid])
					->set($data)
					->update();
	}
	public function listAllRoles($filter, $row, $tolimit){
		
		return $this->db->query("SELECT * FROM user_roles where ".$filter." and roles_status<>3 order by role_id desc limit ".$row.",".$tolimit)->getResult();
	}
	public function AllRolesCount(){
		
		return $this->db->query("SELECT count(*) as rolesnos FROM user_roles where roles_status<>3")->getRow();
	}
	public function AllRolesFilterCount($filter) {
		return $this->db->query("SELECT count(*) as filterrolesnos FROM user_roles where ".$filter." and roles_status<>3")->getRow();
	}
	public function changeRolesStatus($rl_status, $rl_id, $modified_by) {
		return $this->db->query("update user_roles set roles_status = '".$rl_status."', modified_on=NOW(), modified_by='".$modified_by."' where role_id = '".$rl_id."'");
	}
}
?>