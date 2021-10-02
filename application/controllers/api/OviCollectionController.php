<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class OviCollectionController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('OviModel');
	}

	public function indexOviCollection_get()
	{
		$ovi = new OviModel;
		$ovi_traps = $ovi->get_collection_pending_points();
		$this->response($ovi_traps, 200);
	}
	public function insertOviCollection_get($collection_id,$trap_id,$collection_date,$collection_time,$collection_status,$run_id)
	{
		$ovi = new OviModel;
		$ovi_traps_count = $ovi->check_ovi_collection_id($collection_id);
		if ($ovi_traps_count != 0) {
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
			$result = $ovi->insert_ovi_collection($data);
			if($result > 0)
			{
				$pending_count = $this->OviModel->checkRunPendingOviCollectionPoints($run_id);
				if ($pending_count== 0) {
					$pending_count_response = $this->OviModel->updateRunStatus($run_id);
				}
				$this->response([
					'status' => true,
					'message' => 'new ovi collection created'
				], RestController::HTTP_OK);
			}
			else
			{
				$this->response([
					'status' => false,
					'message' => 'failed to create new ovi collection'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}
}
?>
