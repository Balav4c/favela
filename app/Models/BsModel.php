<?php 
namespace App\Models;

use CodeIgniter\Model;

class BsModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getAccountsTypes($fv_id, $fin_st, $category) {
		return $this->db->query("select * from accounts_types where fin_statement = ".$fin_st." and statement_category = '".$category."' and (fv_id = ".$fv_id." or fv_id = 0)")->getResult();
	}
	public function getSubhead($accId, $fv_id){
		
		return $this->db->query("select * from account_subhead where acc_id = '".$accId."' and (fv_id = ".$fv_id." or fv_id = 0)")->getResult();
	}
	public function getLedgers($sh_id, $fv_id) {
		return $this->db->query("select * from ledger where (fv_id = ".$fv_id." or fv_id = 0) and sh_id = ".$sh_id)->getResult();
	}
	public function getTransactions($ledgerId, $fv_id, $trntype, $fdate, $tdate) {
		
		return $this->db->query("select sum(trn.trn_amount) as totcr from transactions as trn 
							where trn.trn_type = ".$trntype." and trn.fv_id = '".$fv_id."' 
							and trn.from_ledger_id = ".$ledgerId." 
							and trn_date between '".$fdate."' and '".$tdate."'
							and trn.trn_status = 1")->getRow();
		
	}
}
?>