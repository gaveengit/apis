<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class LoginController extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('BgModel');
		$this->load->model('PersonModel');
		$this->load->model('AddressModel');
		$this->load->model('LoginModel');
	}
	public function indexCheckLogin_get($username,$password)
	{
		$login = new LoginModel;
		$data = [
			'username' => $username,
			'password'=>hash("sha256", $password)
		];
		$login_check = $login->checkLogin($data);
		$this->response($login_check, 200);
	}

}
?>

