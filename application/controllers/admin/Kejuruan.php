<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kejuruan extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in();
      $this->load->model("private/app_query", "mod_data");
   }

   public function index()
   {
      $data['title'] = 'Kejuruan';
      $data['page'] = 'admin/kejuruan/index';
      $this->load->view('admin/template', $data);
   }

   public function view_data()
   {
      $set_query["search"]    = ["kjr_nama", "kjr_harga"];
      $set_query["table"]     = "app_kejuruan";
      $set_query["select"]    = "*";
      $set_query["join"]      = null;
      $set_query["where"]     = ["kjr_deleted_at" => null, 'kjr_type' => 'kejuruan'];
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

   function getPemateri()
   {
      $data = $this->db
         ->where('usr_deleted_at', null)
         ->where('grp_name', 'Pemateri')
         ->order_by('usr_name', 'asc')
         ->join('system_group b', 'a.usr_group=b.grp_id')
         ->get('system_users a')->result_array();
      if ($data) {
         echo json_encode(array('status' => 1, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
      } else {
         echo json_encode(array('status' => 0, 'pesan' => 'Gagal ambil data !!'));
      }
   }

   public function getByID()
   {
      if (isset($_POST['kjr_id'])) {
         $data = $this->db->get_where('app_kejuruan', ['kjr_id' => $_POST['kjr_id']])->row_array();
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
      if (isset($_POST['kjr_locked']) && isset($_POST['kjr_id'])) {
         if ($_POST['kjr_locked'] == 0) {
            $kjr_locked = 1;
         } else {
            $kjr_locked = 0;
         }
         $this->db->where('kjr_id', $_POST['kjr_id']);
         $query = $this->db->update('app_kejuruan', ['kjr_locked' => $kjr_locked]);
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
      if (isset($_POST['kjr_id'])) {
         $this->db->where('kjr_id', $_POST['kjr_id']);
         $query = $this->db->update('app_kejuruan', ['kjr_deleted_at' => date('Y-m-d H:i:s')]);
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
         $slug = url_title($_POST['kjr_nama'], 'dash', true);
         $this->cekSlug($slug);
         $data = array(
            'kjr_nama'           => $_POST['kjr_nama'],
            'kjr_harga'          => $_POST['kjr_harga'],
            'kjr_slug'           => $slug,
            'kjr_deskripsi'      => $_POST['kjr_deskripsi'],
            'kjr_pemateri'       => $_POST['kjr_pemateri'],
         );
         if (!empty($_FILES['kjr_image']['name'])) {
            $config['upload_path']          = './storage/kejuruan/';
            $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG';
            $config['max_size']             = 2048;
            $config['remove_spaces']        = TRUE;
            $config['file_name']            = url_title($_POST['kjr_nama'], 'dash', true) . "-" . date("Y_m_d His");
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('kjr_image')) {
               die;
               echo json_encode(array('status' => 2, 'pesan' => "<b>Gagal upload gambar</b> <br>" . $this->upload->display_errors()));
               die;
            } else {
               $data_foto = $this->upload->data();
               $data['kjr_image'] = $data_foto['file_name'];
            }
         } else {
            if (empty($_POST['kjr_id'])) {
               $array = array(
                  'error'   => true,
                  'kjr_nama' => form_error('kjr_nama'),
                  'kjr_image' => 'Gambar harus diisi',
                  'message' => form_error('message')
               );
               echo json_encode(array('status' => 3, 'pesan' => $array));
               die;
            }
         }
         if (!empty($_POST['kjr_id'])) {
            $foto = $this->db->select('kjr_image')->where('kjr_id', $_POST['kjr_id'])->get('app_kejuruan')->row()->kjr_image;
            if (!empty($data_foto['file_name'])) {
               if (!empty($foto)) {
                  unlink($config['upload_path'] . $foto);
               }
            }
            $this->db->where('kjr_id', $_POST['kjr_id']);
            $query = $this->db->update('app_kejuruan', $data);
         } else {
            $data['kjr_id'] = GENERATOR['app_kejuruan'] . "-" . random_string("alnum", 10);
            $data['kjr_created_at'] = date('Y-m-d H:i:s');
            $data['kjr_created_by'] = $_SESSION['system_users']['usr_id'];
            $data['kjr_locked'] = 0;
            $data['kjr_type'] = 'kejuruan';
            $query = $this->db->insert('app_kejuruan', $data);
         }
         if ($query) {
            echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
         } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
         }
      } else {
         $array = array(
            'error'   => true,
            'kjr_nama' => form_error('kjr_nama'),
            'kjr_harga' => form_error('kjr_harga'),
            'kjr_deskripsi' => form_error('kjr_deskripsi'),
            'kjr_pemateri' => form_error('kjr_pemateri'),
            'kjr_image' => '',
            'message' => form_error('message')
         );
         echo json_encode(array('status' => 3, 'pesan' => $array));
      }
   }

   private function cekSlug($slug = '')
   {
      $this->db->where('kjr_slug', $slug);
      $this->db->where('kjr_deleted_at', null);
      if (isset($_POST['kjr_id'])) {
         $this->db->where('kjr_id !=', $_POST['kjr_id']);
      }
      $kjr_slug = $this->db->get('app_kejuruan')->row_array();
      if ($kjr_slug) {
         $array = array(
            'error'   => true,
            'kjr_nama' => "Nama kejuruan sudah digunakan"
         );
         echo json_encode(array('status' => 3, 'pesan' => $array));
         die;
      }
   }

   private function get_validation()
   {
      $this->load->library('form_validation');
      $config = [
         [
            'field' => 'kjr_nama',
            'label' => 'Nama Kejuruan',
            'rules' => 'required',
            'errors' => [
               'required' => 'Nama Kejuruan harus diisi',
            ],
         ],
         [
            'field' => 'kjr_harga',
            'label' => 'Harga',
            'rules' => 'required|numeric',
            'errors' => [
               'required' => 'Harga harus diisi',
               'numeric' => 'Harga harus berupa angka',
            ],
         ],
         [
            'field' => 'kjr_deskripsi',
            'label' => 'Deskripsi',
            'rules' => 'required',
            'errors' => [
               'required' => 'Deskripsi harus diisi',
            ],
         ],
         [
            'field' => 'kjr_pemateri',
            'label' => 'Pemateri',
            'rules' => 'required',
            'errors' => [
               'required' => 'Pemateri harus diisi',
            ],
         ],
      ];
      $this->form_validation->set_rules($config);
      return $this->form_validation->run();
   }
}
