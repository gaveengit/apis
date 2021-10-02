<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PersonModel extends CI_Model
{

	public function insert_person($data)
	{
		$this->load->database();
		return $this->db->insert('person', $data);
	}
	public function get_person_id()
	{
		$this->load->database();
		$this->db->select_max('Person_id');
		$query = $this->db->get('person');
		return $query->result();
	}



}
?>

