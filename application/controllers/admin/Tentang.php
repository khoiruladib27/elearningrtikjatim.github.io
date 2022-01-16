<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tentang extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in();
   }

   public function index()
   {
      $data['title'] = 'Tentang';
      $data['page'] = 'admin/tentang/index';
      $data['data']     = $this->db->get('app_tentang')->row_array();
      $this->load->view('admin/template', $data);
   }

   public function store()
   {
      if ($this->get_validation()) {
         $data = array(
            'tnt_isi'        => $_POST['tnt_isi'],
         );
         if (!empty($_FILES['tnt_image']['name'])) {
            $config['upload_path']          = './storage/tentang/';
            $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG';
            $config['max_size']             = 2048;
            $config['remove_spaces']        = TRUE;
            $config['file_name']            = "tentang-" . date("Y_m_d His");
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('tnt_image')) {
               die;
               echo json_encode(array('status' => 2, 'pesan' => "<b>Gagal upload gambar</b> <br>" . $this->upload->display_errors()));
               die;
            } else {
               $data_foto = $this->upload->data();
               $data['tnt_image'] = $data_foto['file_name'];
            }
         } else {
            if (empty($_POST['tnt_id'])) {
               $array = array(
                  'error'   => true,
                  'tnt_isi' => form_error('tnt_isi'),
                  'tnt_image' => 'Gambar harus diisi',
                  'message' => form_error('message')
               );
               echo json_encode(array('status' => 3, 'pesan' => $array));
               die;
            }
         }
         if (!empty($_POST['tnt_id'])) {
            $foto = $this->db->select('tnt_image')->where('tnt_id', $_POST['tnt_id'])->get('app_tentang')->row()->tnt_image;
            if (!empty($data_foto['file_name'])) {
               if (!empty($foto)) {
                  unlink($config['upload_path'] . $foto);
               }
            }
            $this->db->where('tnt_id', $_POST['tnt_id']);
            $query = $this->db->update('app_tentang', $data);
         }
         if ($query) {
            echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
         } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
         }
      } else {
         $array = array(
            'error'   => true,
            'tnt_isi' => form_error('tnt_isi'),
            'tnt_image' => '',
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
            'field' => 'tnt_isi',
            'label' => 'Isi',
            'rules' => 'required',
            'errors' => [
               'required' => 'Isi harus diisi',
            ],
         ],
      ];
      $this->form_validation->set_rules($config);
      return $this->form_validation->run();
   }
}
