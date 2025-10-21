<?php
namespace App\Controllers\UserMobile;
use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserMobile\GatepassModel;
use App\Models\UserMobile\TokenVerifyModel;
use App\Libraries\JWT;

class VisitorGatepass extends BaseController
{

	public function __construct()
	{

		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->TokenModel = new TokenVerifyModel();
		$this->GatepassModel = new GatepassModel();
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
    $input = $this->request->getJSON(true) ?? $this->request->getPost();

    $visitor = $input['visitor_name'] ?? null;
    $purpose = $input['purpose_of_visit'] ?? null;
    $person_flat = $input['person_flat_visit'] ?? null;
    $fv_id = $input['fv_id'] ?? null;
    $place = $input['visitor_place'] ?? null;
    $phone = $input['visitor_phone'] ?? null;
    $vehicle_no = $input['vehicle_no'] ?? null;
    $dateofvisit = $input['date_of_visit'] ?? null;
    $created_by = $input['created_by'] ?? null;
    $uid = $input['uid'] ?? null;

    //  Visitor image optional
    $visitor_image = null;
    $uploadPath = FCPATH . 'public/uploads/visitor_images/';
    $webPath = 'public/uploads/visitor_images/';

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    //  Handle optional visitor image (file or base64)
    if ($file = $this->request->getFile('visitor_image')) {
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = 'visitor_' . time() . '.' . $file->getClientExtension();
            $file->move($uploadPath, $newName);
            $visitor_image = $newName;
        }
    } elseif (!empty($input['visitor_image'])) {
        $imgData = $input['visitor_image'];
        if (base64_decode($imgData, true) !== false) {
            $imgName = 'visitor_' . time() . '.png';
            file_put_contents($uploadPath . $imgName, base64_decode($imgData));
            $visitor_image = $imgName;
        }
    }

    //  Required field validation
    if ($visitor && $purpose && $person_flat && $fv_id && $created_by && $uid) {

        // Unique gatepass token
        $token = $fv_id . strtotime(date("Y-m-d H:i:s.")) . gettimeofday()['usec'];

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
            "visitor_image" => $visitor_image, //  Will be null if not uploaded
            "created_on" => date("Y-m-d H:i:s"),
            "created_by" => $created_by,
            "created_type" => 2,
            "status" => 1,
            "token" => $token
        ];

        $createGatepass = $this->GatepassModel->saveGatepass($data);

        if (!$createGatepass) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 500,
                'success' => false,
                'message' => 'Failed to create gatepass.'
            ]);
        }

        $qrLink = base_url('visitorgatepass/access/' . $token);

        return $this->response->setStatusCode(200)->setJSON([
            'status' => 200,
            'success' => true,
            'message' => 'Visitor gate pass created successfully.',
            'qrlink' => $qrLink,
            'gpdata' => $createGatepass,
        ]);
    } else {
        return $this->response->setStatusCode(400)->setJSON([
            'status' => 400,
            'success' => false,
            'message' => 'Missing required parameters.'
        ]);
    }
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
		$input = $this->request->getJSON(true);
		$token = $input['token'] ?? null;
		$sc_id = $input['security_gaurd_id'] ?? null;
		$type = $input['type'] ?? null; // check_in or check_out

		if (!$token || !$sc_id || !$type) {
			return $this->response->setStatusCode(400)->setJSON([
				'status' => 400,
				'success' => false,
				'message' => 'Missing token, security_gaurd_id or type (check_in/check_out).'
			]);
		}

		// Find gatepass by token
		$gatepass = $this->GatepassModel->getGatepassByToken($token);

		if (!$gatepass) {
			return $this->response->setStatusCode(404)->setJSON([
				'status' => 404,
				'success' => false,
				'message' => 'Invalid gatepass token.'
			]);
		}

		$updateData = [];

		// Handle check-in
		if ($type === 'check_in') {
			if (!empty($gatepass['check_in'])) {
				return $this->response->setStatusCode(409)->setJSON([
					'status' => 409,
					'success' => false,
					'message' => 'Visitor already checked in.'
				]);
			}

			$updateData = [
				'check_in' => date('Y-m-d H:i:s'),
				'security_gaurd_id' => $sc_id,
				'modified_on' => date('Y-m-d H:i:s'),
				'status' => 1 // still "issued", since check-out not done
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
				'check_out' => date('Y-m-d H:i:s'),
				'security_gaurd_id' => $sc_id,
				'modified_on' => date('Y-m-d H:i:s'),
				'status' => 5 // mark as used
			];
		} else {
			return $this->response->setStatusCode(400)->setJSON([
				'status' => 400,
				'success' => false,
				'message' => 'Invalid type. Must be check_in or check_out.'
			]);
		}

		// Update gatepass record
		$this->GatepassModel->updateGatepassByToken($token, $updateData);

		$updated = $this->GatepassModel->getGatepassByToken($token);

		return $this->response->setStatusCode(200)->setJSON([
			'status' => 200,
			'success' => true,
			'message' => "Visitor {$type} recorded successfully.",
			'data' => $updated
		]);
	}


}