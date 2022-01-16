<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Testimoni extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in();
      $this->load->model('M_Datatables');
      $this->load->model("private/app_query", "mod_data");
   }

   public function index()
   {
      $data['title'] = 'Testimoni';
      $data['page'] = 'admin/testimoni/index';
      $this->load->view('admin/template', $data);
   }

   // public function view_data()
   // {
   //    $query  = "SELECT * FROM app_testimoni";
   //    $search = array('tst_nama');
   //    $where = null;
   //    // $where  = array('tst_locked' => 0);

   //    // jika memakai IS NULL pada where sql
   //    $isWhere = 'tst_deleted_at IS NULL';
   //    header('Content-Type: application/json');
   //    echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
   // }

   public function view_data()
   {
      $data = $this->db->select('max(tst_order) as tst_max_order, min(tst_order) as tst_min_order')->get('app_testimoni')->row_array();
      $set_query["search"]   = ["tst_nama"];
      $set_query["table"]      = "app_testimoni";
      $set_query["select"]   = "*";
      $set_query["order"]      = ["tst_order" => "asc"];
      if (isset($_POST["order"])) {
         $set_query["order"]      = [null, null, null, 'tst_nama'];
      }
      $query      = $this->mod_data->getData_table($set_query)->result_array();
      $nomor      = $_POST["start"];
      $output      = [];
      $count_data   = 0;
      if ($query) {
         foreach ($query as $key) {
            $out      = [];
            $out["tst_id"]          = $key["tst_id"];
            $out["tst_nama"]        = $key["tst_nama"];
            $out["tst_image"]       = $key["tst_image"];
            $out["tst_order"]       = $key["tst_order"];
            $out["tst_locked"]       = $key["tst_locked"];
            $out["tst_min_order"]   = $data['tst_min_order'];
            $out["tst_max_order"]   = $data['tst_max_order'];
            $output[]   = $out;
         }
         $count_data   = $this->mod_data->getData_count($set_query);
      }
      $return["draw"]            = $_POST["draw"];
      $return["recordsTotal"]      = count($output);
      $return["recordsFiltered"]   = $count_data;
      $return["data"]            = $output;
      echo json_encode($return, true);
   }
   public function getByID()
   {
      if (isset($_POST['tst_id'])) {
         $data = $this->db->get_where('app_testimoni', ['tst_id' => $_POST['tst_id']])->row_array();
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
      if (isset($_POST['tst_locked']) && isset($_POST['tst_id'])) {
         if ($_POST['tst_locked'] == 0) {
            $tst_locked = 1;
         } else {
            $tst_locked = 0;
         }
         $this->db->where('tst_id', $_POST['tst_id']);
         $query = $this->db->update('app_testimoni', ['tst_locked' => $tst_locked]);
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
      if (isset($_POST['tst_order']) && isset($_POST['tst_id']) && isset($_POST['tst_arrow'])) {
         if ($_POST['tst_arrow'] == 'up') {
            $tst_order = $_POST['tst_order'] - 1;
            if ($tst_order <= 0) {
               die;
            }
         } else {
            $max_order = $this->db->select('max(tst_order) as max_ord')->get('app_testimoni')->row_array()['max_ord'];
            if ($_POST['tst_order'] >= $max_order) {
               die;
            }
            $tst_order = $_POST['tst_order'] + 1;
         }
         $this->db->where('tst_order', $tst_order);
         $query = $this->db->update('app_testimoni', ['tst_order' => $_POST['tst_order']]);
         if ($query) {
            $this->db->where('tst_id', $_POST['tst_id']);
            $query2 = $this->db->update('app_testimoni', ['tst_order' => $tst_order]);
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
      if (isset($_POST['tst_id'])) {
         $this->db->where('tst_id', $_POST['tst_id']);
         $query = $this->db->update('app_testimoni', ['tst_deleted_at' => date('Y-m-d H:i:s')]);
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
            'tst_nama'        => $_POST['tst_nama'],
            'tst_isi'        => $_POST['tst_isi'],
            'tst_created_by'  => $_SESSION['system_users']['usr_id'],
         );
         if (!empty($_FILES['tst_image']['name'])) {
            $config['upload_path']          = './storage/testimoni/';
            $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG';
            $config['max_size']             = 2048;
            $config['remove_spaces']        = TRUE;
            $config['file_name']            = url_title($_POST['tst_nama'], 'dash', true) . "-" . date("Y_m_d His");
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('tst_image')) {
               die;
               echo json_encode(array('status' => 2, 'pesan' => "<b>Gagal upload gambar</b> <br>" . $this->upload->display_errors()));
               die;
            } else {
               $data_foto = $this->upload->data();
               $data['tst_image'] = $data_foto['file_name'];
            }
         } else {
            if (empty($_POST['tst_id'])) {
               $array = array(
                  'error'   => true,
                  'tst_nama' => form_error('tst_nama'),
                  'tst_image' => 'Gambar harus diisi',
                  'message' => form_error('message')
               );
               echo json_encode(array('status' => 3, 'pesan' => $array));
               die;
            }
         }
         if (!empty($_POST['tst_id'])) {
            $foto = $this->db->select('tst_image')->where('tst_id', $_POST['tst_id'])->get('app_testimoni')->row()->tst_image;
            if (!empty($data_foto['file_name'])) {
               if (!empty($foto)) {
                  unlink($config['upload_path'] . $foto);
               }
            }
            $this->db->where('tst_id', $_POST['tst_id']);
            $query = $this->db->update('app_testimoni', $data);
         } else {
            $data['tst_id']         = GENERATOR['app_testimoni'] . "-" . random_string("alnum", 10);
            $data['tst_created_at'] = date('Y-m-d H:i:s');
            $data['tst_locked']     = 0;
            $data['tst_order']      = $this->db->select('max(tst_order) as tst_order')->get('app_testimoni')->row_array()['tst_order'] + 1;
            $query = $this->db->insert('app_testimoni', $data);
         }
         if ($query) {
            echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
         } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
         }
      } else {
         $array = array(
            'error'   => true,
            'tst_nama' => form_error('tst_nama'),
            'tst_isi' => form_error('tst_isi'),
            'tst_image' => '',
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
            'field' => 'tst_nama',
            'label' => 'Nama slider',
            'rules' => 'required',
            'errors' => [
               'required' => 'Nama slider harus diisi',
            ],
         ],
      ];
      $config = [
         [
            'field' => 'tst_isi',
            'label' => 'Isi Testimoni',
            'rules' => 'required',
            'errors' => [
               'required' => 'Isi testimoni harus diisi',
            ],
         ],
      ];
      $this->form_validation->set_rules($config);
      return $this->form_validation->run();
   }
}
