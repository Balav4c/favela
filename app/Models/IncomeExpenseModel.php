<?php 
namespace App\Models;

use CodeIgniter\Model;

class IncomeExpenseModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getTransactions($fDate, $toDate, $fv_id, $type) {
		return $this->db->query("select trn.*, DATE_FORMAT(trn.trn_date, '%d-%m-%Y') as trndate, ld.ledger_name 
								from transactions as trn
								left join ledger as ld on ld.ld_id = trn.from_ledger_id
								where trn.trn_date between '".$fDate."' and '".$toDate."' 
								and trn.fv_id = '".$fv_id."' and trn.trn_type =".$type)->getResult();
	}
}
?>