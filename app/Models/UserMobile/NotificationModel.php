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
	// security Notification
	
public function loadsecurityNotifications($user_id, $fv_id)
{
    $sql = "
        SELECT nf.*, nu.status AS userstatus
        FROM app_notifications AS nf
        LEFT JOIN notification_user_status AS nu 
            ON nu.notify_id = nf.notify_id 
            AND nu.us_id =  '".$user_id."'
            AND nu.fv_id = '".$fv_id."'
        WHERE nf.fv_id = '".$fv_id."'
          AND nf.notify_user_type = 2      
          AND nf.status <> 3                
          AND (nf.us_id = '".$user_id."' OR nf.notify_type = 1 OR nf.notify_type = 2)
        ORDER BY nf.created_on DESC
    ";

    return $this->db->query($sql, [$user_id, $fv_id, $fv_id, $user_id])->getResult();
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