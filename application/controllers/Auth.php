<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Beranda extends CI_Controller
{

   public function __construct()
   {
      parent::__construct();
      $this->load->model('M_kejuruan');
   }

   public function index()
   {
      $data['title'] = 'Auth | Simuda Cource';
      $data['page'] = 'depan/auth/index';
      $data['kejuruan'] = $this->M_kejuruan->getKejuruan()['app_kejuruan'];
      $this->load->view('depan/template', $data);
   }
}
