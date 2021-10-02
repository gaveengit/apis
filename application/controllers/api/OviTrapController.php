<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class OviTrapController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('OviModel');
		$this->load->model('PersonModel');
		$this->load->model('AddressModel');
	}

	public function insertOviTrap_get($trap_id,$trap_status,$trap_position,$coordinates,$ovi_date,$ovi_time,$person_name,
										$person_phone,$add_line1,$add_line2,$location_description)
	{
		$ovi = new OviModel;
		$person = new PersonModel;
		$address = new AddressModel;

		$ovi_traps_count = $ovi->check_ovi_trap_id($trap_id);
		if ($ovi_traps_count != 0) {
			$this->response([
				'status' => false,
				'message' => 'existing OVI trap id'
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

			$data_ovi_trap = [
				'trap_id' => $trap_id,
				'trap_status'=>$trap_status,
				'trap_position'=>$trap_position,
				'coordinates'=>$coordinates,
				'ovi_date'=>$ovi_date,
				'ovi_time'=>$ovi_time,
				'person_id'=>$result_person_id,
				'address_id'=>$result_address_id
			];
			$result_trap = $ovi->insert_ovi_trap($data_ovi_trap);
			if($result_trap > 0)
			{
				$this->response([
					'status' => true,
					'message' => 'NEW OVI TRAP CREATED'
				], RestController::HTTP_OK);
			}
			else
			{
				$this->response([
					'status' => false,
					'message' => 'FAILED TO CREATE NEW OVI TRAP'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}


}
?>

