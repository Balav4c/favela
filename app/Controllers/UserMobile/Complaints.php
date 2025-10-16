<?php
namespace App\Controllers\UserMobile;
use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserMobile\ComplaintsModel;
use App\Models\UserMobile\TokenVerifyModel;
use App\Libraries\JWT;

class Complaints extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->TokenModel = new TokenVerifyModel();	
		$this->ComplaintsModel = new ComplaintsModel();	
	}
    public function getComplaints() {
        $headers = $this->request->headers();
        $token = trim(explode("Bearer", $headers['Authorization'])[1]);
    
        $json_data = $this->request->getBody();
        $data = json_decode($json_data, true);
    
        $user_id = isset($data['us_id']) ? $data['us_id'] : $this->request->getPost("us_id");
        $fv_id = isset($data['fv_id']) ? $data['fv_id'] : $this->request->getPost("fv_id");
    
        $tokencheck = $this->TokenModel->verifyToken($token, $user_id);
    
        if ($tokencheck) {
            if ($user_id && $fv_id) {
                $complaints = $this->ComplaintsModel->getAllcomplaints($user_id, $fv_id);
                $response = [
                    'status'  => 200,
                    'success' => true,
                    'data'    => $complaints
                ];
                return $this->response->setStatusCode(200)->setJSON($response);
            } else {
                $response = [
                    'status'  => 400,
                    'success' => false,
                    'message' => 'Missing user ID or feedback ID.'
                ];
                return $this->response->setStatusCode(400)->setJSON($response);
            }
        } else {
            $response = [
                'status'  => 401,
                'success' => false,
                'message' => 'Unauthorized access. Invalid token.'
            ];
            return $this->response->setStatusCode(401)->setJSON($response);
        }
    }


    public function saveComplaints() {
        $headers = $this->request->headers();
        $token = trim(explode("Bearer", $headers['Authorization'])[1]);
    
        $json_data = $this->request->getBody();
        $data = json_decode($json_data, true);
    
        $user_id = $this->request->getPost("us_id");
        $fv_id = $this->request->getPost("fv_id");
        $msg_type = $this->request->getPost("msg_type");
        
        $subject = $this->request->getPost("subject");
       
        $content = $this->request->getPost("content");
        $status = $this->request->getPost("status") ?? 1;
        $action_status = $this->request->getPost("action_status") ?? 1;
    
      $tokencheck = $this->TokenModel->verifyToken($token, $user_id);
       
    
        if (!$tokencheck) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 401,
                'success' => false,
                'message' => 'Unauthorized access. Invalid token.'
            ]);
        }
    
         $insertData = [
            'us_id'         => $user_id,
            'fv_id'         => $fv_id,
            'msg_type'      => $msg_type,
            'subject'       => $subject,
            'content'       => $content,
            'status'        => $status,
            'action_status' => $action_status,
            'created_on'    => date('Y-m-d H:i:s'),
            'created_by'    => $user_id
        ];
       
      $complaints = $this->ComplaintsModel->saveComplaints($insertData);
     
    
        return $this->response->setStatusCode(200)->setJSON([
            'status' => 200,
            'success' => true,
            'data' => $complaints,
            'message' => 'Complaint saved successfully.'
        ]);
    }
    
    
    
	
}