<?php 
namespace App\Models;

use CodeIgniter\Model;

class PaymentRequestModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function loadThisPayRequest($pr_id, $fv_id) {
		return $this->db->query("select pr.*, us.name 
					from payment_request as pr
					left join app_users as us on us.uid = pr.uid
					where pr.fv_id = '".$fv_id."' and pr.pr_id = '".$pr_id."'")->getRow();
	}
	public function getResidents($fv_id, $searchkey) {
		
		return $this->db->query("select us.* 
							from user_residences as ur
							join app_users as us on us.uid = ur.us_id
							where us.name like '%".$searchkey."%' and ur.fv_id = ".$fv_id)->getResult();
	}
	public function savepayrequest($data) {
		
		return $this->db->table('payment_request')->insert($data);
	}
	public function updatepayrequest($data, $payid) {
		
		return $this->db->table('payment_request')
					->where(["pr_id" => $payid])
					->set($data)
					->update();
	}
	public function listAllPayRequest($filter, $row, $tolimit) {
	
		return $this->db->query("select pr.*, us.name 
								from payment_request as pr
								left join app_users as us on us.uid = pr.uid
								where ".$filter." and pr.status<>3 
								order by pr_id desc limit ".$row.",".$tolimit)->getResult();
	}
	public function AllPayreqCount() {
		return $this->db->query("select count(pr_id) as prnos from payment_request where status<>3")->getRow();
	}
	public function AllPayreqFilterCount($filter) {
		return $this->db->query("select count(pr.pr_id) as filterpayreqsnos
								from payment_request as pr
								left join app_users as us on us.uid = pr.uid
								where ".$filter." and pr.status<>3")->getRow();
	}
}
?>