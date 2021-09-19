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
}
?>



