<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class BgServiceController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('BgModel');
	}

	public function indexBgService_get()
	{
		$bg = new BgModel;
		$bg_traps = $bg->get_service_pending_points();
		$this->response($bg_traps, 200);
	}
	public function insertBgService_get($service_id,$trap_id,$service_date,$service_time,$service_status,$run_id)
	{
		$bg = new BgModel;
		$bg_traps_count = $bg->check_bg_service_id($service_id);
		if ($bg_traps_count != 0) {
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
			$result = $bg->insert_bg_service($data);
			if($result > 0)
			{
				if($service_status=='1'){
					$data_old = ['trap_id' => $trap_id,'trap_status' => '2'];
					$trap_update_count = $this->BgModel->updateRecordsBgTrap($data_old);
				}
				$pending_count = $this->BgModel->checkRunPendingBgServicePoints($run_id);
				if ($pending_count== 0) {
					$pending_count_response = $this->BgModel->updateRunStatus($run_id);
				}
				$this->response([
					'status' => true,
					'message' => 'NEW BG SERVICE CREATED'
				], RestController::HTTP_OK);
			}
			else
			{
				$this->response([
					'status' => false,
					'message' => 'FAILED TO CREATE NEW BG Service'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}
}
?>

