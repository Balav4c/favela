<?php
namespace App\Controllers\UserMobile;
use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserMobile\PaymentsModel;
use App\Models\UserMobile\TokenVerifyModel;
use App\Libraries\JWT;

class Payments extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->TokenModel = new TokenVerifyModel();	
		$this->PaymentsModel = new PaymentsModel();	
	}
	
	public function getPaymentsRequest() {
		
		$headers = $this->request->headers();
		$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
		$fv_id = isset($data['fv_id']) ? $data['fv_id'] : $this->request->getPost("fv_id");
		$tokencheck = $this->TokenModel->verifyToken($token, $user_id);
		if($tokencheck) {
			
			if($user_id && $fv_id) {
				
				$fdate = date("Y-m-01");
				$tdate = date("Y-m-31");
				$getPaymentReqs = $this->PaymentsModel->getPayReqs($user_id, $fv_id, $fdate, $tdate);
				$response = [
					'status'=>200,
					'success' => true,
					'data' => $getPaymentReqs 
				]; 
			}	
			else{
				$response = [
					'status'=>500,
					'success' => true,
					'message' => 'User or flat not found in the system.' 
				]; 
			}
		}
		else {
			$response = [
				'status'=>500,
				'success' => true,
				'message' => 'Unautherised token authentication.' 
			]; 
		}
		$this->response->setStatusCode(200)->setJSON($response)->send();
	}
	public function getPaymentDetails() {
		
		$headers = $this->request->headers();
		$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
		$fv_id = isset($data['fv_id']) ? $data['fv_id'] : $this->request->getPost("fv_id");
		$prc_id = isset($data['prc_id']) ? $data['prc_id'] : $this->request->getPost("prc_id");
		$tokencheck = $this->TokenModel->verifyToken($token, $user_id);
		if($tokencheck) {
			if($user_id && $fv_id) {
				
				$getpaymentdetails = $this->PaymentsModel->getpaymentinfo($prc_id, $user_id, $fv_id);
				
				$response = [
					'status'=>200,
					'success' => true,
					'data' => $getpaymentdetails
				]; 
			}	
			else{
				$response = [
					'status'=>500,
					'success' => true,
					'message' => 'User or flat not found in the system.' 
				]; 
			}
		}
		else {
			$response = [
				'status'=>500,
				'success' => true,
				'message' => 'Unautherised token authentication.' 
			]; 
		}
		$this->response->setStatusCode(200)->setJSON($response)->send();
			
	}
	public function updatePayment() {
		
		$headers = $this->request->headers();
		$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
		$fv_id = isset($data['fv_id']) ? $data['fv_id'] : $this->request->getPost("fv_id");
		$pr_id = isset($data['pr_id']) ? $data['pr_id'] : $this->request->getPost("pr_id");
		$amount = isset($data['amount']) ? $data['amount'] : $this->request->getPost("amount");
		$paymentType = isset($data['paytype']) ? $data['paytype'] : $this->request->getPost("paytype");
		$transaction_ref = isset($data['transaction_ref']) ? $data['transaction_ref'] : $this->request->getPost("transaction_ref");
		$tokencheck = $this->TokenModel->verifyToken($token, $user_id);
		
		if($tokencheck) {
			if($user_id && $fv_id) {
				
				$paydata  = [
					"paytype" => $paymentType,
					"fv_id" => $fv_id,
					"pr_id" => $pr_id,
					"amount_paid" => $amount,
					"uid" => $user_id,
					"transaction_referece"=>$transaction_ref,
					"paid_on" => date("Y-m-d H:i:s"),
					"status" => 1
				];
				
				$target_dir = "paymentreceipts/";
				$attachemntName = (isset($_FILES["attachment"]["name"]) ? basename($_FILES["attachment"]["name"]) : '');
				$paydata['attachment'] = NULL;
				if($attachemntName && $paymentType == 2) {
					
					$FileType = strtolower(pathinfo($attachemntName,PATHINFO_EXTENSION));
					$target_file = $target_dir . $fv_id . strtotime(date("Y-m-d")). $user_id . "." . $FileType;
					$checkFile = getimagesize($_FILES["attachment"]["tmp_name"]);
					if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf") {
						$response = [
							'status'=>500,
							'success' => true,
							'message' => 'Only pdf or image is allowed to attach.' 
						];
					}
					else {
						if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
							$paydata['attachment'] = $target_file;
						}
					}
				}
				$savepayment = $this->PaymentsModel->updatePayment($paydata);
				$response = [
					'status'=>200,
					'success' => true,
					'message' => 'Payment information updated successfully.'
				];
			}
			else {
				$response = [
					'status'=>500,
					'success' => true,
					'message' => 'User or flat not found in the system.' 
				]; 
			}
		}
		else {
			$response = [
				'status'=>500,
				'success' => true,
				'message' => 'Unautherised token authentication.' 
			]; 
		}
		$this->response->setStatusCode(200)->setJSON($response)->send();
	}
}
