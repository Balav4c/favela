<?php
namespace App\Controllers;
use App\Models\DashboardModel;

class Dashboard extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->dashModel = new DashboardModel();	
		 
	}
	
	public function index(){
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 1;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$template = view('common/header',$data);
			$template .= view('dashboard');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			//$template .= view('common/flotjs');
			//$template .= view('common/mapjs');
			//$template .= view('common/datatablejs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}
		//$getCustomer = $this->userModel->getThisCustomer($identifier);
		//print_r($getCustomer);
		
    }
}
