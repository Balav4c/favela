<?php
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'residence_messages';
    protected $primaryKey = 'msg_id';
    protected $allowedFields = [
        'chat_id',
        'fv_id',
        'sender_id',
        'receiver_id',
         'sender_type',
        'receiver_type',
        'message',
        'status',
        'created_at'
    ];
    public $useTimestamps = false;
}
