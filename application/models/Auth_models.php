<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_models extends CI_Model
{
    public function index()
    {
        $this->load->view('index');
    }
    public function captcha()
    {
        $characters = '123456789abcdefghijklmnpqrstuvwxyz';
        $n = 6;
        $randomString = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $vals = array(
            'word'          => $randomString,
            'img_path'      => './assets/captcha/',
            'img_url'       => base_url('assets/captcha/'),
            'font_path'     => './assets/font/RockoFLF-Bold.ttf',
            'img_width'     => '150',
            'img_height'    => 40,
            'expiration'    => 7200,
            'word_length'   => 6,
            'font_size'     => 16,
            'img_id'        => 'Imageid',
            'pool'          => '123456789abcdefghijklmnpqrstuvwxyz',

            // White background and border, black text and red grid
            'colors'        => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            )
        );
        $cap = create_captcha($vals);
        $data = array(
            'captcha_time'  => $cap['time'],
            'ip_address'    => $this->input->ip_address(),
            'word'          => $cap['word'],
            'img' => $cap['filename'],
        );
        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);
        return $cap;
    }
    public function verifyCaptcha($captcha, $ip)
    {
        // First, delete old captchas
        $expiration = time() - 7200; // Two hour limit
        $this->db->where('captcha_time < ', $expiration)
            ->delete('captcha');

        // Then see if a captcha exists:
        $this->db->select('COUNT(*) as count');
        $this->db->from('captcha');
        $this->db->where('word', $captcha);
        $this->db->where('ip_address', $ip);
        $this->db->where('captcha_time >', $expiration);

        $query = $this->db->get();
        $result = $query->row();
        $count = $result->count;
        $filename = $result->img;
        if ($count > 0) {
            unlink(FCPATH . 'assets/captcha/' . $filename);
        }
        return $count;
    }
    public function login($email, $password)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);

        $query = $this->db->get();
        $result = $query->row();

        // Anda dapat mengakses hasilnya seperti ini:
        if ($result) {
            $password = $result->password;
            if (password_verify($password, $password)) {
                $sess = [
                    'email' => $result->email,
                    'phone' => $result->phone,
                    'role_id' => $result->role_id,
                    'nama' => $result->nama
                ];
                $this->session->set_userdata($sess);
                $data = ['status' => 'Success', 'message' => 'Congratulation.'];
                return $data;
            } else {
                $data = ['status' => 'failed', 'message' => 'password not match.'];
                return $data;
            }
        }
        $data = ['status' => 'failed', 'message' => 'Email not found.'];
        return $data;
    }
}
