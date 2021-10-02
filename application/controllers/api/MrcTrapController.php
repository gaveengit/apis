<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class MrcTrapController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MrcModel');
		$this->load->model('PersonModel');
		$this->load->model('AddressModel');
	}

	public function insertMrcTrap_get($trap_id,$trap_status,$coordinates,$mrc_date,$mrc_time,$person_name,
									 $person_phone,$add_line1,$add_line2,$location_description)
	{
		$mrc = new MrcModel;
		$person = new PersonModel;
		$address = new AddressModel;

		$mrc_traps_count = $mrc->check_mrc_trap_id($trap_id);
		if ($mrc_traps_count != 0) {
			$this->response([
				'status' => false,
				'message' => 'existing MRC trap id'
			], RestController::HTTP_OK);

		}
		else{
			$data_person = [
				'Full_name' => $person_name,
				'Contact_number'=>$person_phone,
				'Person_status'=>'Active'
			];
			$result_person = $person->insert_person($data_person);
			$result_person_id = $person->get_person_id();
			$result_person_id = $result_person_id[0]->Person_id;

			$data_address = [
				'add_line1' => str_replace('%20', ' ', $add_line1),
				'add_line2'=>str_replace('%20', ' ', $add_line2),
				'location_description'=>$location_description,
				'location_status'=>'Active'
			];
			$result_address = $address->insert_address($data_address);
			$result_address_id = $address->get_address_id();
			$result_address_id = $result_address_id[0]->Address_id;

			$data_mrc_trap = [
				'mrc_identifier' => $trap_id,
				'mrc_status'=>$trap_status,
				'coordinates'=>$coordinates,
				'mrc_date'=>$mrc_date,
				'mrc_time'=>$mrc_time,
				'person_id'=>$result_person_id,
				'address_id'=>$result_address_id
			];
			$result_trap = $mrc->insert_mrc_trap($data_mrc_trap);
			if($result_trap > 0)
			{
				$this->response([
					'status' => true,
					'message' => 'NEW MRC TRAP CREATED'
				], RestController::HTTP_OK);
			}
			else
			{
				$this->response([
					'status' => false,
					'message' => 'FAILED TO CREATE NEW MRC TRAP'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}


}
?>


