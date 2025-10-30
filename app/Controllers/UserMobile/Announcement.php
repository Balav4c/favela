<?php
namespace App\Controllers\UserMobile;

use App\Controllers\BaseController;
use App\Models\UserMobile\AnnouncementModel;
use App\Models\UserMobile\TokenVerifyModel;

class Announcement extends BaseController {

    public function __construct() {
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
        $this->TokenModel = new TokenVerifyModel();
        $this->AnnounceModel = new AnnouncementModel();
    }

   public function getAnnouncements() {
    $headers = $this->request->headers();
    $token = trim(explode("Bearer", $headers['Authorization'])[1]);
    $json_data = $this->request->getBody();
    $data = json_decode($json_data, true);

    $user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
    $fv_id   = isset($data['fv_id']) ? $data['fv_id'] : $this->request->getPost("fv_id");

    $tokencheck = $this->TokenModel->verifyToken($token, $user_id);

    if ($tokencheck) {
        if ($user_id && $fv_id) {
            $getAnnouncements = $this->AnnounceModel->loadAnnouncements($user_id, $fv_id);

            if (!empty($getAnnouncements)) {
                $response = [
                    'status'  => 200,
                    'success' => true,
                    'data'    => $getAnnouncements
                ];
            } else {
                // No announcements found / all expired
                $response = [
                    'status'  => 200,
                    'success' => true,
                    'data'    => [],
                    'message' => 'No active announcements found for this flat.'
                ];
            }
        } else {
            $response = [
                'status'  => 500,
                'success' => false,
                'message' => 'User or flat not found in the system.'
            ];
        }
    } else {
        $response = [
            'status'  => 500,
            'success' => false,
            'message' => 'Unauthorised token authentication.'
        ];
    }

    return $this->response->setStatusCode(200)->setJSON($response);
}
 public function getSecurityAnnouncements() {
        $headers = $this->request->headers();
        $token = trim(explode("Bearer", $headers['Authorization'])[1]);
        $json_data = $this->request->getBody();
        $data = json_decode($json_data, true);

        $security_id = isset($data['sc_id']) ? $data['sc_id'] : $this->request->getPost("sc_id");
        $fv_id       = isset($data['fv_id']) ? $data['fv_id'] : $this->request->getPost("fv_id");

        $tokencheck = $this->TokenModel->verifySecurityToken($token, $security_id);

        if ($tokencheck) {
            $getAnnouncements = $this->AnnounceModel->loadSecurityAnnouncements($fv_id);

            if (!empty($getAnnouncements)) {
                $response = [
                    'status'  => 200,
                    'success' => true,
                    'data'    => $getAnnouncements
                ];
            } else {
                $response = [
                    'status'  => 200,
                    'success' => true,
                    'data'    => [],
                    'message' => 'No active announcements found for this flat.'
                ];
            }
        } else {
            $response = [
                'status'  => 500,
                'success' => false,
                'message' => 'Unauthorized token authentication.'
            ];
        }

        return $this->response->setStatusCode(200)->setJSON($response);
    }
}


