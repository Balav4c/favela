<?php
namespace App\Controllers\UserMobile;
use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserMobile\GatepassModel;
use App\Models\UserMobile\TokenVerifyModel;
use \App\Models\UserMobile\SecurityModel;
use App\Libraries\JWT;

class VisitorGatepass extends BaseController
{

	public function __construct()
	{

		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->TokenModel = new TokenVerifyModel();
		$this->GatepassModel = new GatepassModel();
		 $this->SecurityModel = new SecurityModel();
	}
	public function getGatepass()
	{
		$input = $this->request->getJSON(true);
		$fv_id = isset($input['fv_id']) ? $input['fv_id'] : null;


		if ($fv_id) {
			$gatepass = $this->GatepassModel->getGatepass($fv_id);
			return $this->response->setJSON([
				'status' => 200,
				'success' => true,
				'data' => $gatepass
			]);
		} else {
			return $this->response->setJSON([
				'status' => 400,
				'success' => false,
				'message' => 'Missing fv_id.'
			]);
		}
	}


	public function createnew()
{
    // 1. Read headers and extract token
    $headers = $this->request->headers();
    if (!isset($headers['Authorization'])) {
        return $this->response->setStatusCode(401)->setJSON([
            'status' => 401,
            'success' => false,
            'message' => 'Authorization header missing.'
        ]);
    }

    $token = trim(explode("Bearer", $headers['Authorization'])[1] ?? '');
    if (!$token) {
        return $this->response->setStatusCode(401)->setJSON([
            'status' => 401,
            'success' => false,
            'message' => 'Token missing.'
        ]);
    }

    // 2. Get request data (JSON or form-data)
    $json_data = $this->request->getBody();
    $input = json_decode($json_data, true) ?? $this->request->getPost();

    $uid = $input['uid'] ?? $this->request->getPost('uid');
    $visitor = $input['visitor_name'] ?? null;
    $purpose = $input['purpose_of_visit'] ?? null;
    $person_flat = $input['person_flat_visit'] ?? null;
    $fv_id = $input['fv_id'] ?? null;
    $place = $input['visitor_place'] ?? null;
    $phone = $input['visitor_phone'] ?? null;
    $vehicle_no = $input['vehicle_no'] ?? null;
    $dateofvisit = $input['date_of_visit'] ?? null;
    $created_by = $input['created_by'] ?? null;

    // 3. Verify token
    $tokencheck = $this->TokenModel->verifyToken($token, $uid);
    if (!$tokencheck) {
        return $this->response->setStatusCode(401)->setJSON([
            'status' => 401,
            'success' => false,
            'message' => 'Unauthorized token authentication.'
        ]);
    }

    // 4. Validate required fields
    if (!$visitor || !$purpose || !$person_flat || !$fv_id || !$created_by || !$uid) {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 400,
            'success' => false,
            'message' => 'Missing required parameters.'
        ]);
    }

    // 5. Handle optional visitor image
    $visitor_image = null;
    $uploadPath = FCPATH . 'public/uploads/visitor_images/';
    if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

    if ($file = $this->request->getFile('visitor_image')) {
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = 'visitor_' . time() . '.' . $file->getClientExtension();
            $file->move($uploadPath, $newName);
            $visitor_image = $newName;
        }
    } elseif (!empty($input['visitor_image']) && base64_decode($input['visitor_image'], true) !== false) {
        $imgName = 'visitor_' . time() . '.png';
        file_put_contents($uploadPath . $imgName, base64_decode($input['visitor_image']));
        $visitor_image = $imgName;
    }

    // 6. Prepare gatepass data
    $tokenValue = $fv_id . strtotime(date("Y-m-d H:i:s.")) . gettimeofday()['usec'];
    $data = [
        "fv_id" => $fv_id,
        "uid" => $uid,
        "visitor_name" => $visitor,
        "visitor_place" => $place,
        "visitor_phone" => $phone,
        "vehicle_no" => $vehicle_no,
        "date_of_visit" => $dateofvisit ? date("Y-m-d", strtotime($dateofvisit)) : date("Y-m-d"),
        "purpose_of_visit" => $purpose,
        "person_flat_visit" => $person_flat,
        "visitor_image" => $visitor_image,
        "created_on" => date("Y-m-d H:i:s"),
        "created_by" => $created_by,
        "created_type" => 2,
        "status" => 1,
        "token" => $tokenValue
    ];

    $createGatepass = $this->GatepassModel->saveGatepass($data);
    if (!$createGatepass) {
        return $this->response->setStatusCode(500)->setJSON([
            'status' => 500,
            'success' => false,
            'message' => 'Failed to create gatepass.'
        ]);
    }

    $qrLink = base_url('visitorgatepass/access/' . $tokenValue);

    return $this->response->setStatusCode(200)->setJSON([
        'status' => 200,
        'success' => true,
        'message' => 'Visitor gate pass created successfully.',
        'qrlink' => $qrLink,
        'gpdata' => $createGatepass,
    ]);
}
	public function getUserGatepassHistory()
	{
		// Accept POST JSON or form-data
		$input = $this->request->getJSON(true);
		$fv_id = isset($input['fv_id']) ? $input['fv_id'] : $this->request->getPost('fv_id');
		$uid = isset($input['uid']) ? $input['uid'] : $this->request->getPost('uid');

		if (!$fv_id || !$uid) {
			return $this->response->setStatusCode(400)->setJSON([
				'status' => 400,
				'success' => false,
				'message' => 'Missing fv_id or uid.'
			]);
		}

		// Fetch gatepass history for this user & flat
		$history = $this->GatepassModel->getGatepassByUserAndFlat($uid, $fv_id);

		return $this->response->setStatusCode(200)->setJSON([
			'status' => 200,
			'success' => true,
			'data' => $history
		]);
	}
	public function deleteGatepass()
	{
		$input = $this->request->getJSON(true) ?? $this->request->getPost();
		$gp_id = $input['gp_id'] ?? null;

		if (empty($gp_id)) {
			return $this->response->setJSON([
				'status' => false,
				'message' => 'Gatepass ID (gp_id) is required.'
			]);
		}

		$gatepassModel = new GatepassModel();

		// Check if gatepass exists
		$gatepass = $gatepassModel->db->table('gatepass')->where('gp_id', $gp_id)->get()->getRow();
		if (!$gatepass) {
			return $this->response->setJSON([
				'status' => false,
				'message' => 'Gatepass not found.'
			]);
		}

		try {
			$updated = $gatepassModel->db->table('gatepass')
				->where('gp_id', $gp_id)
				->update(['status' => 3]);

			if ($updated) {
				return $this->response->setJSON([
					'status' => true,
					'message' => 'Gatepass marked as deleted successfully.'
				]);
			} else {
				return $this->response->setJSON([
					'status' => false,
					'message' => 'Failed to delete gatepass. Please try again.'
				]);
			}
		} catch (\Exception $e) {
			log_message('error', 'Gatepass delete failed: ' . $e->getMessage());
			return $this->response->setJSON([
				'status' => false,
				'message' => 'Server error: ' . $e->getMessage()
			]);
		}
	}
public function scanGatepass()
{
    // Get Bearer token safely
    $authHeader = $this->request->getHeaderLine('Authorization');
    if (!$authHeader || strpos($authHeader, 'Bearer ') === false) {
        return $this->response->setStatusCode(401)->setJSON([
            'status' => 401,
            'success' => false,
            'message' => 'Authorization header missing or invalid.'
        ]);
    }
    $securityToken = trim(str_replace('Bearer ', '', $authHeader));

    // Verify security guard via token
    $security = $this->SecurityModel->getSecurityByToken($securityToken);
    if (!$security) {
        return $this->response->setStatusCode(401)->setJSON([
            'status' => 401,
            'success' => false,
            'message' => 'Unauthorized or invalid security token.'
        ]);
    }

    $sc_id = $security['sc_id'];

    // Read request data
    $input = $this->request->getJSON(true) ?? $this->request->getPost();
    $gatepassToken = $input['gatepass_token'] ?? null;
    $type = $input['type'] ?? null; // optional: check_in / check_out

    if (!$gatepassToken) {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 400,
            'success' => false,
            'message' => 'Missing gatepass_token.'
        ]);
    }

    // Fetch gatepass record
    $gatepass = $this->GatepassModel->getGatepassByToken($gatepassToken);
    if (!$gatepass) {
        return $this->response->setStatusCode(404)->setJSON([
            'status' => 404,
            'success' => false,
            'message' => 'Invalid gatepass token.'
        ]);
    }

    // STEP 1: Only fetching visitor details
    if (!$type) {
        return $this->response->setStatusCode(200)->setJSON([
            'status' => 200,
            'success' => true,
            'message' => 'Visitor details fetched successfully.',
            'data' => [
                'visitor_name' => $gatepass['visitor_name'],
                'purpose_of_visit' => $gatepass['purpose_of_visit'],
                'flat' => $gatepass['person_flat_visit'],
                'check_in' => $gatepass['check_in'],
                'check_out' => $gatepass['check_out'],
                'status' => $gatepass['status']
            ]
        ]);
    }

    // STEP 2: Process check_in / check_out
    $now = date('Y-m-d H:i:s');
    $updateData = [];

    if ($type === 'check_in') {
        if (!empty($gatepass['check_in'])) {
            return $this->response->setStatusCode(409)->setJSON([
                'status' => 409,
                'success' => false,
                'message' => 'Visitor already checked in.'
            ]);
        }

        $updateData = [
            'check_in' => $now,
            'sg_id_in' => $sc_id,
            'modified_on' => $now,
            'status' => 2
        ];
    } elseif ($type === 'check_out') {
        if (empty($gatepass['check_in'])) {
            return $this->response->setStatusCode(409)->setJSON([
                'status' => 409,
                'success' => false,
                'message' => 'Visitor has not checked in yet.'
            ]);
        }
        if (!empty($gatepass['check_out'])) {
            return $this->response->setStatusCode(409)->setJSON([
                'status' => 409,
                'success' => false,
                'message' => 'Visitor already checked out.'
            ]);
        }

        $updateData = [
            'check_out' => $now,
            'sg_id_out' => $sc_id,
            'modified_on' => $now,
            'status' => 5
        ];
    } else {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 400,
            'success' => false,
            'message' => 'Invalid type. Must be check_in or check_out.'
        ]);
    }

    // Update gatepass
    $updated = $this->GatepassModel->updateGatepassByToken($gatepassToken, $updateData);
    if (!$updated) {
        return $this->response->setStatusCode(500)->setJSON([
            'status' => 500,
            'success' => false,
            'message' => 'Database update failed.'
        ]);
    }

    $updatedGatepass = $this->GatepassModel->getGatepassByToken($gatepassToken);

    return $this->response->setStatusCode(200)->setJSON([
        'status' => 200,
        'success' => true,
        'message' => "Visitor {$type} recorded successfully by {$security['security_name']}.",
        'data' => $updatedGatepass
    ]);
}


}