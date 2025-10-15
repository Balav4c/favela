<?php 
namespace App\Models;

use CodeIgniter\Model;

class AccountsModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	
	public function getThisAccType($acc, $fv_id, $user_id) {
		return $this->db->query("select * from accounts_types where acc_id = '".$acc."' and editable = 1 and created_by = '".$user_id."' and fv_id = '".$fv_id."'")->getRow();
	}
	
	/*
	public function getThisRoles($rid) {
		return $this->db->query("select * from user_roles where role_id = ".$rid)->getRow();
	}
	
	*/
	public function changeAccTypeStatus($acc_status, $acc_id, $modified_by) {
		return $this->db->query("update accounts_types set acc_status = '".$acc_status."', modified_on=NOW(), modified_by='".$modified_by."' where acc_id = '".$acc_id."'");
	}
	
	public function checkAccType($acctype, $fv_id, $acctype_id) {
		if($acctype_id) {
			$condition = ' and acc_id<>'.$acctype_id;
		}
		else {
			$condition = '';
		}
		return $this->db->query("select * from accounts_types where master_name = '".$acctype."' and (fv_id = " . $fv_id ." or created_by = 1)". $condition)->getRow();
	}
	public function createAccType($data) {
		return $this->db->table('accounts_types')->insert($data);
	}
	public function modifyAccType($acctype_id,$data) {
		return $this->db->table('accounts_types')
					->where(["acc_id" => $acctype_id])
					->set($data)
					->update();
	}
	public function listAllAccTypes($filter, $row, $tolimit){
		
		return $this->db->query("SELECT * FROM accounts_types where ".$filter." and acc_status<>3 order by acc_id desc limit ".$row.",".$tolimit)->getResult();
	}
	public function AllAccTypeCount(){
		
		return $this->db->query("SELECT count(*) as acctypenos FROM accounts_types where acc_status<>3")->getRow();
	}
	public function AllAccTypeFilterCount($filter) {
		return $this->db->query("SELECT count(*) as filterrolesnos FROM accounts_types where ".$filter." and acc_status<>3")->getRow();
	}
	public function checkLedger($fv_id, $user_id) {
		return $this->db->query("select count(*) as ledrow from ledger where fv_id = '".$fv_id."' and user_id = '".$user_id."'")->getRow();
	}
	public function CreateLedger($ledData) {
		return $this->db->table('ledger')->insert($ledData);
	}
}
?>