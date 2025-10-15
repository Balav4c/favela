<?php
namespace App\Controllers;
use App\Models\PlModel;

class ProfitAndLoss extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->plModel = new PlModel();
		 
	}
	public function index(){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 9;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$fv_id = $this->session->get('fav_id');
			$inc_arr = array();
			$exp_arr = array();
			
			$AccType = $this->plModel->getAccountsTypes($fv_id, 2, 1);
			if($AccType) {
				$crkey = 0;
				foreach($AccType as $ac) {
					
					$getSubheads = $this->plModel->getSubhead($ac->acc_id, $fv_id);
					if($getSubheads) {
						foreach($getSubheads as $sh) {
							$crTotal = 0;
							$ledgerArr = $this->plModel->getLedgers($sh->sh_id, $fv_id);
							if($ledgerArr) {
								foreach($ledgerArr as $ldArr) {
									$getSubheadTotal = $this->plModel->getTransactions($ldArr->ld_id, $fv_id, 1);
									
									if($getSubheadTotal->totcr>0 && $getSubheadTotal->totcr!=NULL) {
										
										$crTotal = $crTotal + $getSubheadTotal->totcr;
									}
									$inc_arr[$crkey] = array("acctype"=>$sh->sub_headname, "subheadtotal"=>$crTotal);
								}
							}
							$crkey++;
						}
					}
				}
			}
			
			$AccType = $this->plModel->getAccountsTypes($fv_id, 2, 2);
			if($AccType) {
				$drkey = 0;
				foreach($AccType as $ac) {
					
					$getSubheads = $this->plModel->getSubhead($ac->acc_id, $fv_id);
					if($getSubheads) {
						foreach($getSubheads as $sh) {
							$drTotal = 0;
							$ledgerArr = $this->plModel->getLedgers($sh->sh_id, $fv_id);
							if($ledgerArr) {
								foreach($ledgerArr as $ldArr) {
									$getSubheadTotal = $this->plModel->getTransactions($ldArr->ld_id, $fv_id, 1);
									
									if($getSubheadTotal->totcr>0 && $getSubheadTotal->totcr!=NULL) {
										
										$drTotal = $drTotal + $getSubheadTotal->totcr;
									}
									$exp_arr[$drkey] = array("acctype"=>$sh->sub_headname, "subheadtotal"=>$drTotal);
								}
							}
							$drkey++;
						}
					}
				}
			}
			$data['income'] = $inc_arr;
			$data['expense'] = $exp_arr;
			$template = view('common/header',$data);
			$template .= view('profitloss');
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