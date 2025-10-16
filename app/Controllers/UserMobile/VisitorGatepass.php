<?php
namespace App\Controllers\UserMobile;
use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserMobile\GatepassModel;
use App\Models\UserMobile\TokenVerifyModel;
use App\Libraries\JWT;

class VisitorGatepass extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->TokenModel = new TokenVerifyModel();	
		$this->GatepassModel = new GatepassModel();	
	}
	
	public function getGatepass() {
		$fv_id = $this->request->getPost('fv_id');

		if ($fv_id) {
			$gatepass = $this->GatepassModel->getGatepass($fv_id);

			$response = [
				'status'  => 200,
				'success' => true,
				'data'    => $gatepass
			];
			return $this->response->setStatusCode(200)->setJSON($response);
		} else {
			$response = [
				'status'  => 400,
				'success' => false,
				'message' => 'Missing fv_id.'
			];
			return $this->response->setStatusCode(400)->setJSON($response);
		}
	}

	
	public function createnew()
{
	$visitor = $this->request->getPost("visitor_name");
	$purpose = $this->request->getPost("purpose_of_visit");
	$person_flat = $this->request->getPost("person_flat_visit");
	$fv_id = $this->request->getPost("fv_id");
	$place = $this->request->getPost("visitor_place");
	$phone = $this->request->getPost("vistor_phone");
	$dateofvisit = $this->request->getPost("date_of_visit");
	$created_by = $this->request->getPost("created_by");
	
	

	if ($visitor && $purpose && $person_flat && $fv_id && $created_by) {

		// Create unique token
		$token = $fv_id . strtotime(date("Y-m-d H:i:s.")) . gettimeofday()['usec'];

		$data = [
			"fv_id" => $fv_id,
			"visitor_name" => $visitor,
			"visitor_place" => $place,
			"vistor_phone" => $phone,
			"date_of_visit" => date("Y-m-d", strtotime($dateofvisit)),
			"purpose_of_visit" => $purpose,
			"person_flat_visit" => $person_flat,
			"created_on" => date("Y-m-d H:i:s"),
			"created_by" => $created_by,
			"created_type" => 2,
			"status" => 1,
			"token" => $token
		];

		// Save to database
		$createGatepass = $this->GatepassModel->saveGatepass($data);

		$qrLink = base_url('visitorgatepass/access/' . $token); 
		
		$qrFilePath = FCPATH . 'gatepass/qr-generator/' . $token . '.png'; 
		$ecc = 'H';
		$pixel_size = 10;
		$frame_size = 2;

		
		//$publicQrImage = base_url('gatepass/qr-generator/' . $token . '.png');
		

		$response = [
			'status' => 200,
			'success' => true,
			'message' => 'Visitor gate pass created successfully.',
			'qrlink' => $qrLink,
			//'qr_image' => $publicQrImage, 
			'gpdata' => $createGatepass,
		];

		return $this->response->setStatusCode(200)->setJSON($response);

	} else {
		return $this->response->setStatusCode(400)->setJSON([
			'status' => 400,
			'success' => false,
			'message' => 'Missing required parameters.'
		]);
	}
}

	
    
}