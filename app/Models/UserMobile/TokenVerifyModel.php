<?php 
namespace App\Models\UserMobile;

use CodeIgniter\Model;

class TokenVerifyModel extends Model {
	
	public function __construct() {
		$this->db = \Config\Database::connect();
	}	
	public function verifyToken($token, $uid)
{
    return $this->db->table('app_users')
                    ->where('token', $token)
                    ->where('uid', $uid)
                    ->get()
                    ->getRowArray();
}


 public function verifySecurityToken($token, $sc_id)
    {
     return $this->db->table('security')
                        ->where('token', $token)
                        ->where('sc_id', $sc_id)
                        ->get()
                        ->getRowArray();
    }
}
?>