<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BgModel extends CI_Model
{

	public function get_service_pending_points()
	{
		$this->load->database();
		$sync_retreive_bg_service_stored_proc = "CALL syncRetreiveBGService()";
		$result = $this->db->query($sync_retreive_bg_service_stored_proc);
		return $result->result();
	}
	public function get_collection_pending_points()
	{
		$this->load->database();
		$sync_retreive_bg_collection_stored_proc = "CALL syncRetreiveBGCollection()";
		$result = $this->db->query($sync_retreive_bg_collection_stored_proc);
		return $result->result();
	}

	public function check_bg_service_id($service_id)
	{
		$this->load->database();
		$this->db->where('service_id',$service_id);
		$query = $this->db->get('bg_service');
		return $query->num_rows();
	}
	public function check_bg_collection_id($collection_id)
	{
		$this->load->database();
		$this->db->where('collection_id',$collection_id);
		$query = $this->db->get('bg_collection');
		return $query->num_rows();
	}
	public function check_bg_trap_id($trap_id)
	{
		$this->load->database();
		$this->db->where('trap_id',$trap_id);
		$query = $this->db->get('bg_trap');
		return $query->num_rows();
	}
	public function insert_bg_service($data)
	{
		$this->load->database();
		return $this->db->insert('bg_service', $data);
	}
	function updateRecordsBgTrap($data)
	{
		try {
			$this->db->where('trap_id', $data['trap_id']);
			return $this->db->update('bg_trap', $data);
		}
		catch(Exception $e)
		{
			return 0;
		}
	}
	public function insert_bg_collection($data)
	{
		$this->load->database();
		return $this->db->insert('bg_collection', $data);
	}
	public function insert_bg_trap($data)
	{
		$this->load->database();
		return $this->db->insert('bg_trap', $data);
	}

	function checkRunPendingBgServicePoints($run_id){
		$sql="select a.bg_trap_id from bg_run_traps a, bg_service b where a.bg_run_id='$run_id' and 
				a.bg_trap_id not in (select trap_id from bg_service where run_id='$run_id')";
		$query=$this->db->query($sql);
		return $query->num_rows();
	}
	function checkRunPendingBgCollectionPoints($run_id){
		$sql="select a.bg_trap_id from bg_run_traps a, bg_collection b where a.bg_run_id='$run_id' and 
				a.bg_trap_id not in (select trap_id from bg_collection where run_id='$run_id')";
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

