<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->appauth->is_logged_in();
        $this->load->model('m_dashboard');
    }
    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['page'] = 'admin/dashboard/index';
        $data['jumlah'] = $this->m_dashboard->getData();
        $this->load->view('admin/template', $data);
    }
}
