<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class OviServiceController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('OviModel');
	}

	public function indexOviService_get()
	{
		$ovi = new OviModel;
		$ovi_traps = $ovi->get_service_pending_points();
		$this->response($ovi_traps, 200);
	}

	public function insertOviService_get($service_id,$trap_id,$service_date,$service_time,$service_status,$run_id)
	{
		$ovi = new OviModel;
		$ovi_traps_count = $ovi->check_ovi_service_id($service_id);
		if ($ovi_traps_count != 0) {
			$this->response([
				'status' => false,
				'message' => 'existing service id'
			], RestController::HTTP_OK);

		}
		else{
			$data = [
				'service_id' => $service_id,
				'trap_id'=>$trap_id,
				'service_date'=>$service_date,
				'service_time'=>$service_time,
				'service_status'=>$service_status,
				'run_id'=>$run_id
			];
			$result = $ovi->insert_ovi_service($data);
			if($result > 0)
			{
				if($service_status=='1'){
					$data_old = ['trap_id' => $trap_id,'trap_status' => '2'];
					$trap_update_count = $this->OviModel->updateRecordsOviTrap($data_old);
				}
				$pending_count = $this->OviModel->checkRunPendingOviServicePoints($run_id);
				if ($pending_count== 0) {
					$pending_count_response = $this->OviModel->updateRunStatus($run_id);
				}
				$this->response([
					'status' => true,
					'message' => 'NEW OVI SERVICE CREATED'
				], RestController::HTTP_OK);
			}
			else
			{
				$this->response([
					'status' => false,
					'message' => 'FAILED TO CREATE NEW OVI Service'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}


}
?>
