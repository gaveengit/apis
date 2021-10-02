<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class MrcServiceController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MrcModel');
	}

	public function indexMrcService_get()
	{
		$mrc = new MrcModel;
		$mrc_traps = $mrc->get_service_pending_points();
		$this->response($mrc_traps, 200);
	}
	public function insertMrcService_get($service_id,$trap_id,$service_date,$service_time,$service_status,$run_id)
	{
		$mrc = new MrcModel;
		$mrc_traps_count = $mrc->check_mrc_service_id($service_id);
		if ($mrc_traps_count != 0) {
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
			$result = $mrc->insert_mrc_service($data);
			if($result > 0)
			{
				if($service_status=='1'){
					$data_old = ['mrc_identifier' => $trap_id,'mrc_status' => '2'];
					$trap_update_count = $this->MrcModel->updateRecordsMrc($data_old);
				}
				$pending_count = $this->MrcModel->checkRunPendingMrcServicePoints($run_id);
				if ($pending_count== 0) {
					$pending_count_response = $this->MrcModel->updateRunStatus($run_id);
				}
				$this->response([
					'status' => true,
					'message' => 'NEW MRC SERVICE CREATED'
				], RestController::HTTP_OK);
			}
			else
			{
				$this->response([
					'status' => false,
					'message' => 'FAILED TO CREATE NEW MRC Service'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}
}
?>


