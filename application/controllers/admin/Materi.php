<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Materi extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in();
      $this->load->model("private/app_query", "mod_data");
   }

   public function index()
   {
      $data['title'] = 'Materi';
      $data['page'] = 'admin/materi/index';
      $this->load->view('admin/template', $data);
   }

   public function view_data()
   {
      $set_query["search"]    = ["kjr_nama", "kjr_harga"];
      $set_query["table"]     = "app_kejuruan";
      $set_query["select"]    = "*";
      $set_query["join"]      = null;
      if ($_SESSION['system_group']['grp_name'] == 'Administrator') {
         $set_query["where"]     = ["kjr_deleted_at" => null];
      } else {
         $set_query["where"]     = ["kjr_pemateri" => $_SESSION['system_users']['usr_id'], "kjr_deleted_at" => null];
      }
      $set_query["order"]     = [];
      if (isset($_POST["order"])) {
         $set_query["order"]      = [null, null, null, 'kjr_nama', 'kjr_harga'];
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

   public function kategori($kjr_slug = null)
   {
      $this->db->where('kjr_deleted_at', null);
      if ($_SESSION['system_group']['grp_name'] != 'Administrator') {
         $this->db->where('kjr_pemateri', $_SESSION['system_users']['usr_id']);
      }
      $data['kejuruan'] = $this->db->where('kjr_slug', $kjr_slug)->get('app_kejuruan')->row_array();
      if ($data['kejuruan']) {
         $data['title'] = 'Kategori Materi';
         $data['page'] = 'admin/materi/kategori';
         $this->load->view('admin/template', $data);
      } else {
         redirect('admin/myerror/e404');
      }
   }

   public function view_data_kategori()
   {
      $set_query["search"]    = ["mtr_nama"];
      $set_query["table"]     = "app_materi";
      $set_query["select"]    = "*";
      $set_query["join"]      = null;
      $set_query["where"]     = ["mtr_kjr_id" => $_POST['kjr_id'], "mtr_index" => null, "mtr_deleted_at" => null];
      $set_query["order"]     = ['mtr_order' => 'asc'];
      if (isset($_POST["order"])) {
         $set_query["order"]      = [null, null, null];
      }
      $query      = $this->mod_data->getData_table($set_query)->result_array();
      $nomor      = $_POST["start"];
      $output      = [];
      $count_data   = 0;
      if ($query) {
         $mtr_order = $this->db->select('max(mtr_order) as max_mtr_order, min(mtr_order) as min_mtr_order')
            ->where('mtr_kjr_id', $_POST['kjr_id'])
            ->where('mtr_index', null)
            ->where('mtr_deleted_at', null)
            ->get('app_materi')->row_array();
         for ($i = 0; $i < count($query); $i++) {
            $query[$i]['max_mtr_order'] = $mtr_order['max_mtr_order'];
            $query[$i]['min_mtr_order'] = $mtr_order['min_mtr_order'];
         }
         $output = $query;
         $count_data   = $this->mod_data->getData_count($set_query);
      }
      $return["draw"]            = $_POST["draw"];
      $return["recordsTotal"]      = count($output);
      $return["recordsFiltered"]   = $count_data;
      $return["data"]            = $output;
      echo json_encode($return, true);
   }

   public function order()
   {
      if (isset($_POST['mtr_order']) && isset($_POST['mtr_id']) && isset($_POST['mtr_arrow'])) {
         if ($_POST['mtr_arrow'] == 'up') {
            $mtr_order = $_POST['mtr_order'] - 1;
            if ($mtr_order <= 0) {
               die;
            }
         } else {
            $this->db->select('max(mtr_order) as max_ord');
            if (isset($_POST['mtr_kjr_id'])) {
               $this->db->where('mtr_kjr_id', $_POST['mtr_kjr_id']);
            }
            if (isset($_POST['mtr_index'])) {
               $this->db->where('mtr_index', $_POST['mtr_index']);
            }
            $max_order = $this->db->get('app_materi')->row_array()['max_ord'];
            if ($_POST['mtr_order'] >= $max_order) {
               die;
            }
            $mtr_order = $_POST['mtr_order'] + 1;
         }
         $this->db->where('mtr_order', $mtr_order);
         if (isset($_POST['mtr_kjr_id'])) {
            $this->db->where('mtr_kjr_id', $_POST['mtr_kjr_id']);
         }
         if (isset($_POST['mtr_index'])) {
            $this->db->where('mtr_index', $_POST['mtr_index']);
         }
         $query = $this->db->update('app_materi', ['mtr_order' => $_POST['mtr_order']]);
         if ($query) {
            $this->db->where('mtr_id', $_POST['mtr_id']);
            $query2 = $this->db->update('app_materi', ['mtr_order' => $mtr_order]);
            if ($query2) {
               echo json_encode(['status' => 1]);
            } else {
               echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan, gagal update order 2']);
            }
         } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan, gagal update order']);
         }
      } else {
         echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan, data kosong']);
      }
   }
   public function getByID()
   {
      if (isset($_POST['mtr_id'])) {
         $data = $this->db->get_where('app_materi', ['mtr_id' => $_POST['mtr_id']])->row_array();
         if ($data) {
            echo json_encode(['status' => 1, 'pesan' => 'Berhasil ambil data', 'data' => $data]);
         } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal ambil data']);
         }
      } else {
         echo json_encode(['status' => 0, 'pesan' => 'Gagal ambil data']);
      }
   }

   public function lock()
   {
      if (isset($_POST['mtr_locked']) && isset($_POST['mtr_id'])) {
         if ($_POST['mtr_locked'] == 0) {
            $mtr_locked = 1;
         } else {
            $mtr_locked = 0;
         }
         $this->db->where('mtr_id', $_POST['mtr_id']);
         $query = $this->db->update('app_materi', ['mtr_locked' => $mtr_locked]);
         if ($query) {
            echo json_encode(['status' => 1]);
         } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
         }
      } else {
         echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
      }
   }

   public function destroy()
   {
      if (isset($_POST['mtr_id'])) {
         $this->db->where('mtr_id', $_POST['mtr_id']);
         $query = $this->db->update('app_materi', ['mtr_deleted_at' => date('Y-m-d H:i:s')]);
         if ($query) {
            echo json_encode(['status' => 1]);
         } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal hapus data']);
         }
      } else {
         echo json_encode(['status' => 0, 'pesan' => 'Gagal hapus data']);
      }
   }

   public function store()
   {
      if ($this->get_validation()) {
         $slug = url_title($_POST['mtr_nama'], 'dash', true);
         $this->cekSlug($slug);
         $data = array(
            'mtr_nama'           => $_POST['mtr_nama'],
            'mtr_slug'           => $slug,
            'mtr_kjr_id'           => $_POST['mtr_kjr_id'],
         );
         if (isset($_POST['mtr_index'])) {
            $data['mtr_index'] = $_POST['mtr_index'];
            $data['mtr_isi'] = $_POST['mtr_isi'];
         }
         if (!empty($_POST['mtr_id'])) {
            $this->db->where('mtr_id', $_POST['mtr_id']);
            $query = $this->db->update('app_materi', $data);
         } else {
            $data['mtr_id'] = GENERATOR['app_materi'] . "-" . random_string("alnum", 10);
            $data['mtr_created_at'] = date('Y-m-d H:i:s');
            $data['mtr_created_by'] = $_SESSION['system_users']['usr_id'];
            $data['mtr_locked']     = 0;
            $data['mtr_order']      = $this->getOrder();
            $query = $this->db->insert('app_materi', $data);
         }
         if ($query) {
            echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
         } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
         }
      } else {
         $array = array(
            'error'   => true,
            'mtr_nama' => form_error('mtr_nama'),
            'mtr_isi' => form_error('mtr_isi'),
            'message' => form_error('message')
         );
         echo json_encode(array('status' => 3, 'pesan' => $array));
      }
   }

   private function getOrder()
   {
      $this->db->select('max(mtr_order) as mtr_order');
      if (isset($_POST['mtr_index'])) {
         $this->db->where('mtr_index', $_POST['mtr_index']);
      } else {
         $this->db->where('mtr_kjr_id', $_POST['mtr_kjr_id']);
      }
      $data =  $this->db->get('app_materi')->row_array()['mtr_order'] + 1;
      return $data;
   }
   private function get_validation()
   {
      $this->load->library('form_validation');
      if (isset($_POST['mtr_index'])) {
         $config = [
            [
               'field' => 'mtr_nama',
               'label' => 'Nama materi',
               'rules' => 'required',
               'errors' => [
                  'required' => 'Nama materi harus diisi',
               ],
            ],
            [
               'field' => 'mtr_isi',
               'label' => 'Isi materi',
               'rules' => 'required',
               'errors' => [
                  'required' => 'Isi materi harus diisi',
               ],
            ],
         ];
      } else {
         $config = [
            [
               'field' => 'mtr_nama',
               'label' => 'Nama kategori',
               'rules' => 'required',
               'errors' => [
                  'required' => 'Nama kategori harus diisi',
               ],
            ],
         ];
      }
      $this->form_validation->set_rules($config);
      return $this->form_validation->run();
   }


   private function cekSlug($slug = '')
   {
      $this->db->where('mtr_slug', $slug);
      $this->db->where('mtr_deleted_at', null);
      if (isset($_POST['mtr_index'])) {
         $this->db->where('mtr_index', $_POST['mtr_index']);
      } else {
         $this->db->where('mtr_kjr_id', $_POST['mtr_kjr_id']);
      }
      if (isset($_POST['mtr_id'])) {
         $this->db->where('mtr_id !=', $_POST['mtr_id']);
      }
      $mtr_slug = $this->db->get('app_materi')->row_array();
      if ($mtr_slug) {
         $array = array(
            'error'   => true,
            'mtr_nama' => "Nama kategori sudah digunakan"
         );
         echo json_encode(array('status' => 3, 'pesan' => $array));
         die;
      }
   }

   // detail materi
   public function detail($mtr_slug = null)
   {
      $data['materi'] = $this->db
         ->where('mtr_deleted_at', null)
         ->where('mtr_slug', $mtr_slug)
         ->get('app_materi')->row_array();
      if ($data['materi']) {
         $data['kejuruan'] = $this->db->where('kjr_id', $data['materi']['mtr_kjr_id'])->get('app_kejuruan')->row_array();
         $data['title'] = 'Detail Materi';
         $data['page'] = 'admin/materi/detail';
         $this->load->view('admin/template', $data);
      } else {
         redirect('admin/myerror/e404');
      }
   }

   public function view_data_detail()
   {
      $set_query["search"]    = ["mtr_nama"];
      $set_query["table"]     = "app_materi";
      $set_query["select"]    = "*";
      $set_query["join"]      = null;
      $set_query["where"]     = ["mtr_index" => $_POST['mtr_id'], "mtr_deleted_at" => null];
      $set_query["order"]     = ['mtr_order' => 'asc'];
      if (isset($_POST["order"])) {
         $set_query["order"]      = [null, null, null];
      }
      $query      = $this->mod_data->getData_table($set_query)->result_array();
      $output      = [];
      $count_data   = 0;
      if ($query) {
         $mtr_order = $this->db->select('max(mtr_order) as max_mtr_order, min(mtr_order) as min_mtr_order')
            ->where('mtr_index', $_POST['mtr_id'])
            ->where('mtr_deleted_at', null)
            ->get('app_materi')->row_array();
         for ($i = 0; $i < count($query); $i++) {
            $query[$i]['max_mtr_order'] = $mtr_order['max_mtr_order'];
            $query[$i]['min_mtr_order'] = $mtr_order['min_mtr_order'];
         }
         $output = $query;
         $count_data   = $this->mod_data->getData_count($set_query);
      }
      $return["draw"]            = $_POST["draw"];
      $return["recordsTotal"]      = count($output);
      $return["recordsFiltered"]   = $count_data;
      $return["data"]            = $output;
      echo json_encode($return, true);
   }
}
