<?php
namespace App\Controllers\Usermobile;

use App\Controllers\BaseController;
use App\Models\Usermobile\EmergencyModel;
use App\Models\Usermobile\NotificationModel;
use App\Models\UserModel;

class EmergencyAlert extends BaseController
{
    protected $alertModel;
    protected $notificationModel;
    protected $userModel;

    public function __construct()
    {
        $this->alertModel = new EmergencyModel();
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();

    }
    public function sendAlert()
    {
        $input = $this->request->getJSON(true);

        $fv_id = $input['fv_id'] ?? null;
        $sender_uid = $input['sender_uid'] ?? null;
        $alert_type = $input['alert_type'] ?? null;
        $message = $input['message'] ?? '';

        if (empty($fv_id) || empty($sender_uid) || empty($alert_type) || empty($message)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing required fields.'
            ]);
        }

        $alertId = $this->alertModel->insert([
            'fv_id' => $fv_id,
            'sender_uid' => $sender_uid,
            'alert_type' => $alert_type,
            'message' => $message,
            'status' => 1
        ], true);

        $response = [
            'success' => true,
            'id' => $alertId,
            'message' => 'Emergency alert sent successfully.'
        ];

        log_message('debug', 'SendAlert response: ' . json_encode($response));

        return $this->response
            ->setContentType('application/json')
            ->setStatusCode(200)
            ->setBody(json_encode($response));
    }
    public function getActiveAlerts()
    {
        $input = $this->request->getJSON(true);
        $fv_id = $input['fv_id'] ?? null;


        if (empty($fv_id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'fv_id required']);
        }

        $alerts = $this->alertModel
            ->select('emergency_alerts.*, app_users.name as sender_name')
            ->join('app_users', 'app_users.uid = emergency_alerts.sender_uid', 'left')
            ->where(['emergency_alerts.fv_id' => $fv_id, 'emergency_alerts.status' => 1])
            ->orderBy('emergency_alerts.id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'alerts' => $alerts
        ]);
    }
    public function resolveAlert()
    {
        $input = $this->request->getJSON(true);
        $alert_id = $input['alert_id'] ?? null;
        $resolved_by = $input['resolved_by'] ?? null;

        if (empty($alert_id) || empty($resolved_by)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'alert_id and resolved_by are required.'
            ]);
        }

        $alert = $this->alertModel->find($alert_id);
        if (!$alert) {
            return $this->response->setJSON(['success' => false, 'message' => 'Alert not found.']);
        }

        $this->alertModel->update($alert_id, [
            'status' => 2, // 2 = resolved
            'resolved_by' => $resolved_by,
            'resolved_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alert resolved successfully.'
        ]);
    }
}
