<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in_member();
      $_SESSION['member_act'] = 'setting';
      $this->load->library('form_validation');
   }

   public function index()
   {
      $data['title'] = 'Setting';
      $data['memberpage']  = 'member/setting/index';
      $data['page']  = 'member/template/index';
      $this->load->view('depan/template', $data);
   }

   public function store()
   {
      $array = $this->init_store();
      if ($this->get_validation()) {
         if (isset($_POST['mbr_id'])) {
            $mbr_username = $this->db->get_where('system_members', ['mbr_username' => $_POST['mbr_username'], 'mbr_deleted_at' => null, 'mbr_id !=' => $_POST['mbr_id']])->row_array();
            if ($mbr_username && $_POST['mbr_id'] != '') {
               $array['error'] = true;
               $array['mbr_username'] = "Username sudah digunakan";
               echo json_encode(array('status' => 3, 'pesan' => $array));
               die;
            }
            $data = array(
               'mbr_name' => $_POST['mbr_name'],
               'mbr_tempat_lahir' => $_POST['mbr_tempat_lahir'],
               'mbr_tanggal_lahir' => $this->to_date($_POST['mbr_tanggal_lahir']),
               'mbr_kota_asal' => $_POST['mbr_kota_asal'],
               'mbr_username' => $_POST['mbr_username'],
               'mbr_email' => $_POST['mbr_email'],
               'mbr_ip' => $this->input->ip_address(),
            );
            if (!empty($_FILES['mbr_foto']['name'])) {
               $config['upload_path']          = './storage/member/';
               $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG';
               $config['max_size']             = 2048;
               $config['remove_spaces']        = TRUE;
               $config['file_name']            = $_POST['mbr_username'] . "-" . date("Y_m_d His");
               $this->upload->initialize($config);
               if (!$this->upload->do_upload('mbr_foto')) {
                  die;
                  echo json_encode(array('status' => 2, 'pesan' => "<b>Gagal upload gambar</b> <br>" . $this->upload->display_errors()));
                  die;
               } else {
                  $data_foto = $this->upload->data();
                  $data['mbr_foto'] = $data_foto['file_name'];
                  $foto = $this->db->select('mbr_foto')->where('mbr_id', $_POST['mbr_id'])->get('system_members')->row()->mbr_foto;
                  if (!empty($data_foto['file_name'])) {
                     if (!empty($foto)) {
                        unlink($config['upload_path'] . $foto);
                     }
                  }
               }
            }
            $this->db->where('mbr_id', $_POST['mbr_id']);
            if ($_POST['mbr_password'] != '') {
               $data['mbr_password'] = generatePasswordHash($_POST['mbr_password']);
            }
            $query = $this->db->update('system_members', $data);
            if ($query) {
               $ses['system_members'] = $this->db->get_where('system_members', ['mbr_id' => $_POST['mbr_id']])->row_array();
               $this->session->set_userdata($ses);
               echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
            } else {
               echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
            }
         } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!<br>ID tidak terdata'));
         }
      } else {
         $array['error'] = false;
         $array['gcaptcha'] = '';
         $array['mbr_name'] = form_error('mbr_name');
         $array['mbr_email'] = form_error('mbr_email');
         $array['mbr_username'] = form_error('mbr_username');
         $array['mbr_password'] = form_error('mbr_password');
         $array['mbr_cpassword'] = form_error('mbr_cpassword');
         echo json_encode(array('status' => 3, 'pesan' => $array));
      }
   }

   private function init_store()
   {
      $array['error'] = false;
      $array['gcaptcha'] = '';
      $array['mbr_name'] = '';
      $array['mbr_email'] = '';
      $array['mbr_username'] = '';
      $array['mbr_password'] = '';
      $array['mbr_cpassword'] = '';
      return $array;
   }

   private function get_validation()
   {
      $config = [
         [
            'field' => 'mbr_name',
            'label' => 'Nama Lengkap',
            'rules' => 'required',
            'errors' => [
               'required' => 'Nama Lengkap harus diisi',
            ],
         ],
         [
            'field' => 'mbr_email',
            'label' => 'Email',
            'rules' => 'required|valid_email',
            'errors' => [
               'required' => 'Email harus diisi',
               'valid_email' => 'Email tidak valid',
            ],
         ],
         [
            'field' => 'mbr_username',
            'label' => 'Password',
            'rules' => 'required',
            'errors' => [
               'required' => 'Username harus diisi'
            ],
         ],
      ];
      if ($_POST['mbr_password'] != '') {
         array_push(
            $config,
            [
               'field' => 'mbr_cpassword',
               'label' => 'Confirm Password',
               'rules' => 'required|matches[mbr_password]',
               'errors' => [
                  'required' => 'Confirm Password harus diisi',
                  'matches' => 'Confirm Password tidak sama'
               ],
            ],
         );
      }
      $this->form_validation->set_rules($config);
      return $this->form_validation->run();
   }

   private function to_date($tanggal)
   {
      $tgl = explode('-', $tanggal);
      $dd = $tgl[0];
      $mm = $tgl[1];
      $yyyy = $tgl[2];
      return $yyyy . "-" . $mm . "-" . $dd;
   }
}
