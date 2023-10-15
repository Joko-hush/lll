<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hub extends CI_Controller
{

    public function index()
    {
        $this->load->view('index');
    }
}
