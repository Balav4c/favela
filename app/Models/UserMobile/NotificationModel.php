<?php 
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class NotificationModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function loadNotifications($user_id, $fv_id) {
		return $this->db->query("select nf.*, nu.status as userstatus 
							from app_notifications as nf
							left join notification_user_status as nu on nu.notify_id = nf.notify_id and nu.us_id = '".$user_id."' and nu.fv_id = '".$fv_id."'
							where nf.fv_id = '".$fv_id."' and nf.status<>3 and (nf.us_id = '".$user_id."' OR nf.notify_type = 1)")->getResult();
	}
	public function checkNotifyStatus($user_id, $nid, $fv_id) {
		return $this->db->query("select * from notification_user_status 
							where fv_id='".$fv_id."' and notify_id = '".$nid."' and us_id = '".$user_id."'")->getRow();
	}
	public function updateNofityStatus($ns_id, $data) {
		return $this->db->table('notification_user_status')
					->where(["ns_id" => $ns_id])
					->set($data)
					->update();
	}
	public function createNofityStatus($data) {
		return $this->db->table('notification_user_status')->insert($data);
	}
}
?>