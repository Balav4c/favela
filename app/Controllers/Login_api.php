<?php
namespace App\Controllers;
use App\Models\AuthModel;
use App\Models\LoginModel;
use App\Libraries\JWT;

class Login_api extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->LoginModel = new LoginModel();	
	}
	// Apies 
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
					'message' => 'Enter Mpin',
				];
			} else {
				$response = [
					'status' => 200,
					'success' => true,
					'message' => 'Create Mpin',
				];
			}
		} else {
			$response = [
				'status' => 500,
				'success' => true,
				'message' => 'Phone number is invalid',
			];
		}
		// Set response code and send JSON response
		return $this->response->setStatusCode(200)->setJSON($response);
	}

	public function verifympinapi(){
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$phone = isset($data['phone']) ? $data['phone'] : $this->request->getPost("phone");
		$mpin = isset($data['mpin']) ? $data['mpin'] : $this->request->getPost("mpin");
		$user =  $this->LoginModel->verifyUsermpin($phone,$mpin);
		if($user) {
			
			$jwt = new JWT();
			$token = $jwt->encode(['user_id' => $user->uid ]);
			//echo "hi";exit();
			$response = [
				'status' => 200,
				'success' => true,
				'data' => $user,
				'token' => $token,
			];
		}
		else{
			//echo('hi');
			$response = [
				'status' => 500,
				'success' => true,
				'message' => 'Invalid phone or mpin',
			];
		}
		$this->response->setStatusCode(200)->setJSON($response)->send();
	}
	/* 
	public function loginapi(){
		$phone = $this->request->getPost('phone');
		$user = $this->LoginModel->verifyUser($phone);
		if ($user) {
			$mpin = $user->m_pin;
			if ($mpin) {
				$jwt = new JWT();
				$token = $jwt->encode(['user_id' => $user->uid ]);
				$response = [
					'status' => 200,
					'success' => true,
					'data' => $user,
					'token' => $token,
				];
			} 
			else {
				$response = [
					'status' => 200,
					'success' => true,
					'message' => 'Create M PIN',
				];
			}
		} 
		else {
			$response = [
				'status' => 500,
				'success' => true,
				'message' => 'No User Exist',
			];
		}
		$this->response->setStatusCode(200)->setJSON($response)->send();
	}
	*/
}
