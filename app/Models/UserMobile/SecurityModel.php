<?php
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class SecurityModel extends Model
{
    protected $table = 'security';
    protected $primaryKey = 'sc_id';
    protected $allowedFields = [
        'security_name',
        'security_phone',
        'mpin',
        'id_proof',
        'id_proof_no',
        'status',
        'feedback',
        'security_company',
        'security_company_address',
        'token'
    ];

    // Get security by phone
    public function getSecurityByPhone($phone)
    {
        return $this->db->table($this->table)
            ->where('security_phone', $phone)
            ->where('status', 1)
            ->get()
            ->getRowArray();
    }

    // Update token
    public function updateToken($sc_id, $token)
    {
        return $this->db->table($this->table)
            ->where('sc_id', $sc_id)
            ->update(['token' => $token]);
    }

    // Create/update MPIN
    public function updateMpin($phone, $mpin)
    {
        return $this->db->table($this->table)
            ->where('security_phone', $phone)
            ->update(['mpin' => $mpin]);
    }

    public function getSecurityByToken($token)
    {
        return $this->db->table('security')
            ->where('token', $token)
            ->get()
            ->getRowArray();
    }



    public function updateMpinById($id, $hashedMpin)
    {
        return $this->update($id, ['mpin' => $hashedMpin]);
    }
    
public function getOrgsList($sc_id) {
    return $this->db->table('flats_security')
                    ->select('fv_id')
                    ->where('sc_id', $sc_id)
                    ->where('status', 1)
                    ->get()
                    ->getResult();
}



}
