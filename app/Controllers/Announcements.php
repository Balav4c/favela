<?php
namespace App\Controllers;
use App\Models\AnnouncementsModel;



class Announcements extends BaseController {
	protected $announcementModel;
   
	
	public function __construct() {
		
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->announcementModel = new AnnouncementsModel();
   }
    public function index($anoc_id= ''){ 
		$data = [];
        	if($anoc_id) {
			$ThisAnnouc = $this->announcementModel->getThisAnnouncement($anoc_id);
			$data['subject'] = $ThisAnnouc->subject;
			$data['announcements'] = $ThisAnnouc->announcements;
			$data['announce_date'] = $ThisAnnouc->announce_date;
			$data['expiry_date'] = $ThisAnnouc->expiry_date;
			$data['anoc_id'] = $ThisAnnouc->anoc_id;
			$data['anoc'] = true;
		}
		else {
			$data['subject'] = null;
			$data['announcements'] = null;
			$data['announce_date'] = null;
			$data['expiry_date'] = null;
			$data['anoc_id'] = null;
		}
   
    	if($this->session->get('fav_user_id')!== null && $this->session->get('fav_user_id')!='') {
			
			$fv_id = $this->session->get('fav_id');
			$user_id = $this->session->get('fav_user_id');
		
			
			$data['menu'] = 15;
			$data['username'] = ucwords($this->session->get('fav_user_name'));
			$data['orgname'] = ucwords($this->session->get('fav_org'));
			$template = view('common/header',$data);
			$template .= view('announcement');
			$template .= view('common/footer');
			$template .= view('common/pluginjs');
			$template .= view('common/datatablejs');
			$template .= view('page_script/announcejs');
			$template .= view('common/footer-closure');
			echo $template;
		}
		else {
			$redirectUrl = base_url().'sync/';
			return redirect()->to($redirectUrl); 
		}	
    }

   public function createnew() {
    $subject = $this->request->getPost('subject');
    $announcement = $this->request->getPost('announcements');
    $announc_date = $this->request->getPost('announce_date');
	$expiry_date = $this->request->getPost('expiry_date');
    $anoc_id = $this->request->getPost('anoc_id');
    $fv_id = $this->session->get('fav_id');

    $checkAnnounc = $this->announcementModel->checkAnnouncement($announcement, $fv_id, $anoc_id);

    if ($checkAnnounc) {
        echo json_encode(["status" => 0, "respmsg" => "This Announcement already exists in the system."]);
    } else {
        if ($announcement) {
            $data = [
                'fv_id'        => $fv_id,
                'subject'      => $subject,
                'announcements' => $announcement,
                'announce_date' => $announc_date,
				'expiry_date ' => $expiry_date,
                'created_on'   => date("Y-m-d H:i:s"),
                'created_by'   => $this->session->get('fav_user_id'),
            ];

            if (empty($anoc_id) || $anoc_id == 0) {
                $this->announcementModel->createAnnouncement($data);
                echo json_encode(["status" => 1, "respmsg" => "Announcement created successfully."]);
            } else {
                $this->announcementModel->modifyAnnouncement($anoc_id, $data);
                echo json_encode(["status" => 1, "respmsg" => "Announcement updated successfully."]);
            }
        } else {
            echo json_encode(["status" => 0, "respmsg" => "Please enter announcement."]);
        }
    }
}

    public function listannouncement() {
		
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
			$filter .= " and announcements like '%".$searchValue."%'";
		}
		$filter .= " and fv_id = ".$this->session->get('fav_id');
		$ListAnnouncements= $this->announcementModel->listAllAnnouncement($filter, $row, $tolimit);
		$data = array();
		$slno = $row;
		foreach($ListAnnouncements as $alist) {
			$action = '<a href="'.base_url("announcements/$alist->anoc_id").'"><i class="fa fa-pencil-square-o"></i></a>';
			$action .= '<a href="javascript:void(0)" onclick="deleteAnnouncement('.$alist->anoc_id.')"><i class="fa fa-trash-o"></i></a>';
			  // Toggle publish/unpublish icon based on status
    if ($alist->announce_status == 0) {
        $action .= '<a href="javascript:void(0)" class="toggle-status" data-id="'.$alist->anoc_id.'" data-status="1"><i class="fa-solid fa-check text-success"></i></a>';
    } else {
        $action .= '<a href="javascript:void(0)" class="toggle-status" data-id="'.$alist->anoc_id.'" data-status="0"><i class="fa-solid fa-ban text-danger"></i></a>';
    }

			$checked = ($alist->status==1 ? 'checked="checked"' : '' );
			$data[] = array(
				"slno"=>$slno + 1,
				"subject"=>$alist->subject,
				"announcements"=>$alist->announcements,
				"announce_date"=>$alist->announce_date,
				"expiry_date" => $alist->expiry_date,
                 "announce_status" => ($alist->announce_status == 0)
    ? '<span>Published</span>'
    : '<span>Unpublished</span>',

				"status"=>'<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$alist->anoc_id.'" '.$checked.'/>',
				"action"=>$action
			);
			$slno++;
		}
		// Response
		$ListAnnouncements = $this->announcementModel->AllAnnouncementsCount();
		$totalRecords = $ListAnnouncements->announos;
		$ListFilterAnnounce = $this->announcementModel->AllAnnouncementsFilterCount($filter);
		$totalRecordwithFilter=$ListFilterAnnounce->filterannounnos;
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
		echo json_encode($response);
	}
    
    	public function changeStatus() {
		$status = $this->input->getPost('status');
		$anoc_id = $this->input->getPost('anoc_id');
		$AnnouncStatus = $this->announcementModel->changeAnnouncementStatus($status, $anoc_id);
		echo json_encode(1);
	}
	public function deleteAnnouncement($aid) {
		if($aid) {
			$announceStatus = $this->announcementModel->changeAnnouncementStatus(3, $aid);
			echo json_encode(1);
		}
		else {
			echo json_encode(2);
		}
	}

	  public function toggleStatus()
{
    $id = $this->request->getPost('id');
    $status = $this->request->getPost('status');

    $data = ['announce_status' => $status];

    $update = $this->announcementModel->modifyAnnouncement($id, $data);

    if ($update) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}


}
?>