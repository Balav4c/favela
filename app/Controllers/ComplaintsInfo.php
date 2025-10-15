<?php
namespace App\Controllers;
use App\Models\ComplaintsInfoModel;
use App\Models\ReplyinfoModel;

class ComplaintsInfo extends BaseController {
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->replyinfoModel = new ReplyinfoModel();
		$this->complaintsinfoModel = new ComplaintsInfoModel();
		
		 
	}
	public function index($cid=''){ 
		
		if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			$data['menu'] = 5;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			if($cid) {
				$getComplaint = $this->complaintsinfoModel->loadThisComplaint($cid);
				if($getComplaint) {
					$data['residentname'] = $getComplaint->name;
					$data['res_id'] = $getComplaint->to_us_id;
					$data['type'] = $getComplaint->msg_type;
					$data['subject'] = $getComplaint->subject;
					$data['content'] = $getComplaint->content;
					$data['cid'] = $getComplaint->cm_id;
				}
				else {
					$data['residentname'] = '';
					$data['res_id'] = '';
					$data['type'] = '';
					$data['subject'] = '';
					$data['content'] = '';
					$data['cid'] = '';
				}
			}
			else {
				$data['residentname'] = '';
				$data['res_id'] = '';
				$data['type'] = '';
				$data['subject'] = '';
				$data['content'] = '';
				$data['cid'] = '';
			}
			$fv_id = $this->session->get('fav_id');
			$template = view('common/header',$data);
			$template .= view('complaints_informations');
			$template .=view('viewdetails');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/complaintsinfojs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}		
	}
	public function getResidents() {
		
		$fv_id = $this->session->get('fav_id');
		$searchkey = $this->input->getPost('searchkey');
		if($searchkey) {
			$residents_res = $this->complaintsinfoModel->getResidents($fv_id, $searchkey);
			if($residents_res) {
				echo json_encode(array("status"=>1, "resdata"=>$residents_res));
			}
			else {
				echo json_encode(array("status"=>0, "resdata"=>null));
			}
		}
		else {
			echo json_encode(array("status"=>0, "resdata"=>null));
		}
		
	}
	public function saveComplaint() {
		
		$cid = $this->input->getPost('cmp_id');
		$data = [
			'fv_id' => $this->session->get('fav_id'),
			'us_id' => $this->session->get('fav_user_id'),
			'to_us_id' => ($this->input->getPost('residence_hd') ? $this->input->getPost('residence_hd') : 0),
			'msg_type' => $this->input->getPost('msgtype'),
			'subject' => ucwords($this->input->getPost('subject')),
			'content' => ucfirst($this->input->getPost('content')),
			'status' => 1,
			'created_on' => date("Y-m-d H:i:s"),
			'created_by' => $this->session->get('fav_user_id')
		];		
		if($data['subject'] && $data['content']) {
			$msgText = ($this->input->getPost('msgtype') == 1 ? 'Complaint' : 'Information');
			if($cid) {
				$createComplaint = $this->complaintsinfoModel->updateComplaints($data,$cid);
				echo json_encode(array("status"=>1,
									"respmsg"=>$msgText . " data modified successfully."));
			}
			else {
				$createComplaint = $this->complaintsinfoModel->saveComplaints($data);
				echo json_encode(array("status"=>1,
									"respmsg"=>"New complaint created successfully."));
			}
		}
		else {
			echo json_encode(array("status"=>0,
								"respmsg"=>"Subject and content are mandatory. Please enter the required details."));
		}
		
	}
	public function changeStatus() {
		$data['status'] = $this->input->getPost('rl_status');
		$cid = $this->input->getPost('rl_id');
		$createComplaint = $this->complaintsinfoModel->updateComplaints($data,$cid);
		echo json_encode(1);
	}
	public function deleteComplaint($cid) {
		$data['status'] = 3;
		$createComplaint = $this->complaintsinfoModel->updateComplaints($data,$cid);
		echo json_encode(1);
	}
	public function loadComplaints() {
		
		## Read value
		$draw = $_POST['draw'];
		$row = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value
		$filter = '1=1';
		$tolimit = $row + $rowperpage;
		if($searchValue) {
			$filter .= " and cmp.subject like '%".$searchValue."%' or cmp.content like '%".$searchValue."%' or cmp.created_on like '%".$searchValue."%'";
		}
		$filter .= " and cmp.fv_id = ".$this->session->get('fav_id');
		$ListComplaints = $this->complaintsinfoModel->listAllComplaints($filter, $row, $tolimit);
		$data = array();
		$slno = $row;
		foreach($ListComplaints as $complaints) {
			$statusAct = '<div class="col-md-12 text-right">';
			$action = '';
			$toname = ($complaints->to_us_id!=0 ? $complaints->name : ($complaints->msg_type == 2 ? 'All Residence' : 'Association'));
			if($complaints->us_id == $this->session->get('fav_user_id')) {
				$action = '<a href="'.base_url("complaintsinfo/$complaints->cm_id").'"><i class="fa fa-pencil-square-o"></i></a>';
				$action .= '<a href="javascript:void(0)" onclick="deleteComplaints('.$complaints->cm_id.')"><i class="fa fa-trash-o"></i></a>';
			}
			else{
				$action .= '<a href="javascript:void(0)"><i class="fa fa-ban"></i></a>';
			}
		

			$statusAct .= '<a href="javascript:void(0);" data-id="' . $complaints->cm_id . '" onclick="viewDetails(' . $complaints->cm_id . ');" class="sm-abtn-view">View Details</a>';


			
			if($complaints->action_status == 1 || $complaints->action_status == 0) {
				$statusAct .= '<a href="javascript:void(0);" onclick="actionStat(this);" class="sm-abtn-pending">Pending</a>';
			}elseif ($complaints->action_status == 4) {
				$statusAct .= '<a href="javascript:void(0);" class="sm-abtn-replied">Replied</a>';
			}
			
			$statusAct .= '<a href="javascript:void(0);" class="sm-abtn-reply complaints-reply" onclick="replyComplaints('.$complaints->cm_id.',this)">Reply</a></div>';
			
			$checked = ($complaints->status==1 ? 'checked="checked"' : '' );
			$data[] = array(
				"slno"=>$slno + 1,
				"subject"=>ucwords($complaints->subject),
			    "content"=>'To '.$toname . ",<br/>" .ucfirst($complaints->content) . "<br/>" . $statusAct,
				"posted_on"=>date("d-m-Y H:i:s", strtotime($complaints->created_on)),
				"status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$complaints->cm_id.'" '.$checked.'/>',
				"action"=>$action
			);
			$slno++;
		}
		// Response
		$ListAllComps = $this->complaintsinfoModel->AllComplaintCount();
		$totalRecords = $ListAllComps->cmpnos;
		$ListFilterCmps = $this->complaintsinfoModel->AllComplaintsFilterCount($filter);
		$totalRecordwithFilter=$ListFilterCmps->filtercmpnos;
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
		echo json_encode($response);
		
	}

	//**Bala function start */

	public function saveReply() {
		$cm_id = $this->request->getPost('cm_id');
		$rply = $this->request->getPost('reply');
		$fv_id = $this->session->get('fav_id');
		$us_id = $this->session->get('fav_user_id');
	
		if (empty($cm_id)) {
			return $this->response->setJSON(["status" => 0, "respmsg" => "Invalid Complaint ID."]);
		}
	    $data = [
			'fv_id' => $fv_id,
			'cm_id' => $cm_id,
			'us_id' => $us_id,
			'reply' => ucfirst($rply),
			'created_on' => date("Y-m-d H:i:s"),
			'created_by' => $us_id
		];
	
		if ($this->replyinfoModel->saveComplaintReply($data)) {
			
			$updateData = ['action_status' => 4]; 
			if ($this->complaintsinfoModel->update($cm_id, $updateData)) {
				return $this->response->setJSON(["status" => 1, "respmsg" => "Reply sent successfully."]);
			}
			return $this->response->setJSON(["status" => 0, "respmsg" => "Reply sent, but status update failed."]);
		}
	
		return $this->response->setJSON(["status" => 0, "respmsg" => "Failed to send reply."]);
	}
	
	public function viewDetails($cm_id) {
		$data = $this->replyinfoModel->getComplaintDetails($cm_id);
		$loggedInUser = $this->session->get('fav_user_name');
		if (!empty($data)) {
			$complaintContent = isset($data[0]['content']) ? $data[0]['content'] : "No complaint found."; 
	
			$replies = [];
			foreach ($data as $reply) {
				if (!empty($reply['reply'])) {
					$replies[] = [
						'reply' => $reply['reply'],
						'reply_date' => $reply['reply_date'],
						];
				}
			}
          return $this->response->setJSON([
				'status' => 'success',
				'content' => $complaintContent,
				'replies' => $replies,
				'logged_in_user' => $loggedInUser 
			]);
		} else {
			return $this->response->setJSON([
				'status' => 'error',
				'message' => 'No complaint details found'
			]);
		}
	}
	
}
/******** Bala Function End */
?>