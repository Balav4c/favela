<?php
namespace App\Controllers\UserMobile;

use App\Controllers\BaseController;
use App\Models\UserMobile\SecurityModel;
use App\Libraries\JWT;

class Security extends BaseController {

    protected $SecurityModel;

    public function __construct() {
        $this->SecurityModel = new SecurityModel();
        $this->request = \Config\Services::request();
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

        // Check user in database
        $security = $this->SecurityModel->getSecurityByPhone($phone);

        if (!$security) {
            return $this->response->setJSON([
                'status' => 404,
                'success' => false,
                'availble_mpin' => false,
                'message' => 'User not found.'
            ]);
        }

        // Check if MPIN exists
        if (empty($security['mpin'])) {
            return $this->response->setJSON([
                'status' => 200,
                'success' => true,
                'availble_mpin' => false,
                'message' => 'Create Mpin'
            ]);
        }

        // MPIN already exists
        return $this->response->setJSON([
            'status' => 200,
            'success' => true,
            'availble_mpin' => true,
            'message' => 'Mpin available. Proceed to verify.'
        ]);
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

}
