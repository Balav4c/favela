<?php
namespace App\Controllers;
use App\Models\LedgerModel;

class Ledger extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->ledgerModel = new LedgerModel();
		 
	}
	public function index($ld=''){ 
		
		if($ld) {
			$ThisLedger = $this->ledgerModel->getThisLedger($ld);
			$data['ledger_name'] = $ThisLedger->ledger_name;
			$data['opening_balance'] = $ThisLedger->opening_balance;
			$data['account_type'] = $ThisLedger->account_type;
			$data['subhead'] = $ThisLedger->sh_id;
			$data['ledger_id'] = $ThisLedger->ld_id;
		}
		else {
			$data['ledger_name'] = NULL;
			$data['opening_balance'] = NULL;
			$data['account_type'] = NULL;
			$data['ledger_id'] = NULL;
			$data['subhead'] = NULL;
		}
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 9;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$data['ledger'] = '';
			$fv_id = $this->session->get('fav_id');
			$data['accountType'] = $this->ledgerModel->getAllAccounts($fv_id);
			$template = view('common/header',$data);
			$template .= view('ledger');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/ledgerjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}	
	}
	public function createnew() {
		
		$ledger_name = $this->input->getPost('ledgername');
		$account_type = $this->input->getPost('acctype');
		$subhead = $this->input->getPost('subhead');
		$opening_balance = $this->input->getPost('openbal');
		$ledger_id = $this->input->getPost('ledger_id');
		if($ledger_name) { 
			$fv_id = $this->session->get('fav_id');
			$checkLedger = $this->ledgerModel->checkLedger($ledger_name, $fv_id, $account_type, $ledger_id);
			if($checkLedger) {
				echo json_encode(array("status"=>0,"respmsg"=>"Ledger already exist in the system under this account type."));
			}
			else {
				$data = [
					'fv_id'       		=> 	$this->session->get('fav_id'),
					'ledger_name'   	=> 	$ledger_name,
					'account_type'  	=> 	$account_type,
					'sh_id'				=>	$subhead,
					'opening_balance'  	=> 	$opening_balance,
					'created_on' 		=> 	date("Y-m-d H:i:s"),
					'created_by	'		=>	$this->session->get('fav_user_id'),
					'modified_on'		=>	date("Y-m-d H:i:s"),
					'modified_by'		=>	$this->session->get('fav_user_id'),
				];
				
				if($ledger_id == 0 || $ledger_id == NULL) {
					$CreateLedger = $this->ledgerModel->createLedger($data);
					echo json_encode(array("status"=>1,"respmsg"=>"Ledger created successfully."));
				}
				else {
					$ModifyLedger = $this->ledgerModel->modifyLedger($ledger_id, $data);
					echo json_encode(array("status"=>1,"respmsg"=>"Ledger updated successfully."));
				}
			}
		}
		else {
			echo json_encode(array("status"=>0,"respmsg"=>"Please enter the ledger name."));
		}
		
	}
	public function changeStatus() {
		$ld_status = $this->input->getPost('ld_status');
		$ld_id = $this->input->getPost('ld_id');
		$modified_by = $this->session->get('fav_user_id');
		$LedgerStatus = $this->ledgerModel->changeLedgerStatus($ld_status, $ld_id, $modified_by);
		echo json_encode(1);
	}
	public function deleteLedger($ld_id) {
		if($ld_id) {
			$modified_by = $this->session->get('fav_user_id');
			$LedgerStatus = $this->ledgerModel->changeLedgerStatus(3, $ld_id, $modified_by);
			echo json_encode(1);
		}
		else {
			echo json_encode(2);
		}
	}
	public function openledger() {
		
		$ledId = $this->input->getPost('ledId');
		$fv_id = $this->session->get('fav_id');
		
		//$fdate = ($this->input->getPost('fdate') ? $this->input->getPost('fdate') : date('Y-m').'-01');
		//$tdate = ($this->input->getPost('tdate') ? $this->input->getPost('tdate') : date('Y-m-t'));
		/*$prevYear = date('Y') - 1;
		$fdate = $prevYear.'-04-01';
		$tdate = date('Y')."-03-31";*/
		
		$fyear = $this->input->getPost('fromYear');
		$tyear = $this->input->getPost('toYear');
		
		if($ledId) {
			/*$cr_data = array();
			$dr_data = array();
			$LedCrData = $this->ledgerModel->getLdDataList($ledId, $fv_id, 1, $fdate, $tdate);
			$LedDrData = $this->ledgerModel->getLdDataList($ledId, $fv_id, 2, $fdate, $tdate);			
			$Cr_Rows = count($LedCrData);
			$Dr_Rows = count($LedDrData);
			$finyear = date("d/m/Y", strtotime($fdate)) . " - " .date("d/m/Y", strtotime($tdate));
			echo json_encode(array("CrData"=>$LedCrData,
								"DrData"=>$LedDrData,
								"CrRows"=>$Cr_Rows,
								"DrRows"=>$Dr_Rows,
								"FinYear"=>$finyear));*/
								
			$j=1;
			for($i=4;$i<16;$i++) {
				if($i>12) {
					$year = date('Y') + 1;
					$fdate = date("Y-m-d", strtotime($tyear.'-'.$j.'-01'));
					$tdate = date("Y-m-t", strtotime($tyear.'-'.$j));
					$j++;
				}
				else {
					
					$fdate = date("Y-m-d", strtotime($fyear.'-'.$i.'-01'));
					$tdate = date("Y-m-t", strtotime($fyear.'-'.$i));
				}
				$LedCrData = $this->ledgerModel->getLdDataList($ledId, $fv_id, 1, $fdate, $tdate);
				$LedDrData = $this->ledgerModel->getLdDataList($ledId, $fv_id, 2, $fdate, $tdate);
				$ledgerArr[$i] = array("CrData"=>$LedCrData,"DrData"=>$LedDrData);
			}
			/*print_r($ledgerArr);
			echo json_encode(array("CrData"=>$LedCrData,
								"DrData"=>$LedDrData,
								"CrRows"=>$Cr_Rows,
								"DrRows"=>$Dr_Rows,
								"FinYear"=>$finyear));*/
			echo json_encode($ledgerArr);
		}
	}
	public function subheadlist() {
		$acc_id = $this->input->getPost('acc_id');
		if($acc_id) {
			$subheads = $this->ledgerModel->getSubheads($acc_id);
			echo json_encode($subheads);
		}
	}
	public function listledger() {
		
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
			$filter .= " and led.ledger_name like '%".$searchValue."%'";
		}
		$filter .= " and led.fv_id = ".$this->session->get('fav_id');
		$ListLedger = $this->ledgerModel->listAllLedgers($filter, $row, $tolimit);
		$data = array();
		$slno = $row;
		foreach($ListLedger as $ld_list) {
			$action = '<a href="'.base_url("ledger/$ld_list->ld_id").'"><i class="fa fa-pencil-square-o"></i></a>';
			$action .= '<a href="javascript:void(0)" onclick="deleteLedger('.$ld_list->ld_id.')"><i class="fa fa-trash-o"></i></a>';
			$action .= '<a href="javascript:void(0)" onclick="openLedger('.$ld_list->ld_id.',\''.$ld_list->ledger_name.'\')"><i class="fa fa-folder-open"></i></a>';
			$checked = ($ld_list->status==1 ? 'checked="checked"' : '' );
			$data[] = array(
				"slno"=>$slno + 1,
				"ledgername"=>$ld_list->ledger_name,
				"acctype"=>$ld_list->master_name,
				"subhead"=>$ld_list->sub_headname,
				"openBal"=>$ld_list->opening_balance,
				"createdon"=>date("d-m-Y H:i:s", strtotime($ld_list->created_on)),
				"status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$ld_list->ld_id.'" '.$checked.'/>',
				"action"=>$action
			);
			$slno++;
		}
		// Response
		$allFilter = " fv_id = ".$this->session->get('fav_id');
		$ListLedgerCount = $this->ledgerModel->AllLedgersCount($allFilter);
		$totalRecords = $ListLedgerCount->ledgnos;
		$ListFilterRoles = $this->ledgerModel->AllLedgersFilterCount($filter);
		$totalRecordwithFilter=$ListFilterRoles->filterledgnos;
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
		echo json_encode($response);
	}
}
?>