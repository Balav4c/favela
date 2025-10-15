<?php
namespace App\Controllers;
use App\Models\JournalModel;

class Journals extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->journalModel = new JournalModel();
		 
	}
	public function index(){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 9;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));			
			$template = view('common/header',$data);
			$template .= view('journals');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('page_script/journaljs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
	public function loadjournal() {
		if($this->input->getPost('fromDate') && $this->input->getPost('toDate')) {
			$fDate = date("Y-m-d", strtotime($this->input->getPost('fromDate')));
			$toDate = date("Y-m-d", strtotime($this->input->getPost('toDate')));
		}
		else {
			$fDate = date('Y-m')."-01";
			$toDate = date("Y-m-t");
		}
		
		$fv_id = $this->session->get('fav_id');
		$journalData = array();
		if($fDate && $toDate) {
			$getTransactions = $this->journalModel->getAllTransaction($fDate, $toDate, $fv_id);
			if($getTransactions){
				foreach($getTransactions as $trns) {
					$trnId = $trns->trn_id;
					$getJournalData = $this->journalModel->getJournalData($trnId);
					if($getJournalData) {
						array_push($journalData, $getJournalData);
					}
				}
			}
		}
		$from_Date = date("d/m/Y", strtotime($fDate));
		$to_Date = date("d/m/Y", strtotime($toDate));
		echo json_encode(array("journalData"=>$journalData,
						"daterange"=>$from_Date . " To ".$to_Date));
	}
}
?>