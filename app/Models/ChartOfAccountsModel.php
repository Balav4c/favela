<?php 
namespace App\Models;

use CodeIgniter\Model;

class ChartOfAccountsModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getAccountsTypes($fv_id) {
		return $this->db->query("select * from accounts_types where fv_id = 0 or fv_id = ".$fv_id)->getResult();
	}
	public function saveSubhead($data) {
		$this->db->table('account_subhead')->insert($data);
		return $this->db->insertID();
	}
	public function getSubheads($acc_id, $fv_id) {
		
		/*return $this->db->query("select sh.*, count(sh.sh_id) as ledgerNos 
							from account_subhead as sh
							left join ledger as ld on ld.sh_id = sh.sh_id and ld.fv_id = ".$fv_id."
							where sh.fv_id = '".$fv_id."' and sh.acc_id = '".$acc_id."'")->getResult();*/
							
		return $this->db->query("select sh.*, count(ld.ld_id) as ledgernos
								from account_subhead as sh
								left join ledger as ld on ld.sh_id = sh.sh_id
								where sh.acc_id='".$acc_id."' and sh.fv_id = '".$fv_id."' group by sh.sh_id")->getResult();
	}
	public function deleteSubHeads($sh_Id, $fv_id) {
		return $this->db->query("delete from account_subhead where fv_id = '".$fv_id."' and sh_id = '".$sh_Id."'");
	}
	public function updateSubHead($sh_Id, $data) {
		return $this->db->table('account_subhead')
					->where(["sh_id" => $sh_Id])
					->set($data)
					->update();
	}
	public function getLedgers($sh_Id, $fv_id) {
		return $this->db->query("select * from ledger where fv_id = '".$fv_id."' and sh_id = '".$sh_Id."'")->getResult();
	}
}
?>