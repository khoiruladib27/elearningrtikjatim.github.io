<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Artikel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->appauth->is_logged_in();
        $this->load->model('M_Datatables');
    }

    public function index()
    {
        $data['title'] = 'Artikel';
        $data['page'] = 'admin/artikel/index';
        $this->load->view('admin/template', $data);
    }

    public function view_data()
    {
        $this->load->library("datatables_ssp");
        $table     = "artikel a";
        $key    = "art_id";
        $cols = [
            ["db" => "art_id",    "dt" => "art_id"],
            ["db" => "art_gambar",    "dt" => "art_gambar"],
            ["db" => "art_isi",    "dt" => "art_isi"],
            ["db" => "art_locked",    "dt" => "art_locked"],
            ["db" => "art_headline",    "dt" => "art_headline"],
            ["db" => "art_tgl_upload",    "dt" => "art_tgl_upload"],
            ["db" => "art_judul",    "dt" => "art_judul"],
            ["db" => "ktg_locked",    "dt" => "ktg_locked"]
        ];
        $_Conn = [
            "user"     => $this->db->username,
            "pass"     => $this->db->password,
            "db"     => $this->db->database,
            "host"     => $this->db->hostname,
            "port"     => $this->db->port
        ];
        $join    = "left join kategori b on a.art_ktg_id=b.ktg_id";
        $custome = null;
        $where    = "a.art_deleted_at is null";
        echo json_encode(
            Datatables_ssp::complex($_POST, $_Conn, $table, $key, $cols, NULL, $where, $join, $custome)
        );
    }

    public function lock()
    {
        if (isset($_POST['art_locked']) && isset($_POST['art_id'])) {
            if ($_POST['art_locked'] == 0) {
                $art_locked = 1;
            } else {
                $art_locked = 0;
            }
            $this->db->where('art_id', $_POST['art_id']);
            $query = $this->db->update('artikel', ['art_locked' => $art_locked]);
            if ($query) {
                echo json_encode(['status' => 1]);
            } else {
                echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
            }
        } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
        }
    }

    public function headline()
    {
        if (isset($_POST['art_headline']) && isset($_POST['art_id'])) {
            if ($_POST['art_headline'] == 0) {
                $art_headline = 1;
                $this->db->where('art_id !=', $_POST['art_id']);
                $this->db->update('artikel', ['art_headline' => 0]);
            } else {
                $art_headline = 0;
            }
            $this->db->where('art_id', $_POST['art_id']);
            $query = $this->db->update('artikel', ['art_headline' => $art_headline]);
            if ($query) {
                echo json_encode(['status' => 1]);
            } else {
                echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
            }
        } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal simpan data']);
        }
    }

    public function store()
    {
        if ($this->get_validation()) {
            $slug = url_title($_POST['art_judul'], 'dash', true);
            $this->cekSlug($slug);
            $data = array(
                'art_judul' => $_POST['art_judul'],
                'art_slug' => $slug,
                'art_isi' => $_POST['art_isi'],
                'art_ktg_id' => $_POST['art_ktg_id'],
            );
            if (!empty($_FILES['art_gambar']['name'])) {
                $config['upload_path']          = './storage/artikel/';
                $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG';
                $config['max_size']             = 2048;
                $config['remove_spaces']        = TRUE;
                $config['file_name']            = $slug . "-" . date("Y_m_d His");
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('art_gambar')) {
                    die;
                    echo json_encode(array('status' => 2, 'pesan' => "<b>Gagal upload gambar</b> <br>" . $this->upload->display_errors()));
                    die;
                } else {
                    $data_foto = $this->upload->data();
                    $data['art_gambar'] = $data_foto['file_name'];
                }
            }
            if (!empty($_POST['art_id'])) {
                $foto = $this->db->select('art_gambar')->where('art_id', $_POST['art_id'])->get('artikel')->row()->art_gambar;
                if (!empty($data_foto['file_name'])) {
                    if (!empty($foto)) {
                        unlink($config['upload_path'] . $foto);
                    }
                }
                $this->db->where('art_id', $_POST['art_id']);
                $query = $this->db->update('artikel', $data);
            } else {
                $data['art_id'] = GENERATOR['artikel'] . "-" . random_string("alnum", 10);
                $data['art_usr_id'] = $_SESSION['system_users']['usr_id'];
                $query = $this->db->insert('artikel', $data);
            }
            if ($query) {
                echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
            } else {
                echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
            }
        } else {
            $array = array(
                'error'   => true,
                'art_judul' => form_error('art_judul'),
                'art_isi' => form_error('art_isi'),
                'art_ktg_id' => form_error('art_ktg_id'),
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
                'field' => 'art_judul',
                'label' => 'Judul',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Judul harus diisi',
                ],
            ],
            [
                'field' => 'art_isi',
                'label' => 'Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Isi harus diisi',
                ],
            ],
            [
                'field' => 'art_ktg_id',
                'label' => 'kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori harus diisi',
                ],
            ],
        ];
        $this->form_validation->set_rules($config);
        return $this->form_validation->run();
    }

    private function cekSlug($slug = '')
    {
        $this->db->where('art_slug', $slug);
        $this->db->where('art_deleted_at', null);
        if (isset($_POST['art_id'])) {
            $this->db->where('art_id !=', $_POST['art_id']);
        }
        $art_slug = $this->db->get('artikel')->row_array();
        if ($art_slug) {
            $array = array(
                'error'   => true,
                'art_judul' => "Judul sudah digunakan"
            );
            echo json_encode(array('status' => 3, 'pesan' => $array));
            die;
        }
    }

    public function getKategori()
    {
        $data = $this->db
            ->where('ktg_locked', 0)
            ->where('ktg_deleted_at', null)
            ->order_by('ktg_nama', 'asc')
            ->get('kategori')->result_array();
        if ($data) {
            echo json_encode(array('status' => 1, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
        } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal ambil data !!'));
        }
    }

    public function getByID()
    {
        if (isset($_POST['art_id'])) {
            $data = $this->db->get_where('artikel', ['art_id' => $_POST['art_id']])->row_array();
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
        if (isset($_POST['art_id'])) {
            $this->db->where('art_id', $_POST['art_id']);
            $query = $this->db->update('artikel', ['art_deleted_at' => date('Y-m-d H:i:s')]);
            if ($query) {
                echo json_encode(['status' => 1]);
            } else {
                echo json_encode(['status' => 0, 'pesan' => 'Gagal hapus data']);
            }
        } else {
            echo json_encode(['status' => 0, 'pesan' => 'Gagal hapus data']);
        }
    }

    public function generate()
    {
        for ($i = 11; $i <= 1000; $i++) {
            $judul = "Judul " . $i;
            $slug = url_title($judul, 'dash', true);
            $this->cekSlug($slug);
            $data = array(
                'art_judul' => $judul,
                'art_slug' => $slug,
                'art_isi' => "Isi",
                'art_ktg_id' => "didikam",
                'art_usr_id' => "didikam",
            );
            $data['art_gambar'] = "artikel-33333-2021_09_25_090953.jpg";
            $data['art_id'] = GENERATOR['artikel'] . "-" . random_string("alnum", 10);
            $query = $this->db->insert('artikel', $data);
            echo $judul . "<br>";
        }
    }
}
