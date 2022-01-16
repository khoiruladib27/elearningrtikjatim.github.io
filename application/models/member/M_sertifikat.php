<?php
class M_sertifikat extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   public function getSertifikat()
   {
      $data = $this->db
         ->where('srt_mbr_id', $_SESSION['system_members']['mbr_id'])
         ->join('app_kejuruan b', 'a.srt_kjr_id=b.kjr_id', 'left')
         ->get('app_sertifikat a')->result_array();
      if ($data) {
         for ($i = 0; $i < count($data); $i++) {
            $data[$i]['jml_modul'] = $this->db
               ->select('count(mtr_id) as jml')
               ->where('mtr_kjr_id', $data[$i]['kjr_id'])
               ->where('mtr_index !=', null)
               ->get('app_materi')->row_array()['jml'];
         }
      }
      return $data;
   }
}
