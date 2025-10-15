<?php
namespace App\Controllers;
use App\Models\SecurityModel;

class Securities extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->securityModel = new SecurityModel();
		 
	}
	public function index($security_id=""){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 13;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$fv_id = $this->session->get('fav_id');
			if($security_id) {
				$data['security_id'] = $security_id;
				$data['editmode'] = 1;
				$getSecurityInfo = $this->securityModel->getSecurityInfo($security_id);
				$data['id_card_no'] = $getSecurityInfo->id_proof_no;
				$data['security_name'] = $getSecurityInfo->security_name;
				$data['security_phone'] = $getSecurityInfo->security_phone;
				$data['security_cmp_name'] = $getSecurityInfo->security_company;
				$data['security_address'] = $getSecurityInfo->security_company_address;
			}
			else {
				$data['security_id'] = 0;
				$data['editmode'] = 0;
				$data['id_card_no'] = null;
				$data['security_name'] = null;
				$data['security_phone'] = null;
				$data['security_cmp_name'] = null;
				$data['security_address'] = null;
			}
			$template = view('common/header',$data);
			$template .= view('securities');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/securityjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
	public function searchsecurity() {
		$id_no = $this->input->getPost('id_no');
		if($id_no) {
			$securitylist = $this->securityModel->getSecurity($id_no);
			if($securitylist) {
				echo json_encode(array("status"=>1,
									"securitylist"=>$securitylist));
			}
			else{
				echo json_encode(array("status"=>0,
									"securitylist"=>null));
			}
		}
		else {
			echo json_encode(array("status"=>0,
								"securitylist"=>null));
		}
	}
	public function createnew() {
		
		if($this->input->getPost("security_name") && $this->input->getPost("security_phone") && $this->input->getPost("id_card") && $this->input->getPost("id_card_no")) {
			
			$security_id = $this->input->getPost('security_id');
			$editmode = $this->input->getPost('editmode');
			$fv_id = $this->session->get('fav_id');
			$data = [
				"security_name"=>$this->input->getPost("security_name"),
				"security_phone"=>$this->input->getPost("security_phone"),
				"id_proof"=>$this->input->getPost("id_card"),
				"id_proof_no"=>$this->input->getPost("id_card_no"),
				"security_company"=>$this->input->getPost("security_cmp_name"),
				"security_company_address"=>$this->input->getPost("security_address"),
				"status"=>1,
			];
			if($security_id) {
				
				$check_exisit = $this->securityModel->checkSecurity($fv_id, $security_id);
				if($check_exisit && $editmode==0) {
					echo json_encode(array("status"=>3,
										"respmsg"=>"Security already exist in the association."));
				}
				else {
					
					$updatesecurity = $this->securityModel->updateSecurity($data, $security_id);
					$fdata = [
						"fv_id"=>$fv_id,
						"sc_id"=>$security_id,
						"status"=>1
					];
					
					if($editmode==0) {
						$assign_to_flat = $this->securityModel->assignToFlat($fdata);
						echo json_encode(array("status"=>1,
										"respmsg"=>"Security added to flat successfully."));
					}
					else {
						echo json_encode(array("status"=>1,
										"respmsg"=>"Security details updated successfully."));
					}
					
				}
			}
			else {
				
				$cratesecurty = $this->securityModel->createSecurity($data);
				$fdata = [
					"fv_id"=>$fv_id,
					"sc_id"=>$cratesecurty,
					"status"=>1
				];
				$assign_to_flat = $this->securityModel->assignToFlat($fdata);
				echo json_encode(array("status"=>1,
								"respmsg"=>"Security added to flat successfully."));
			}
		}
		else {
			echo json_encode(array("status"=>0,"respmsg"=>"Please enter the security name, mobile number, id card and number."));
		}
	}
	public function deleteSecurity($fs_id) {
		$data = [
			"status"=>3
		];
		$fv_id = $this->session->get('fav_id');
		$this->securityModel->deleteSecurity($data, $fs_id);
		echo json_encode(1);
	}
	
	public function listfeedbacks() {
		$sc_id = $this->input->getPost("sc_id");
		if($sc_id) {
			$feedbackArr = array();
			$AllFeedbacks = $this->securityModel->getAllFeedback($sc_id);
			if($AllFeedbacks) {
				foreach($AllFeedbacks as $fd) {
					
					$url = MASTER_APP_URI . 'residenceinfo.php';
					$curl = curl_init($url);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					$data = "fv_id=" . $fd->fv_id;
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					$result = curl_exec($curl);
					$resultArr = json_decode($result);
					curl_close($curl);
					$data = [
						"fname"=>$resultArr->orgname,
						"feedback"=>$fd->feedback,
						"star_rating"=>$fd->star_rating
					];
					array_push($feedbackArr,$data);
				}
			}
			echo json_encode(array("feedbacklist"=>$feedbackArr));
		}
	}
	
	public function listsecurities() {
		
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
			$filter .= " and (sc.security_name like '%".$searchValue."%' || sc.security_phone like '%".$searchValue."%' || sc.id_proof_no like '".$searchValue."' || sc.security_company like '%".$searchValue."%')";
		}
		$filter .= " and fs.fv_id = ".$this->session->get('fav_id');
		$ListSecurity = $this->securityModel->listAllSecurity($filter, $row, $tolimit);
		$data = array();
		$slno = $row; 
		$IdArr = array("1"=>"Aadhaar");
       
        if($ListSecurity) {
           
            foreach($ListSecurity as $slist) {
                $action = '<a href="'.base_url("securities/$slist->sc_id").'"><i class="fa fa-pencil-square-o"></i></a>';
                $action .= '<a href="javascript:void(0)" onclick="deleteSecurity('.$slist->fs_id.')"><i class="fa fa-trash-o"></i></a>';
                $action .= '<a href="javascript:void(0)" onclick="popupfeedback('.$slist->fs_id.')"><i class="fa fa-commenting-o"></i></a>';
                $checked = ($slist->status==1 ? 'checked="checked"' : '' );
                $data[] = array(
                    "slno"=>$slno + 1,
                    "security_name"=>$slist->security_name,
                    "security_phone"=>$slist->security_phone,
                    "proof"=>$IdArr[$slist->id_proof],
                    "proof_no"=>$slist->id_proof_no,
                    "security_company"=>$slist->security_company."<br/>".$slist->security_company_address,
                    "status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$slist->sc_id.'" '.$checked.'/>',
                    "action"=>$action
                );
                $slno++;
            }
          
        }
		// Response
		$ListSecurity = $this->securityModel->AllSecurityCount();
		$totalRecords = $ListSecurity->securitynos;
		$ListFilterSecurity = $this->securityModel->AllSecurityFilterCount($filter);
		$totalRecordwithFilter=$ListFilterSecurity->filsecuritynos;
       
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