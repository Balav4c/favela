<?php 
namespace App\Models;

use CodeIgniter\Model;

class JournalModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getAllTransaction($fDate, $toDate, $fv_id) {
		return $this->db->query("select * from transactions 
								where trn_date between '".$fDate."' and '".$toDate."' 
								and fv_id = '".$fv_id."'
								order by trn_date asc")->getResult();
	}
	public function getJournalData($trnId) {
		return $this->db->query("select jr.*, ld.ledger_name, DATE_FORMAT(trns.trn_date, '%d/%m/%Y') as trndate
							from journal as jr
							left join ledger as ld on ld.ld_id = jr.ledger_id
							left join transactions as trns on trns.trn_id = jr.trn_id
							where jr.trn_id = ".$trnId." order by trn_type asc")->getResult();
	}
}
?>