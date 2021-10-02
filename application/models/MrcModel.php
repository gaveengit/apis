<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MrcModel extends CI_Model
{

	public function get_service_pending_points()
	{
		$this->load->database();
		$sync_retreive_mrc_service_stored_proc = "CALL syncRetreiveMRCService()";
		$result = $this->db->query($sync_retreive_mrc_service_stored_proc);
		return $result->result();
	}
	public function get_release_pending_points()
	{
		$this->load->database();
		$sync_retreive_mrc_release_stored_proc = "CALL syncRetreiveMrcRelease()";
		$result = $this->db->query($sync_retreive_mrc_release_stored_proc);
		return $result->result();
	}

	public function check_mrc_service_id($service_id)
	{
		$this->load->database();
		$this->db->where('service_id',$service_id);
		$query = $this->db->get('mrc_service');
		return $query->num_rows();
	}
	public function check_mrc_release_id($release_id)
	{
		$this->load->database();
		$this->db->where('release_id',$release_id);
		$query = $this->db->get('mrc_release');
		return $query->num_rows();
	}
	public function check_mrc_trap_id($trap_id)
	{
		$this->load->database();
		$this->db->where('mrc_identifier',$trap_id);
		$query = $this->db->get('mrc');
		return $query->num_rows();
	}
	public function insert_mrc_service($data)
	{
		$this->load->database();
		return $this->db->insert('mrc_service', $data);
	}
	public function insert_mrc_release($data)
	{
		$this->load->database();
		return $this->db->insert('mrc_release', $data);
	}
	public function insert_mrc_trap($data)
	{
		$this->load->database();
		return $this->db->insert('mrc', $data);
	}
	function updateRecordsMrc($data)
	{
		try {
			$this->db->where('mrc_identifier', $data['mrc_identifier']);
			return $this->db->update('mrc', $data);
		}
		catch(Exception $e)
		{
			return 0;
		}
	}
	function checkRunPendingMrcservicePoints($run_id){
		$sql="select a.mrc_trap_id from mrc_run_traps a, mrc_service b where a.mrc_run_id='$run_id' and 
				a.mrc_trap_id not in (select trap_id from mrc_service where run_id='$run_id')";
		$query=$this->db->query($sql);
		return $query->num_rows();
	}
	function checkRunPendingMrcReleasePoints($run_id){
		$sql="select a.mrc_trap_id from mrc_run_traps a, mrc_release b where a.mrc_run_id='$run_id' and 
				a.mrc_trap_id not in (select identifier from mrc_release where run_id='$run_id')";
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


