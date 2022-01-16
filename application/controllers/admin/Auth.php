<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->session->userdata('system_users')) {
            redirect('/admin');
        }
        $data['title'] = 'Login';
        $this->load->view('auth/login', $data);
    }

    public function login()
    {
        if ($this->get_validation()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $user = $this->security->xss_clean($username);
            $pass = $this->security->xss_clean($password);
            $this->appauth->login($user, $pass);
        } else {
            $array = array(
                'error'   => true,
                'username' => form_error('username'),
                'password' => form_error('password'),
                'message' => form_error('message')
            );
            echo json_encode(array('status' => 3, 'pesan' => $array));
        }
    }

    public function logout()
    {
        $this->appauth->logout();
        redirect('/');
    }

    private function get_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'username',
            'Username',
            'required',
            array(
                'required' => 'Username harus diisi'
            )
        );
        $this->form_validation->set_rules(
            'password',
            'Password',
            'required',
            array(
                'required' => 'Password harus diisi'
            )
        );
        return $this->form_validation->run();
    }
}
