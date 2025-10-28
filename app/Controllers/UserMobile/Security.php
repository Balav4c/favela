<?php
namespace App\Controllers\UserMobile;

use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserMobile\SecurityModel;
use App\Models\UserMobile\TokenVerifyModel;
use App\Libraries\JWT;



class Security extends BaseController {

    protected $SecurityModel;

    public function __construct() {
        // $this->SecurityModel = new SecurityModel();
        // $this->request = \Config\Services::request();
        // $this->TokenModel = new TokenVerifyModel();	
        		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->TokenModel = new TokenVerifyModel();	
		$this->SecurityModel = new SecurityModel();	
    }

    /**
     * Step 1: Check if MPIN exists for the user
     * (This is the login check before MPIN creation)
     */
   public function loginapi() {
    $json_data = $this->request->getBody();
    $data = json_decode($json_data, true);

    $phone = $data['phone'] ?? $this->request->getPost("phone");

    if (!$phone) {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 400,
            'success' => false,
            'message' => 'Phone number is required.'
        ]);
    }

    // Fetch user by phone
    $security = $this->SecurityModel->getSecurityByPhone($phone);

    if (!$security) {
        return $this->response->setStatusCode(404)->setJSON([
            'status' => 404,
            'success' => false,
            'message' => 'Phone number is invalid',
            'mpinstatus' => null
        ]);
    }

    // Check MPIN availability
    if (!empty($security['mpin'])) {
        // MPIN exists
        $response = [
            'status' => 200,
            'success' => true,
            'message' => 'Mpin already exists',
            'mpinstatus' => 0
        ];
    } else {
        // MPIN not created yet
        $response = [
            'status' => 200,
            'success' => true,
            'message' => 'Please create Mpin',
            'mpinstatus' => 1
        ];
    }

    return $this->response->setStatusCode(200)->setJSON($response);
}


    /**
     * Step 2: Create MPIN
     */
    public function createMpin() {
        $json_data = $this->request->getBody();
        $data = json_decode($json_data, true);

        $phone = $data['phone'] ?? $this->request->getPost("phone");
        $mpin  = $data['mpin'] ?? $this->request->getPost("mpin");

        if (!$phone || !$mpin) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 400,
                'success' => false,
                'message' => 'Phone and MPIN are required.'
            ]);
        }

        // Hash MPIN before saving
        $hashedMpin = password_hash($mpin, PASSWORD_DEFAULT);
        $updated = $this->SecurityModel->updateMpin($phone, $hashedMpin);

        if ($updated) {
            return $this->response->setJSON([
                'status' => 200,
                'success' => true,
                'message' => 'MPIN created successfully.'
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON([
            'status' => 500,
            'success' => false,
            'message' => 'Failed to create MPIN.'
        ]);
    }

    /**
     * Step 3: Verify MPIN for login
     */
   public function verifympinapi() {
    $json_data = $this->request->getBody();
    $data = json_decode($json_data, true);

    $phone = $data['phone'] ?? $this->request->getPost('phone');
    $mpin  = $data['mpin'] ?? $this->request->getPost('mpin');
    $fcm_token = $data['fcm_token'] ?? $this->request->getPost('fcm_token');

    if (!$phone || !$mpin) {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 400,
            'success' => false,
            'message' => 'Phone and MPIN are required.'
        ]);
    }

    // Get security record
    $security = $this->SecurityModel->getSecurityByPhone($phone);

    if ($security && password_verify($mpin, $security['mpin'])) {
        // Generate JWT token
        $jwt = new JWT();
        $token = $jwt->encode([
            'iat' => time(),
            'exp' => time() + 3600, // 1 hour expiry
            'data' => ['security_id' => $security['sc_id']]
        ]);

        // Update token in DB
        $this->SecurityModel->updateToken($security['sc_id'], $token);

        // Prepare formatted data
        $response = [
            'status' => 200,
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'uid' => $security['sc_id'],
                'name' => $security['security_name'],
                'phone' => $security['security_phone'],
                'id_proof' => $security['id_proof'],
                'id_proof_no' => $security['id_proof_no'],
                'security_company' => $security['security_company'],
                'security_company_address' => $security['security_company_address'],
                'status' => $security['status'],
                'feedback' => $security['feedback'],
            ],
            'token' => $token
        ];

    } else {
        $response = [
            'status' => 401,
            'success' => false,
            'message' => 'Invalid phone or MPIN.'
        ];
    }

    return $this->response->setJSON($response);
}


/**
 * Step 4: Check MPIN (whether exists or not)
 */
public function checkMpin() {
    $json_data = $this->request->getBody();
    $data = json_decode($json_data, true);
    $phone = $data['phone'] ?? $this->request->getPost('phone');

    if (!$phone) {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 400,
            'success' => false,
            'message' => 'Phone number is required.'
        ]);
    }

    $security = $this->SecurityModel->getSecurityByPhone($phone);
    if (!$security) {
        return $this->response->setStatusCode(404)->setJSON([
            'status' => 404,
            'success' => false,
            'message' => 'Phone number not found.'
        ]);
    }

    $mpinExists = !empty($security['mpin']) ? 1 : 0;
    return $this->response->setJSON([
        'status' => 200,
        'success' => true,
        'mpin' => $mpinExists
    ]);
}


/**
 * Step 5: Change MPIN
 */
public function changeMpin() {
    $headers = $this->request->headers();
    $authorization = $headers['Authorization'] ?? '';
    $token = '';

    if (strpos($authorization, 'Bearer') !== false) {
        $parts = explode('Bearer', $authorization);
        $token = trim($parts[1]);
    }

    $json_data = $this->request->getBody();
    $data = json_decode($json_data, true);

    $user_id = $data['uid'] ?? $this->request->getPost('uid');
    $currentMpin = $data['currentMpin'] ?? $this->request->getPost('currentMpin');
    $newMpin = $data['newMpin'] ?? $this->request->getPost('newMpin');

    if (!$user_id || !$currentMpin || !$newMpin) {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 400,
            'success' => false,
            'message' => 'User ID, current MPIN, and new MPIN are required.'
        ]);
    }

    // Verify token
    $security = $this->SecurityModel->getSecurityById($user_id);
    if (!$security || $security['token'] !== $token) {
        return $this->response->setStatusCode(401)->setJSON([
            'status' => 401,
            'success' => false,
            'message' => 'Unauthorized token authentication.'
        ]);
    }

    if ($currentMpin === $newMpin) {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 400,
            'success' => false,
            'message' => 'Current MPIN and new MPIN should not be the same.'
        ]);
    }

    // Verify current MPIN
    if (!password_verify($currentMpin, $security['mpin'])) {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 400,
            'success' => false,
            'message' => 'Current MPIN does not match.'
        ]);
    }

    // Update with new MPIN
    $hashedMpin = password_hash($newMpin, PASSWORD_DEFAULT);
    $updated = $this->SecurityModel->updateMpinById($user_id, $hashedMpin);

    if ($updated) {
        return $this->response->setJSON([
            'status' => 200,
            'success' => true,
            'message' => 'MPIN changed successfully.'
        ]);
    }

    return $this->response->setStatusCode(500)->setJSON([
        'status' => 500,
        'success' => false,
        'message' => 'Failed to change MPIN.'
    ]);
}



	public function getSecurityResidences() {
		
		$headers = $this->request->headers();
		$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
		$tokencheck = $this->TokenModel->verifySecurityToken($token, $user_id);
       
		if($tokencheck) {
			
			if($user_id) {
				
				$getOrgs = $this->SecurityModel->getOrgsList($user_id);
              
				if($getOrgs) {
					$OrgsArr = array();
					foreach($getOrgs as $orgs) {
						$fv_id = $orgs->fv_id;
						$url = MASTER_APP_URI . 'residenceinfo.php';
						$curl = curl_init($url);
						curl_setopt($curl, CURLOPT_POST, true);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						$data = "fv_id=".$fv_id;
						curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
						$result = curl_exec($curl);
						$resultArr = json_decode($result);
						curl_close($curl);
						$dataArr = array("fv_id"=>$fv_id,"fv_data"=>$resultArr);
						array_push($OrgsArr, $dataArr);
					}
				}
				$response = [
					'status'=>200,
					'success' => true,
					'data' => $OrgsArr 
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



}
