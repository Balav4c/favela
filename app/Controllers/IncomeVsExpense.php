<?php
namespace App\Controllers;
use App\Models\IncomeExpenseModel;

class IncomeVsExpense extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->incexpModule = new IncomeExpenseModel();
		 
	}
	public function index(){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 9;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$fv_id = $this->session->get('fav_id');
			$template = view('common/header',$data);
			$template .= view('incomevsexpense');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('page_script/incomeexpensejs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
	public function loadIncomeExpense() {
		
		if($this->input->getPost('fromDate') && $this->input->getPost('toDate')) {
			$fDate = date("Y-m-d", strtotime($this->input->getPost('fromDate')));
			$toDate = date("Y-m-d", strtotime($this->input->getPost('toDate')));
		}
		else {
			$fDate = date('Y-m')."-01";
			$toDate = date("Y-m-t");
		}
		$fv_id = $this->session->get('fav_id');
		$getIncomes = $this->incexpModule->getTransactions($fDate, $toDate, $fv_id, 1);
		$getExpense = $this->incexpModule->getTransactions($fDate, $toDate, $fv_id, 2);
		
		echo json_encode(array("income"=>$getIncomes,
							"expense"=>$getExpense));
	}
}
?>