<?php
namespace App\Models\Usermobile;

use CodeIgniter\Model;

class EmergencyModel extends Model
{
    protected $table = 'emergency_alerts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fv_id',
        'sender_uid',
        'alert_type',
        'message',
        'status',
        'resolved_by',
        'resolved_at'
    ];
}
