<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->session->userdata('system_members')) {
            redirect('/member');
        }
        $data['title'] = 'Login';
        $data['page']  = 'member/auth/login';
        $this->load->view('depan/template', $data);
    }

    public function daftar()
    {
        $data['title'] = 'Daftar';
        $data['page']  = 'member/auth/daftar';
        $this->load->view('depan/template', $data);
    }

    public function do_daftar()
    {
        $array = $this->init_do_daftar();
        if ($this->get_validation()) {
            $mbr_username = $this->db->get_where('system_members', ['mbr_username' => $_POST['mbr_username'], 'mbr_deleted_at' => null])->row_array();
            if ($mbr_username) {
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
                'mbr_no_induk' => $this->getNoInduk(),
                'mbr_ip' => $this->input->ip_address(),
            );
            $data['mbr_id'] = GENERATOR['system_members'] . "-" . random_string("alnum", 10);
            $data['mbr_password'] = generatePasswordHash($_POST['mbr_password']);
            $status = $this->recaptcha();
            if ($status['success']) {
                $query = $this->db->insert('system_members', $data);
                if ($query) {
                    echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!'));
                } else {
                    echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
                }
            } else {
                $array['error'] = true;
                $array['gcaptcha'] = 'Google Recaptcha Tidak Valid!!';
                echo json_encode(array('status' => 3, 'pesan' => $array));
            }
        } else {
            $array['error'] = false;
            $array['gcaptcha'] = '';
            $array['mbr_name'] = form_error('mbr_name');
            $array['mbr_tempat_lahir'] = form_error('mbr_tempat_lahir');
            $array['mbr_tanggal_lahir'] = form_error('mbr_tanggal_lahir');
            $array['mbr_kota_asal'] = form_error('mbr_kota_asal');
            $array['mbr_email'] = form_error('mbr_email');
            $array['mbr_username'] = form_error('mbr_username');
            $array['mbr_password'] = form_error('mbr_password');
            $array['mbr_cpassword'] = form_error('mbr_cpassword');
            echo json_encode(array('status' => 3, 'pesan' => $array));
        }
    }
    private function getNoInduk()
    {
        $no = $this->db
            ->select('max(mbr_no_induk) as no')
            ->get('system_members')
            ->row_array()['no'];
        $no++;
        $no = str_pad($no, 4, '0', STR_PAD_LEFT);
        return $no;
    }
    private function init_do_daftar()
    {
        $array['error'] = false;
        $array['gcaptcha'] = '';
        $array['mbr_name'] = '';
        $array['mbr_tempat_lahir'] = '';
        $array['mbr_tanggal_lahir'] = '';
        $array['mbr_email'] = '';
        $array['mbr_username'] = '';
        $array['mbr_kota_asal'] = '';
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
                'field' => 'mbr_tempat_lahir',
                'label' => 'Tempat Lahir',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tempat Lahir harus diisi',
                ],
            ],
            [
                'field' => 'mbr_tanggal_lahir',
                'label' => 'Tanggal Lahir',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Lahir harus diisi',
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
                'field' => 'mbr_kota_asal',
                'label' => 'Username',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kota asal harus diisi'
                ],
            ],
            [
                'field' => 'mbr_username',
                'label' => 'Username',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Username harus diisi'
                ],
            ],
            [
                'field' => 'mbr_password',
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password harus diisi',
                ],
            ],
            [
                'field' => 'mbr_cpassword',
                'label' => 'Confirm Password',
                'rules' => 'required|matches[mbr_password]',
                'errors' => [
                    'required' => 'Confirm Password harus diisi',
                    'matches' => 'Confirm Password tidak sama'
                ],
            ],
        ];

        $this->form_validation->set_rules($config);
        return $this->form_validation->run();
    }

    public function do_login()
    {
        $array = $this->init_do_login();
        if ($this->get_validation_login()) {
            $username = $this->input->post('mbr_username');
            $password = $this->input->post('mbr_password');
            $user = $this->security->xss_clean($username);
            $pass = $this->security->xss_clean($password);
            $status = $this->recaptcha();
            if ($status['success']) {
                $this->db->select('*');
                $this->db->from('system_members');
                $this->db->where('mbr_username', $user);
                $this->db->where('mbr_deleted_at', null);
                $this->db->limit(1);
                $query = $this->db->get();
                $result = false;
                if ($query->num_rows() == 1) {
                    $userpass = $query->result_array()[0]['mbr_password'];
                    if (password_verify($pass, $userpass)) {
                        $usr_locked = $query->result_array()[0]['mbr_locked'];
                        if (!$usr_locked) {
                            $result = $query->row_array();
                        } else {
                            echo json_encode(array('status' => 0, 'pesan' => 'Username tidak aktif !!'));
                            die;
                        }
                    } else {
                        echo json_encode(array('status' => 0, 'pesan' => 'Password tidak sesuai !!'));
                        die;
                    }
                } else {
                    echo json_encode(array('status' => 0, 'pesan' => 'Username tidak terdaftar !!'));
                    die;
                }
                if ($result) {
                    $update = [
                        'mbr_last_login' => date('Y-m-d H:i:s')
                    ];
                    $this->db->where('mbr_id', $result['mbr_id'])->update('system_members', $update);
                    $ses['system_members'] = $result;
                    $this->session->set_userdata($ses);
                    echo json_encode(array('status' => 1, 'pesan' => 'Berhasil masuk !!'));
                } else {
                    echo json_encode(array('status' => 0, 'pesan' => 'Gagal Masuk !!'));
                }
            } else {
                $array['error'] = true;
                $array['gcaptcha'] = 'Google Recaptcha Tidak Valid!!';
                echo json_encode(array('status' => 3, 'pesan' => $array));
            }
        } else {
            $array['error'] = false;
            $array['gcaptcha'] = '';
            $array['mbr_username'] = form_error('mbr_username');
            $array['mbr_password'] = form_error('mbr_password');
            echo json_encode(array('status' => 3, 'pesan' => $array));
        }
    }
    private function init_do_login()
    {
        $array['error'] = false;
        $array['gcaptcha'] = '';
        $array['mbr_username'] = '';
        $array['mbr_password'] = '';
        return $array;
    }
    private function get_validation_login()
    {
        $config = [
            [
                'field' => 'mbr_username',
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Username harus diisi'
                ],
            ],
            [
                'field' => 'mbr_password',
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password harus diisi',
                ],
            ],
        ];

        $this->form_validation->set_rules($config);
        return $this->form_validation->run();
    }

    public function logout()
    {
        session_destroy();
        redirect(base_url());
    }

    private function recaptcha()
    {
        // recaptcha
        $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
        $ipUser = $this->input->ip_address();
        $secret = $this->config->item('google_secret');
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $ipUser;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $status = json_decode($output, true);
        return $status;
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
