<?php
namespace App\Controllers\UserMobile;
use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserMobile\NotificationModel;
use App\Models\UserMobile\TokenVerifyModel;
use App\Libraries\JWT;

class Notifications extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->TokenModel = new TokenVerifyModel();	
		$this->NotifyModel = new NotificationModel();	
	}
	public function getnotifications() {
		
		$headers = $this->request->headers();
		$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
		$fv_id = isset($data['fv_id']) ? $data['fv_id'] : $this->request->getPost("fv_id");
		$tokencheck = $this->TokenModel->verifyToken($token, $user_id);
		if($tokencheck) {
			
			if($user_id && $fv_id) {
				
				$getNotifications = $this->NotifyModel->loadNotifications($user_id, $fv_id);
				$response = [
					'status'=>200,
					'success' => true,
					'data' => $getNotifications 
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
	public function processNotify(){
		
		$headers = $this->request->headers();
		$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
		$fv_id = isset($data['fv_id']) ? $data['fv_id'] : $this->request->getPost("fv_id");
		$status = isset($data['status']) ? $data['status'] : $this->request->getPost("status");
		$nid = isset($data['nid']) ? $data['nid'] : $this->request->getPost("nid");
		$tokencheck = $this->TokenModel->verifyToken($token, $user_id);
		if($tokencheck) {
			
			if($user_id && $fv_id) {
				
				$checkNofity = $this->NotifyModel->checkNotifyStatus($user_id, $nid, $fv_id);
				if($checkNofity) {
					$data = [
						"status" => $status
					];
					$updateNotify = $this->NotifyModel->updateNofityStatus($checkNofity->ns_id, $data);
				}
				else {
					$data = [
						"status" => $status,
						"fv_id" => $fv_id,
						"notify_id" => $nid,
						"us_id" => $user_id
					];
					$createNofityStatus = $this->NotifyModel->createNofityStatus($data);
				}
				
				$response = [
					'status'=>200,
					'success' => true,
					'data' => 1 
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
