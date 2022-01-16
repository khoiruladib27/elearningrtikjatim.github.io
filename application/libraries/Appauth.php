<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Appauth
{
    public function login($user, $pass)
    {
        $ci = get_instance();
        $ci->db->select('*');
        $ci->db->from('system_users');
        $ci->db->where('usr_username', $user);
        $ci->db->where('usr_deleted_at', null);
        $ci->db->limit(1);
        $query = $ci->db->get();
        $result = false;
        if ($query->num_rows() == 1) {
            $userpass = $query->result_array()[0]['usr_password'];
            if (password_verify($pass, $userpass)) {
                $usr_locked = $query->result_array()[0]['usr_locked'];
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
            $ses['system_group'] = $ci->db->get_where('system_group', ['grp_id' => $result['usr_group']])->row_array();
            $ses['system_users'] = $result;
            $ci->session->set_userdata($ses);
            $this->setAkey($result['usr_id']);
            echo json_encode(array('status' => 1, 'pesan' => 'Berhasil masuk !!'));
        } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal Masuk !!'));
        }
    }

    public function logout()
    {
        $ci = get_instance();
        $sess_array = array(
            'system_group',
            'system_users',
        );
        $ci->session->unset_userdata($sess_array);
        // Hapus file rfm ketika logout
        unlink(sys_get_temp_dir() . '/config_rfm_' . $_SESSION['fm_key_file']);
    }

    public function is_logged_in()
    {
        $ci = get_instance();
        if ($ci->session->userdata('system_users')) {
            if ($ci->uri->segment(3)) {
                $uri = $ci->uri->segment(1) . "/" . $ci->uri->segment(2);
            } else {
                $uri = $ci->uri->uri_string();
            }
            if ($uri != 'admin' && $uri != 'admin/profil') {
                $where    = [
                    "system_access.acs_group"    => $_SESSION['system_users']['usr_group'],
                    "system_menu.mnu_link"        => $uri
                ];
                $cek    = $ci->db->join("system_menu", "system_menu.mnu_id=system_access.acs_menu", "left")
                    ->get_where("system_access", $where)->num_rows();
                if ($cek < 1) {
                    redirect("admin/MyError/e403");
                }
            }
        } else {
            redirect('/admin/auth');
        }
    }

    public function generatePasswordHash($string)
    {
        $string = is_string($string) ? $string : strval($string);
        $pwHash = password_hash($string, PASSWORD_BCRYPT);
        if (password_needs_rehash($pwHash, PASSWORD_BCRYPT)) {
            $pwHash = password_hash($string, PASSWORD_BCRYPT);
        }
        return $pwHash;
    }

    private function setAkey($usr_id)
    {
        $salt = rand(100000, 999999);
        $salt = strrev($salt);
        $fm_key = MD5($usr_id . 'Dikamhost' . $salt);
        $_SESSION['fm_key'] = $fm_key;
        // Gunakan cara penambahan prefix ini karena Windows hanya menggunakan 3 karakter dari prefix
        $fname = tempnam(sys_get_temp_dir(), '');
        $sesi = basename($fname);
        $tmpfname = sys_get_temp_dir() . "/config_rfm_" . $sesi;
        rename($fname, $tmpfname);
        $_SESSION['fm_key_file'] = $sesi;
        $rfm = '<?php $config["fm_key_' . $sesi . '"] ="' . $fm_key . '";';
        $handle = fopen($tmpfname, "w");
        fwrite($handle, $rfm);
        fclose($handle);
    }

    public function is_logged_in_member()
    {
        $ci = get_instance();
        if (!$ci->session->userdata('system_members')) {
            redirect('/member/auth');
        }
    }
}
