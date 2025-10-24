<?php
namespace App\Controllers\UserMobile;
use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserMobile\ComplaintsModel;
use App\Models\UserMobile\TokenVerifyModel;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\JWT;

class Complaints extends BaseController {
    use ResponseTrait;
	
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

 


    // public function saveComplaints() {
    //     $headers = $this->request->headers();
    //     $token = trim(explode("Bearer", $headers['Authorization'])[1]);
    
    //     $json_data = $this->request->getBody();
       
    //     $data = json_decode($json_data, true);
        

    
    //     $user_id = $this->request->getPost("us_id");
         
    //     $fv_id = $this->request->getPost("fv_id");
    //     $msg_type = $this->request->getPost("msg_type");
    //     // print_r($msg_type);
    //     // exit;
        
    //     $subject = $this->request->getPost("subject");
    //     // print_r($subject);
    //     // exit;
       
    //     $content = $this->request->getPost("content");
        
    //     $status = $this->request->getPost("status") ?? 1;
    //     $action_status = $this->request->getPost("action_status") ?? 1;
    
    //   $tokencheck = $this->TokenModel->verifyToken($token, $user_id);
       
    
    //     if (!$tokencheck) {
    //         return $this->response->setStatusCode(401)->setJSON([
    //             'status' => 401,
    //             'success' => false,
    //             'message' => 'Unauthorized access. Invalid token.'
    //         ]);
    //     }
    
    //      $insertData = [
    //         'us_id'         => $user_id,
    //         'fv_id'         => $fv_id,
    //         'msg_type'      => $msg_type,
    //         'subject'       => $subject,
    //         'content'       => $content,
    //         'status'        => $status,
    //         'action_status' => $action_status,
    //         'created_on'    => date('Y-m-d H:i:s'),
    //         'created_by'    => $user_id
    //     ];
       
    //   $complaints = $this->ComplaintsModel->saveComplaints($insertData);
     
    
    //     return $this->response->setStatusCode(200)->setJSON([
    //         'status' => 200,
    //         'success' => true,
    //         'data' => $complaints,
    //         'message' => 'Complaint saved successfully.'
    //     ]);
    // }
public function saveComplaints() 
{
    
    // Get token from headers
    $headers = $this->request->getHeaders();
    $token = isset($headers['Authorization']) ? trim(str_replace('Bearer', '', $headers['Authorization']->getValue())) : null;

    if (!$token) {
        return $this->response->setStatusCode(401)->setJSON([
            'status' => 401,
            'success' => false,
            'message' => 'Unauthorized access. No token provided.'
        ]);
    }

    // Get user ID from POST
    $user_id = $this->request->getPost("us_id");

    // Verify token
    $tokencheck = $this->TokenModel->verifyToken($token, $user_id);
    if (!$tokencheck) {
        return $this->response->setStatusCode(401)->setJSON([
            'status' => 401,
            'success' => false,
            'message' => 'Unauthorized access. Invalid token.'
        ]);
    }

    // Collect complaint data
    $fv_id = $this->request->getPost("fv_id");
    $msg_type = $this->request->getPost("msg_type");
    $subject = $this->request->getPost("subject");
    $content = $this->request->getPost("content");
    $status = $this->request->getPost("status") ?? 1;
    $action_status = $this->request->getPost("action_status") ?? 1;

    // Handle file uploads (multiple)
    $uploadedFiles = [];
    $files = $this->request->getFileMultiple('file'); // use file[] in Postman

    if ($files) {
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $mimeType = $file->getMimeType();
                $uploadPath = FCPATH . 'uploads/';
                $uploadPath .= strpos($mimeType, 'image') !== false ? 'images/' : 'videos/';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $newName = $file->getRandomName();
                if ($file->move($uploadPath, $newName)) {
                    // Store only the file name, not full URL
                    $uploadedFiles[] = $newName;
                }
            }
        }
    }

    // Insert complaint data + uploaded file names
    $insertData = [
        'us_id'         => $user_id,
        'fv_id'         => $fv_id,
        'msg_type'      => $msg_type,
        'subject'       => $subject,
        'content'       => $content,
        'status'        => $status,
        'action_status' => $action_status,
        'uploads'       => !empty($uploadedFiles) ? json_encode($uploadedFiles) : null,
        'created_on'    => date('Y-m-d H:i:s'),
        'created_by'    => $user_id
    ];

    $complaints = $this->ComplaintsModel->saveComplaints($insertData);

    return $this->response->setStatusCode(200)->setJSON([
        'status' => 200,
        'success' => true,
        'data' => $complaints,
        'message' => 'Complaint saved successfully.',
        'uploaded_files' => $uploadedFiles
    ]);
}



 public function mediaUploads()
{
    $files = $this->request->getFileMultiple('file'); // name="file[]" in form-data

    if (!$files) {
        return $this->fail('No files uploaded.');
    }

    $uploadedFiles = [];

    foreach ($files as $file) {
        if (!$file->isValid()) {
            continue; // skip invalid files
        }

        $mimeType = $file->getMimeType();
        $uploadPath = FCPATH . 'uploads/';

        // Define folder based on file type
        if (strpos($mimeType, 'image') !== false) {
            $uploadPath .= 'images/';
        } elseif (strpos($mimeType, 'video') !== false) {
            $uploadPath .= 'videos/';
        } else {
            continue; // skip unsupported file types
        }

        // Ensure the folder exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Generate a unique name
        $newName = $file->getRandomName();

        // Move file
        if ($file->move($uploadPath, $newName)) {
            $relativePath = 'uploads/' . (strpos($mimeType, 'image') !== false ? 'images/' : 'videos/') . $newName;
            $fileUrl = base_url($relativePath);

            $uploadedFiles[] = [
                'file_url' => $fileUrl,
                'file_type' => strpos($mimeType, 'image') !== false ? 'image' : 'video'
            ];
        }
    }

    if (empty($uploadedFiles)) {
        return $this->fail('No valid files were uploaded.');
    }

    return $this->respond([
        'status' => 200,
        'success' => true,
        'message' => 'Files uploaded successfully',
        'uploaded_files' => $uploadedFiles
    ]);
}





    
    
	
}