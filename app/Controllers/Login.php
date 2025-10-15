<?php
namespace App\Controllers;
use App\Models\AuthModel;

class Login extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
	}
	public function index(): string {
		
		return view('pagenotfound');
    }
	public function login($identifier): string {
		
		$url = MASTER_APP_URI. "response.php";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "keygen=".$identifier);
		$result = curl_exec($ch);
	
		curl_close ($ch);
		$resultArr = json_decode($result);
		if($resultArr->auth==1) {
			$data['organisation'] = ucwords($resultArr->orgdata);
			$data['identifier'] = $identifier;
			return view('login', $data);
		}
		else {
			return view('pagenotfound');
		}
	}
	public function signin() {
		
		$email = $this->input->getPost('email');
		$pwd = md5($this->input->getPost('pwd'));
		$identifier = $this->input->getPost('identifier');
		$recaptcha = $this->input->getPost('g-recaptcha-response');
		$classArr = array("3"=>'alert alert-warning','4'=>'alert alert-danger','5'=>'alert alert-danger');
		if($recaptcha) {
			$res = $this->reCaptcha($recaptcha);
			if(1==1) { //For local only
			//if($res['success']==1) { //For live domain
				
				if($email && $pwd && $email!='' && $pwd!='') {
							
					$url = MASTER_APP_URI . 'signin.php';
					$curl = curl_init($url);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					$data = "keygen=".$identifier."&email=".$email."&pwd=".$pwd;
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					$result = curl_exec($curl);
					$resultArr = json_decode($result);
					curl_close($curl);
					if($resultArr->auth==1) {
						
						$this->session->set([
											'fav_id' =>  $resultArr->favid,
											'fav_org' => $resultArr->orgname,
											'fav_user_id' => $resultArr->userid,
											'fav_user_name' => $resultArr->username,
											'fav_ident'=> $identifier
										]);



						$this->session->get('fav_user_name');
						setcookie("fav_ident", $identifier, time() + 3600, "/"); // 3600 seconds = 1 hour
						echo json_encode(array('status'=>1,
										'msg'=>'',
										"class"=>''));
					}
					else {
						
						echo json_encode(array('status'=>0,
										'msg'=>$resultArr->msg,
										"class"=>$classArr[$resultArr->status]));
					}
				}
				else {
					echo json_encode(array('status'=>0,
										'msg'=>"Access denied! Invalid credentials.",
										'class'=>$classArr[5]));
				}
			}
			else{
				echo json_encode(array('status'=>0,
									'msg'=>"Access denied! Invalid captcha.",
									'class'=>$classArr[5]));
			}
		}
		else {
			echo json_encode(array('status'=>0,
									'msg'=>"Access denied! Invalid captcha.",
									'class'=>$classArr[5]));
		}
	}
	public function reCaptcha($recaptcha){
		$secret = "6LeoL5UpAAAAANCPPYP_gZWrENl5vYFJIZytnUkD";
		$ip = $_SERVER['REMOTE_ADDR'];

		$postvars = array("secret"=>$secret, "response"=>$recaptcha, "remoteip"=>$ip);
		$url = "https://www.google.com/recaptcha/api/siteverify";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
		$data = curl_exec($ch);
		curl_close($ch);
		return json_decode($data, true);
	}
	public function signout() {
		$identifier = $this->session->get('fav_ident');
		$redirect_to = base_url('sync/'.$identifier);
		$data = ['fav_id','fav_org','fav_user_id','fav_user_name','fav_ident'];
		session()->remove($data);
		setcookie("fav_ident", "", time() + 3600, "/"); // Set expiration in the past
		return redirect()->to($redirect_to); 
	}

	public function authenticate()
   {
    $key = $this->request->getPost('token');
    if (!empty($key)) {
    return redirect()->to(base_url("sync/{$key}"));
    } else {
		return view('pagenotfound', ['error' => 'Your key is not authenticated. Please provide the correct key.']);
    }
	
}


}