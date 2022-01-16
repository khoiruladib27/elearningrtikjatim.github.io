<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in();
      $this->load->model('M_Datatables');
   }

   public function index()
   {
      $data['title'] = 'Menu';
      $data['page'] = 'admin/menu/index';
      $this->load->view('admin/template', $data);
   }

   public function view_data()
   {
      $query  = "SELECT * FROM system_menu";
      $search = array('mnu_name', 'mnu_link', 'mnu_icon');
      $where  = null;
      $isWhere = 'mnu_deleted_at IS NULL';
      header('Content-Type: application/json');
      echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
   }

   public function getByID()
   {
      if (isset($_POST['mnu_id'])) {
         $data = $this->db->get_where('system_mnu', ['mnu_id' => $_POST['mnu_id']])->row_array();
         if ($data) {
            echo json_encode(['status' => 1, 'pesan' => 'Berhasil ambil data', 'data' => $data]);
         } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal ambil data']);
         }
      } else {
         echo json_encode(['status' => 0, 'pesan' => 'Gagal ambil data']);
      }
   }

   public function destroy()
   {
      if (isset($_POST['mnu_id'])) {
         $this->db->where('mnu_id', $_POST['mnu_id']);
         $query = $this->db->update('system_menu', ['mnu_deleted_at' => date('Y-m-d H:i:s')]);
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
         if (!isset($_POST['check']) || count($_POST['check']) <= 0) {
            echo json_encode(array('status' => 0, 'pesan' => 'Silahkan Pilih Akses'));
         } else {
            $data = [
               'grp_name' => $_POST['grp_name'],
               // 'grp_create_by' => $_SESSION['user_id']
            ];
            if (!empty($_POST['grp_id'])) {
               $this->db->where('grp_id', $_POST['grp_id']);
               $query = $this->db->update('system_group', $data);
               $data['grp_id'] = $_POST['grp_id'];
            } else {
               $data['grp_id'] = GENERATOR['system_group'] . "-" . random_string("alnum", 10);
               $query = $this->db->insert('system_group', $data);
            }
            if ($query) {
               $data2 = array();
               $grp_menu = $_POST['check'];
               for ($i = 0; $i < count($grp_menu); $i++) {
                  array_push(
                     $data2,
                     [
                        'acs_group' => $data['grp_id'],
                        'acs_menu' => $grp_menu[$i],
                     ],
                  );
               }
               $this->db->where('acs_group', $data['grp_id'])->delete('system_access');
               $insert = $this->db->insert_batch('system_access', $data2);
               if ($insert) {
                  echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan'));
               } else {
                  echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan ke system_access'));
               }
            } else {
               echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan ke system_group'));
            }
         }
      } else {
         $array = array(
            'error'   => true,
            'grp_name' => form_error('grp_name'),
         );
         echo json_encode(array('status' => 3, 'pesan' => $array));
      }
   }

   private function get_validation()
   {
      $this->load->library('form_validation');
      $this->form_validation->set_rules(
         'grp_name',
         'grp_name',
         'required',
         array(
            'required' => 'Nama Group harus diisi'
         )
      );
      return $this->form_validation->run();
   }
}
