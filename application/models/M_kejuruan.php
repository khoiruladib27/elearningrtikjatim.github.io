<?php
class M_kejuruan extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   function getKejuruan($start = null)
   {
      if ($start) {
         $start = ($start * 8) - 8;
         $this->db->limit(8, $start);
      } else {
         $this->db->limit(8);
      }
      $data = $this->kejuruan();
      $this->load->library('pagination');
      $config = $this->getConfig($data);
      $this->pagination->initialize($config);
      $data['pagination'] = $this->pagination->create_links();
      return $data;
   }

   private function kejuruan()
   {
      if (isset($_POST['usr_id'])) {
         $this->db->where('b.usr_id', $_POST['usr_id']);
      }
      if (isset($_GET['key'])) {
         $this->db->like('kjr_nama', $_GET['key']);
      }
      $data['app_kejuruan'] = $this->db
         ->where('kjr_deleted_at', null)
         ->where('kjr_type', 'kejuruan')
         ->where('kjr_locked', 0)
         ->order_by('kjr_created_at', 'desc')
         ->join('system_users b', 'a.kjr_pemateri=b.usr_id')
         ->get('app_kejuruan a')->result_array();
      return $data;
   }

   private function getConfig()
   {
      $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul>';
      $config['num_tag_open'] = '<li class="page-item">';
      $config['num_tag_close'] = '</li>';
      $config['last_tag_open'] = '<li class="page-item">';
      $config['last_tag_close'] = '</li>';
      $config['prev_tag_open'] = '<li class="page-item">';
      $config['prev_tag_close'] = '</li>';
      $config['next_tag_open'] = '<li class="page-item">';
      $config['next_tag_close'] = '</li>';
      $config['cur_tag_open'] = '<li class="page-item"><a class="page-link active"  href="#">';
      $config['cur_tag_close'] = '</a></li>';
      $config['base_url'] = base_url('kejuruan/index/');
      $config['total_rows'] = count($this->kejuruan()['app_kejuruan']);
      $config['per_page'] = 8;
      $config['use_page_numbers'] = TRUE;
      return $config;
   }

   function getKejuruanLihat($slug = null)
   {
      $data = $this->db
         ->where('kjr_slug', $slug)
         ->where('kjr_deleted_at', null)
         ->where('kjr_locked', 0)
         ->order_by('kjr_created_at', 'desc')
         ->join('system_users b', 'a.kjr_pemateri=b.usr_id')
         ->get('app_kejuruan a')->row_array();
      if ($data) {
         $this->db
            ->where('kjr_slug', $slug)
            ->update('app_kejuruan', ['kjr_hit' => $data['kjr_hit'] + 1]);
         $data['materi'] = $this->getMateri($data['kjr_id']);
      }
      return $data;
   }

   private function getMateri($kjr_id = null)
   {
      $data = $this->db
         ->where('mtr_kjr_id', $kjr_id)
         ->where('mtr_index', null)
         ->where('mtr_locked', 0)
         ->where('mtr_deleted_at', null)
         ->order_by('mtr_order', 'asc')
         ->get('app_materi')->result_array();
      if ($data) {
         for ($i = 0; $i < count($data); $i++) {
            $data[$i]['submateri'] = $this->db
               ->where('mtr_index', $data[$i]['mtr_id'])
               ->where('mtr_locked', 0)
               ->where('mtr_deleted_at', null)
               ->order_by('mtr_order', 'asc')
               ->get('app_materi')->result_array();
         }
      }
      return $data;
   }
}
