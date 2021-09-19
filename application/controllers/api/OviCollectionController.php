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
}
?>
