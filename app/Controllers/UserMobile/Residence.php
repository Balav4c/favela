<?php
namespace App\Controllers\UserMobile;
use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\UserMobile\ResidenceModel;
use App\Models\UserMobile\TokenVerifyModel;
use App\Libraries\JWT;

class Residence extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->TokenModel = new TokenVerifyModel();	
		$this->ResidenceModel = new ResidenceModel();	
	}
	
	public function getResidences() {
		
		$headers = $this->request->headers();
		$token = trim(explode("Bearer" , $headers['Authorization'])[1]);
		$json_data = $this->request->getBody();
		$data = json_decode($json_data, true);
		$user_id = isset($data['uid']) ? $data['uid'] : $this->request->getPost("uid");
		$tokencheck = $this->TokenModel->verifyToken($token, $user_id);
		if($tokencheck) {
			
			if($user_id) {
				
				$getOrgs = $this->ResidenceModel->getOrgsList($user_id);
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
