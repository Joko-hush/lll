<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		isLogin();
	}
	public function index()
	{
		$data = $this->session->userdata();
		var_dump($data);
	}
}
