<?php
namespace App\Controllers;
use App\Models\BsModel;

class Balancesheet extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->bsModel = new BsModel();
		 
	}
	public function index(){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 9;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$template = view('common/header',$data);
			$template .= view('balancesheet');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('page_script/balancesheetjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
	public function loadBalanceSheet() {
		
		$asset_arr = array();
		$liable_arr = array();
		$fyear = explode("-",$this->input->getPost('fyear'));
		$fdate = $fyear[0]."-04-01";
		$tdate = $fyear[1]."-03-31";
		$fv_id = $this->session->get('fav_id');
		$AccType = $this->bsModel->getAccountsTypes($fv_id, 1, 3);
		if($AccType) {
			$crkey = 0;
			foreach($AccType as $ac) {
				
				$getSubheads = $this->bsModel->getSubhead($ac->acc_id, $fv_id);
				if($getSubheads) {
					foreach($getSubheads as $sh) {
						$crTotal = 0;
						$ledgerArr = $this->bsModel->getLedgers($sh->sh_id, $fv_id);
						if($ledgerArr) {
							foreach($ledgerArr as $ldArr) {
								$getSubheadTotal = $this->bsModel->getTransactions($ldArr->ld_id, $fv_id, 1, $fdate, $tdate);
								if($getSubheadTotal->totcr>0 && $getSubheadTotal->totcr!=NULL) {
									
									$crTotal = $crTotal + $getSubheadTotal->totcr;
								}
								$asset_arr[$crkey] = array("acctype"=>$sh->sub_headname, "subheadtotal"=>$crTotal);
								$crkey++;
							}
						}
						
					}
				}
			}
		}
		
		$AccType = $this->bsModel->getAccountsTypes($fv_id, 1, 4);
		if($AccType) {
			$drkey = 0;
			foreach($AccType as $ac) {
				
				$getSubheads = $this->bsModel->getSubhead($ac->acc_id, $fv_id);
				if($getSubheads) {
					foreach($getSubheads as $sh) {
						$drTotal = 0;
						$ledgerArr = $this->bsModel->getLedgers($sh->sh_id, $fv_id);
						if($ledgerArr) {
							foreach($ledgerArr as $ldArr) {
								$getSubheadTotal = $this->bsModel->getTransactions($ldArr->ld_id, $fv_id, 1, $fdate, $tdate);
								
								if($getSubheadTotal->totcr>0 && $getSubheadTotal->totcr!=NULL) {
									
									$drTotal = $drTotal + $getSubheadTotal->totcr;
								}
								$liable_arr[$drkey] = array("acctype"=>$sh->sub_headname, "subheadtotal"=>$drTotal);
								$drkey++;
							}
						}
						
					}
				}
			}
		}
		echo json_encode(array("asset_arr"=>$asset_arr,
							"liable_arr"=>$liable_arr));
	}
}
?>