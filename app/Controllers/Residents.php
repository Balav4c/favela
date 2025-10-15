<?php
namespace App\Controllers;
use App\Models\ResidentsModel;
use App\Models\AccountsModel;

class Residents extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->residentsModel = new ResidentsModel();
		$this->accountsModel = new AccountsModel();
		 
	}
	public function index($rl=''){ 
		/*if($rl) {
			$ThisRole = $this->rolesModel->getThisRoles($rl);
			$data['rolename'] = $ThisRole->role_name;
			$data['role_id'] = $ThisRole->role_id;
			$data['role_previlage'] = explode(",",$ThisRole->role_previlage);
		}
		else {
			$data['rolename'] = null;
			$data['role_id'] = null;
			$data['role_previlage'] = array();
		}*/
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 4;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$template = view('common/header',$data);
			$template .= view('residents');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/residentsjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
	
	public function verifyaadhaar() {
		$aadhno = base64_decode($this->input->getPost('aadhno'));
		if($aadhno && ($aadhno < 999999999999 && $aadhno > 100000000000)) {
			$checkUserExistance = $this->residentsModel->userExistance($aadhno);
			if($checkUserExistance) {
				echo json_encode(array("status"=>1,
								"message"=>"Aadhaar verified registered user. <br/>Click \"Proceed\" to accept the user.",
								"userdetails"=>$checkUserExistance));
			}
			else {
				echo json_encode(array("status"=>2,
								"message"=>"User not found in the system. <br/>To verify the aadhaar and proceed with registration, click \"Send OTP\".<br/> 
								Otp will send to the mobile number registered with the aadhaar.<br/>Make sure the mobile number is accessable.",
								"userdetails"=>null));
			}
		}
		else{
			echo json_encode(array("status"=>0,
							"message"=>"Invalid aadhaar card! Aadhaar verification failed.",
							"userdetails"=>null));
		}
	}
	
	public function acceptUser() {
		$data['us_id'] = $this->input->getPost('userId');
		$data['fv_id'] = $this->session->get('fav_id');
		$data['fv_registered_on'] = date('Y-m-d H:i:s');
		$data['fv_status'] = 1;
		$checkUser = $this->residentsModel->checkUser($data['us_id'], $data['fv_id']);
		if($checkUser) {
			$status = 2;
			$message = 'User already exist in the flat/tower.';
			$us_id = $data['us_id'];
		}
		else {
			$acceptUser = $this->residentsModel->registerToFlat($data);
			$us_id = $this->residentsModel->getInsertID();
			$userinfo = $this->residentsModel->getUser($us_id);
			$status = 2;
			$message = 'User created successfully in the flat/tower.';
		}
		
		echo json_encode(array("status"=>$status,
								"userinfo"=>$userinfo,
								"message"=>$message));
	}
	
	public function sendotp() {
		$aadhaarno = base64_decode($this->input->getPost('aadhno'));
		/*$auth_token = '';
		$otpUrl = 'https://api.sandbox.co.in/kyc/aadhaar/okyc/otp';
		$headers[] = 'Authorization' : $auth_token;
		$headers[] = 'accept: application/json';
		$headers[] = 'x-api-key: key_live_'; //Enter api key
		$headers[] = 'x-api-version: 1.0'; //Enter api key
		
		$data = '{
			"aadhaar_number":"'.$aadhaarno.'"
		}';
		
		$ch = curl_init($url);
		curl_setotp($ch, CURLOTP_POST, 1);
		curl_setotp($ch, CURLOTP_POSTFIELDS, $data);
		
		curl_setotp($ch, CURLOTP_RETURNTRANSFER, 1);
		curl_setotp($ch, CURLOTP_HTTPHEADER, $headers);
		$results = curl_exec($ch);*/
		
		$result = array('code'=>200,
						'data'=>array('ref_id'=>4249039,
									'message'=>'OTP sent successfully'),
						'timestamp'=>strtotime(date('Y-m-d H:i:s')),
						'transaction_id'=>12345678901
				);
		
		echo json_encode(array("result"=>$result));
	}
	
	public function verifyotp() {
		
		$otp 	= $this->input->getPost('otp');
		$ref_id = $this->input->getPost('ref_id');
		
		/*$vurl = 'https://api.sandbox.co.in/kyc/aadhaar/okyc/otp/verfiy';
		$headers[] = 'Authorization' : $auth_token;
		$headers[] = 'accept: application/json';
		$headers[] = 'x-api-key: key_live_'; //Enter api key
		$headers[] = 'x-api-version: 1.0'; //Enter api key
		
		$data = '{
			"ref_id":"'.$ref_id.'",
			"otp":"'.$otp.'"
		}';
		
		$ch = curl_init($vurl);
		curl_setotp($ch, CURLOTP_POST, 1);
		curl_setotp($ch, CURLOTP_POSTFIELDS, $data);
		
		curl_setotp($ch, CURLOTP_RETURNTRANSFER, 1);
		curl_setotp($ch, CURLOTP_HTTPHEADER, $headers);
		$results = curl_exec($ch);*/
		
		if($otp == '808936') {
			$result = array('code'=>200,
			"care_of"=>'S/o: Khan',
			"dob"=>'14-07-1999',
			'email'=>'sdfdsfsdfsdfwerwe32432423432423dsfsd3w432fsd3wer',
			'gender'=>'M',
			'message'=>'Aadhaar Card Exists',
			'mobile_hash'=>'12332g432jh4g23jhg4jh34534jhg34j',
			'name'=>'Shah Rukh Khan',
			'photo_link'=>'https://m.media-amazon.com/images/M/MV5BZDk1ZmU0NGYtMzQ2Yi00N2NjLTkyNWEtZWE2NTU4NTJiZGUzXkEyXkFqcGdeQXVyMTExNDQ2MTI@._V1_.jpg',
			'ref_id'=>'4249039',
			'split_address'=>array('country'=>'India','dist'=>'West Delhi','house'=>'C-537','landmark'=>'Near CIAL','pincode'=>'110059'),
			'status'=>'VALID',
			'year_of_birth'=>'1999',
			'timestamp'=>strtotime(date('Y-m-d H:i:s')),
			'transaction_id'=>12345678901
			);
		}
		$p_address = $result['split_address']['house'].",".$result['split_address']['landmark'].",".$result['split_address']['dist'].",".$result['split_address']['country'].",".$result['split_address']['pincode'];
		$data['name'] = ucwords($result['name']);
		$data['gender'] = $result['gender'];
		$data['dob'] = $result['dob'];
		$data['p_address'] = $p_address;
		$data['profile_photo'] = $result['photo_link'];
		$data['status'] = 1;
		$createUser = $this->residentsModel->createUser($data);
		echo json_encode(array("userId"=>$createUser,
						"result"=>$result,
						"newuser"=>1));
	}
	
	// public function updateuser() {
	// 	$data['aadhar_no'] = $this->input->getPost('aadhaar_hd');
	// 	$user_id = $this->input->getPost('user_id');
	// 	$data['email_id'] = $this->input->getPost('email');
	// 	$data['phone'] = $this->input->getPost('contactno');
	// 	$data['c_address'] = $this->input->getPost('caddress');
	// 	$modifyUser = $this->residentsModel->modifyUser($user_id,$data);
	// 	$checkFlatUser = $this->residentsModel->checkTowerUser($user_id, $this->session->get('fav_id'));
	// 	if($checkFlatUser->fvuserno == 0) {
	// 		$udata['us_id'] = $user_id;
	// 		$udata['fv_id'] = $this->session->get('fav_id');
	// 		$udata['fv_registered_on'] = date('Y-m-d H:i:s');
	// 		$residenceAssign = $this->residentsModel->acceptNewUser($udata);
	// 	}
	// 	else {
	// 		$us_id = $user_id;
	// 		$fv_id = $this->session->get('fav_id');
	// 		$udata['fv_status'] = 1;
	// 		$residenceAssign = $this->residentsModel->updateNewUser($us_id, $fv_id, $udata);
	// 	}
		
	// 	/*Ledger Creation*/
		
	// 	$userinfo = $this->residentsModel->getUser($user_id);
	// 	$ledData['fv_id'] = $this->session->get('fav_id');
	// 	$ledData['user_id'] = $user_id;
	// 	$ledData['created_on'] = date("Y-m-d H:i:s");
	// 	$ledData['account_type'] = 4; //Income type from accounts_types table.
	// 	$ledData['ledger_name'] = $userinfo->name;
	// 	$ledData['opening_balance'] = 0;
		
	// 	$checkLedger = $this->accountsModel->checkLedger($ledData['fv_id'], $ledData['user_id']);
	// 	if($checkLedger->ledrow == 0) {
	// 		$CreateLedger = $this->accountsModel->CreateLedger($ledData);
	// 	}
	// 	echo json_encode(1);
	// }
	
	public function updateuser() {
		$residence_method = $this->input->getPost('residence_method');
		$fv_id = $this->session->get('fav_id');
		$current_time = date('Y-m-d H:i:s');

		if ($residence_method == 'with_aadhaar') {
			// Aadhaar verified flow
			$data = [
				'aadhar_no'  => $this->input->getPost('aadhaar_hd'),
				'email_id'   => $this->input->getPost('email'),
				'phone'      => $this->input->getPost('contactno'),
				'c_address'  => $this->input->getPost('caddress'),
			];

			$user_id = $this->input->getPost('user_id');

			if (!empty($user_id)) {
				// Update existing user
				$this->residentsModel->modifyUser($user_id, $data);
			} else {
				// Should not happen ideally — but handle safely
				$user_id = $this->residentsModel->createUser($data);
			}

			// --- Tower/Flat relation ---
			$checkFlatUser = $this->residentsModel->checkTowerUser($user_id, $fv_id);
			if ($checkFlatUser->fvuserno == 0) {
				$udata = [
					'us_id' => $user_id,
					'fv_id' => $fv_id,
					'fv_registered_on' => $current_time
				];
				$this->residentsModel->acceptNewUser($udata);
			} else {
				$udata['fv_status'] = 1;
				$this->residentsModel->updateNewUser($user_id, $fv_id, $udata);
			}

			// --- Ledger Creation ---
			$userinfo = $this->residentsModel->getUser($user_id);
			$ledData = [
				'fv_id'           => $fv_id,
				'user_id'         => $user_id,
				'created_on'      => $current_time,
				'account_type'    => 4,
				'ledger_name'     => $userinfo->name,
				'opening_balance' => 0
			];

			$checkLedger = $this->accountsModel->checkLedger($ledData['fv_id'], $ledData['user_id']);
			if ($checkLedger->ledrow == 0) {
				$this->accountsModel->CreateLedger($ledData);
			}

			echo json_encode(1);
		} 
	

else {
    // Without Aadhaar flow
    $user_id = $this->input->getPost('user_id');

    // --- Handle Profile Photo ---
    $profilePhotoData = null;

    // Case 1: Uploaded file
    if (!empty($_FILES['profile_photo_file']['name'])) {
        $uploadPath = FCPATH . 'uploads/profile_photos/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $fileName = time() . '_' . $_FILES['profile_photo_file']['name'];
        $fullPath = $uploadPath . $fileName;

        if (move_uploaded_file($_FILES['profile_photo_file']['tmp_name'], $fullPath)) {
            // Save file path or binary based on DB column type
            $profilePhotoData = file_get_contents($fullPath); // because your DB column is LONGBLOB
        }
    }
    // Case 2: Captured camera image (base64)
 
else if (!empty($_POST['captured_image'])) {
    $base64 = $_POST['captured_image'];

    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));

    $uploadPath = FCPATH . 'uploads/profile_photos/';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $fileName = 'captured_' . time() . '.png';
    file_put_contents($uploadPath . $fileName, $imageData);

    $profilePhotoData = $imageData;

}


    // --- Prepare user data ---
    $data = [
        'name'            => ucwords($this->input->getPost('manual_name')),
        'gender'          => $this->input->getPost('manual_gender'),
        'dob'             => $this->input->getPost('manual_dob'),
        'p_address'       => $this->input->getPost('manual_address'),
        'c_address'       => $this->input->getPost('caddress'),
        'email_id'        => $this->input->getPost('email'),
        'phone'           => $this->input->getPost('contactno'),
        'status'          => 1,
        'aadhar_no'       => '', 
        'id_proof'        => $this->input->getPost('idproof') ?? '',
        'id_proof_number' => $this->input->getPost('id_proof_number') ?? ''
    ];

    if ($profilePhotoData) {
        $data['profile_photo'] = $profilePhotoData;
    }

    if (!empty($user_id)) {
        $this->residentsModel->modifyUser($user_id, $data);
    } else {
        $user_id = $this->residentsModel->createUser($data);
    }

    // --- Rest of your existing code unchanged ---
    $checkFlatUser = $this->residentsModel->checkTowerUser($user_id, $fv_id);
    if ($checkFlatUser->fvuserno == 0) {
        $udata = [
            'us_id' => $user_id,
            'fv_id' => $fv_id,
            'fv_registered_on' => $current_time,
            'fv_status' => 1
        ];
        $this->residentsModel->acceptNewUser($udata);
    } else {
        $udata['fv_status'] = 1;
        $this->residentsModel->updateNewUser($user_id, $fv_id, $udata);
    }

    $ledData = [
        'fv_id'           => $fv_id,
        'user_id'         => $user_id,
        'created_on'      => $current_time,
        'account_type'    => 4,
        'ledger_name'     => $data['name'],
        'opening_balance' => 0
    ];

    $checkLedger = $this->accountsModel->checkLedger($ledData['fv_id'], $ledData['user_id']);
    if ($checkLedger->ledrow == 0) {
        $this->accountsModel->CreateLedger($ledData);
    }

    echo json_encode(1);
}


	}


	public function changeuser() {
		$userId = $this->input->getPost('userId');
		$fv_id = $this->session->get('fav_id');
		$this->residentsModel->removeAadhaar($userId, $fv_id);
		echo json_encode(1);
	}
	
	public function deleteUser($us_id) {
		$data['fv_status'] = 3;
		$fv_id = $this->session->get('fav_id');
		$this->residentsModel->deleteUser($us_id, $fv_id, $data);
		echo json_encode(1);
	}
	
	public function changeStatus() {
		$data['fv_status'] = $this->input->getPost('us_status');
		$us_id = $this->input->getPost('us_id');
		$fv_id = $this->session->get('fav_id');
		$UserStatus = $this->residentsModel->changeUserStatus($fv_id, $us_id, $data);
		echo json_encode(1);
	}
	
	public function userDetails() {
		$us_id = $this->input->getPost('us_id');
		if($us_id) {
			$getUser = $this->residentsModel->getUserDetails($us_id);
			echo json_encode($getUser);
		}
	}
	
	public function addnewdoor() {
		$data['door_no'] = $this->input->getPost('doorno');
		$data['us_id'] = $this->input->getPost('list_us_id');
		$data['fv_id'] = $this->session->get('fav_id');
		$data['door_status'] = 1;
		$data['modified_on'] = date('Y-m-d H:i:s');
		$checkDoorNo = $this->residentsModel->checkDoorNo($this->input->getPost('doorno'), $this->session->get('fav_id'));
		if($checkDoorNo->DoorsNos>0) {
			echo json_encode(array("status"=>0, "message"=>"Door number already in use. Allocate different door no."));
		}
		else {
			if($data['door_no'] && $data['us_id'] && $data['fv_id']) {
				$SaveDoor = $this->residentsModel->saveDoorNo($data);
				echo json_encode(array("status"=>1, "message"=>"Door number saved for the user."));
			}
			else {
				echo json_encode(array("status"=>0, "message"=>"Unexpected error. Can't save the details at the moment."));
			}
		}
	}
	
	public function listdoor() {
		$list_us_id = $this->input->getPost('list_us_id');
		$fav_id = $this->session->get('fav_id');
		if($list_us_id) {
			$ListDoor = $this->residentsModel->listDoor($list_us_id, $fav_id);
			echo json_encode($ListDoor);
		}
	}
	
	public function deleteDoor($did) {
		$data['door_status'] = 2;
		$delDoor = $this->residentsModel->deleteDoor($did, $data);
		echo json_encode(1);
	}
	
	public function listresidents() {
		
		## Read value
		$draw = $_POST['draw'];
		$row = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		//foreach ($empRecords as $row) {
		$filter = '1=1';
		$tolimit = $row + $rowperpage;
		$fv_id = $this->session->get('fav_id');
		if($searchValue) {
			$filter .= " and (au.name like '%".$searchValue."%' OR au.aadhar_no like '%".$searchValue."%' OR au.email_id like '%".$searchValue."%' OR au.phone like '%".$searchValue."%')";
		}
		$filter .= " and fv_id = ".$this->session->get('fav_id');
		$ListResidents = $this->residentsModel->listAllResidents($filter, $row, $tolimit);
		$data = array();
		$slno = $row;
		
		foreach($ListResidents as $reslist) {
			$action = '<a href="javascript:void(0);" onclick="userDetails('.$reslist->us_id.')"><i class="fa fa-info-circle"></i></a>';
			$action .= '<a href="javascript:void(0)" onclick="userDoors('.$reslist->us_id.',\''.$reslist->name.'\')"><i class="fa fa-th"></i></a>';
			$action .= '<a href="javascript:void(0)" onclick="deleteFlatUser('.$reslist->us_id.')"><i class="fa fa-trash-o"></i></a>';
			$checked = ($reslist->fv_status==1 ? 'checked="checked"' : '' );
			$data[] = array(
				"slno"=>$slno + 1,
				"res_name"=>$reslist->name,
				"res_aadhaar"=>$reslist->aadhar_no,
				"res_phone"=>$reslist->phone,
				"res_email"=>$reslist->email_id,
				"status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$reslist->us_id.'" '.$checked.'/>',
				"action"=>$action
			);
			$slno++;
		}
		// Response
		$ListRes = $this->residentsModel->AllResidentsCount($fv_id);
		$totalRecords = $ListRes->resnos;
		$ListFilterRes = $this->residentsModel->AllResidFilterCount($filter);
		$totalRecordwithFilter = $ListFilterRes->filterressnos;
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
		echo json_encode($response);
	}
}
?>