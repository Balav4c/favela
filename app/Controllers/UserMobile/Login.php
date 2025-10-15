<?php
namespace App\Controllers\UserMobile;
use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserMobile\LoginModel;
use App\Models\UserMobile\TokenVerifyModel;
use App\Libraries\JWT;

class Login extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->TokenModel = new TokenVerifyModel();	
		$this->LoginModel = new LoginModel();	
	}
	public function phonenumbervalidapi(){
		$phone = $this->request->getPost('phone');
		$user = $this->LoginModel->verifyUser($phone);
		if($user) {
			$response = [
					'status' => 200,
					'success' => true,
					'message' => 'Phone number is valid',
			];
		}
		else{
			$response = [
					'status' => 500,
					'success' => False,
					'message' => 'Phone number is invalid',
			];
		}
	}
	public function loginapi() {
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$phone = isset($data['phone']) ? $data['phone'] : $this->request->getPost("phone");
		$user = $this->LoginModel->verifyUser($phone);
		if ($user) {
			$mpin = $user->m_pin;
			if ($mpin) {
				$response = [
					'status' => 200,
					'success' => true,
					'message' => 'Mpin already exist',
					'mpinstatus'=>0
				];
			} else {
				$response = [
					'status' => 200,
					'success' => true,
					'message' => 'Please enter Mpin',
					'mpinstatus'=>1
				];
			}
		} else {
			$response = [
				'status' => 500,
				'success' => true,
				'message' => 'Phone number is invalid',
			];
		}
		return $this->response->setStatusCode(200)->setJSON($response);
	}
//backup code
	public function verifympinapi(){

		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$phone = isset($data['phone']) ? $data['phone'] : $this->request->getPost("phone");
		$mpin = isset($data['mpin']) ? $data['mpin'] : $this->request->getPost("mpin");
		$user =  $this->LoginModel->verifyUsermpin($phone,$mpin);
		if($user) {
			
			$jwt = new JWT();
			$token = $jwt->encode(['user_id' => $user->uid . strtotime('d-m-Y His')]);
			$data = [
				'token' => $token
			];
			$updateToken = $this->LoginModel->updateUserAcc($data, $user->uid);
			$response = [
				'status' => 200,
				'success' => true,
				'data' => $user,
				'token' => $token,
			];
		}
		else{
			$response = [
				'status' => 500,
				'success' => true,
				'message' => 'Invalid phone or mpin',
			];
		}
		$this->response->setStatusCode(200)->setJSON($response)->send();
	}
	//verifympin with fv_id
	// public function verifympinapi() {
	// 	$json_data = $this->request->getBody();
	// 	$data = json_decode($json_data, true);
	
	//     $phone = isset($data['phone']) ? $data['phone'] : $this->request->getPost("phone");
	// 	$mpin = isset($data['mpin']) ? $data['mpin'] : $this->request->getPost("mpin");
	
		
	// 	$user = $this->LoginModel->verifyUsermpin($phone, $mpin);
	
	// 	if ($user) {
			
	// 		$jwt = new JWT();
	// 		$token = $jwt->encode(['user_id' => $user->uid . time()]); // Fixed encoding time()
	//         $updateData = ['token' => $token];
	// 		$this->LoginModel->updateUserAcc($updateData, $user->uid);
	// 		$updatedUser = $this->LoginModel->getFvId($user->uid);
	// 		$fv_id = $updatedUser ? $updatedUser->fv_id : null; 
	
	// 		// Response
	// 		$response = [
	// 			'status' => 200,
	// 			'success' => true,
	// 			'data' => $user,
	// 			'token' => $token,
	// 			'fv_id' => $fv_id 
	// 		];
	// 	} else {
	// 		$response = [
	// 			'status' => 500,
	// 			'success' => false,
	// 			'message' => 'Invalid phone or MPIN',
	// 		];
	// 	}
	
	// 	return $this->response->setStatusCode(200)->setJSON($response);
	// }
	
	
	
	
	public function getprofile() {
		
		$headers = $this->request->headers();
		$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
		$tokencheck = $this->TokenModel->verifyToken($token, $user_id);
		if($tokencheck) {
			if($user_id) {
				$getuser = $this->LoginModel->getThisProfile($user_id);
				$response = [
					'status' => 200,
					'success' => true,
					'data' => $getuser
				];
			}
			else{
				$response = [
					'status'=>500,
					'success' => true,
					'message' => 'User not found in the system.' 
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
	
	public function updateprofile() {
		
		$headers = $this->request->headers();
		$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
		$tokencheck = $this->TokenModel->verifyToken($token, $user_id);
		if($tokencheck) {
			if($user_id) {
				$data = [
					"name" => isset($data['name']) ? $data['name'] : $this->request->getPost("name"),
					"gender" => isset($data['gender']) ? $data['gender'] : $this->request->getPost("gender"),
					"dob" => isset($data['dob']) ? $data['dob'] : $this->request->getPost("dob"),
					"email_id" => isset($data['email_id']) ? $data['email_id'] : $this->request->getPost("email_id"),
					"c_address" => isset($data['c_address']) ? $data['c_address'] : $this->request->getPost("c_address"),
				];
				$updateProfile = $this->LoginModel->updateUserAcc($data, $user_id);
				
				$response = [
					'status' => 200,
					'success' => true,
					'message' => "Profile updated successfully"
				];
			}
			else {
				$response = [
					'status'=>500,
					'success' => true,
					'message' => 'User not found in the system.' 
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
	
	public function createMpin() {
		$headers = $this->request->headers();
	    //$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$phone = isset($data['phone']) ? $data['phone'] : $this->request->getPost("phone");
		$mpin = isset($data['mpin']) ? $data['mpin'] : $this->request->getPost("mpin");
		if($mpin) {
			$data = [
				'm_pin' => $mpin
			];
			$this->LoginModel->updatempin($data, $phone);
			$response = [
				'status'=>200,
				'success' => true,
				'message' => 'MPIN created successfully.' 
			]; 
		}
		else {
			$response = [
				'status'=>500,
				'success' => true,
				'message' => 'MPIN not found.' 
			]; 
		}
		
		$this->response->setStatusCode(200)->setJSON($response)->send();
	}
	
	public function changeMpin() {
		
		$headers = $this->request->headers();
		$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
		$tokencheck = $this->TokenModel->verifyToken($token, $user_id);
		if($tokencheck) {
			if($data['currentMpin']!= $data['newMpin']) {
				
				$checkCurrentPin = $this->LoginModel->checkCurrentPin($data['currentMpin'], $user_id);
				if($checkCurrentPin) {
					$data = [
						"m_pin" => isset($data['newMpin']) ? $data['newMpin'] : $this->request->getPost("newMpin")
					];
					$updateProfile = $this->LoginModel->updateUserAcc($data, $user_id);
					$response = [
						'status'=>500,
						'success' => true,
						'message' => 'MPIN changed successfully.' 
					]; 
				}
				else {
					$response = [
						'status'=>500,
						'success' => true,
						'message' => 'Current mpin not matching with the profile.' 
					]; 
				}
			}
			else {
				$response = [
					'status'=>500,
					'success' => true,
					'message' => 'Current mpin and new mpin should not be different.' 
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
	
	public function checkeMpin() { 
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$phone = isset($data['phone']) ? $data['phone'] : $this->request->getPost("phone");
		if($phone) {
			$checkNumber = $this->LoginModel->verifyUser($phone);
			$status = 0;
			if($checkNumber->m_pin) {
				$status = 1;
			}
			$response = [
				'status'=>200,
				'success' => true,
				'mpin' => $status
			]; 
		}
		else {
			$response = [
				'status'=>500,
				'success' => true,
				'message' => 'Phone number missing.' 
			]; 
		}
		$this->response->setStatusCode(200)->setJSON($response)->send();
	}
}
