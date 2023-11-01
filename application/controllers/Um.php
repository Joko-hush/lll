<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Um extends CI_Controller
{

    public function index()
    {
        $this->load->view('index');
    }
    public function dashboard()
    {
        $data['webname'] = 'Inventory';
        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/breadcrum', $data);
        $this->load->view('um/home', $data);
        $this->load->view('layout/footer', $data);
    }
}
