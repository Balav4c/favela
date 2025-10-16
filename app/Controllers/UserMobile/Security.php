<?php
namespace App\Controllers\UserMobile;

use App\Controllers\BaseController;
use App\Models\UserMobile\SecurityModel;
use App\Libraries\JWT;

class Security extends BaseController {
    
    public function __construct() {
        $this->SecurityModel = new SecurityModel();
        $this->request = \Config\Services::request();
    }

    // Create or update MPIN
    public function createMpin() {
        $json_data = $this->request->getBody();
        $data = json_decode($json_data, true);

        $phone = $data['phone'] ?? $this->request->getPost("phone");
        $mpin  = $data['mpin'] ?? $this->request->getPost("mpin");

        if ($phone && $mpin) {
            // Hash the MPIN before storing
            $hashedMpin = password_hash($mpin, PASSWORD_DEFAULT);
            $this->SecurityModel->updateMpin($phone, $hashedMpin);

            $response = [
                'status' => 200,
                'success' => true,
                'message' => 'MPIN created successfully.'
            ]; 
        } else {
            $response = [
                'status' => 400,
                'success' => false,
                'message' => 'Phone and MPIN are required.'
            ]; 
        }

        return $this->response->setJSON($response);
    }

    // Verify MPIN for login
    public function verifympinapi() {
        $json_data = $this->request->getBody();
        $data = json_decode($json_data, true);

        $phone = $data['phone'] ?? $this->request->getPost('phone');
        $mpin  = $data['mpin'] ?? $this->request->getPost('mpin');

        if (!$phone || !$mpin) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 400,
                'success' => false,
                'message' => 'Phone and MPIN are required.'
            ]);
        }

        // Get security user by phone
        $security = $this->SecurityModel->getSecurityByPhone($phone);

        if ($security && password_verify($mpin, $security['mpin'])) {
            // Generate JWT token
            $jwt = new JWT();
            $token = $jwt->encode(['security_id' => $security['sc_id'], 'time' => time()]);

            // Store token in DB
            $this->SecurityModel->updateToken($security['sc_id'], $token);

            $response = [
                'status' => 200,
                'success' => true,
                'message' => 'Login successful',
                'data' => $security,
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
