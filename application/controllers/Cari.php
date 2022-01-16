<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cari extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('M_kilat');
      $this->load->model('M_kejuruan');
      $this->load->helper('tgl_indo');
   }

   public function index()
   {
      $start = $this->uri->segment(3);
      $data['title']       = 'Cari';
      $data['page']        = 'depan/cari/index';
      $data['kilat']        = $this->M_kilat->getKilat($start)['app_kejuruan'];
      $data['kejuruan']        = $this->M_kejuruan->getKejuruan($start)['app_kejuruan'];
      $this->load->view('depan/template', $data);
   }
}
