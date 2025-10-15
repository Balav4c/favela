<?php 
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class LoginModel extends Model {
	
	public function __construct() {
		//protected $table = 'app_users';
		$this->db = \Config\Database::connect();
	}	
	public function verifyUser($phone) {
		return $this->db->query("select * from app_users where phone = ".$phone)->getRow();
	}
	public function verifyUsermpin($phone,$mpin) {
		return $this->db->query("select * from app_users where phone ='".$phone."' and m_pin = '".$mpin."'")->getRow();
	}
	public function getThisProfile($user_id) {
		return $this->db->query("select * from app_users where uid = '".$user_id."' and status = 1")->getRow();
	}
	public function updateUserAcc($data, $user_id) {
		return $this->db->table('app_users')
					->where(["uid" => $user_id])
					->set($data)
					->update();
	}

	public function getFvId($user_id) {
		return $this->db->query("select * from user_residences where us_id='".$user_id."' and fv_status = 1")->getRow();
	}
	
	public function checkCurrentPin($currentMpin, $user_id) {
		return $this->db->query("select uid from app_users where m_pin = ''".$currentMpin."' and uid=".$user_id)->getRow();
	}
	public function updatempin($data, $phone) {
		return $this->db->table('app_users')
					->where(["phone" => $phone])
					->set($data)
					->update();
		 echo $this->db->last_query();
		exit();
	}
	public function CheckPin($phone) {
		
	}
}
?>