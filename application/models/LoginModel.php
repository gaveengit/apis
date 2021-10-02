<?php


class LoginModel extends CI_Model
{
	function checkLogin($data)
	{
		try {
			$this->load->database();
			$array = array('username' => $data["username"],'password'=>$data["password"],'STATUS'=>'1');
			$array_in = array('1','7');
			$this->db->where($array);
			$this->db->where_in('user_type', $array_in);
			$query = $this->db->get('login');
			return $query->result();
		}
		catch(Exception $e){
			echo $e;
		}
	}
}
