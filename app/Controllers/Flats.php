<?php
namespace App\Controllers;
use App\Models\FlatsModel;

class Flats extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->flatsModel = new FlatsModel();	
		 
	}
	public function index($bd=''){
		if($bd) {
			$ThisFlats = $this->flatsModel->getThisFlats($bd);
			$data['building'] = $ThisFlats->bd_name;
			$data['flats'] = $ThisFlats->bd_flat_nos;
			$data['reception'] = $ThisFlats->bd_recpt_no;
			$data['bd'] = $ThisFlats->bd_id;
		}
		else {
			$data['building'] = null;
			$data['flats'] = null;
			$data['reception'] = null;
			$data['bd'] = null;
		}
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 2;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$template = view('common/header',$data);
			$template .= view('flats');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/flatsjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
    }
	public function createnew() {
		$buildname = $this->input->getPost('buildname');
		$flatnos = $this->input->getPost('flatnos');
		$recptno = $this->input->getPost('recptno');
		$bd_id = $this->input->getPost('bd_id');
		$fv_id = $this->session->get('fav_id');
		$checkFlats = $this->flatsModel->checkFlats($buildname, $fv_id, $bd_id);
		if($checkFlats) {
			echo json_encode(array("status"=>0,"respmsg"=>"Building/tower name already exist in the system."));
		}
		else {
			if($buildname) {
				$data = [
					'fv_id'       	=> 	$this->session->get('fav_id'),
					'bd_name'       => 	$buildname,
					'bd_flat_nos' 	=> 	$flatnos,
					'bd_recpt_no' 	=> 	$recptno,
					'created_on' 	=> 	date("Y-m-d H:i:s"),
					'created_by	'	=>	$this->session->get('fav_user_id'),
					'modified_on'	=>	date("Y-m-d H:i:s"),
					'modified_by'	=>	$this->session->get('fav_user_id'),
				];
				
				if($bd_id == 0 || $bd_id == NULL) {
					$CreateFlats = $this->flatsModel->createFlats($data);
					echo json_encode(array("status"=>1,"respmsg"=>"Building/Tower created successfully."));
				}
				else {
					$ModifyFlats = $this->flatsModel->modifyFlats($bd_id, $data);
					echo json_encode(array("status"=>1,"respmsg"=>"Building/Tower updated successfully."));
				}
				
			}
			else {
				echo json_encode(array("status"=>0,"respmsg"=>"Please enter building/tower name."));
			}
		}
	}
	public function changeStatus() {
		$bd_status = $this->input->getPost('bd_status');
		$bd_id = $this->input->getPost('bd_id');
		$FlatsStatus = $this->flatsModel->changeFlatStatus($bd_status, $bd_id);
		echo json_encode(1);
	}
	public function deleteFlat($fid) {
		if($fid) {
			$FlatsStatus = $this->flatsModel->changeFlatStatus(3, $fid);
			echo json_encode(1);
		}
		else {
			echo json_encode(2);
		}
	}
	public function listflats() {
		
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
			$filter .= " and bd_name like '%".$searchValue."%'";
		}
		$filter .= " and fv_id = ".$this->session->get('fav_id');
		$ListFlats = $this->flatsModel->listAllFlats($filter, $row, $tolimit);
		$data = array();
		$slno = $row;
		foreach($ListFlats as $flist) {
			$action = '<a href="'.base_url("flats/$flist->bd_id").'"><i class="fa fa-pencil-square-o"></i></a>';
			$action .= '<a href="javascript:void(0)" onclick="deleteFlat('.$flist->bd_id.')"><i class="fa fa-trash-o"></i></a>';
			$checked = ($flist->bd_status==1 ? 'checked="checked"' : '' );
			$data[] = array(
				"slno"=>$slno + 1,
				"building"=>$flist->bd_name,
				"flatnos"=>$flist->bd_flat_nos,
				"recepno"=>$flist->bd_recpt_no,
				"status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$flist->bd_id.'" '.$checked.'/>',
				"action"=>$action
			);
			$slno++;
		}
		// Response
		$ListFlats = $this->flatsModel->AllFlatsCount();
		$totalRecords = $ListFlats->flatnos;
		$ListFilterFlats = $this->flatsModel->AllFlatsFilterCount($filter);
		$totalRecordwithFilter=$ListFilterFlats->filterflatnos;
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
		echo json_encode($response);
	}
	
}
