<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->appauth->is_logged_in();
        $this->load->model('M_Datatables');
    }

    public function index()
    {
        $data['title'] = 'Pengguna';
        $data['page'] = 'admin/user/index';
        $this->load->view('admin/template', $data);
    }

    public function view_data()
    {
        $tables = "system_users";
        $search = array('usr_name', 'usr_email', 'usr_username');
        $isWhere = 'usr_deleted_at IS NULL';
        header('Content-Type: application/json');
        $data = $this->M_Datatables->get_tables($tables, $search, $isWhere);
        header('Content-Type: application/json');
        echo $data;
    }

    public function getByID()
    {
        if (isset($_POST['usr_id'])) {
            $data = $this->db->get_where('system_users', ['usr_id' => $_POST['usr_id']])->row_array();
            if ($data) {
                echo json_encode(['status' => 1, 'pesan' => 'Berhasil ambil data', 'data' => $data]);
            } else {
                echo json_encode(['status' => 0, 'pesan' => 'Gagal ambil data']);
            }
        } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal ambil data']);
        }
    }

    function getGroup()
    {
        $data = $this->db
            ->where('grp_deleted_at', null)
            ->order_by('grp_name', 'asc')
            ->get('system_group')->result_array();
        if ($data) {
            echo json_encode(array('status' => 1, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
        } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal ambil data !!'));
        }
    }

    public function lock()
    {
        if (isset($_POST['usr_locked']) && isset($_POST['usr_id'])) {
            if ($_POST['usr_locked'] == 0) {
                $usr_locked = 1;
            } else {
                $usr_locked = 0;
            }
            $this->db->where('usr_id', $_POST['usr_id']);
            $query = $this->db->update('system_users', ['usr_locked' => $usr_locked]);
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
        if (isset($_POST['usr_id'])) {
            $this->db->where('usr_id', $_POST['usr_id']);
            $query = $this->db->update('system_users', ['usr_deleted_at' => date('Y-m-d H:i:s')]);
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
            $usr_username = $this->db->get_where('system_users', ['usr_username' => $_POST['usr_username'], 'usr_deleted_at' => null])->row_array();
            if ($usr_username && $_POST['usr_id'] == "") {
                $array = array(
                    'error'   => true,
                    'usr_username' => "Username sudah digunakan"
                );
                echo json_encode(array('status' => 3, 'pesan' => $array));
                die;
            }
            $data_image['file_name'] = '';
            $data = array(
                'usr_name' => $_POST['usr_name'],
                'usr_group' => $_POST['usr_group'],
                'usr_username' => $_POST['usr_username'],
                'usr_email' => $_POST['usr_email'],
                'usr_deskripsi' => $_POST['usr_deskripsi'],
                'usr_twitter' => $_POST['usr_twitter'],
                'usr_facebook' => $_POST['usr_facebook'],
                'usr_instagram' => $_POST['usr_instagram'],
            );
            if (!empty($_FILES['usr_foto']['name'])) {
                $config['upload_path']          = './storage/user/';
                $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG';
                $config['max_size']             = 2048;
                $config['remove_spaces']        = TRUE;
                $config['file_name']            = $_POST['usr_username'] . "-" . date("Y_m_d His");
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('usr_foto')) {
                    die;
                    echo json_encode(array('status' => 2, 'pesan' => "<b>Gagal upload gambar</b> <br>" . $this->upload->display_errors()));
                    die;
                } else {
                    $data_foto = $this->upload->data();
                    $data['usr_foto'] = $data_foto['file_name'];
                }
            }
            if (!empty($_POST['usr_id'])) {
                $foto = $this->db->select('usr_foto')->where('usr_id', $_POST['usr_id'])->get('system_users')->row()->usr_foto;
                if (!empty($data_foto['file_name'])) {
                    if (!empty($foto)) {
                        unlink($config['upload_path'] . $foto);
                    }
                }
                if (!empty($_POST['usr_password'])) {
                    $data['usr_password'] = generatePasswordHash($_POST['usr_password']);
                }
                // $data['usr_update_by'] = $_SESSION['user_id'];
                $data['usr_update_at'] = date('Y-m-d H:i:s');
                $this->db->where('usr_id', $_POST['usr_id']);
                $query = $this->db->update('system_users', $data);
            } else {
                $data['usr_id'] = GENERATOR['system_users'] . "-" . random_string("alnum", 10);
                $data['usr_password'] = generatePasswordHash($_POST['usr_password']);
                $query = $this->db->insert('system_users', $data);
            }
            if ($query) {
                echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
            } else {
                echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
            }
        } else {
            $array = array(
                'error'   => true,
                'usr_name' => form_error('usr_name'),
                'usr_group' => form_error('usr_group'),
                'usr_email' => form_error('usr_email'),
                'usr_username' => form_error('usr_username'),
                'usr_password' => form_error('usr_password'),
                'usr_cpassword' => form_error('usr_cpassword'),
                'usr_foto' => form_error('usr_foto'),
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
                'field' => 'usr_name',
                'label' => 'Nama Lengkap',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Lengkap harus diisi',
                ],
            ],
            [
                'field' => 'usr_group',
                'label' => 'Group User',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Group User harus diisi',
                ],
            ],
            [
                'field' => 'usr_email',
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid',
                ],
            ],
        ];

        if ($_POST['usr_id'] == "") {
            array_push(
                $config,
                [
                    'field' => 'usr_username',
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
                    'field' => 'usr_password',
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
                    'field' => 'usr_cpassword',
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
                    'field' => 'usr_username',
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
