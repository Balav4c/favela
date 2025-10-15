<?php 
namespace App\Models;

use CodeIgniter\Model;

class AnnouncementsModel extends Model {
    protected $table = 'announcements';
    protected $primaryKey = 'anoc_id';
    protected $allowedFields = ['subject', 'announcements', 'announce_date', 'expiry_date','announce_status','us_id', 'fv_id', 'status', 'created_by', 'created_on'];

    public function __construct() {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }


    	public function checkAnnouncement($announcements, $fv_id, $anoc_id) {
		if($anoc_id) {
			$condition = ' and anoc_id<>'.$anoc_id;
		}
		else {
			$condition = '';
		}
		return $this->db->query("select * from announcements where announcements = '".$announcements."' and fv_id = " . $fv_id . $condition)->getRow();
	}

   	public function createAnnouncement($data) {
		return $this->db->table('announcements')->insert($data);
	}

    
    	public function modifyAnnouncement($anoc_id,$data) {
		return $this->db->table('announcements')
					->where(["anoc_id" => $anoc_id])
					->set($data)
					->update();
	}

   public function listAllAnnouncement($filter, $row, $tolimit){
		
		return $this->db->query("SELECT * FROM announcements where ".$filter." and status<>3 order by anoc_id desc limit ".$row.",".$tolimit)->getResult();
	}
	public function AllAnnouncementsCount(){
		
		return $this->db->query("SELECT count(*) as announos FROM announcements where status<>3")->getRow();
	}
	public function AllAnnouncementsFilterCount($filter) {
		return $this->db->query("SELECT count(*) as filterannounnos FROM announcements where ".$filter." and status<>3")->getRow();
	}
	public function changeAnnouncementsStatus($status, $anoc_id) {
		return $this->db->query("update announcements set status = '".$status."' where anoc_id = '".$anoc_id."'");
	}
  
    public function changeAnnouncementStatus($status, $anoc_id) {
		return $this->db->query("update announcements set status = '".$status."' where anoc_id = '".$anoc_id."'");
	}
    public function getThisAnnouncement($anoc_id) {
		return $this->db->query("select * from announcements where anoc_id = ".$anoc_id)->getRow();
	}



 
}
?>