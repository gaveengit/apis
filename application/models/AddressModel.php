<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AddressModel extends CI_Model
{

	public function insert_address($data)
	{
		$this->load->database();
		return $this->db->insert('address', $data);
	}
	public function get_address_id()
	{
		$this->load->database();
		$this->db->select_max('Address_id');
		$query = $this->db->get('address');
		return $query->result();
	}



}
?>

