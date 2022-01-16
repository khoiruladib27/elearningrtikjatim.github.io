<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelas extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in_member();
      $this->load->model('member/M_kelas', 'm_kelas');
      $_SESSION['member_act'] = 'kelas';
   }

   public function index()
   {
      $data['title'] = 'Kelas';
      $data['memberpage']  = 'member/kelas/index';
      $data['page']  = 'member/template/index';
      $data['kelas']    = $this->m_kelas->getKelas();
      $this->load->view('depan/template', $data);
   }

   public function bayar($kjr_slug)
   {
      $data['title'] = 'Bayar Kelas';
      $data['memberpage']  = 'member/kelas/bayar';
      $data['page']  = 'member/template/index';
      $data['kelas']    = $this->m_kelas->getKejuruanBayar($kjr_slug);
      $data['bayar']    = $this->db->get('app_transfer')->result_array();
      $this->load->view('depan/template', $data);
   }

   public function checkout()
   {
      $kls_kjr_id = $_POST['kjr_id'];
      $kls_trf_id = $_POST['trf_id'];
      $kls_mbr_id = $_SESSION['system_members']['mbr_id'];
      $update = $this->db
         ->where('kls_kjr_id', $kls_kjr_id)
         ->where('kls_mbr_id', $kls_mbr_id)
         ->update('app_kelas', ['kls_trf_id' => $kls_trf_id]);
      if ($update) {
         echo json_encode(array('status' => 1, 'pesan' => 'Berhasil Checkout !!'));
      } else {
         echo json_encode(array('status' => 0, 'pesan' => 'Gagal Checkout !!'));
      }
   }

   public function getBayar()
   {
      $kjr_id = $_POST['kjr_id'];
      $data = $this->m_kelas->getKelasBayar($kjr_id);
      if ($data) {
         $data['kjr_harga'] = number_format($data['kjr_harga'], 2, ',', '.');
         echo json_encode(array('status' => 1, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
      } else {
         echo json_encode(array('status' => 0, 'pesan' => 'Gagal ambil data !!'));
      }
   }

   public function destroy()
   {
      if (isset($_POST['kls_id'])) {
         $this->db->where('kls_id', $_POST['kls_id']);
         $query = $this->db->delete('app_kelas');
         if ($query) {
            echo json_encode(['status' => 1, 'pesan' => 'Berhasil dibatalkan']);
         } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal hapus data']);
         }
      } else {
         echo json_encode(['status' => 0, 'pesan' => 'Gagal hapus data']);
      }
   }
}
