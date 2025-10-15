<?php 
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class PaymentsModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	
	public function getPayReqs($user_id, $fv_id, $fdate, $tdate) {
		
		return $this->db->query("select pr.*, prcp.prc_id, prcp.status 
						from payment_request as pr
						left join payment_request_receipt  as prcp on prcp.pr_id = pr.pr_id and paid_on between '".$fdate."' and '".$tdate."'
						where (pr.uid = '".$user_id."' OR pr.request_to = 1) and pr.status = 1")->getResult();
	}
	public function getpaymentinfo($prc_id, $user_id, $fv_id) {
		return $this->db->query("select * from payment_request_receipt where fv_id = '".$fv_id."' and prc_id = '".$prc_id."' and uid = '".$user_id."'")->getResult();
	}
	public function updatePayment($paydata) {
		return $this->db->table('payment_request_receipt')->insert($paydata);
	}
}
?>