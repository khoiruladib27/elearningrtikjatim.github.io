<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cert extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
   }

   public function index()
   {
      $data['title']       = 'Cek Sertifikat';
      $data['page']        = 'depan/sertifikat/index';
      unset($_SESSION['msg']);
      if (isset($_POST['srt_id']) && isset($_POST['g-recaptcha-response'])) {
         $status = $this->recaptcha();
         if ($status['success']) {
            $data['sertifikat'] = $this->db->where('srt_id', $_POST['srt_id'])->get('app_sertifikat')->row_array();
         } else {
            $this->session->set_flashdata('msg', 'Kode captcha salah !!');
         }
      }
      $this->load->view('depan/template', $data);
   }

   private function recaptcha()
   {
      // recaptcha
      $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
      $ipUser = $this->input->ip_address();
      $secret = $this->config->item('google_secret');
      $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $ipUser;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      $status = json_decode($output, true);
      return $status;
   }
}
