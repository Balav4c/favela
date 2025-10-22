<?php
namespace App\Controllers;
use App\Models\GatePassModel;

class VisitorGatepass extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->gatepassModel = new GatePassModel();
		 
	}
	public function index(){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 6;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$data['gp_id'] = '';
			$fv_id = $this->session->get('fav_id');
			$template = view('common/header',$data);
			$template .= view('gatepass');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/visitorgatepassjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
	public function createnew() {
		
		if($this->input->getPost("visitor") && $this->input->getPost("purpose") && $this->input->getPost("person_flat")) {
			$token = $this->session->get('fav_id') . strtotime(date("Y-m-d H:i:s.")) . gettimeofday()['usec'] . $this->session->get('fav_user_id');
			$data = [
				"fv_id" =>$this->session->get('fav_id'),
				"visitor_name"=>$this->input->getPost("visitor"),
				"visitor_place"=>$this->input->getPost("location"),
				"vistor_phone"=>$this->input->getPost("phone"),
				"date_of_visit"=>date("Y-m-d", strtotime($this->input->getPost("dateofvisit"))),
				"purpose_of_visit"=>$this->input->getPost("purpose"),
				"person_flat_visit"=>$this->input->getPost("person_flat"),
				"created_on" => date("Y-m-d H:i:s"),
				"created_by"=>$this->session->get('fav_user_name'),
				"created_type"=>1,
				"status"=>1,
				"token"=> $token
			];
			$crateGatepass = $this->gatepassModel->createPass($data);
			
			$qrfor = base_url('visitorgatepass/access/'.$token);
			$url = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=".$qrfor."&choe=UTF-8";
			$image = file_get_contents($url);
			$qr_path = "gatepass/qr-generator/$token.jpg";
			file_put_contents($qr_path, $image);
			
			echo json_encode(array("status"=>1,
									"respmsg"=>"Visitor gate pass created successfully.",
									"gpid"=>$crateGatepass,
									"qr"=>$qr_path,
									"gpdata"=>$data));
		}
		else {
			echo json_encode(array("status"=>0,"respmsg"=>"Please enter the visitor details, purpose of visit & whome to visit."));
		}
	}
	public function savepassimg() {
		$qr = $_POST['qr'];
		$image = $_POST['image'];
		$image = explode(";",$image)[1];
		$image = explode(",",$image)[1];
		$image = str_replace(" ","+",$image);
		$image = base64_decode($image);
		file_put_contents("gatepass/$qr.jpg",$image);
		@unlink("gatepass/qr-generator/$qr.jpg");
		echo "done";
	}
	public function access($accesskey) {
		
		$gatepassdata = $this->gatepassModel->getTokenGatePass($accesskey);
		if($gatepassdata) {
			$data = [
				"vname"=>$gatepassdata->visitor_name,
				"vplace"=>$gatepassdata->visitor_place,
				"vphone"=>$gatepassdata->visitor_phone,
				"vdate"=>date("d-m-Y", strtotime($gatepassdata->date_of_visit)),
				"vpurpose"=>$gatepassdata->purpose_of_visit,
				"vperson"=>$gatepassdata->person_flat_visit
			];
			if($accesskey) {
				$template = view('gatepassaccess', $data);
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
	public function getPassDetails() {
		$gp_id = $this->input->getPost('gp_id');
		$fv_id = $this->session->get('fav_id');
		$gatepassdata = $this->gatepassModel->getThisGatePass($gp_id, $fv_id);
		$statusArr = array(
						"1"=>'<div class="btn btn-primary">Issued</div>',
						"2"=>'<div class="btn btn-default">Issued</div>',
						"4"=>'<div class="btn btn-danger">Expired</div>',
						"5"=>'<div class="btn btn-warning">Used</div>',
					);
		$gatepassinfo = array("vname"=>$gatepassdata->visitor_name,
							"vplace"=>$gatepassdata->visitor_place,
							"vphone"=>$gatepassdata->vistor_phone,
							"vdate"=>$gatepassdata->date_of_visit,
							"vpurpose"=>$gatepassdata->purpose_of_visit,
							"vperson"=>$gatepassdata->person_flat_visit,
							"vcheckin"=>($gatepassdata->check_in ? date("d-m-Y H:i:s", strtotime($gatepassdata->check_in)) : ''),
							"vcheckout"=>($gatepassdata->check_out ? date("d-m-Y H:i:s", strtotime($gatepassdata->check_out)) : ''),
							"vsecurity"=>$gatepassdata->security_name,
							"vcreated"=>$gatepassdata->created_by,
							"vcreatedon"=>date("d-m-Y H:i:s", strtotime($gatepassdata->created_on)),
							"status"=>$statusArr[$gatepassdata->status],
							"token"=>$gatepassdata->token
						);
		if($gp_id) {
			echo json_encode(array("status"=>1,
								"gatepass"=>$gatepassinfo,
								"respmsg"=>null));
		}
		else {
			echo json_encode(array("status"=>0,
								"gatepass"=>null,
								"respmsg"=>"No data found in the system.!"));
		}
	}
	public function deletePass($gp_id) {
		
		$fv_id = $this->session->get('fav_id');
		$getGatePass = $this->gatepassModel->getThisGatePass($gp_id, $fv_id);
		if($getGatePass) {
			$passPath = "gatepass/".$getGatePass->token.".jpg";
			@unlink($passPath);
		}
		$this->gatepassModel->deleteGatepass($gp_id, $fv_id);
		echo json_encode(1);
	}
	public function listgatepass() {
		
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
		if($searchValue) {
			$filter .= " and (visitor_name like '%".$searchValue."%' || visitor_place like '%".$searchValue."%' || date_of_visit like '".$searchValue."' || person_flat_visit like '%".$searchValue."%')";
		}
		$filter .= " and fv_id = ".$this->session->get('fav_id');
		$ListPass = $this->gatepassModel->listAllPass($filter, $row, $tolimit);
		$data = array();
		$slno = $row; 
		foreach($ListPass as $plist) {
			$action = '<a href="javascript:void(0)" onclick="deletePass('.$plist->gp_id.')"><i class="fa fa-trash-o"></i></a>';
			$action .= '<a href="javascript:void(0)" onclick="popupDetails('.$plist->gp_id.')"><i class="fa fa-window-maximize"></i></a>';
			$checked = ($plist->status==1 ? 'checked="checked"' : '' );
			$data[] = array(
				"slno"=>$slno + 1,
				"visitor"=>$plist->visitor_name . "<br/>" . $plist->visitor_place,
				"datevisit"=>date("d-m-Y", strtotime($plist->date_of_visit)),
				"checkIn"=>($plist->check_in ? date("d-m-Y H:i:s", strtotime($plist->check_in)) : "-NA-"),
				"checkOut"=>($plist->check_out ? date("d-m-Y H:i:s", strtotime($plist->check_out)) : "-NA-"),
				//"status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$plist->gp_id.'" '.$checked.'/>',
				"action"=>$action
			);
			$slno++;
		}
		// Response
		$ListPasses = $this->gatepassModel->AllPassCount();
		$totalRecords = $ListPasses->passnos;
		$ListFilterPasses = $this->gatepassModel->AllPassFilterCount($filter);
		$totalRecordwithFilter=$ListFilterPasses->filpassnos;
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