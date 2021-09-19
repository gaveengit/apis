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

}
?>


