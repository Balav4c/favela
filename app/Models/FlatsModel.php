<?php 
namespace App\Models;

use CodeIgniter\Model;

class FlatsModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function checkFlats($buildname, $fv_id, $bd_id) {
		if($bd_id) {
			$condition = ' and bd_id<>'.$bd_id;
		}
		else {
			$condition = '';
		}
		return $this->db->query("select * from buildings where bd_name = '".$buildname."' and fv_id = " . $fv_id . $condition)->getRow();
	}
	public function getThisFlats($bd) {
		return $this->db->query("select * from buildings where bd_id = ".$bd)->getRow();
	}
	public function createFlats($data) {
		return $this->db->table('buildings')->insert($data);
	}
	public function modifyFlats($bd_id,$data) {
		return $this->db->table('buildings')
					->where(["bd_id" => $bd_id])
					->set($data)
					->update();
	}
	public function listAllFlats($filter, $row, $tolimit){
		
		return $this->db->query("SELECT * FROM buildings where ".$filter." and bd_status<>3 order by bd_id desc limit ".$row.",".$tolimit)->getResult();
	}
	public function AllFlatsCount(){
		
		return $this->db->query("SELECT count(*) as flatnos FROM buildings where bd_status<>3")->getRow();
	}
	public function AllFlatsFilterCount($filter) {
		return $this->db->query("SELECT count(*) as filterflatnos FROM buildings where ".$filter." and bd_status<>3")->getRow();
	}
	public function changeFlatStatus($bd_status, $bd_id) {
		return $this->db->query("update buildings set bd_status = '".$bd_status."' where bd_id = '".$bd_id."'");
	}
}
?>