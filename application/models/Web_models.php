<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Web_models extends CI_Model
{
    public function getWeb()
    {
        $this->db->where('id', 1);
        $web = $this->db->get('website')->row_array();
        return $web;
    }
}
