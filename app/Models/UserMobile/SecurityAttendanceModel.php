<?php

namespace App\Models\UserMobile;

use CodeIgniter\Model;

class SecurityAttendanceModel extends Model
{
    protected $table = 'security_attendance';
    protected $primaryKey = 'attendance_id';
    protected $allowedFields = [
        'sc_id', 'attendance_date', 'check_in_time', 'check_out_time', 'status', 'created_at', 'updated_at'
    ];
}
