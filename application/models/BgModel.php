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

}
?>

