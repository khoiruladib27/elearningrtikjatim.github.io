<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Slider extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in();
   }

   public function index()
   {
      $data['title'] = 'Slider';
      $data['page'] = 'admin/slider/index';
      $this->load->view('admin/template', $data);
   }

   public function view_data()
   {
      $this->load->library("datatables_ssp");
      $table     = "app_slider";
      $key    = "sld_id";
      $cols = [
         ["db" => "sld_id",         "dt" => "sld_id"],
         ["db" => "sld_nama",       "dt" => "sld_nama"],
         ["db" => "sld_image",      "dt" => "sld_image"],
         ["db" => "sld_locked",     "dt" => "sld_locked"],
         ["db" => "sld_order",      "dt" => "sld_order"],
         [
            "db" => "sld_id",      "dt" => "sld_min_order",
            'formatter' => function ($sld_id) {
               $min = $this->db->select('min(sld_order) as min')
                  ->get('app_slider')->row_array()['min'];
               return $min;
            }
         ],
         [
            "db" => "sld_id",      "dt" => "sld_max_order",
            'formatter' => function ($sld_id) {
               $max = $this->db->select('max(sld_order) as max')
                  ->get('app_slider')->row_array()['max'];
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
      $custome = ' ORDER BY sld_order asc';
      $where    = "sld_deleted_at IS NULL";
      echo json_encode(
         Datatables_ssp::complex($_POST, $_Conn, $table, $key, $cols, NULL, $where, $join, $custome)
      );
   }

   public function getByID()
   {
      if (isset($_POST['sld_id'])) {
         $data = $this->db->get_where('app_slider', ['sld_id' => $_POST['sld_id']])->row_array();
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
      if (isset($_POST['sld_locked']) && isset($_POST['sld_id'])) {
         if ($_POST['sld_locked'] == 0) {
            $sld_locked = 1;
         } else {
            $sld_locked = 0;
         }
         $this->db->where('sld_id', $_POST['sld_id']);
         $query = $this->db->update('app_slider', ['sld_locked' => $sld_locked]);
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
      if (isset($_POST['sld_order']) && isset($_POST['sld_id']) && isset($_POST['sld_arrow'])) {
         if ($_POST['sld_arrow'] == 'up') {
            $sld_order = $_POST['sld_order'] - 1;
            if ($sld_order <= 0) {
               die;
            }
         } else {
            $max_order = $this->db->select('max(sld_order) as max_ord')->get('app_slider')->row_array()['max_ord'];
            if ($_POST['sld_order'] >= $max_order) {
               die;
            }
            $sld_order = $_POST['sld_order'] + 1;
         }
         $this->db->where('sld_order', $sld_order);
         $query = $this->db->update('app_slider', ['sld_order' => $_POST['sld_order']]);
         if ($query) {
            $this->db->where('sld_id', $_POST['sld_id']);
            $query2 = $this->db->update('app_slider', ['sld_order' => $sld_order]);
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
      if (isset($_POST['sld_id'])) {
         $this->db->where('sld_id', $_POST['sld_id']);
         $query = $this->db->update('app_slider', ['sld_deleted_at' => date('Y-m-d H:i:s')]);
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
            'sld_nama'        => $_POST['sld_nama'],
            'sld_link'        => $_POST['sld_link'],
         );
         if (!empty($_FILES['sld_image']['name'])) {
            $config['upload_path']          = './storage/slider/';
            $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG';
            $config['max_size']             = 2048;
            $config['remove_spaces']        = TRUE;
            $config['file_name']            = url_title($_POST['sld_nama'], 'dash', true) . "-" . date("Y_m_d His");
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('sld_image')) {
               die;
               echo json_encode(array('status' => 2, 'pesan' => "<b>Gagal upload gambar</b> <br>" . $this->upload->display_errors()));
               die;
            } else {
               $data_foto = $this->upload->data();
               $data['sld_image'] = $data_foto['file_name'];
            }
         } else {
            if (empty($_POST['sld_id'])) {
               $array = array(
                  'error'   => true,
                  'sld_nama' => form_error('sld_nama'),
                  'sld_image' => 'Gambar harus diisi',
                  'message' => form_error('message')
               );
               echo json_encode(array('status' => 3, 'pesan' => $array));
               die;
            }
         }
         if (!empty($_POST['sld_id'])) {
            $foto = $this->db->select('sld_image')->where('sld_id', $_POST['sld_id'])->get('app_slider')->row()->sld_image;
            if (!empty($data_foto['file_name'])) {
               if (!empty($foto)) {
                  unlink($config['upload_path'] . $foto);
               }
            }
            $this->db->where('sld_id', $_POST['sld_id']);
            $query = $this->db->update('app_slider', $data);
         } else {
            $data['sld_id'] = GENERATOR['app_slider'] . "-" . random_string("alnum", 10);
            $data['sld_created_at'] = date('Y-m-d H:i:s');
            $data['sld_created_by'] = $_SESSION['system_users']['usr_id'];
            $data['sld_locked'] = 0;
            $data['sld_order'] = $this->db->select('max(sld_order) as sld_order')->get('app_slider')->row_array()['sld_order'] + 1;
            $query = $this->db->insert('app_slider', $data);
         }
         if ($query) {
            echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
         } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
         }
      } else {
         $array = array(
            'error'   => true,
            'sld_nama' => form_error('sld_nama'),
            'sld_image' => '',
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
            'field' => 'sld_nama',
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
