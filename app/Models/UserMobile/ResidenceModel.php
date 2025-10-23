<?php 
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class ResidenceModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	  public function getOrgsList($user_id) {
        return $this->db->table('user_residences')
                        ->where('us_id', $user_id)
                        ->where('fv_status', 1)
                        ->get()
                        ->getResult();
    }
}
?>