<?php
namespace App\Models;

use CodeIgniter\Model;

class GatePassModel extends Model
{

	public function __construct()
	{
		$this->db = \Config\Database::connect();
	}
	public function createPass($data)
	{
		$this->db->table('gatepass')->insert($data);
		return $this->db->insertID();
	}
	public function updatePass($data, $gp_id)
	{
		return $this->db->table('gatepass')
			->where(["gp_id" => $gp_id])
			->set($data)
			->update();
	}
	public function listAllPass($filter, $row, $tolimit)
	{
		return $this->db->query("SELECT * FROM gatepass where " . $filter . " and status<>3 order by gp_id desc limit " . $row . "," . $tolimit)->getResult();
	}
	public function AllPassCount()
	{
		return $this->db->query("SELECT count(*) as passnos FROM gatepass where status<>3")->getRow();
	}
	public function AllPassFilterCount($filter)
	{
		return $this->db->query("SELECT count(*) as filpassnos FROM gatepass where " . $filter . " and status<>3")->getRow();
	}
	public function getThisGatePass($gp_id, $fv_id)
	{
		$sql = "
        SELECT 
            gp.*,
            sc_in.security_name AS security_in_name,
            sc_out.security_name AS security_out_name
        FROM gatepass AS gp
        LEFT JOIN security AS sc_in ON sc_in.sc_id = gp.sg_id_in
        LEFT JOIN security AS sc_out ON sc_out.sc_id = gp.sg_id_out
        WHERE gp.gp_id = ? AND gp.fv_id = ?
    ";

		return $this->db->query($sql, [$gp_id, $fv_id])->getRow();
	}

	public function deleteGatepass($gp_id, $fv_id)
	{
		return $this->db->query("delete from gatepass where gp_id = " . $gp_id . " and fv_id = " . $fv_id);
	}
	public function getTokenGatePass($accesskey)
	{
		return $this->db->query("
        SELECT gp.*, 
               sc_in.security_name AS security_in_name,
               sc_out.security_name AS security_out_name
        FROM gatepass AS gp
        LEFT JOIN security AS sc_in ON sc_in.sc_id = gp.sg_id_in
        LEFT JOIN security AS sc_out ON sc_out.sc_id = gp.sg_id_out
        WHERE gp.token = '" . $accesskey . "'
    ")->getRow();
	}

}
?>