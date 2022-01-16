<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kilat extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('M_kilat');
      $this->load->helper('tgl_indo');
   }

   public function index()
   {
      $start = $this->uri->segment(3);
      $data['title']       = 'Kilat';
      $data['page']        = 'depan/kilat/index';
      $data['data']        = $this->M_kilat->getKilat($start)['app_kejuruan'];
      $data['pagination']  = $this->M_kilat->getKilat()['pagination'];
      $this->load->view('depan/template', $data);
   }

   public function lihat($slug = null)
   {
      $data['kilat']    = $this->M_kilat->getKilatLihat($slug);
      if ($data['kilat']) {
         $data['title']       = 'Kilat | ' . $data['kilat']['kjr_nama'];
         $data['page']        = 'depan/kilat/lihat';
      } else {
         $data['title']       = '404 Not found !!';
         $data['page']        = 'depan/error/404';
      }
      $this->load->view('depan/template', $data);
   }
}
