<?php
class M_belajar extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   public function getKejuruan($slug)
   {
      $data = $this->db
         ->where('kjr_slug', $slug)
         ->where('kls_locked', '0')
         ->where('kls_lunas !=', null)
         ->where('kls_mbr_id', $_SESSION['system_members']['mbr_id'])
         ->join('app_kelas b', 'a.kjr_id=b.kls_kjr_id', 'left')
         ->get('app_kejuruan a')->row_array();
      if ($data) {
         $data['materi'] = $this->getMateri($data['kjr_id']);
      }
      return $data;
   }

   private function getMateri($kjr_id)
   {
      $data = $this->db
         ->select('mtr_id, mtr_nama')
         ->order_by('mtr_order', 'asc')
         ->where('mtr_kjr_id', $kjr_id)
         ->where('mtr_index', null)
         ->get('app_materi')->result_array();
      if ($data) {
         for ($i = 0; $i < count($data); $i++) {
            $data[$i]['submateri'] = $this->getSubMateri($data[$i]['mtr_id']);
         }
      }
      return $data;
   }

   private function getSubMateri($mtr_index)
   {
      $data = $this->db
         ->select('mtr_nama, mtr_slug, mtr_id')
         ->order_by('mtr_order', 'asc')
         ->where('mtr_index', $mtr_index)
         ->get('app_materi a')->result_array();
      if ($data) {
         for ($i = 0; $i < count($data); $i++) {
            $data[$i]['mdl_id'] = $this->db
               ->where('mdl_mtr_id', $data[$i]['mtr_id'])
               ->where('mdl_mbr_id', $_SESSION['system_members']['mbr_id'])
               ->get('app_modul')->row_array();
         }
      }
      return $data;
   }

   public function getDetailMateri($mtr_slug)
   {
      $data = $this->db
         ->where('mtr_slug', $mtr_slug)
         ->get('app_materi')->row_array();
      return $data;
   }

   public function getMateriSelanjutnya($mtr_id = null)
   {
      $sub_materi = $this->db
         ->where('mtr_id', $mtr_id)
         ->get('app_materi')->row_array();
      if ($sub_materi) {
         $sub_materi_selanjutnya = $this->db
            ->order_by('mtr_order', 'asc')
            ->where('mtr_index', $sub_materi['mtr_index'])
            ->where('mtr_order >', $sub_materi['mtr_order'])
            ->get('app_materi')->row_array();
         if (!$sub_materi_selanjutnya) {
            $head_materi = $this->db
               ->where('mtr_id', $sub_materi['mtr_index'])
               ->get('app_materi')->row_array();
            if ($head_materi) {
               $head_materi_selanjutnya = $this->db
                  ->order_by('mtr_order', 'asc')
                  ->where('mtr_kjr_id', $head_materi['mtr_kjr_id'])
                  ->where('mtr_index', null)
                  ->where('mtr_order >', $head_materi['mtr_order'])
                  ->get('app_materi')->row_array();
               if ($head_materi_selanjutnya) {
                  $sub_materi_selanjutnya = $this->db
                     ->order_by('mtr_order', 'asc')
                     ->where('mtr_index', $head_materi_selanjutnya['mtr_id'])
                     ->get('app_materi')->row_array();
               } else {
                  $this->db
                     ->where('kls_kjr_id',  $head_materi['mtr_kjr_id'])
                     ->where('kls_mbr_id',  $_SESSION['system_members']['mbr_id'])
                     ->update('app_kelas', ['kls_selesai' => '1']);
               }
            }
         }
      }
      return $sub_materi_selanjutnya;
   }
}
