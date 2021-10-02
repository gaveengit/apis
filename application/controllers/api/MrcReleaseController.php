<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class MrcReleaseController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MrcModel');
	}

	public function indexMrcRelease_get()
	{
		$mrc = new MrcModel;
		$mrc_traps = $mrc->get_release_pending_points();
		$this->response($mrc_traps, 200);
	}
	public function insertMrcRelease_get($release_id,$trap_id,$release_date,$release_time,$release_status,$run_id)
	{
		$mrc = new MrcModel;
		$mrc_traps_count = $mrc->check_mrc_release_id($release_id);
		if ($mrc_traps_count != 0) {
			$this->response([
				'status' => false,
				'message' => 'existing release id'
			], RestController::HTTP_OK);

		}
		else{
			$data = [
				'release_id' => $release_id,
				'identifier'=>$trap_id,
				'released_date'=>$release_date,
				'released_time'=>$release_time,
				'released_status'=>$release_status,
				'run_id'=>$run_id
			];
			$result = $mrc->insert_mrc_release($data);
			if($result > 0)
			{
				$pending_count = $this->MrcModel->checkRunPendingMrcReleasePoints($run_id);
				if ($pending_count== 0) {
					$pending_count_response = $this->MrcModel->updateRunStatus($run_id);
				}
				$this->response([
					'status' => true,
					'message' => 'new mrc release created'
				], RestController::HTTP_OK);
			}
			else
			{
				$this->response([
					'status' => false,
					'message' => 'failed to create new mrc release'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}
}
?>



