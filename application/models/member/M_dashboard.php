<?php
class M_dashboard extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   public function getData()
   {
      $data['kelas'] = $this->getKelas();
      $data['progres'] = $this->getProses();
      $data['selesai'] = $this->getSelesai();
      $data['sertifikat'] = $this->getSertifikat();
      return $data;
   }

   private function getSertifikat()
   {
      $data = $this->db
         ->select('count(srt_id) as jml')
         ->where('srt_mbr_id', $_SESSION['system_members']['mbr_id'])
         ->get('app_sertifikat')->row_array()['jml'];
      return $data;
   }

   private function getSelesai()
   {
      $data = $this->db
         ->select('count(kls_id) as jml')
         ->where('kls_selesai', '1')
         ->where('kls_mbr_id', $_SESSION['system_members']['mbr_id'])
         ->get('app_kelas')->row_array()['jml'];
      return $data;
   }

   private function getProses()
   {
      $data = $this->db
         ->select('count(kls_id) as jml')
         ->where('kls_lunas !=', null)
         ->where('kls_locked', '0')
         ->where('kls_selesai', '0')
         ->where('kls_mbr_id', $_SESSION['system_members']['mbr_id'])
         ->get('app_kelas')->row_array()['jml'];
      return $data;
   }

   private function getKelas()
   {
      $data = $this->db
         ->select('count(kls_id) as jml')
         ->where('kls_mbr_id', $_SESSION['system_members']['mbr_id'])
         ->get('app_kelas')->row_array()['jml'];
      return $data;
   }
}
