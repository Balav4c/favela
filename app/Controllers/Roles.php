<?php
namespace App\Controllers;
use App\Models\RolesModel;

class Roles extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->rolesModel = new RolesModel();
		 
	}
	public function index($rl=''){ 
		if($rl) {
			$ThisRole = $this->rolesModel->getThisRoles($rl);
			$data['rolename'] = $ThisRole->role_name;
			$data['role_id'] = $ThisRole->role_id;
			$data['role_previlage'] = explode(",",$ThisRole->role_previlage);
		}
		else {
			$data['rolename'] = null;
			$data['role_id'] = null;
			$data['role_previlage'] = array();
		}
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 3;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$template = view('common/header',$data);
			$template .= view('roles');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/rolesjs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
	
	public function createnew() {
		$rolename = $this->input->getPost('rolename');
		$role_id = $this->input->getPost('role_id');
		$menu_role = implode(",",$this->input->getPost('menu_role'));
		if($rolename) {
			$fv_id = $this->session->get('fav_id');
			$checkRole = $this->rolesModel->checkRoles($rolename, $fv_id, $role_id);
			if($checkRole) {
				echo json_encode(array("status"=>0,"respmsg"=>"Role name already exist in the system."));
			}
			else {
				$data = [
					'fv_id'       	=> 	$this->session->get('fav_id'),
					'role_name'     => 	$rolename,
					'roles_status' 	=> 	1,
					'created_on' 	=> 	date("Y-m-d H:i:s"),
					'created_by	'	=>	$this->session->get('fav_user_id'),
					'modified_on'	=>	date("Y-m-d H:i:s"),
					'modified_by'	=>	$this->session->get('fav_user_id'),
					'role_previlage'=>	$menu_role
				];
				
				if($role_id == 0 || $role_id == NULL) {
					$CreateFlats = $this->rolesModel->createRoles($data);
					echo json_encode(array("status"=>1,"respmsg"=>"User role created successfully."));
				}
				else {
					$ModifyFlats = $this->rolesModel->modifyRoles($role_id, $data);
					echo json_encode(array("status"=>1,"respmsg"=>"User role updated successfully."));
				}
			}
			
		}
		else {
			echo json_encode(array("status"=>0,"respmsg"=>"Please enter the role name."));
		}
	}
	public function changeStatus() {
		$rl_status = $this->input->getPost('rl_status');
		$role_id = $this->input->getPost('rl_id');
		$modified_by = $this->session->get('fav_user_id');
		$RoleStatus = $this->rolesModel->changeRolesStatus($rl_status, $role_id, $modified_by);
		echo json_encode(1);
	}
	public function deleteRoles($rid) {
		if($rid) {
			$modified_by = $this->session->get('fav_user_id');
			$FlatsStatus = $this->rolesModel->changeRolesStatus(3, $rid, $modified_by);
			echo json_encode(1);
		}
		else {
			echo json_encode(2);
		}
	}
	
	
	public function listroles() {
		
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
			$filter .= " and role_name like '%".$searchValue."%'";
		}
		$filter .= " and fv_id = ".$this->session->get('fav_id');
		$ListRoles = $this->rolesModel->listAllRoles($filter, $row, $tolimit);
		$data = array();
		$slno = $row;
		foreach($ListRoles as $rlist) {
			$action = '<a href="'.base_url("roles/$rlist->role_id").'"><i class="fa fa-pencil-square-o"></i></a>';
			$action .= '<a href="javascript:void(0)" onclick="deleteRoles('.$rlist->role_id.')"><i class="fa fa-trash-o"></i></a>';
			$checked = ($rlist->roles_status==1 ? 'checked="checked"' : '' );
			$data[] = array(
				"slno"=>$slno + 1,
				"rolename"=>$rlist->role_name,
				"status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$rlist->role_id.'" '.$checked.'/>',
				"action"=>$action
			);
			$slno++;
		}
		// Response
		$ListRoles = $this->rolesModel->AllRolesCount();
		$totalRecords = $ListRoles->rolesnos;
		$ListFilterRoles = $this->rolesModel->AllRolesFilterCount($filter);
		$totalRecordwithFilter=$ListFilterRoles->filterrolesnos;
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