<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class BgTrapController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('BgModel');
		$this->load->model('PersonModel');
		$this->load->model('AddressModel');
	}

	public function insertBgTrap_get($trap_id,$trap_status,$trap_position,$coordinates,$bg_date,$bg_time,$person_name,
									  $person_phone,$add_line1,$add_line2,$location_description)
	{
		$bg = new BgModel;
		$person = new PersonModel;
		$address = new AddressModel;

		$bg_traps_count = $bg->check_bg_trap_id($trap_id);
		if ($bg_traps_count != 0) {
			$this->response([
				'status' => false,
				'message' => 'existing BG trap id'
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

			$data_bg_trap = [
				'trap_id' => str_replace('%20', '', $trap_id),
				'trap_status'=>str_replace('%20', '', $trap_status),
				'trap_position'=>str_replace('%20', '', $trap_position),
				'coordinates'=>str_replace('%20', '', $coordinates),
				'bg_date'=>$bg_date,
				'bg_time'=>$bg_time,
				'person_id'=>$result_person_id,
				'address_id'=>$result_address_id
			];
			$result_trap = $bg->insert_bg_trap($data_bg_trap);
			if($result_trap > 0)
			{
				$this->response([
					'status' => true,
					'message' => 'NEW BG TRAP CREATED'
				], RestController::HTTP_OK);
			}
			else
			{
				$this->response([
					'status' => false,
					'message' => 'FAILED TO CREATE NEW BG TRAP'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}


}
?>

