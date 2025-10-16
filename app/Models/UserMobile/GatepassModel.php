<?php 
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class GatepassModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getGatepass($fv_id) {
        return $this->db->query("SELECT * FROM gatepass WHERE fv_id = '".$fv_id."'")->getResult();
    }

	public function saveGatepass($data)
	{
		$gatepass = $this->db->table('gatepass');
		$gatepass->insert($data);
		$insertId = $this->db->insertID(); 
	   return $gatepass->where('gp_id', $insertId)->get()->getRowArray();
	}
	
}
?>