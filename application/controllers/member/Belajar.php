<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Belajar extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in_member();
      $this->load->model('member/M_belajar', 'm_belajar');
   }

   public function index($kjr_slug = null, $mtr_slug = null)
   {
      $_SESSION['belajar_act'] = $mtr_slug;
      $data['title'] = 'Belajar';
      $data['kejuruan']   = $this->m_belajar->getKejuruan($kjr_slug);
      if ($data['kejuruan']) {
         if ($mtr_slug) {
            $data['materi'] = $this->m_belajar->getDetailMateri($mtr_slug);
            if ($data['materi']) {
               $data['page']   = 'belajar/materi';
            } else if ($mtr_slug == 'selesai') {
               $cek_where = [
                  'kls_kjr_id' => $data['kejuruan']['kjr_id'],
                  'kls_mbr_id' => $_SESSION['system_members']['mbr_id']
               ];
               $cek_selesai = $this->db->get_where('app_kelas', $cek_where)->row_array()['kls_selesai'];
               if ($cek_selesai == 1) {
                  $srt_data = [
                     'srt_mbr_id' => $_SESSION['system_members']['mbr_id'],
                     'srt_kjr_id' => $data['kejuruan']['kjr_id'],
                  ];
                  $data['sertifikat']  = $this->db->get_where('app_sertifikat', $srt_data)->row_array();
                  $data['page']        = 'belajar/selesai';
               } else {
                  $data['page']        = 'belajar/error/403';
               }
            } else {
               $data['page']        = 'belajar/error/404';
            }
         } else {
            $data['page']   = 'belajar/index';
         }
         $this->load->view('belajar/template', $data);
      } else {
         $data['title']       = '404 Not found !!';
         $data['page']        = 'belajar/error/404';
         $this->load->view('depan/template', $data);
      }
   }

   public function selesai()
   {
      $data = array(
         'mdl_mtr_id'             => $_POST['mtr_id'],
         'mdl_mbr_id'             => $_SESSION['system_members']['mbr_id'],
      );
      $ada = $this->db->get_where('app_modul', $data)->row_array();
      if (!$ada) {
         $data['mdl_id'] = GENERATOR['app_modul'] . "-" . random_string("alnum", 10);
         $this->db->insert('app_modul', $data);
      }
      $selanjutnya = $this->m_belajar->getMateriSelanjutnya($_POST['mtr_id']);
      if ($selanjutnya) {
         $link = $selanjutnya['mtr_slug'];
      } else {
         $link = 'selesai';
      }
      echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!', 'link' => $link));
   }
}
