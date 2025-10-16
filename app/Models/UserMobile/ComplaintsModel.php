<?php 
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class ComplaintsModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
      
    public function getAllcomplaints($us_id, $fv_id) {
        return $this->db->query("SELECT cm_id, fv_id, us_id AS uid, to_us_id, msg_type, subject, content, status, action_status, created_on, created_by  FROM complaintsinfo WHERE fv_id = '".$fv_id."' AND us_id = '".$us_id."'")->getResult();
    }
    // public function saveComplaints($data) {
	// 	$this->db->table('complaintsinfo')->insert($data);
	// }
    public function saveComplaints($data)
{
    $complaint = $this->db->table('complaintsinfo');
    $complaint->insert($data);
    $insertId = $this->db->insertID(); 
   return $complaint->where('cm_id', $insertId)->get()->getRowArray();
}

    
    
}
?>