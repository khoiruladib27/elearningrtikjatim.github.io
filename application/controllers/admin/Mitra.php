<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mitra extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in();
   }

   public function index()
   {
      $data['title'] = 'Mitra';
      $data['page'] = 'admin/mitra/index';
      $this->load->view('admin/template', $data);
   }

   public function view_data()
   {
      $this->load->library("datatables_ssp");
      $table     = "app_mitra";
      $key    = "mtr_id";
      $cols = [
         ["db" => "mtr_id",         "dt" => "mtr_id"],
         ["db" => "mtr_nama",       "dt" => "mtr_nama"],
         ["db" => "mtr_image",      "dt" => "mtr_image"],
         ["db" => "mtr_locked",     "dt" => "mtr_locked"],
         ["db" => "mtr_order",      "dt" => "mtr_order"],
         [
            "db" => "mtr_id",      "dt" => "mtr_min_order",
            'formatter' => function ($mtr_id) {
               $min = $this->db->select('min(mtr_order) as min')
                  ->get('app_mitra')->row_array()['min'];
               return $min;
            }
         ],
         [
            "db" => "mtr_id",      "dt" => "mtr_max_order",
            'formatter' => function ($mtr_id) {
               $max = $this->db->select('max(mtr_order) as max')
                  ->get('app_mitra')->row_array()['max'];
               return $max;
            }
         ],
      ];
      $_Conn = [
         "user"     => $this->db->username,
         "pass"     => $this->db->password,
         "db"     => $this->db->database,
         "host"     => $this->db->hostname,
         "port"     => $this->db->port
      ];
      $join    =  null;
      $custome = ' ORDER BY mtr_order asc';
      $where    = "mtr_deleted_at IS NULL";
      echo json_encode(
         Datatables_ssp::complex($_POST, $_Conn, $table, $key, $cols, NULL, $where, $join, $custome)
      );
   }

   public function getByID()
   {
      if (isset($_POST['mtr_id'])) {
         $data = $this->db->get_where('app_mitra', ['mtr_id' => $_POST['mtr_id']])->row_array();
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
         $query = $this->db->update('app_mitra', ['mtr_locked' => $mtr_locked]);
         if ($query) {
            echo json_encode(['status' => 1]);
         } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
         }
      } else {
         echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
      }
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
            $max_order = $this->db->select('max(mtr_order) as max_ord')->get('app_mitra')->row_array()['max_ord'];
            if ($_POST['mtr_order'] >= $max_order) {
               die;
            }
            $mtr_order = $_POST['mtr_order'] + 1;
         }
         $this->db->where('mtr_order', $mtr_order);
         $query = $this->db->update('app_mitra', ['mtr_order' => $_POST['mtr_order']]);
         if ($query) {
            $this->db->where('mtr_id', $_POST['mtr_id']);
            $query2 = $this->db->update('app_mitra', ['mtr_order' => $mtr_order]);
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
   public function destroy()
   {
      if (isset($_POST['mtr_id'])) {
         $this->db->where('mtr_id', $_POST['mtr_id']);
         $query = $this->db->update('app_mitra', ['mtr_deleted_at' => date('Y-m-d H:i:s')]);
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
         $data = array(
            'mtr_nama'        => $_POST['mtr_nama'],
            'mtr_link'        => $_POST['mtr_link'],
         );
         if (!empty($_FILES['mtr_image']['name'])) {
            $config['upload_path']          = './storage/mitra/';
            $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG';
            $config['max_size']             = 2048;
            $config['remove_spaces']        = TRUE;
            $config['file_name']            = url_title($_POST['mtr_nama'], 'dash', true) . "-" . date("Y_m_d His");
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('mtr_image')) {
               die;
               echo json_encode(array('status' => 2, 'pesan' => "<b>Gagal upload gambar</b> <br>" . $this->upload->display_errors()));
               die;
            } else {
               $data_foto = $this->upload->data();
               $data['mtr_image'] = $data_foto['file_name'];
            }
         } else {
            if (empty($_POST['mtr_id'])) {
               $array = array(
                  'error'   => true,
                  'mtr_nama' => form_error('mtr_nama'),
                  'mtr_image' => 'Gambar harus diisi',
                  'message' => form_error('message')
               );
               echo json_encode(array('status' => 3, 'pesan' => $array));
               die;
            }
         }
         if (!empty($_POST['mtr_id'])) {
            $foto = $this->db->select('mtr_image')->where('mtr_id', $_POST['mtr_id'])->get('app_mitra')->row()->mtr_image;
            if (!empty($data_foto['file_name'])) {
               if (!empty($foto)) {
                  unlink($config['upload_path'] . $foto);
               }
            }
            $this->db->where('mtr_id', $_POST['mtr_id']);
            $query = $this->db->update('app_mitra', $data);
         } else {
            $data['mtr_id']         = GENERATOR['app_mitra'] . "-" . random_string("alnum", 10);
            $data['mtr_created_at'] = date('Y-m-d H:i:s');
            $data['mtr_created_by'] = $_SESSION['system_users']['usr_id'];
            $data['mtr_locked']     = 0;
            $data['mtr_order']      = $this->db->select('max(mtr_order) as mtr_order')->get('app_mitra')->row_array()['mtr_order'] + 1;
            $query = $this->db->insert('app_mitra', $data);
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
            'mtr_image' => '',
            'message' => form_error('message')
         );
         echo json_encode(array('status' => 3, 'pesan' => $array));
      }
   }

   private function get_validation()
   {
      $this->load->library('form_validation');
      $config = [
         [
            'field' => 'mtr_nama',
            'label' => 'Nama slider',
            'rules' => 'required',
            'errors' => [
               'required' => 'Nama slider harus diisi',
            ],
         ],
      ];
      $this->form_validation->set_rules($config);
      return $this->form_validation->run();
   }
}
