<?php
namespace App\Controllers;
use App\Models\TrialBalanceModel;

class TrialBalance extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->trialbalanceModel = new TrialBalanceModel();
		 
	}
	public function index(){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 9;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$fv_id = $this->session->get('fav_id');
			$tl_arr = array();
			$AccType = $this->trialbalanceModel->getAccountsTypes($fv_id);
			$key = 0;
			foreach($AccType as $tl) {
				$getLedgers = $this->trialbalanceModel->getledgers($tl->acc_id);
				if($getLedgers) {
					$crTotal = 0;
					$drTotal = 0;
					$ledgerIds = $getLedgers->ledger_ids;
					if($ledgerIds) { 
						$getTotal = $this->trialbalanceModel->getTotal($ledgerIds, 1);
						$crTotal = $getTotal->Total;
						
						$getTotal = $this->trialbalanceModel->getTotal($ledgerIds, 2);
						$drTotal = $getTotal->Total;
					}
				}
				$AccType[$key] = array('account_type'=>$tl->master_name,
									'crtotal'=>$crTotal,
									'drtotal'=>$drTotal);
				$key++;
			}
			$data['trialbalancelist'] = $AccType;
			$template = view('common/header',$data);
			$template .= view('trialbalance');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
}
?>