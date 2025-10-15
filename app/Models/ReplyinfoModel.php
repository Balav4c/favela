<?php 
namespace App\Models;

use CodeIgniter\Model;

class ReplyinfoModel extends Model {

    protected $table = 'complaints_reply';
    protected $primaryKey = 'id'; 
    protected $allowedFields = ['cm_id', 'reply', 'created_on', 'created_by'];
  
    
    public function __construct() {
		$this->db = \Config\Database::connect();
	}

    public function saveComplaintReply($data) {
        return $this->db->table($this->table)->insert($data);
    }
    
     public function getComplaintDetails($cm_id) {
        $builder = $this->db->table('complaintsinfo ci');
        $builder->select('ci.content, cr.reply, cr.created_on as reply_date');
        $builder->join('complaints_reply cr', 'ci.cm_id = cr.cm_id', 'left'); 
        $builder->where('ci.cm_id', $cm_id);
        return $builder->get()->getResultArray(); 
    }

    // public function getComplaintDetails($cm_id) {
    //     $builder = $this->db->table('complaintsinfo ci');
    //     $builder->select('ci.content, cr.reply, cr.created_on as reply_date, cu.cu_name as username');
    //     $builder->join('complaints_reply cr', 'ci.cm_id = cr.cm_id', 'left'); 
    //     $builder->join('vcstagin_favela_master.console_users cu', 'cr.us_id = cu.cu_id', 'left'); 
    //     $builder->where('ci.cm_id', $cm_id);
        
    //     return $builder->get()->getResultArray(); 
    // }
    
    
}

?>