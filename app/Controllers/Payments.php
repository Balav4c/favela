<?php
namespace App\Controllers;
use App\Models\PaymentModel;

class Payments extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->paymentModel = new PaymentModel();	
		 
	}
	public function index(){
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 8;
			$fv_id = $this->session->get('fav_id');
			$settings = $this->paymentModel->getSettings($fv_id);
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$receiptno = $this->paymentModel->getLastVoucherNo($fv_id, $settings->invoice_prefix);
			$data['receiptno'] = ($receiptno ? ($receiptno->recpno + 1) : 1);
			$data['recp_prefix'] = $settings->invoice_prefix;
			$template = view('common/header',$data);
			$template .= view('payments');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('page_script/paymentjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
    }
	
	public function listledgers() {
		$ledgername = $this->input->getPost('ledname');
		$fv_id = $this->session->get('fav_id');
		$ledgerlist = $this->paymentModel->getLedgers($ledgername, $fv_id);
		if($ledgerlist) {
			$flag = 1;
		}
		else {
			$flag = 2;
		}
		echo json_encode(array('ledgerlist'=>$ledgerlist,
							'findflag'=>$flag));
	}
	
	public function savereceipt() {
		
		//PAY_TYPES[1]
		
		$fv_id = $this->session->get('fav_id');
		$settings = $this->paymentModel->getSettings($fv_id);
		$recp_pref = $settings->invoice_prefix;
		$cr_ledger_id = $this->input->getPost('cr_ledger_id');
		$cr_amnt = $this->input->getPost('cr_amnt');
		
		$dr_ledger_id = $this->input->getPost('dr_ledger_id');
		$dr_amnt = $this->input->getPost('dr_amnt');
		$narrate = $this->input->getPost('narrate');
		$receipt_no = $this->input->getPost('receipt_no');
		$receipt_prefix = $this->input->getPost('receipt_prefix');
		$trntype_hd = $this->input->getPost('trntype_hd');
		$trnref_hd = $this->input->getPost('trnref_hd');
		$recp_date = $this->input->getPost('recp_date');
		
		//Transaction entry
		
		$data = [
			'fv_id' 		=> 	$fv_id,
			'paper_prefix' 	=> 	$receipt_prefix,
			'paper_no'		=>	$receipt_no,
			'from_ledger_id'=>	$cr_ledger_id,
			'trn_type'		=>	2,
			'trn_amount'	=>	$cr_amnt,
			'trn_narration'	=>	$narrate,
			'trn_paytype'	=>	$trntype_hd,
			'trnref_hd'		=>	$trnref_hd,
			'trn_date'		=>	$recp_date,
			'trn_status'	=>	1,
			'created_on'	=>	date("Y-m-d H:i:s")
		];
		
		$trnId = $this->paymentModel->saveTransactions($data);
		
		if($trnId) {
			//Journal Credit entry
			$cr_data = [
				'ledger_id' => 	$cr_ledger_id,
				'trn_type'	=>	1,
				'narration'	=>	$narrate,
				'fv_id'		=>	$fv_id,
				'trn_id'	=>	$trnId,
				'amount'	=>	$cr_amnt
			];
			$journalEntry = $this->paymentModel->createJournalEntry($cr_data);
			
			if(count($dr_ledger_id)>0) {
				for($i=0;$i<count($dr_ledger_id);$i++) {
					
					//Journal Debit entry
					if($dr_ledger_id[$i]) {
						$dr_data = [
							'ledger_id' => 	$dr_ledger_id[$i],
							'trn_type'	=>	2,
							'narration'	=>	$narrate,
							'fv_id'		=>	$fv_id,
							'trn_id'	=>	$trnId,
							'amount'	=>	$dr_amnt[$i]
						];
						$journalEntry = $this->paymentModel->createJournalEntry($dr_data);
					}
					
				}
			}
		}
		//echo $createJournal;exit();
		echo json_encode(1);
	}
}
