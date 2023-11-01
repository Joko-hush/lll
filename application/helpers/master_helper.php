<?php

function getPass()
{
    $pass = 'C6h12o6@inv';
    return $pass;
}
function getAdmin()
{
    $admin = 'Super';
    return $admin;
}

function isLogin()
{
    $ci = get_instance();
    if ($ci->session->userdata('email')) {
        $role = $ci->session->userdata('role');
        $email = $ci->session->userdata('email');
        $phone = $ci->session->userdata('phone');
        $nama = $ci->session->userdata('nama');
        $ci->db->where('email', $email);
        $user = $ci->db->get('users')->row_array();
        $kantor = $user['id_kantor'];
        if ($kantor > 0) {
            redirect('home');
        }
    } else {
        redirect('auth');
    }
}
