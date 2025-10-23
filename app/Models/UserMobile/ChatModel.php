<?php
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table = 'residence_chat';
    protected $primaryKey = 'chat_id';
    protected $allowedFields = ['unique_key','fv_id', 'created_date', 'status'];
    public $useTimestamps = false;
}
