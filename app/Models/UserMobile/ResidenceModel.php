<?php 
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class ResidenceModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getOrgsList($user_id) {
		//echo "select * from user_residences where us_id = ".$user_id." and fv_status = 1";
		return $this->db->query("select * from user_residences where us_id = ".$user_id." and fv_status = 1")->getResult();
	}
}
?>