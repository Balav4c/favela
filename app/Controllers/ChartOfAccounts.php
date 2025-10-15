<?php
namespace App\Controllers;
use App\Models\ChartOfAccountsModel;

class ChartOfAccounts extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->chartofaccountsModel = new ChartOfAccountsModel();
		 
	}
	public function index(){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 9;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$fv_id = $this->session->get('fav_id');
			
			$AccType = $this->chartofaccountsModel->getAccountsTypes($fv_id);
			$key = 0;
			$AccArr = array();
			if($AccType) {
				foreach($AccType as $tl) {
					$getAccSubHeads = $this->chartofaccountsModel->getSubheads($tl->acc_id, $fv_id);
					$AccArr[$key] = array("acc_name"=>$tl->master_name,
										"acc_id"=>$tl->acc_id,
										"subheads"=>$getAccSubHeads);
					$key++;	
				}
			}
			$data['AccArr'] = $AccArr;
			$template = view('common/header',$data);
			$template .= view('chartofaccounts');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('page_script/chartofaccountsjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
	public function loadCharts() {
		
		$fv_id = $this->session->get('fav_id');
		$AccType = $this->chartofaccountsModel->getAccountsTypes($fv_id);
		$key = 0;
		$AccArr = array();
		if($AccType) {
			foreach($AccType as $tl) {
				$getAccSubHeads = $this->chartofaccountsModel->getSubheads($tl->acc_id, $fv_id);
				$AccArr[$key] = array("acc_name"=>$tl->master_name,
									"acc_id"=>$tl->acc_id,
									"subheads"=>$getAccSubHeads);
				$key++;	
			}
		}
		echo json_encode($AccArr);
	}
	public function savesubhead() {
		$data['acc_id'] = $this->input->getPost('acc_id');
		$data['sub_headname'] = ucwords($this->input->getPost('subhead'));
		if($data['acc_id'] && $data['sub_headname']) {
			
			$data['fv_id'] = $this->session->get('fav_id');
			$data['created_on'] = date("Y-m-d H:i:s");
			$data['created_by'] = $this->session->get('fav_user_id');
			
			$Subheadid = $this->chartofaccountsModel->saveSubhead($data);
			
			echo json_encode(array("status"=>1,
							"subhead"=>$data['sub_headname'],
							"subhead_id"=>$Subheadid,
							"acc_id"=>$data['acc_id']));
		}
		else {
			echo json_encode(array("status"=>0,
								"msg"=>"Something went wrong."));
		}
	}
	public function getallsubheads() {
		$acc_id = $this->input->getPost('acc_id');
		$fv_id = $this->session->get('fav_id');
		$getAllsubs = $this->chartofaccountsModel->getSubheads($acc_id, $fv_id);
		echo json_encode($getAllsubs);
	}
	public function deleteSubhead($sh_Id) {
		$fv_id = $this->session->get('fav_id');
		$delSubHead = $this->chartofaccountsModel->deleteSubHeads($sh_Id, $fv_id);
		echo json_encode(1);
	}
	public function updatesubhead() {
		$sh_Id = $this->input->getPost('sh_Id');
		$data['sub_headname'] = $this->input->getPost('subheadval');
		if($sh_Id) {
			$updateSubhead = $this->chartofaccountsModel->updateSubHead($sh_Id, $data);
			echo json_encode(array("success"=>1,
								"subhead"=>$data['sub_headname']));
		}
		else {
			echo json_encode(array("success"=>0,
								"subhead"=>""));
		}
	}
	public function loadLedgers() {
		$sh_id = $this->input->getPost("sh_id");
		$fv_id = $this->session->get('fav_id');
		if($sh_id) {
			$allLedgers = $this->chartofaccountsModel->getLedgers($sh_id, $fv_id);
			echo json_encode(array("success"=>1,
							"sh_id"=>$sh_id,
							"ledgerlist"=>$allLedgers));
		}
		else {
			echo json_encode(array("success"=>0,
							"sh_id"=>$sh_id,
							"ledgerlist"=>null));
		}
	}
}
?>