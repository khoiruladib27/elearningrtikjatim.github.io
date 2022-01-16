<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in_member();
      $this->load->model('member/M_dashboard', 'm_dashboard');
      $_SESSION['member_act'] = 'dashboard';
   }

   public function index()
   {
      $data['title'] = 'Dashboard';
      $data['memberpage']  = 'member/dashboard/index';
      $data['jml']      = $this->m_dashboard->getData();
      $data['page']  = 'member/template/index';
      $this->load->view('depan/template', $data);
   }
}
