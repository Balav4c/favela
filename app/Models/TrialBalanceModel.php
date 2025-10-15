<?php 
namespace App\Models;

use CodeIgniter\Model;

class TrialBalanceModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getAccountsTypes($fv_id) {
		return $this->db->query("select * from accounts_types where fv_id = 0 or fv_id = ".$fv_id)->getResult();
	}
	public function getledgers($acc_id) {
		return $this->db->query("select GROUP_CONCAT(ld_id SEPARATOR ',') as ledger_ids from ledger where account_type = ".$acc_id)->getRow();
	}
	public function getTotal($ledgerIds, $type) {
		return $this->db->query("select SUM(amount) as Total from journal where ledger_id IN (".$ledgerIds.") and trn_type = ".$type)->getRow();
	}
}
?>