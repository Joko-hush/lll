<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('home/dashboard');
        } else {
            return $this->login();
        }
    }
    public function login()
    {
        $data['webname'] = 'Inventory';

        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('captcha', 'captcha', 'required|trim');
        if ($this->form_validation->run() == true) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $captcha = $this->input->post('captcha');
            $ip = $this->input->ip_address();
            $cap = $this->Auth_models->verifyCaptcha($captcha, $ip);
            if ($cap > 0) {
                $this->db->where('email', $email);
                $query = $this->db->get('users');
                $result = $query->row_array();
                // Anda dapat mengakses hasilnya seperti ini:
                if ($result) {
                    $password1 = $result['password'];
                    if (password_verify($password, $password1)) {
                        $sess = [
                            'email' => $result['email'],
                            'phone' => $result['phone'],
                            'role' => $result['role'],
                            'nama' => $result['nama']
                        ];
                        $this->session->set_userdata($sess);
                        $this->session->set_flashdata('message', '<div class="alert alert-success mt-2" role="alert">Berhasil login</div>');
                        redirect('welcome');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger mt-2" role="alert">Password Salah</div>');
                        redirect('auth');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger mt-2" role="alert">Email tidak terdaftar</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger mt-2" role="alert">Captcha salah. Silahkan diulangi</div>');
                $cap = $this->Auth_models->captcha();
                $data['capImage'] = $cap['image'];
                $this->load->view('auth/layout/header', $data);
                $this->load->view('auth/login', $data);
                $this->load->view('auth/layout/footer', $data);
            }
        } else {
            $cap = $this->Auth_models->captcha();
            $data['capImage'] = $cap['image'];
            $this->load->view('auth/layout/header', $data);
            $this->load->view('auth/login', $data);
            $this->load->view('auth/layout/footer', $data);
        }
    }
    public function register()
    {
        $data['webname'] = 'Inventory';

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('phone', 'Phone', 'required|trim');
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[6]|matches[password2]', ['matches' => 'Password not match!', 'min_length' => 'password too short!']);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $cap = $this->Auth_models->captcha();
            $data['capImage'] = $cap['image'];
            $this->load->view('auth/layout/header', $data);
            $this->load->view('auth/register', $data);
            $this->load->view('auth/layout/footer', $data);
        } else {
            $nama = $this->input->post('nama');
            $email = $this->input->post('email');
            $password = $this->input->post('password1');
            $captcha = $this->input->post('captcha');
            $phone = $this->input->post('phone');
            $ip = $this->input->ip_address();
            $cap = $this->Auth_models->verifyCaptcha($captcha, $ip);

            if ($cap == 1) {
                $dataHome = [
                    'nama' => $nama,
                    'alamat' => '',
                    'provinsi' => '',
                    'kabupaten' => '',
                    'kecamatan' => '',
                    'desa' => '',
                    'email' => $email,
                    'phone1' => '',
                    'phone2' => '',
                    'website' => '',
                    'logo' => 'defaultHome.png',
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
                $this->db->insert('home', $dataHome);
                // home created
                $this->db->where('email', $email);
                $home = $this->db->get_where('home')->row_array();
                $data = [
                    'nama' => $nama,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => '',
                    'id_kantor' => 0,
                    'id_home' => $home['id'],
                    'created_at' => time(),
                    'updated_at' => time()
                ];
                $this->db->insert('users', $data);
                // user created
                $this->db->where('email', $email);
                $user = $this->db->get_where('users')->row_array();
                $dataKantor = [
                    'nama' => 'Kantor Utama',
                    'alamat' => '',
                    'provinsi' => '',
                    'kabupaten' => '',
                    'kecamatan' => '',
                    'desa' => '',
                    'email' => $email,
                    'phone1' => '',
                    'phone2' => '',
                    'website' => '',
                    'logo' => 'defaultHome.png',
                    'created_at' => time(),
                    'updated_at' => time(),
                    'id_user' => $user['id'],
                    'id_home' => $home['id'],
                ];
                $this->db->insert('kantor', $dataKantor);

                $this->db->set('id_user', $user['id']);
                $this->db->where('email', $email);
                $this->db->update('home');
                $this->session->set_flashdata('message', '<div class="alert alert-success mt-2" role="alert">Congratulation. Your Account has been registered.</div>');
                redirect('auth');
                // }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger mt-2" role="alert">Wrong Captcha</div>');
                redirect('auth/register');
            }
        }
    }
    public function forgot()
    {
        $data['webname'] = 'Inventory';
        $cap = $this->Auth_models->captcha();
        $data['capImage'] = $cap['image'];
        $this->load->view('auth/layout/header', $data);
        $this->load->view('auth/forgot', $data);
        $this->load->view('auth/layout/footer', $data);
    }
    public function recaptcha()
    {
        $cap = $this->Auth_models->captcha();
        $image = $cap['image'];
        $data = array('img' => $image);
        return json_encode($data);
    }
    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out.</div>');
        redirect('auth');
    }
}
