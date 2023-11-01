<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Su extends CI_Controller
{

    public function index()
    {
        if ($this->session->userdata('su')) {
            redirect('su/dashboard');
        }
        $data['webname'] = 'Inventory';
        $cap = $this->Auth_models->captcha();
        $data['capImage'] = $cap['image'];
        $this->load->view('auth/layout/header', $data);
        $this->load->view('su/login', $data);
        $this->load->view('auth/layout/footer', $data);
    }
    public function dashboard()
    {
        $data['webname'] = 'Super User';
        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/breadcrum', $data);
        $this->load->view('su/home', $data);
        $this->load->view('layout/footer', $data);
    }
    public function login()
    {
        $user = $this->input->post('user');
        $password = $this->input->post('password');
        $captcha = $this->input->post('captcha');
        $captchaVerify = count($this->auth_models->verifyCaptcha($captcha, $this->input->ip_address()));
        if ($captchaVerify == 0) {
            $this->session->flash_data('message', 'You must submit the word that appears in the image.');
            return $this->index();
        }
        if (getAdmin() === $user) {
            if (getPass() === $password) {
                $data = [
                    'su' => 'Su'
                ];
                $this->session->set_userdata($data);
                redirect('su/dashboard');
            } else {
                redirect('auth');
                // echo 'salah pass';
            }
        } else {
            redirect('auth');
            // echo 'salah user' . getAdmin() . ' & ' . password_hash($user, PASSWORD_DEFAULT);
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out.</div>');
        redirect('auth');
    }
}
