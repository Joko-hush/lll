<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function index()
    {
        $data['webname'] = 'Inventory';
        $this->load->view('auth/layout/header', $data);
        $this->load->view('auth/login', $data);
        $this->load->view('auth/layout/footer', $data);
    }
}
