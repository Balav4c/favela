<?php
namespace App\Controllers;

class Mygatepass extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		 
	}
	public function index($accesskey=""){ 
		
		if($accesskey!='') {
			$data['gatepass'] = base_url("gatepass/".$accesskey.".jpg");
			if (file_exists("gatepass/".$accesskey.".jpg")) { 
				$template = view('sharegatepass', $data);
				echo $template;
			} 
			else {
				$template = view('passnotfound');
				echo $template;
			}
		}
		else {
			$template = view('passnotfound');
			echo $template;
		}		
	}
}
?>