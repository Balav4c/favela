<?php 
namespace App\Models;

use CodeIgniter\Model;

class ResidentsModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function userExistance($aadhno) {
		
		return $this->db->query("select * from app_users where aadhar_no = '".$aadhno."'")->getRow();
	}
	public function registerToFlat($data) {
		
		return $this->db->table('user_residences')->insert($data);
	}
	public function checkUser($us_id, $fv_id) {
		return $this->db->query("select * from user_residences where us_id = '".$us_id."' and fv_id = '".$fv_id."'")->getRow();
	}
	public function getUser($us_id) {
		
		return $this->db->query("select * from app_users where uid = '".$us_id."'")->getRow();
	}
	public function createUser($data) {
		
		$this->db->table('app_users')->insert($data);
		return $this->db->insertID();
	}
	public function modifyUser($user_id,$data) {
		return $this->db->table('app_users')
					->where(["uid" => $user_id])
					->set($data)
					->update();
	}
	public function checkTowerUser($user_id, $fav_id) {
		return $this->db->query("select count(*) as fvuserno from user_residences where us_id = '".$user_id."' and fv_id = '".$fav_id."'")->getRow();
	}
	public function acceptNewUser($udata) {
		
		$this->db->table('user_residences')->insert($udata);
		return $this->db->insertID();
	}
	public function updateNewUser($us_id, $fv_id, $udata) {
		return $this->db->table('user_residences')
					->where(["us_id" => $us_id])
					->where(["fv_id" => $fv_id])
					->set($udata)
					->update();
	}
	
	public function deleteUser($us_id, $fv_id, $data) {
		
		return $this->db->table('user_residences')
					->where(["us_id" => $us_id])
					->where(["fv_id" => $fv_id])
					->set($data)
					->update();
	}
	
	public function changeUserStatus($fv_id, $us_id, $data) {
		return $this->db->table('user_residences')
					->where(["us_id" => $us_id])
					->where(["fv_id" => $fv_id])
					->set($data)
					->update();
	}
	public function removeAadhaar($userId, $fv_id) {
		$this->db->query("delete from user_residences where us_id = ".$userId." and fv_id = ".$fv_id);
		return $this->db->query("delete from app_users where uid = ".$userId);
	}
	public function listAllResidents($filter, $row, $tolimit){
							
		return $this->db->query("SELECT ur.*,au.name, au.phone, au.email_id, au.aadhar_no  
							FROM user_residences as ur
							left join app_users as au on au.uid = ur.us_id
							where ".$filter." and fv_status<>3 order by rd_id desc limit ".$row.",".$tolimit)->getResult();
	}
	public function AllResidentsCount($fv_id){
		
		return $this->db->query("SELECT count(*) as resnos FROM user_residences where fv_status<>3 and fv_id = ".$fv_id)->getRow();
	}
	public function AllResidFilterCount($filter) {
		
		return $this->db->query("SELECT count(*) as filterressnos 
								FROM user_residences as ur
								left join app_users as au on au.uid = ur.us_id
								where ".$filter." and ur.fv_status<>3")->getRow();
	}
	public function getUserDetails($us_id) {
		return $this->db->query("select * from app_users where uid = ".$us_id)->getRow();
	}
	public function saveDoorNo($data) {
		$this->db->table('building_doors')->insert($data);
		return $this->db->insertID();
	}
	public function listDoor($list_us_id, $fav_id) {
		return $this->db->query("select * from building_doors where fv_id = '".$fav_id."' and us_id = '".$list_us_id."'  and door_status<>2 order by door_id desc")->getResult();
	}
	public function checkDoorNo($doorno, $fav_id) {
		return $this->db->query("select count(*) as DoorsNos from building_doors where fv_id = '".$fav_id."' and door_no = '".$doorno."' and door_status<>2")->getRow();
	}
	public function deleteDoor($did, $data) {
		return $this->db->table('building_doors')
					->where(["door_id" => $did])
					->set($data)
					->update();
	}
}
?>