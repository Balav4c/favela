<?php
namespace App\Controllers;
use App\Models\ReceiptModel;

class Receipts extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->receiptModel = new ReceiptModel();	
		 
	}
	public function index(){
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 8;
			$fv_id = $this->session->get('fav_id');
			$settings = $this->receiptModel->getSettings($fv_id);
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$receiptno = $this->receiptModel->getLastVoucherNo($fv_id, $settings->receipt_prefix);
			$data['receiptno'] = ($receiptno ? ($receiptno->recpno + 1) : 1);
			$data['recp_prefix'] = $settings->receipt_prefix;
			$template = view('common/header',$data);
			$template .= view('receipts');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('page_script/receiptsjs');
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
		$ledgerlist = $this->receiptModel->getLedgers($ledgername, $fv_id);
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
		$settings = $this->receiptModel->getSettings($fv_id);
		$recp_pref = $settings->receipt_prefix;
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
			'trn_type'		=>	1,
			'trn_amount'	=>	$cr_amnt,
			'trn_narration'	=>	$narrate,
			'trn_paytype'	=>	$trntype_hd,
			'trnref_hd'		=>	$trnref_hd,
			'trn_date'		=>	$recp_date,
			'trn_status'	=>	1,
			'created_on'	=>	date("Y-m-d H:i:s")
		];
		
		$trnId = $this->receiptModel->saveTransactions($data);
		
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
			$journalEntry = $this->receiptModel->createJournalEntry($cr_data);
			
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
						$journalEntry = $this->receiptModel->createJournalEntry($dr_data);
					}
					
				}
			}
		}
		//echo $createJournal;exit();
		echo json_encode(1);
	}
	
	/*public function createnew() {
		$buildname = $this->input->getPost('buildname');
		$flatnos = $this->input->getPost('flatnos');
		$recptno = $this->input->getPost('recptno');
		$bd_id = $this->input->getPost('bd_id');
		$fv_id = $this->session->get('fav_id');
		$checkFlats = $this->flatsModel->checkFlats($buildname, $fv_id, $bd_id);
		if($checkFlats) {
			echo json_encode(array("status"=>0,"respmsg"=>"Building/tower name already exist in the system."));
		}
		else {
			if($buildname) {
				$data = [
					'fv_id'       	=> 	$this->session->get('fav_id'),
					'bd_name'       => 	$buildname,
					'bd_flat_nos' 	=> 	$flatnos,
					'bd_recpt_no' 	=> 	$recptno,
					'created_on' 	=> 	date("Y-m-d H:i:s"),
					'created_by	'	=>	$this->session->get('fav_user_id'),
					'modified_on'	=>	date("Y-m-d H:i:s"),
					'modified_by'	=>	$this->session->get('fav_user_id'),
				];
				
				if($bd_id == 0 || $bd_id == NULL) {
					$CreateFlats = $this->flatsModel->createFlats($data);
					echo json_encode(array("status"=>1,"respmsg"=>"Building/Tower created successfully."));
				}
				else {
					$ModifyFlats = $this->flatsModel->modifyFlats($bd_id, $data);
					echo json_encode(array("status"=>1,"respmsg"=>"Building/Tower updated successfully."));
				}
				
			}
			else {
				echo json_encode(array("status"=>0,"respmsg"=>"Please enter building/tower name."));
			}
		}
	}
	public function changeStatus() {
		$bd_status = $this->input->getPost('bd_status');
		$bd_id = $this->input->getPost('bd_id');
		$FlatsStatus = $this->flatsModel->changeFlatStatus($bd_status, $bd_id);
		echo json_encode(1);
	}
	public function deleteFlat($fid) {
		if($fid) {
			$FlatsStatus = $this->flatsModel->changeFlatStatus(3, $fid);
			echo json_encode(1);
		}
		else {
			echo json_encode(2);
		}
	}
	public function listflats() {
		
		## Read value
		$draw = $_POST['draw'];
		$row = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		//foreach ($empRecords as $row) {
		$filter = '1=1';
		$tolimit = $row + $rowperpage;
		if($searchValue) {
			$filter .= " and bd_name like '%".$searchValue."%'";
		}
		$filter .= " and fv_id = ".$this->session->get('fav_id');
		$ListFlats = $this->flatsModel->listAllFlats($filter, $row, $tolimit);
		$data = array();
		$slno = $row;
		foreach($ListFlats as $flist) {
			$action = '<a href="'.base_url("flats/$flist->bd_id").'"><i class="fa fa-pencil-square-o"></i></a>';
			$action .= '<a href="javascript:void(0)" onclick="deleteFlat('.$flist->bd_id.')"><i class="fa fa-trash-o"></i></a>';
			$checked = ($flist->bd_status==1 ? 'checked="checked"' : '' );
			$data[] = array(
				"slno"=>$slno + 1,
				"building"=>$flist->bd_name,
				"flatnos"=>$flist->bd_flat_nos,
				"recepno"=>$flist->bd_recpt_no,
				"status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$flist->bd_id.'" '.$checked.'/>',
				"action"=>$action
			);
			$slno++;
		}
		// Response
		$ListFlats = $this->flatsModel->AllFlatsCount();
		$totalRecords = $ListFlats->flatnos;
		$ListFilterFlats = $this->flatsModel->AllFlatsFilterCount($filter);
		$totalRecordwithFilter=$ListFilterFlats->filterflatnos;
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
		echo json_encode($response);
	}*/
	
}
