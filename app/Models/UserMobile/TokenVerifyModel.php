<?php 
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class TokenVerifyModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}	
	public function verifyToken($token, $uid) {
		return $this->db->query("select * from app_users where token = '".$token."' and uid = '".$uid."'")->getRow();
	}
}
?>