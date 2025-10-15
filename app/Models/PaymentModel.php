<?php 
namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getLastVoucherNo($fv_id, $prefix) {
		return $this->db->query("select MAX(paper_no) as recpno from transactions where fv_id = '".$fv_id."' and paper_prefix = '".$prefix."' and trn_type = 2")->getRow();
	}
	public function getLedgers($ledgername, $fv_id) {
		return $this->db->query("select * from ledger where ledger_name like '%".$ledgername."%' and status = 1 and fv_id = '".$fv_id."'")->getResult();
	}
	public function getSettings($fv_id) {
		return $this->db->query("select * from app_settings where fv_id = ".$fv_id)->getRow();
	}
	public function saveTransactions($data) {
		$this->db->table('transactions')->insert($data);
		return $this->db->insertID();
	}
	public function createJournalEntry($data) {
		return $this->db->table('journal')->insert($data);
	}
}
?>