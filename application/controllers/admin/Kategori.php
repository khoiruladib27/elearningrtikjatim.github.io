<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->appauth->is_logged_in();
        $this->load->model('M_Datatables');
    }

    public function index()
    {
        $data['title'] = 'Kategori';
        $data['page'] = 'admin/kategori/index';
        $this->load->view('admin/template', $data);
    }

    public function view_data()
    {
        $this->load->library("datatables_ssp");
        $table     = "kategori";
        $key    = "ktg_id";
        $cols = [
            ["db" => "ktg_id",    "dt" => "ktg_id"],
            ["db" => "ktg_nama",    "dt" => "ktg_nama"],
            // ["db" => "ktg_order",    "dt" => "ktg_order"],
            ["db" => "ktg_locked",    "dt" => "ktg_locked"],
        ];
        $_Conn = [
            "user"     => $this->db->username,
            "pass"     => $this->db->password,
            "db"     => $this->db->database,
            "host"     => $this->db->hostname,
            "port"     => $this->db->port
        ];
        $join    =  null;
        // $custome = ' ORDER BY ktg_order asc';
        $custome = null;
        $where    = "ktg_deleted_at IS NULL";
        echo json_encode(
            Datatables_ssp::complex($_POST, $_Conn, $table, $key, $cols, NULL, $where, $join, $custome)
        );
    }

    public function getByID()
    {
        if (isset($_POST['ktg_id'])) {
            $data = $this->db->get_where('kategori', ['ktg_id' => $_POST['ktg_id']])->row_array();
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
        if (isset($_POST['ktg_locked']) && isset($_POST['ktg_id'])) {
            if ($_POST['ktg_locked'] == 0) {
                $ktg_locked = 1;
            } else {
                $ktg_locked = 0;
            }
            $this->db->where('ktg_id', $_POST['ktg_id']);
            $query = $this->db->update('kategori', ['ktg_locked' => $ktg_locked]);
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
        if (isset($_POST['ktg_order']) && isset($_POST['ktg_id']) && isset($_POST['ktg_arrow'])) {
            if ($_POST['ktg_arrow'] == 'up') {
                $ktg_order = $_POST['ktg_order'] - 1;
                if ($ktg_order <= 0) {
                    die;
                }
            } else {
                $max_order = $this->db->select('max(ktg_order) as max_ord')->get('kategori')->row_array()['max_ord'];
                if ($_POST['ktg_order'] >= $max_order) {
                    die;
                }
                $ktg_order = $_POST['ktg_order'] + 1;
            }
            $this->db->where('ktg_order', $ktg_order);
            $query = $this->db->update('kategori', ['ktg_order' => $_POST['ktg_order']]);
            if ($query) {
                $this->db->where('ktg_id', $_POST['ktg_id']);
                $query2 = $this->db->update('kategori', ['ktg_order' => $ktg_order]);
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
        if (isset($_POST['ktg_id'])) {
            $this->db->where('ktg_id', $_POST['ktg_id']);
            $query = $this->db->update('kategori', ['ktg_deleted_at' => date('Y-m-d H:i:s')]);
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
            $slug = url_title($_POST['ktg_nama'], 'dash', true);
            $this->cekSlug($slug);
            $data = array(
                'ktg_nama' => $_POST['ktg_nama'],
                'ktg_slug' => $slug,
                'ktg_usr_id' => $_SESSION['system_users']['usr_id'],
            );
            if (!empty($_POST['ktg_id'])) {
                $this->db->where('ktg_id', $_POST['ktg_id']);
                $query = $this->db->update('kategori', $data);
            } else {
                $data['ktg_id'] = GENERATOR['kategori'] . "-" . random_string("alnum", 10);
                $data['ktg_locked'] = 0;
                // $data['ktg_order'] = $this->db->select('max(ktg_order) as ktg_order')->get('kategori')->row_array()['ktg_order'] + 1;
                $query = $this->db->insert('kategori', $data);
            }
            if ($query) {
                echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
            } else {
                echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
            }
        } else {
            $array = array(
                'error'   => true,
                'ktg_nama' => form_error('ktg_nama'),
                'message' => form_error('message')
            );
            echo json_encode(array('status' => 3, 'pesan' => $array));
        }
    }

    private function cekSlug($slug = '')
    {
        $this->db->where('ktg_slug', $slug);
        $this->db->where('ktg_deleted_at', null);
        if (isset($_POST['ktg_id'])) {
            $this->db->where('ktg_id !=', $_POST['ktg_id']);
        }
        $ktg_slug = $this->db->get('kategori')->row_array();
        if ($ktg_slug) {
            $array = array(
                'error'   => true,
                'ktg_nama' => "kategori sudah digunakan"
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
                'field' => 'ktg_nama',
                'label' => 'Nama Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama kategori harus diisi',
                ],
            ],
        ];
        $this->form_validation->set_rules($config);
        return $this->form_validation->run();
    }
}
