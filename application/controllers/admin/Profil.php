<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->appauth->is_logged_in();
    }

    public function getByID()
    {
        if (isset($_POST['profil_usr_id'])) {
            $data = $this->db->get_where('system_users', ['usr_id' => $_POST['profil_usr_id']])->row_array();
            if ($data) {
                echo json_encode(['status' => 1, 'pesan' => 'Berhasil ambil data', 'data' => $data]);
            } else {
                echo json_encode(['status' => 0, 'pesan' => 'Gagal ambil data']);
            }
        } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal ambil data']);
        }
    }

    public function store()
    {
        if ($this->get_validation()) {
            $usr_username = $this->db->get_where('system_users', ['usr_username' => $_POST['profil_usr_username'], 'usr_deleted_at' => null])->row_array();
            if ($usr_username && $_POST['profil_usr_id'] == "") {
                $array = array(
                    'error'   => true,
                    'usr_username' => "Username sudah digunakan"
                );
                echo json_encode(array('status' => 3, 'pesan' => $array));
                die;
            }
            $data_image['file_name'] = '';
            $data = array(
                'usr_name' => $_POST['profil_usr_name'],
                'usr_username' => $_POST['profil_usr_username'],
                'usr_email' => $_POST['profil_usr_email'],
            );
            if (!empty($_FILES['profil_usr_foto']['name'])) {
                $config['upload_path']          = './storage/user/';
                $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG';
                $config['max_size']             = 2048;
                $config['remove_spaces']        = TRUE;
                $config['file_name']            = $_POST['profil_usr_username'] . "-" . date("Y_m_d His");
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('profil_usr_foto')) {
                    die;
                    echo json_encode(array('status' => 2, 'pesan' => "<b>Gagal upload gambar</b> <br>" . $this->upload->display_errors()));
                    die;
                } else {
                    $data_foto = $this->upload->data();
                    $data['usr_foto'] = $data_foto['file_name'];
                }
            }
            if (!empty($_POST['profil_usr_id'])) {
                $foto = $this->db->select('usr_foto')->where('usr_id', $_POST['profil_usr_id'])->get('system_users')->row()->usr_foto;
                if (!empty($data_foto['file_name'])) {
                    if (!empty($foto)) {
                        unlink($config['upload_path'] . $foto);
                    }
                }
                if (!empty($_POST['profil_usr_password'])) {
                    $data['usr_password'] = generatePasswordHash($_POST['profil_usr_password']);
                }
                // $data['usr_update_by'] = $_SESSION['user_id'];
                $data['usr_update_at'] = date('Y-m-d H:i:s');
                $this->db->where('usr_id', $_POST['profil_usr_id']);
                $query = $this->db->update('system_users', $data);
            } else {
                echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !! Tidak ada profil id'));
            }
            if ($query) {
                $result = $this->db->get_where('system_users', ['usr_id' => $_POST['profil_usr_id'], 'usr_deleted_at' => null])->row_array();
                if ($result) {
                    $ses['system_users'] = $result;
                    $this->session->set_userdata($ses);
                }
                echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
            } else {
                echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
            }
        } else {
            $array = array(
                'error'   => true,
                'profil_usr_name' => form_error('profil_usr_name'),
                'profil_usr_email' => form_error('profil_usr_email'),
                'profil_usr_username' => form_error('profil_usr_username'),
                'profil_usr_password' => form_error('profil_usr_password'),
                'profil_usr_cpassword' => form_error('profil_usr_cpassword'),
                'profil_usr_foto' => form_error('profil_usr_foto'),
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
                'field' => 'profil_usr_name',
                'label' => 'Nama Lengkap',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Lengkap harus diisi',
                ],
            ],
            [
                'field' => 'profil_usr_email',
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid',
                ],
            ],
        ];

        if ($_POST['profil_usr_id'] == "") {
            array_push(
                $config,
                [
                    'field' => 'profil_usr_username',
                    'label' => 'Password',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Username harus diisi'
                    ],
                ],
            );
            array_push(
                $config,
                [
                    'field' => 'profil_usr_password',
                    'label' => 'Password',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password harus diisi',
                    ],
                ],
            );
            array_push(
                $config,
                [
                    'field' => 'profil_usr_cpassword',
                    'label' => 'Confirm Password',
                    'rules' => 'required|matches[usr_cpassword]',
                    'errors' => [
                        'required' => 'Confirm Password harus diisi',
                        'matches' => 'Confirm Password tidak sama'
                    ],
                ],
            );
        } else {
            array_push(
                $config,
                [
                    'field' => 'profil_usr_username',
                    'label' => 'Username',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Username harus diisi',
                    ],
                ],
            );
        }

        $this->form_validation->set_rules($config);
        return $this->form_validation->run();
    }
}
