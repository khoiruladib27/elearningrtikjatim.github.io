<?php
class M_dashboard extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   public function getData()
   {
      $data['kejuruan'] = $this->getKejuruan();
      $data['kilat'] = $this->getKilat();
      $data['instruktur'] = $this->getInstruktur();
      $data['members'] = $this->getMember();
      return $data;
   }

   private function getKejuruan()
   {
      $data = $this->db
         ->select('count(kjr_id) as jml')
         ->where('kjr_type', 'kejuruan')
         ->where('kjr_deleted_at', null)
         ->get('app_kejuruan')->row_array()['jml'];
      return $data;
   }

   private function getKilat()
   {
      $data = $this->db
         ->select('count(kjr_id) as jml')
         ->where('kjr_type', 'kilat')
         ->where('kjr_deleted_at', null)
         ->get('app_kejuruan')->row_array()['jml'];
      return $data;
   }

   private function getInstruktur()
   {
      $data = $this->db
         ->select('count(usr_id) as jml')
         ->where('grp_name', 'Pemateri')
         ->where('usr_deleted_at', null)
         ->join('system_group b', 'b.grp_id=a.usr_group', 'left')
         ->get('system_users a')->row_array()['jml'];
      return $data;
   }

   private function getMember()
   {
      $data = $this->db
         ->select('count(mbr_id) as jml')
         ->where('mbr_deleted_at', null)
         ->get('system_members')->row_array()['jml'];
      return $data;
   }
}
