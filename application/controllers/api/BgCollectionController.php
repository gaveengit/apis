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
}
?>


