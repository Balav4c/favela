<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'app_users';        // Table name
    protected $primaryKey = 'uid';         // Primary key column

    protected $allowedFields = [
        'token',
        'name',
        'gender',
        'dob',
        'phone',
        'email_id',
        'm_pin',
        'aadhar_no',
        'p_address',
        'c_address',
        'aadhar_copy',
        'profile_photo',
        'manual_photo',
        'id_proof',
        'id_proof_number',
        'status',
        'status_comment'
    ];

    protected $useTimestamps = false; // Disable auto timestamps (since no created_at/updated_at in your table)
}
