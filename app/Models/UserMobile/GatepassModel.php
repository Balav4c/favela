<?php
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class GatepassModel extends Model
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function getGatepass($fv_id)
    {
        return $this->db->table('gatepass')
            ->where('fv_id', $fv_id)
            ->where('status !=', 3)
            ->get()
            ->getResult();
    }
    public function saveGatepass($data)
    {
        $gatepass = $this->db->table('gatepass');

        // Ensure optional fields exist in $data
        $optionalFields = ['vehicle_no', 'visitor_place', 'visitor_image', 'visitor_phone'];
        foreach ($optionalFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $data[$field] = null;
            }
        }

        try {
            $gatepass->insert($data);
            if ($this->db->affectedRows() > 0) {
                $insertId = $this->db->insertID();
                return $gatepass->where('gp_id', $insertId)->get()->getRowArray();
            } else {
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Gatepass insert failed: ' . $e->getMessage());
            return false;
        }
    }

    public function getGatepassByUserAndFlat($uid, $fv_id)
    {
        return $this->db
            ->table('gatepass')
            ->where('fv_id', $fv_id)
            ->where('uid', $uid)
            ->where('status !=', 3)
            ->orderBy('date_of_visit', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function getGatepassByToken($token)
    {
        return $this->db->table('gatepass')->where('token', $token)->get()->getRowArray();
    }

    public function updateGatepassByToken($token, $data)
    {
        return $this->db->table('gatepass')->where('token', $token)->update($data);
    }

}
?>