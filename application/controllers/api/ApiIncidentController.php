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

	public function storeIncident_get($member_name,$email,$phone,$incident_type,$incident_priority,$description
										,$incident_date,$incident_time,$coordinates,$full_address,$location_description,
									   $gnd,$trap_code,$incident_status)
	{
		if($gnd=="NULL"){
			$gnd="";
		}
		if($trap_code=="NULL"){
			$trap_code="";
		}
		if($description=="NULL"){
			$description="";
		}
		if($coordinates=="NULL"){
			$coordinates="";
		}
		if($email=="NULL"){
			$email="";
		}

		$incidents = new IncidentModel;
		$data = [
			'member_name' => str_replace("%20"," ",$member_name),
			'email'=>$email,
			'phone'=>$phone,
			'incident_type'=>$incident_type,
			'incident_priority'=>$incident_priority,
			'description'=>str_replace("%20"," ",$description),
			'incident_date'=>$incident_date,
			'incident_time'=>$incident_time,
			'coordinates'=>$coordinates,
			'full_address'=>str_replace("%20"," ",$full_address),
			'location_description'=>str_replace("%20"," ",$location_description),
			'gnd'=>str_replace("%20"," ",$gnd),
			'trap_code'=>$trap_code,
			'incident_status'=>$incident_status
		];
		$result = $incidents->insert_incident($data);
		if($result > 0)
		{
			$this->response([
				'status' => true,
				'message' => 'NEW INCIDENT CREATED'
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
