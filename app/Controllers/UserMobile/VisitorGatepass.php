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
    // Get data from JSON or form-data
    $input = $this->request->getJSON(true) ?? $this->request->getPost();

    $visitor     = $input['visitor_name'] ?? null;
    $purpose     = $input['purpose_of_visit'] ?? null;
    $person_flat = $input['person_flat_visit'] ?? null;
    $fv_id       = $input['fv_id'] ?? null;
    $place       = $input['visitor_place'] ?? null;
    $phone       = $input['vistor_phone'] ?? null;
    $dateofvisit = $input['date_of_visit'] ?? null;
    $created_by  = $input['created_by'] ?? null;
    $uid         = $input['uid'] ?? null;

    if ($visitor && $purpose && $person_flat && $fv_id && $created_by && $uid) {

        // Create unique token
        $token = $fv_id . strtotime(date("Y-m-d H:i:s.")) . gettimeofday()['usec'];

        $data = [
            "fv_id"             => $fv_id,
            "uid"               => $uid,
            "visitor_name"      => $visitor,
            "visitor_place"     => $place,
            "vistor_phone"      => $phone,
            "date_of_visit"     => date("Y-m-d", strtotime($dateofvisit)),
            "purpose_of_visit"  => $purpose,
            "person_flat_visit" => $person_flat,
            "created_on"        => date("Y-m-d H:i:s"),
            "created_by"        => $created_by,
            "created_type"      => 2,
            "status"            => 1,
            "token"             => $token
        ];

        // Save to database
        $createGatepass = $this->GatepassModel->saveGatepass($data);

        $qrLink = base_url('visitorgatepass/access/' . $token);

        $response = [
            'status'  => 200,
            'success' => true,
            'message' => 'Visitor gate pass created successfully.',
            'qrlink'  => $qrLink,
            'gpdata'  => $createGatepass,
        ];

        return $this->response->setStatusCode(200)->setJSON($response);

    } else {
        return $this->response->setStatusCode(400)->setJSON([
            'status'  => 400,
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



}