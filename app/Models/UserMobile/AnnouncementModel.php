<?php
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{

    protected $table = 'announcements';
    protected $primaryKey = 'anoc_id';
    protected $allowedFields = [
        'subject',
        'announcements',
        'announce_date',
        'expiry_date',
        'us_id',
        'fv_id',
        'status',
        'created_by',
        'created_on'
    ];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function loadAnnouncements($user_id, $fv_id = null)
{
    $builder = $this->db->table('announcements a');
    $builder->select('a.*');

    // Filter by fv_id only if provided
    if ($fv_id) {
        $builder->where('a.fv_id', $fv_id);
    }

    // Only for this user
    $builder->where('a.us_id', $user_id);

    // Exclude expired announcements
    $builder->where("(a.expiry_date IS NULL OR a.expiry_date >= CURDATE())");

    // Only active announcements (status = 1)
    $builder->where('a.status', 1);

    $builder->orderBy('a.announce_date', 'DESC');

    return $builder->get()->getResult();
}

}
