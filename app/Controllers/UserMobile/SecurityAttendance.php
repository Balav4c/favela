<?php

namespace App\Controllers\UserMobile;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserMobile\SecurityAttendanceModel;
use App\Models\SecurityModel;

class SecurityAttendance extends ResourceController
{
    protected $modelName = SecurityAttendanceModel::class;
    protected $format = 'json';

    public function recordAttendance()
    {
        $data = $this->request->getJSON(true);

        // Validate required fields
        if (empty($data['sc_id']) || empty($data['fv_id']) || empty($data['type'])) {
            return $this->failValidationErrors('sc_id, fv_id, and type (check_in/check_out) are required.');
        }

        $sc_id = $data['sc_id'];
        $fv_id = $data['fv_id'];
        $type = $data['type']; // "check_in" or "check_out"

        // Validate if security guard is assigned to this flat
        $securityModel = new SecurityModel();
        $assigned = $securityModel->checkSecurity($fv_id, $sc_id);
        if (!$assigned) {
            return $this->failNotFound('Security guard not assigned to this flat.');
        }

        $attendanceModel = new SecurityAttendanceModel();
        $today = date('Y-m-d');

        // Check if attendance already exists for today
        $existing = $attendanceModel
            ->where('sc_id', $sc_id)
            ->where('attendance_date', $today)
            ->first();

        if ($type === 'check_in') {
            // if ($existing && !empty($existing['check_in_time'])) {
            //     return $this->fail('Already checked in today.');
            // }

            $attendanceData = [
                'sc_id' => $sc_id,
                'fv_id' => $fv_id,                  // <-- store fv_id
                'attendance_date' => $today,
                'check_in_time' => date('Y-m-d H:i:s'), // only check-in time
                'status' => '1',                    // 1 = present
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($existing) {
                // Update record if partially exists
                $attendanceModel->update($existing['attendance_id'], $attendanceData);
            } else {
                $attendanceModel->insert($attendanceData);
            }

            return $this->respond(['message' => 'Check-in recorded successfully.']);
        }

        if ($type === 'check_out') {
            if (!$existing || empty($existing['check_in_time'])) {
                return $this->fail('Cannot check out without checking in.');
            }

            // if (!is_null($existing['check_out_time']) && $existing['check_out_time'] != '0000-00-00 00:00:00') {
            //     return $this->fail('Already checked out today.');
            // }

            $attendanceModel->update($existing['attendance_id'], [
                'fv_id' => $fv_id,
                'check_out_time' => date('Y-m-d H:i:s'), // store check-out time
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respond(['message' => 'Check-out recorded successfully.']);
        }

        return $this->fail('Invalid type. Use "check_in" or "check_out".');
    }
    public function getAttendanceBySecurity()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['sc_id'])) {
            return $this->failValidationErrors('sc_id is required.');
        }

        $sc_id = $data['sc_id'];

        $attendanceModel = new \App\Models\UserMobile\SecurityAttendanceModel();
        $records = $attendanceModel
            ->where('sc_id', $sc_id)
            ->orderBy('attendance_date', 'DESC')
            ->findAll();

        if (empty($records)) {
            return $this->failNotFound('No attendance records found for this security guard.');
        }

        return $this->respond([
            'status' => true,
            'message' => 'Attendance records fetched successfully.',
            'data' => $records
        ]);
    }



}
