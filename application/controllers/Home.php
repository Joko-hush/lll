<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function index()
    {

        if (get_cookie('inventory_cookie')) {
            redirect('admin/dashboard');
        }
        if ($this->session->userdata('email')) {
            redirect('admin/dashboard');
        }
        $data['web'] = getWeb();
        $this->load->view('layout/webheader', $data);
        $this->load->view('layout/webnav', $data);
        $this->load->view('home', $data);
        $this->load->view('layout/webfooter', $data);
    }
}
