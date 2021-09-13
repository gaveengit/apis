<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class ApiIncidentController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('IncidentModel');
	}

	public function indexIncident_get()
	{
		$incident = new IncidentModel;
		$incidents = $incident->get_incident();
		$this->response($incidents, 200);
	}

	public function storeStudent_post()
	{
		$students = new IncidentModel;
		$data = [
			'name' =>  "Neesham",
			'class' => "13m6",
			'email' => "neesham@gmail.com",
			'created_at'=>"2021-09-12 18:43:27",
			'updated_at'=>"2021-09-12 18:43:27"
		];
		$result = $students->insert_student($data);
		if($result > 0)
		{
			$this->response([
				'status' => true,
				'message' => 'NEW STUDENT CREATED'
			], RestController::HTTP_OK);
		}
		else
		{
			$this->response([
				'status' => false,
				'message' => 'FAILED TO CREATE NEW STUDENT'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function editStudent_get($id)
	{
		$students = new IncidentModel;
		$students = $students->edit_student($id);
		$this->response($students, 200);
	}

	public function updateStudent_post($id)
	{
		$students = new IncidentModel;
		$data = [
			'name' =>  "Neesham123",
			'class' => "13m6",
			'email' => "neesham@gmail.com",
			'created_at'=>"2021-09-12 18:43:27",
			'updated_at'=>"2021-09-12 18:43:27"
		];
		$result = $students->update_student($data,$id);
		if($result > 0)
		{
			$this->response([
				'status' => true,
				'message' => 'STUDENT UPDATED'
			], RestController::HTTP_OK);
		}
		else
		{
			$this->response([
				'status' => false,
				'message' => 'FAILED TO UPDATE STUDENT'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function deleteStudent_post($id)
	{
		$students = new IncidentModel;
		$result = $students->delete_student($id);
		if($result > 0)
		{
			$this->response([
				'status' => true,
				'message' => 'STUDENT DELETED'
			], RestController::HTTP_OK);
		}
		else
		{
			$this->response([
				'status' => false,
				'message' => 'FAILED TO DELETE STUDENT'
			], RestController::HTTP_BAD_REQUEST);
		}
	}
}

?>
