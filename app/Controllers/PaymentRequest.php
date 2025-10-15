<?php
namespace App\Controllers;
use App\Models\PaymentRequestModel;

class PaymentRequest extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->payrequestModel = new PaymentRequestModel();	
		 
	}
	public function index($pr_id=""){
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$fv_id = $this->session->get('fav_id');
			if($pr_id) {
				$getPayRequest = $this->payrequestModel->loadThisPayRequest($pr_id, $fv_id);
				if($getPayRequest) {
					$data['payment_type'] = $getPayRequest->payment_type;
					$data['paynotes'] = $getPayRequest->payment_notes;
					$data['amount'] = $getPayRequest->amount;
					$data['request_to'] = $getPayRequest->request_to;
					$data['uid'] = $getPayRequest->uid;
					$data['uname'] = $getPayRequest->name;
					$data['pr_id'] = $getPayRequest->pr_id;
				}
				else {
					$data['payment_type'] = null;
					$data['paynotes'] = null;
					$data['amount'] = null;
					$data['request_to'] = null;
					$data['uid'] = null;
					$data['uname'] = null;
					$data['pr_id'] = null;
				}
			}
			else {
				$data['payment_type'] = null;
				$data['paynotes'] = null;
				$data['amount'] = null;
				$data['request_to'] = null;
				$data['uid'] = null;
				$data['uname'] = null;
				$data['pr_id'] = null;
			}
			
			$data['menu'] = 8;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			
			$template = view('common/header',$data);
			$template .= view('paymentrequest');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/paymentrequestjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
    }
	public function savepayrequest() {
		
		$payid = $this->input->getPost("payid");
		$data['payment_type'] = $this->input->getPost('payment_type');
		$data['payment_notes'] = $this->input->getPost('paynotes');
		$data['amount'] = $this->input->getPost('amount');
		$data['request_to'] = $this->input->getPost('request_to');
		$data['uid'] = $this->input->getPost('residence_hd');
		$data['created_on'] = date("Y-m-d H:i:s");
		$data['created_by'] = $this->session->get('fav_user_id');
		$data['fv_id'] = $this->session->get('fav_id');
		$data['status'] = 1;
		if($data['payment_type'] && $data['amount'] && $data['request_to']) {
			
			if($payid) {
				$savepayrequest = $this->payrequestModel->updatepayrequest($data, $payid);
				echo json_encode(array("status"=>1,"respmsg"=>"Payment request updated & notification send successfully."));
			}
			else {
				$savepayrequest = $this->payrequestModel->savepayrequest($data);
				echo json_encode(array("status"=>1,"respmsg"=>"Payment request created & notification send successfully."));
			}
		}
		else {
			echo json_encode(array("status"=>0,"respmsg"=>"Please enter the payment type, amount and request to."));
		}
	}
	public function getResidents() {
		$fv_id = $this->session->get('fav_id');
		$searchkey = $this->input->getPost('searchkey');
		if($searchkey) {
			$residents_res = $this->payrequestModel->getResidents($fv_id, $searchkey);
			if($residents_res) {
				echo json_encode(array("status"=>1, "resdata"=>$residents_res));
			}
			else {
				echo json_encode(array("status"=>0, "resdata"=>null));
			}
		}
		else {
			echo json_encode(array("status"=>0, "resdata"=>null));
		}
	}
	public function deletePayrequest($pr_id='') {
		
		if($pr_id) {
			$data['status'] = 3;
			$savepayrequest = $this->payrequestModel->updatepayrequest($data, $pr_id);
			echo json_encode(1);
		}
	}
	public function changeStatus() {
		
		$data['status'] = $this->input->getPost('pay_status');
		$pr_id = $this->input->getPost('pr_id');
		$savepayrequest = $this->payrequestModel->updatepayrequest($data, $pr_id);
		echo json_encode(1);
	}
	public function listpayrequest() {
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
			$filter .= " and pr.payment_notes like '%".$searchValue."%' OR us.name like '%".$searchValue."%'";
		}
		$filter .= " and pr.fv_id = ".$this->session->get('fav_id');
		$ListPayReq = $this->payrequestModel->listAllPayRequest($filter, $row, $tolimit);
		$data = array();
		$slno = $row;
		$request_toArr = array("1"=>"All Residents", "2"=>"Individuals");
		$payment_typeArr = array("1"=>"Monthly", "2"=>"Yearly", "3"=>"Quarterly", "4"=>"Weekly", "5"=>"One Type");
		
		foreach($ListPayReq as $payrqlist) {
			$action = '<a href="'.base_url("paymentrequest/$payrqlist->pr_id").'"><i class="fa fa-pencil-square-o"></i></a>';
			$action .= '<a href="javascript:void(0)" onclick="deletePayrequest('.$payrqlist->pr_id.')"><i class="fa fa-trash-o"></i></a>';
			$checked = ($payrqlist->status==1 ? 'checked="checked"' : '' );
			$data[] = array(
				"slno"=>$slno + 1,
				"payment_type"=>$payment_typeArr[$payrqlist->payment_type],
				"payment_notes"=>ucfirst($payrqlist->payment_notes),
				"amount"=>$payrqlist->amount,
				"request_to"=>$request_toArr[$payrqlist->request_to],
				"user_app"=>($payrqlist->request_to == 1 ? 'All Residents' : $payrqlist->name),
				"created_on"=>date("d-m-Y H:i:s",strtotime($payrqlist->created_on)),
				"status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$payrqlist->pr_id.'" '.$checked.'/>',
				"action"=>$action
			);
			$slno++;
		}
		// Response
	
		$Listpayreqcount = $this->payrequestModel->AllPayreqCount();
		$totalRecords = $Listpayreqcount->prnos;
		$ListFilterPayreq = $this->payrequestModel->AllPayreqFilterCount($filter);
		$totalRecordwithFilter=$ListFilterPayreq->filterpayreqsnos;
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