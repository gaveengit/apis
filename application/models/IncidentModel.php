<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IncidentModel extends CI_Model
{

	public function get_incident()
	{
		$this->load->database();
		$query = $this->db->get("incident");
		return $query->result();
	}

	public function insert_incident($data)
	{
		$this->load->database();
		return $this->db->insert('incident', $data);
	}

	public function edit_student($id)
	{
		$this->load->database();
		$this->db->where('id',$id);
		$query = $this->db->get('students');
		return $query->row;
	}

	public function update_student($data,$id)
	{
		$this->load->database();
		$this->db->where('id', $id);
		return $this->db->update('students', $data);
	}

	public function delete_student($id)
	{
		$this->load->database();
		return $this->db->delete('students', ['id' => $id]);
	}

}

?>
