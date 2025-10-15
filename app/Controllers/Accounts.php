<?php
namespace App\Controllers;
use App\Models\AccountsModel;

class Accounts extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->accountsModel = new AccountsModel();
		 
	}
	public function index($acc=''){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			
			$fv_id = $this->session->get('fav_id');
			$user_id = $this->session->get('fav_user_id');
			if($acc) {
				$ThisAccType = $this->accountsModel->getThisAccType($acc, $fv_id, $user_id);
				if($ThisAccType) {
					$data['acctype_name'] = $ThisAccType->master_name;
					$data['fin_statement'] = $ThisAccType->fin_statement;
					$data['statement_category'] = $ThisAccType->statement_category;
					$data['acctype_id'] = $ThisAccType->acc_id;
				}
				else {
					$data['acctype_name'] = null;
					$data['acctype_id'] = null;
					$data['fin_statement'] = null;
					$data['statement_category'] = null;
				}
			}
			else {
				$data['acctype_name'] = null;
				$data['acctype_id'] = null;
				$data['fin_statement'] = null;
				$data['statement_category'] = null;
			}
			
			$data['menu'] = 9;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$template = view('common/header',$data);
			$template .= view('accountstype');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/acctypejs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
	
	public function createnew() {
		$acctype = $this->input->getPost('acctype');
		$acctype_id = $this->input->getPost('acctype_id');
		$fin_statement = $this->input->getPost('fin_statement');
		$statement_category = $this->input->getPost('statement_category');
		if($acctype) {
			$fv_id = $this->session->get('fav_id');
			$checkAccType = $this->accountsModel->checkAccType($acctype, $fv_id, $acctype_id);
			if($checkAccType) {
				echo json_encode(array("status"=>0,"respmsg"=>"Account type already exist in the system."));
			}
			else {
				$data = [
					'fv_id'       			=> 	$this->session->get('fav_id'),
					'master_name'   		=> 	$acctype,
					'fin_statement' 		=> 	$fin_statement,
					'statement_category' 	=> 	$statement_category,
					'acc_status' 			=> 	1,
					'editable' 				=> 	1,
					'created_on' 			=> 	date("Y-m-d H:i:s"),
					'created_by'			=>	$this->session->get('fav_user_id'),
					'modified_on'			=>	date("Y-m-d H:i:s"),
					'modified_by'			=>	$this->session->get('fav_user_id')
				];
				
				if($acctype_id == 0 || $acctype_id == NULL) {
					$CreateFlats = $this->accountsModel->createAccType($data);
					echo json_encode(array("status"=>1,"respmsg"=>"Account Type created successfully."));
				}
				else {
					$ModifyFlats = $this->accountsModel->modifyAccType($acctype_id, $data);
					echo json_encode(array("status"=>1,"respmsg"=>"Account Type updated successfully."));
				}
			}
			
		}
		else {
			echo json_encode(array("status"=>0,"respmsg"=>"Please enter the account type name."));
		}
	}
	
	public function changeStatus() {
		$acc_status = $this->input->getPost('acc_status');
		$acc_id = $this->input->getPost('acc_id');
		$modified_by = $this->session->get('fav_user_id');
		$AccStatus = $this->accountsModel->changeAccTypeStatus($acc_status, $acc_id, $modified_by);
		echo json_encode(1);
	}
	
	public function deleteAccType($acc_id) {
		if($acc_id) {
			$modified_by = $this->session->get('fav_user_id');
			$AccStatus = $this->accountsModel->changeAccTypeStatus(3, $acc_id, $modified_by);
			echo json_encode(1);
		}
		else {
			echo json_encode(2);
		}
	}
	
	public function listacctype() {
		## Read value
		$draw = $_POST['draw'];
		$row = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		$filter = '1=1';
		$tolimit = $row + $rowperpage;
		if($searchValue) {
			$filter .= " and master_name like '%".$searchValue."%'";
		}
		$filter .= " and (fv_id = ".$this->session->get('fav_id')." OR fv_id = 0)";
		$ListAccType = $this->accountsModel->listAllAccTypes($filter, $row, $tolimit);
		$data = array();
		$slno = $row;
		$categoryArr = array("1"=>"Profit","2"=>"Loss","3"=>"Assets","4"=>"Liabilities");
		foreach($ListAccType as $aclist) {
			if($aclist->editable==1) {
				$utype = 'User Defined';
				$action = '<a href="'.base_url("accounts/$aclist->acc_id").'"><i class="fa fa-pencil-square-o"></i></a>';
				$action .= '<a href="javascript:void(0)" onclick="deleteAccType('.$aclist->acc_id.')"><i class="fa fa-trash-o"></i></a>';
				$checked = ($aclist->acc_status==1 ? 'checked="checked"' : '' );
			}
			else {
				$utype = 'System Defined';
				$action = '<i class="fa fa-ban"></i>';
				$checked = ($aclist->acc_status==1 ? 'checked="checked" disabled="true"' : 'disabled="true"' );
			}
			$fin_statement = ($aclist->fin_statement==1 ? 'Balance Sheet' : ($aclist->fin_statement==2 ? 'Profit & Loss' : 'Other/NA'));
			$statement_category = ($aclist->statement_category ? $categoryArr[$aclist->statement_category] : 'N/A');
			$data[] = array(
				"slno"=>$slno + 1,
				"acctypename"=>$aclist->master_name,
				"fin_statement"=>$fin_statement,
				"statement_category"=>$statement_category,
				"utype"=>$utype,
				"status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$aclist->acc_id.'" '.$checked.'/>',
				"action"=>$action
			);
			$slno++;
		}
		// Response
		$ListAccType = $this->accountsModel->AllAccTypeCount();
		$totalRecords = $ListAccType->acctypenos;
		$ListFilterAccType = $this->accountsModel->AllAccTypeFilterCount($filter);
		$totalRecordwithFilter=$ListFilterAccType->filterrolesnos;
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