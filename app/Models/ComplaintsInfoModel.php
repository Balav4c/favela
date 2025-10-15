<?php 
namespace App\Models;

use CodeIgniter\Model;

class ComplaintsInfoModel extends Model {
	protected $table = 'complaintsinfo';
    protected $primaryKey = 'cm_id';
    protected $allowedFields = ['to_us_id', 'action_status', 'status', 'content'];
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getResidents($fv_id, $searchkey) {
		
		return $this->db->query("select us.* 
							from user_residences as ur
							join app_users as us on us.uid = ur.us_id
							where us.name like '%".$searchkey."%' and ur.fv_id = ".$fv_id)->getResult();
	}
	public function loadThisComplaint($cid) {
		
		return $this->db->query("select cmp.*, apu.name
								from complaintsinfo as cmp
								left join app_users as apu on apu.uid = cmp.to_us_id
								where cmp.cm_id = ".$cid)->getRow();
	}
	public function saveComplaints($data) {
		$this->db->table('complaintsinfo')->insert($data);
	}
	public function updateComplaints($data, $cid) {
		return $this->db->table('complaintsinfo')
					->where(["cm_id" => $cid])
					->set($data)
					->update();
	}
	public function listAllComplaints($filter, $row, $tolimit) {
		
		return $this->db->query("select cmp.*, apu.name 
								from complaintsinfo as cmp 
								left join app_users as apu on apu.uid = cmp.to_us_id
								where ".$filter." and cmp.status<>3 order by cmp.action_status asc limit ".$row.",".$tolimit)->getResult();
	}
	public function AllComplaintCount() {
		return $this->db->query("select count(cm_id) as cmpnos from complaintsinfo where status <> 3")->getRow();
	}
	public function AllComplaintsFilterCount($filter) {
		return $this->db->query("select count(cmp.cm_id) as filtercmpnos from complaintsinfo as cmp where ".$filter." and cmp.status<>3")->getRow();
	}
	public function updateComplaintStatus($cm_id, $status) {
		return $this->db->table('complaintsinfo')
						->where('cm_id', $cm_id)
						->update(['action_status' => $status]);
	}
	
	
	
}
?>