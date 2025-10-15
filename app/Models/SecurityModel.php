<?php 
namespace App\Models;

use CodeIgniter\Model;

class SecurityModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function createSecurity($data) {
		$this->db->table('security')->insert($data);
		return $this->db->insertID();
	}
	public function updateSecurity($data, $security_id) {
		return $this->db->table('security')
					->where(["sc_id" => $security_id])
					->set($data)
					->update();
	}
	public function checkSecurity($fv_id, $security_id) {
		return $this->db->query("select * from flats_security where sc_id = '".$security_id."' and fv_id = '".$fv_id."'")->getRow();
	}
	public function assignToFlat($fdata) {
		$this->db->table('flats_security')->insert($fdata);
	}
	public function listAllSecurity($filter, $row, $tolimit) {
	
		return $this->db->query("SELECT sc.*, fs.fs_id 
								FROM flats_security as fs
								LEFT JOIN security as sc on sc.sc_id = fs.sc_id
								where ".$filter." and fs.status<>3 
								ORDER BY fs.fs_id desc LIMIT ".$row.",".$tolimit)->getResult();
	}
	public function AllSecurityCount() {
		return $this->db->query("SELECT count(*) as securitynos FROM security where status<>3")->getRow();
	}
	public function AllSecurityFilterCount($filter) {
	
		return $this->db->query("SELECT count(*) as filsecuritynos 
							FROM flats_security as fs 
							LEFT JOIN security as sc on sc.sc_id = fs.sc_id
							where ".$filter." and fs.status<>3")->getRow();
	}
	public function getSecurity($id_no) {
		
		return $this->db->query("SELECT * FROM security where id_proof_no = ".$id_no)->getRow();
	}
	public function deleteSecurity($data, $fs_id){
		return $this->db->table('flats_security')
					->where(["fs_id" => $fs_id])
					->set($data)
					->update();
	}
	public function getSecurityInfo($security_id) {
		return $this->db->query("SELECT * FROM security where sc_id = ".$security_id)->getRow();
	}
	public function getAllFeedback($sc_id) {
		return $this->db->query("select * from security_feedback where sc_id = '".$sc_id."'")->getResult();
	}
}
?>