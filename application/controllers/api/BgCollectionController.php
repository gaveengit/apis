<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class BgCollectionController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('BgModel');
	}

	public function indexBgCollection_get()
	{
		$bg = new BgModel;
		$bg_traps = $bg->get_collection_pending_points();
		$this->response($bg_traps, 200);
	}
	public function insertBgCollection_get($collection_id,$trap_id,$collection_date,$collection_time,$collection_status,$run_id)
	{
		$bg = new BgModel;
		$bg_traps_count = $bg->check_bg_collection_id($collection_id);
		if ($bg_traps_count != 0) {
			$this->response([
				'status' => false,
				'message' => 'existing collection id'
			], RestController::HTTP_OK);

		}
		else{
			$data = [
				'collection_id' => $collection_id,
				'trap_id'=>$trap_id,
				'collect_date'=>$collection_date,
				'collect_time'=>$collection_time,
				'collect_status'=>$collection_status,
				'run_id'=>$run_id
			];
			$result = $bg->insert_bg_collection($data);
			if($result > 0)
			{
				$pending_count = $this->BgModel->checkRunPendingBgCollectionPoints($run_id);
				if ($pending_count== 0) {
					$pending_count_response = $this->BgModel->updateRunStatus($run_id);
				}
				$this->response([
					'status' => true,
					'message' => 'new bg collection created'
				], RestController::HTTP_OK);
			}
			else
			{
				$this->response([
					'status' => false,
					'message' => 'failed to create new bg collection'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}
}
?>


