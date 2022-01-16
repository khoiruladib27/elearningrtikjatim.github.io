<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in();
      $this->load->model("private/app_query", "mod_data");
   }

   public function index()
   {
      $data['title'] = 'Invoice';
      $data['page'] = 'admin/invoice/index';
      $this->load->view('admin/template', $data);
   }

   public function view_data()
   {
      $set_query["search"]    = ['kls_id', 'mbr_name', 'kjr_nama', 'kjr_harga', 'trf_nama'];
      $set_query["table"]     = "app_kelas a";
      $set_query["select"]    = "*";
      $set_query["join"]      = [
         [
            "join"   => "app_kejuruan b",
            "on"   => "b.kjr_id = a.kls_kjr_id",
            "type"   => "left"
         ],
         [
            "join"   => "system_members c",
            "on"   => "c.mbr_id = a.kls_mbr_id",
            "type"   => "left"
         ],
         [
            "join"   => "app_transfer d",
            "on"   => "d.trf_id = a.kls_trf_id",
            "type"   => "left"
         ]
      ];
      $set_query["where"]     = [
         "kjr_deleted_at" => null,
         'kls_trf_id !=' => null
      ];
      $set_query["order"]     = [
         'kls_lunas' => 'asc'
      ];
      if (isset($_POST["order"])) {
         $set_query["order"]      = ['kls_lunas', 'kls_id', 'mbr_name', 'kjr_nama', 'kjr_harga', 'trf_nama'];
      }
      $query      = $this->mod_data->getData_table($set_query)->result_array();
      $output      = [];
      $count_data   = 0;
      if ($query) {
         $output = $query;
         $count_data   = $this->mod_data->getData_count($set_query);
      }
      $return["draw"]            = $_POST["draw"];
      $return["recordsTotal"]      = count($output);
      $return["recordsFiltered"]   = $count_data;
      $return["data"]            = $output;
      echo json_encode($return, true);
   }

   public function lunaskan()
   {
      if (isset($_POST['kls_id'])) {
         $this->db->where('kls_id', $_POST['kls_id']);
         $query = $this->db->update('app_kelas', ['kls_lunas' => date('Y-m-d H:i:s'), 'kls_locked' => '0']);
         if ($query) {
            echo json_encode(['status' => 1]);
         } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
         }
      } else {
         echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
      }
   }

   public function lock()
   {
      if (isset($_POST['kls_locked']) && isset($_POST['kls_id'])) {
         if ($_POST['kls_locked'] == 0) {
            $kls_locked = 1;
         } else {
            $kls_locked = 0;
         }
         $this->db->where('kls_id', $_POST['kls_id']);
         $query = $this->db->update('app_kelas', ['kls_locked' => $kls_locked]);
         if ($query) {
            echo json_encode(['status' => 1]);
         } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
         }
      } else {
         echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
      }
   }
}
