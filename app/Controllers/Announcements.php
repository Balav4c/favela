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

    public function index($anoc_id = '') { 
        $data = [];

        if($anoc_id) {
            $ThisAnnouc = $this->announcementModel->getThisAnnouncement($anoc_id);
            $data['subject'] = $ThisAnnouc->subject;
            $data['announcements'] = $ThisAnnouc->announcements;

            // Convert DB dates YYYY-MM-DD → DD-MM-YYYY for Flatpickr
            $data['announce_date'] = (!empty($ThisAnnouc->announce_date) && $ThisAnnouc->announce_date != '0000-00-00')
    ? date('d-m-Y', strtotime($ThisAnnouc->announce_date)) : '';
$data['expiry_date'] = (!empty($ThisAnnouc->expiry_date) && $ThisAnnouc->expiry_date != '0000-00-00')
    ? date('d-m-Y', strtotime($ThisAnnouc->expiry_date)) : '';


            $data['anoc_id'] = $ThisAnnouc->anoc_id;
            $data['anoc'] = true;
        } else {
            $data['subject'] = null;
            $data['announcements'] = null;
            $data['announce_date'] = null;
            $data['expiry_date'] = null;
            $data['anoc_id'] = null;
        }

        if($this->session->get('fav_user_id') !== null) {
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
        } else {
            return redirect()->to(base_url().'sync/'); 
        }   
    }

    public function createnew() {
        $subject = $this->request->getPost('subject');
        $announcement = $this->request->getPost('announcements');
        $announce_date = $this->request->getPost('announce_date');
        $expiry_date = $this->request->getPost('expiry_date');
        $anoc_id = $this->request->getPost('anoc_id');
        $fv_id = $this->session->get('fav_id');

        // Validate
        if(empty($announcement)) return $this->respondJson(0, "Announcement is required.");
        if(empty($announce_date)) return $this->respondJson(0, "Publish Date is required.");
        if(empty($expiry_date)) return $this->respondJson(0, "End Date is required.");

        // Convert DD-MM-YYYY → YYYY-MM-DD
        $announce_date = date('Y-m-d', strtotime(str_replace('/', '-', $announce_date)));
        $expiry_date = date('Y-m-d', strtotime(str_replace('/', '-', $expiry_date)));

        // Ensure End Date >= Publish Date
        if(strtotime($expiry_date) < strtotime($announce_date)) return $this->respondJson(0, "End Date cannot be before Publish Date.");

        // Check duplicate
        $checkAnnounc = $this->announcementModel->checkAnnouncement($announcement, $fv_id, $anoc_id);
        // if($checkAnnounc) return $this->respondJson(0, "This Announcement already exists in the system.");

        // Prepare data
        $data = [
            'fv_id' => $fv_id,
            'subject' => $subject,
            'announcements' => $announcement,
            'announce_date' => $announce_date,
            'expiry_date' => $expiry_date,
            'status' => 1,
            'created_on' => date("Y-m-d H:i:s"),
            'created_by' => $this->session->get('fav_user_id'),
        ];

        if(empty($anoc_id) || $anoc_id == 0) {
            $this->announcementModel->createAnnouncement($data);
            return $this->respondJson(1, "Announcement created successfully.");
        } else {
            $this->announcementModel->modifyAnnouncement($anoc_id, $data);
            return $this->respondJson(1, "Announcement updated successfully.");
        }
    }

    public function listannouncement() {
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        $rowperpage = $_POST['length'];
        $searchValue = $_POST['search']['value'];

        $filter = "fv_id = ".$this->session->get('fav_id');
        if($searchValue) $filter .= " AND announcements LIKE '%".$searchValue."%'";

        $ListAnnouncements = $this->announcementModel->listAllAnnouncement($filter, $row, $row + $rowperpage);
        $data = [];
        $slno = $row;

        foreach($ListAnnouncements as $alist) {
            $today = date('Y-m-d');

            if(!empty($alist->expiry_date) && $alist->expiry_date < $today && $alist->status != 3) {
                $this->announcementModel->update($alist->anoc_id, ['status'=>3]);
                $alist->status = 3;
            }

            // Action buttons
            $action = ($alist->status != 3) ?
                '<a href="'.base_url("announcements/".$alist->anoc_id).'"><i class="fa fa-pencil-square-o"></i></a>
                 <a href="javascript:void(0)" onclick="deleteAnnouncement('.$alist->anoc_id.')"><i class="fa fa-trash-o text-danger"></i></a>'
                : '<a href="javascript:void(0)" onclick="deleteAnnouncement('.$alist->anoc_id.')"><i class="fa fa-trash-o text-danger"></i></a>';

            // Status text
            switch($alist->status){
                case 1: $statusText = '<span class="text-success">Published</span>'; break;
                case 2: $statusText = '<span class="text-warning">Unpublished</span>'; break;
                case 3: $statusText = '<span class="text-danger">Expired</span>'; break;
                default: $statusText = '<span class="text-muted">Unknown</span>';
            }

            // Dates
            $announceDateDisplay = ($alist->announce_date != '0000-00-00') ? date('d-m-Y', strtotime($alist->announce_date)) : '';
            $expiryDateDisplay = ($alist->expiry_date != '0000-00-00') ? date('d-m-Y', strtotime($alist->expiry_date)) : '';

            // Truncated announcement
            $announcementText = htmlspecialchars($alist->announcements);
            $previewText = mb_substr(strip_tags($announcementText), 0, 100, 'UTF-8');
            $showReadMore = strlen(strip_tags($announcementText)) > 100;
            $announcementDisplay = '<div class="announcement-text" data-full="'.htmlspecialchars($announcementText).'">'
                . nl2br($previewText)
                . ($showReadMore ? '... <a href="javascript:void(0);" class="read-more">Read More</a>' : '')
                . '</div>';

            $checked = ($alist->status == 1 ? 'checked="checked"' : '');

            $data[] = [
                "slno" => $slno+1,
                "subject" => $alist->subject,
                "announcements" => $announcementDisplay,
                "announce_date" => $announceDateDisplay,
                "expiry_date" => $expiryDateDisplay,
                "status_text" => $statusText,
                "status_toggle" => ($alist->status != 3) ? 
                    '<input type="checkbox" value="1" class="statcheck" onclick="statcheck(this);" data-id="'.$alist->anoc_id.'" '.$checked.'/>' 
                    : '<span class="text-muted">—</span>',
                "action" => $action
            ];
            $slno++;
        }

        $totalRecords = $this->announcementModel->AllAnnouncementsCount()->announos;
        $totalRecordwithFilter = $this->announcementModel->AllAnnouncementsFilterCount($filter)->filterannounnos;

        echo json_encode([
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        ]);
    }

    public function changeStatus() {
        $status = $this->request->getPost('status');
        $anoc_id = $this->request->getPost('anoc_id');
        $this->announcementModel->changeAnnouncementStatus($status, $anoc_id);
        echo json_encode(["status"=>1, "respmsg"=>"Status changed successfully."]);
    }

    public function deleteAnnouncement($aid) {
        if($aid) {
            $this->announcementModel->changeAnnouncementStatus(3, $aid);
            echo json_encode(1);
        } else {
            echo json_encode(2);
        }
    }

    // Helper for JSON response
    private function respondJson($status, $respmsg) {
        echo json_encode(['status'=>$status, 'respmsg'=>$respmsg]);
        exit;
    }
}
?>