<?php 
namespace App\Models;

use CodeIgniter\Model;

class LedgerModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getThisLedger($ld) {
		return $this->db->query("select * from ledger where ld_id = ".$ld)->getRow();
	}
	public function checkLedger($ledger_name, $fv_id, $account_type, $ledger_id) {
		return $this->db->query("select * from ledger where ledger_name = '".$ledger_name."' and fv_id = '".$fv_id."' and account_type = '".$account_type."' and ld_id<>'".$ledger_id."'")->getRow();
	}
	public function createLedger($data) {
		return $this->db->table('ledger')->insert($data);
	}
	public function modifyLedger($ledger_id, $data) {
		return $this->db->table('ledger')
					->where(["ld_id" => $ledger_id])
					->set($data)
					->update();
	}
	public function changeLedgerStatus($ld_status, $ld_id, $modified_by) {
		return $this->db->query("update ledger set status = '".$ld_status."', modified_on=NOW(), modified_by='".$modified_by."' where ld_id = '".$ld_id."'");
	}
	public function listAllLedgers($filter, $row, $tolimit) {
		
		return $this->db->query("SELECT led.*, act.master_name, sh.sub_headname 
							FROM ledger as led
							left join accounts_types as act on act.acc_id = led.account_type
							left join account_subhead as sh on sh.sh_id = led.sh_id
							where ".$filter." and status<>3 order by ld_id desc limit ".$row.",".$tolimit)->getResult();
	}
	public function AllLedgersCount($allFilter){
		
		return $this->db->query("SELECT count(*) as ledgnos FROM ledger where status<>3 and " . $allFilter)->getRow();
	}
	public function AllLedgersFilterCount($filter) {
		
		return $this->db->query("SELECT count(*) as filterledgnos 
							FROM ledger as led
							left join accounts_types as act on act.acc_id = led.account_type
							where ".$filter." and status<>3")->getRow();
	}
	public function getAllAccounts($fv_id) {
		return $this->db->query("select * from accounts_types where fv_id = 0 or fv_id = ".$fv_id)->getResult();
	}
	public function getSubheads($acc_id) {
		return $this->db->query("select * from account_subhead where acc_id = ".$acc_id)->getResult();
	}
	public function getLdDataList($ledId, $fv_id, $type, $fdate, $tdate) {
		
		return $this->db->query("SELECT jn.*, DATE_FORMAT(trn.trn_date, '%d/%m/%Y') as trn_date 
								FROM journal as jn 
								join transactions as trn on trn.trn_id = jn.trn_id
								where jn.trn_type='".$type."'
								and jn.fv_id='".$fv_id."' and jn.ledger_id='".$ledId."' 
								and trn.trn_date BETWEEN '".$fdate."' AND '".$tdate."' order by trn.trn_date")->getResult();
	
	}
}
?>