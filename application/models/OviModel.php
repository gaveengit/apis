<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OviModel extends CI_Model
{

	public function get_service_pending_points()
	{
		$this->load->database();
		$sync_retreive_ovi_service_stored_proc = "CALL syncRetreiveOVIService()";
		$result = $this->db->query($sync_retreive_ovi_service_stored_proc);
		return $result->result();
	}
	public function get_collection_pending_points()
	{
		$this->load->database();
		$sync_retreive_ovi_collection_stored_proc = "CALL syncRetreiveOVICollection()";
		$result = $this->db->query($sync_retreive_ovi_collection_stored_proc);
		return $result->result();
	}
	public function check_ovi_service_id($service_id)
	{
		$this->load->database();
		$this->db->where('service_id',$service_id);
		$query = $this->db->get('ovi_service');
		return $query->num_rows();
	}
	public function check_ovi_collection_id($collection_id)
	{
		$this->load->database();
		$this->db->where('collection_id',$collection_id);
		$query = $this->db->get('ovi_collection');
		return $query->num_rows();
	}
	public function check_ovi_trap_id($trap_id)
	{
		$this->load->database();
		$this->db->where('trap_id',$trap_id);
		$query = $this->db->get('ovi_trap');
		return $query->num_rows();
	}
	public function insert_ovi_service($data)
	{
		$this->load->database();
		return $this->db->insert('ovi_service', $data);
	}
	public function insert_ovi_collection($data)
	{
		$this->load->database();
		return $this->db->insert('ovi_collection', $data);
	}
	public function insert_ovi_trap($data)
	{
		$this->load->database();
		return $this->db->insert('ovi_trap', $data);
	}
	function updateRecordsOviTrap($data)
	{
		try {
			$this->db->where('trap_id', $data['trap_id']);
			return $this->db->update('ovi_trap', $data);
		}
		catch(Exception $e)
		{
			return 0;
		}
	}

	function checkRunPendingOviServicePoints($run_id){
		$sql="select a.ovi_trap_id from ovi_run_traps a, ovi_service b where a.ovi_run_id='$run_id' and 
				a.ovi_trap_id not in (select trap_id from ovi_service where run_id='$run_id')";
		$query=$this->db->query($sql);
		return $query->num_rows();
	}
	function checkRunPendingOviCollectionPoints($run_id){
		$sql="select a.ovi_trap_id from ovi_run_traps a, ovi_collection b where a.ovi_run_id='$run_id' and 
				a.ovi_trap_id not in (select trap_id from ovi_collection where run_id='$run_id')";
		$query=$this->db->query($sql);
		return $query->num_rows();
	}
	function updateRunStatus($run_id){
		try {

			$sql = "update field_run set run_status='2' where field_run_id='$run_id'";
			$query = $this->db->query($sql);
			return true;
		}
		catch(Exception $e){
			echo $e;
		}
	}


}
?>
