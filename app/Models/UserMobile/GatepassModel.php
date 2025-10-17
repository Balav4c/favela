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
        return $this->db->query("SELECT * FROM gatepass WHERE fv_id = '" . $fv_id . "'")->getResult();
    }

    public function saveGatepass($data)
    {
        try {
            $gatepass = $this->db->table('gatepass');

            // Ensure optional fields (like vehicle_no) exist in $data, even if null
            if (!isset($data['vehicle_no'])) {
                $data['vehicle_no'] = null;
            }

            $gatepass->insert($data);

            // Check if insert succeeded
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
            ->where('uid', $uid)  // changed from created_by to uid
            ->orderBy('date_of_visit', 'DESC')
            ->get()
            ->getResultArray();
    }


}
?>