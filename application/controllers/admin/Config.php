<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Config extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->appauth->is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Setting Aplikasi';
        $data['page'] = 'admin/config/index';
        $data['setting'] = $this->db
            ->where('conf_deleted_at', null)
            ->order_by('conf_order', 'asc')
            ->get('system_config')->result_array();
        $this->load->view('admin/template', $data);
    }

    public function generate()
    {
        $conf = [
            'danger' => 'Merah',
            'success' => "Hijau",
            'primary' => "Biru",
            'info' => "Biru Muda",
            'warning' => "Orange",
            'purple' => "Ungu",
        ];
        $data = [
            'conf_char' => 'app_col',
            'conf_option' => json_encode($conf),
        ];
        $this->db->insert('system_config', $data);
    }

    public function store()
    {
        $pesan = [];
        $files = $_FILES;
        $post = $_POST;
        foreach ($files as $file => $img) {
            if ($img['name']) {
                $config['upload_path']          = './storage/system/';
                $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG';
                $config['max_size']             = 2048;
                $config['remove_spaces']        = TRUE;
                $config['file_name']            = $file;
                $config['overwrite']            = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload($file)) {
                    echo json_encode(array('status' => 2, 'pesan' => "<b>Gagal upload gambar</b> <br>" . $this->upload->display_errors()));
                    die;
                } else {
                    $data_foto = $this->upload->data();
                    $query = $this->db
                        ->where('conf_char', $file)
                        ->update('system_config', ['conf_value' => $data_foto['file_name']]);
                    if ($query) {
                        $pesan[$file]['status'] = 1;
                        $pesan[$file]['pesan'] = "Berhasil disimpan";
                    } else {
                        $pesan[$file]['status'] = 0;
                        $pesan[$file] = "Gagal disimpan";
                    }
                }
            }
        }
        foreach ($post as $key => $value) {
            $query = $this->db
                ->where('conf_char', $key)
                ->update('system_config', ['conf_value' => $value]);
            if ($query) {
                $pesan[$key]['status'] = 1;
                $pesan[$key]['pesan'] = "Berhasil disimpan";
            } else {
                $pesan[$key]['status'] = 0;
                $pesan[$key] = "Gagal disimpan";
            }
        }
        echo json_encode(array('status' => 3, 'pesan' => $pesan));
    }
}
