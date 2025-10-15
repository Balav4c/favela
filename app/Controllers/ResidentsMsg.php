<?php
namespace App\Controllers;
use App\Models\SecurityModel;

class ResidentsMsg extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->securityModel = new SecurityModel();
		 
	}
	public function index($security_id=""){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 7;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$fv_id = $this->session->get('fav_id');
			
			$template = view('common/header',$data);
			$template .= view('residentsmsg');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			//$template .= view('page_script/securityjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
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