<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
// reference the Dompdf namespace
use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Dompdf\Exception;

class Sertifikat extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->appauth->is_logged_in_member();
      $this->load->model('member/M_sertifikat', 'm_sertifikat');
      $_SESSION['member_act'] = 'sertifikat';
   }

   public function index()
   {
      $data['title'] = 'Sertifikat';
      $data['sertifikat']  = $this->m_sertifikat->getSertifikat();
      $data['memberpage']  = 'member/sertifikat/index';
      $data['page']  = 'member/template/index';
      $this->load->view('depan/template', $data);
   }

   public function store()
   {
      $data = array(
         'srt_kjr_id'             => $_POST['kjr_id'],
         'srt_mbr_id'             => $_SESSION['system_members']['mbr_id'],
      );
      $ada = $this->db->get_where('app_sertifikat', $data)->row_array();
      if (!$ada) {
         $data['srt_id'] = GENERATOR['app_sertifikat'] . "-" . random_string("alnum", 10);
         $data['srt_no_urut'] = $this->getCertUrut();
         $data['srt_img'] = $this->do_cetak($data);
         $sert = $this->db->insert('app_sertifikat', $data);
         if ($sert) {
            echo json_encode(array('status' => 1, 'pesan' => 'Berhasil disimpan !!', 'link' => $data['srt_img']));
         }
      } else {
         echo json_encode(array('status' => 1, 'pesan' => 'Sertifikat sudah ada !!', 'link' => $ada['srt_img']));
      }
   }

   private function getCertUrut()
   {
      $no = $this->db
         ->select('max(srt_no_urut) as no')
         ->where('EXTRACT(year FROM srt_created_at) =', date('Y'))
         ->get('app_sertifikat')
         ->row_array()['no'];
      $no++;
      $no = str_pad($no, 3, '0', STR_PAD_LEFT);
      return $no;
   }

   public function do_cetakpdf(){
      $this->load->library('Pdf'); // MEMANGGIL LIBRARY YANG KITA BUAT TADI
      error_reporting(0); // AGAR ERROR MASALAH VERSI PHP TIDAK MUNCUL
        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
      //   $image = base_url().'assets/sertifikat/kosong.jpg';
      //   $pdf->Image($image,10,10,-300);
        $pdf->Image('demo.jpg',60,30,89);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,7,'DAFTAR MEMBER LPKMUDA',0,1,'C');
        $pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'No',1,0,'C');
        $pdf->Cell(90,6,'Nama Pegawai',1,0,'C');
        $pdf->Cell(120,6,'Alamat',1,0,'C');
        $pdf->Cell(40,6,'Telp',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $pegawai = $this->db->get('system_members')->result();
        $no=0;
        foreach ($pegawai as $data){
            $no++;
            $pdf->Cell(10,6,$no,1,0, 'C');
            $pdf->Cell(90,6,$data->mbr_name,1,0);
            $pdf->Cell(120,6,$data->mbr_email,1,0);
            $pdf->Cell(40,6,$data->mbr_username,1,1);
        }
        $pdf->Output("sertifikat.pdf","I");
   }

   public function do_cetak_pdf(){
      
      // $srt_id = $data['srt_id'];
      $srt_id = 'SC-5kId1m4cR9';
      $data = [
         'title' => 'Sertifikat'
      ];
      // QR COde
      $writer = new PngWriter();
      // Create QR code
      $qrCode = QrCode::create(base_url('cert?srt_id=' . $srt_id))
         ->setEncoding(new Encoding('UTF-8'))
         ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
         ->setSize(450)
         ->setMargin(0)
         ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
         ->setForegroundColor(new Color(0, 0, 0))
         ->setBackgroundColor(new Color(255, 255, 255));
      
      $logo = Logo::create('./storage/system/app_icons.png')
         ->setResizeToWidth(100);
      $result = $writer->write($qrCode, $logo, null);
      $result->saveToFile('./storage/QRcode/' . $srt_id . '.png');
      $dataUri = $result->getDataUri();
      $data['dataqr']   = $dataUri;
      $this->load->view('member/sertifikat/cetak', $data);
      // instantiate and use the dompdf class
      $dompdf = new Dompdf();
      $dompdf->loadHtml($this->load->view('member/sertifikat/cetak', $data, true));
      // (Optional) Setup the paper size and orientation
      $dompdf->setPaper('A4', 'landscape');
      // Render the HTML as PDF
      $dompdf->render();
      // Output the generated PDF to Browser
      // $dompdf->stream();
      // $output = $dompdf->stream();
      $output = $dompdf->output();
      $path = 'sertifikat.pdf';
      file_put_contents($path, $output);
      echo $path;
   }
   private function do_cetak($data)
   {
      $srt_id = $data['srt_id'];
      $img = imagecreatefromjpeg('assets/sertifikat/kosong.jpg');
      $fontFile = FONTPATH . "/arial.ttf";
      $fontSize = 35;
      $fontColor = imagecolorallocate($img, 0, 0, 0);
      $angle = 0;
      $bulan = getRomawi(date('m'));
      $tahun = date('Y');
      $no = $data['srt_no_urut']."/LPK.MAH/".$bulan."/".$tahun;
      $posX = 2700;
      $posY = 139;
      imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $no);
      $fontSize = 111;
      $fontFile = FONTPATH . "/dwardian-script-itc-bold.ttf";
      $name = $_SESSION['system_members']['mbr_name'];
      $iWidth = imagesx($img);
      $tSize = imagettfbbox($fontSize, $angle, $fontFile, $name);
      $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
      $centerX = CEIL(($iWidth - $tWidth) / 2);
      $centerX = $centerX < 0 ? 0 : $centerX;
      $posY = 1000;
      imagettftext($img, $fontSize, $angle, $centerX, $posY, $fontColor, $fontFile, $name);
      $fontSize = 66;
      $fontFile = FONTPATH . "/arial.ttf";
      $nama = $this->db->get_where('app_kejuruan', ['kjr_id' => $_POST['kjr_id']])->row_array()['kjr_nama'];
      $nama = '"' . $nama . '"';
      $tSize = imagettfbbox($fontSize, $angle, $fontFile, $nama);
      $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
      $centerX = CEIL(($iWidth - $tWidth) / 2);
      $centerX = $centerX < 0 ? 0 : $centerX;
      $posY = 1450;
      imagettftext($img, $fontSize, $angle, $centerX, $posY, $fontColor, $fontFile, $nama);
      $fontSize = 44;
      $nama = 'Dengan hasil baik';
      $tSize = imagettfbbox($fontSize, $angle, $fontFile, $nama);
      $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
      $centerX = CEIL(($iWidth - $tWidth) / 2);
      $centerX = $centerX < 0 ? 0 : $centerX;
      $posY = 1550;
      imagettftext($img, $fontSize, $angle, $centerX, $posY, $fontColor, $fontFile, $nama);
      $fontSize = 34;
      $nama = date_indo(date('Y-m-d'));
      $posX = 2590;
      $posY = 1770;
      imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $nama);
      $fontSize = 38;
      $nama = $srt_id;
      $posX = 655;
      $posY = 2236;
      imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $nama);
      $fontSize = 33;
      $nama = 'Cek keaslian sertifikat di : lpkmudaalhidayah.my.id/cert';
      $posX = 2254;
      $posY = 2380;
      imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $nama);
      $quality = 100; // 0 to 100
      imagejpeg($img, './storage/sertifikat/' . $srt_id . '.jpg', $quality);

      // QR COde
      $writer = new PngWriter();
      // Create QR code
      $qrCode = QrCode::create(base_url('cert?srt_id=' . $srt_id))
         ->setEncoding(new Encoding('UTF-8'))
         ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
         ->setSize(400)
         ->setMargin(0)
         ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
         ->setForegroundColor(new Color(0, 0, 0))
         ->setBackgroundColor(new Color(255, 255, 255));
      $logo = Logo::create('./storage/system/app_icons.png')
         ->setResizeToWidth(100);
      $result = $writer->write($qrCode, $logo, null);
      $result->saveToFile('./storage/QRcode/' . $srt_id . '.png');
      $dataUri = $result->getDataUri();
      $dest = imagecreatefromjpeg('./storage/sertifikat/' . $srt_id . '.jpg');
      $src = imagecreatefrompng($dataUri);
      imagecopymerge($dest, $src, 350, 1750, 0, 0, 400, 400, 100);
      imagejpeg($dest, './storage/sertifikat/' . $srt_id . '.jpg', $quality);
      return $srt_id . '.jpg';
   }

   public function cobaImage()
   {
      $img = imagecreatefromjpeg('assets/sertifikat/kosong.jpg');

      // // $fontFile = FONTPATH."\arial.ttf";
      $fontFile = 'D:\arial.ttf';
      $fontSize = 35;
      $fontColor = imagecolorallocate($img, 0, 0, 0);
      $angle = 0;
      $no = "001";
      $posX = 2700;
      $posY = 139;
      imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $no);
      // $bulan = "VI";
      // $posX = 3043;
      // $posY = 139;
      // imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $bulan);
      // $tahun = "2021";
      // $posX = 3125;
      // $posY = 139;
      // imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $tahun);
      // $fontSize = 66;
      // $fontFile = FONTPATH . "\ELEPHNTI.TTF";
      // $name = "Didik Abdul Mukmin";
      // $iWidth = imagesx($img); // (C2) GET TEXT BOX DIMENSIONS
      // $tSize = imagettfbbox($fontSize, $angle, $fontFile, $name);
      // $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
      // $centerX = CEIL(($iWidth - $tWidth) / 2);
      // $centerX = $centerX < 0 ? 0 : $centerX;
      // $posY = 1000;
      // imagettftext($img, $fontSize, $angle, $centerX, $posY, $fontColor, $fontFile, $name);
      // $fontSize = 66;
      // $fontFile = FONTPATH . "\arial.TTF";
      // $nama = '"Administrasi Perkantoran"';
      // $tSize = imagettfbbox($fontSize, $angle, $fontFile, $nama);
      // $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
      // $centerX = CEIL(($iWidth - $tWidth) / 2);
      // $centerX = $centerX < 0 ? 0 : $centerX;
      // $posY = 1450;
      // imagettftext($img, $fontSize, $angle, $centerX, $posY, $fontColor, $fontFile, $nama);
      // $fontSize = 44;
      // $nama = 'Dengan hasil baik';
      // $tSize = imagettfbbox($fontSize, $angle, $fontFile, $nama);
      // $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
      // $centerX = CEIL(($iWidth - $tWidth) / 2);
      // $centerX = $centerX < 0 ? 0 : $centerX;
      // $posY = 1550;
      // imagettftext($img, $fontSize, $angle, $centerX, $posY, $fontColor, $fontFile, $nama);
      // $fontSize = 34;
      // $nama = '30 Juli 2020';
      // $posX = 2590;
      // $posY = 1775;
      // imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $nama);
      // $fontSize = 38;
      // $nama = 'SC-ABDCEFGHIJK';
      // $posX = 655;
      // $posY = 2236;
      // imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $nama);
      // $fontSize = 33;
      // $nama = ': lpkmudaalhidayah.my.id/cert';
      // $posX = 2794;
      // $posY = 2380;
      // imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $nama);
      // $fontSize = 33;
      // $nama = ': lpkmudaalhidayah.my.id/cert';
      // $posX = 2794;
      // $posY = 2380;
      // imagettftext($img, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $nama);

      // (C) OUTPUT IMAGE
      // (C1) DIRECTLY SHOW IMAGE
      header("Content-type: image/jpeg");
      imagejpeg($img);
      // imagedestroy($img);

      // (C2) OR SAVE TO A FILE
      $quality = 100; // 0 to 100
      // imagejpeg($img, './storage/sertifikat/' . $name . '.jpg', $quality);
   }

   private function cobaQr()
   {
      $writer = new PngWriter();
      // Create QR code
      $qrCode = QrCode::create(base_url('cert/?crt_id=SC-ABC'))
         ->setEncoding(new Encoding('UTF-8'))
         ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
         ->setSize(450)
         ->setMargin(0)
         ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
         ->setForegroundColor(new Color(0, 0, 0))
         ->setBackgroundColor(new Color(255, 255, 255));
      // Create generic logo
      $logo = Logo::create('./storage/system/app_icons.png')
         ->setResizeToWidth(100);
      $result = $writer->write($qrCode, $logo, null);
      // Directly output the QR code
      // header('Content-Type: ' . $result->getMimeType());
      // echo $result->getString();
      // Save it to a file
      $result->saveToFile('./storage/QRcode/sc-abc.png');
      // Generate a data URI to include image data inline (i.e. inside an <img> tag)
      $dataUri = $result->getDataUri();
      // Create image instances
      $dest = imagecreatefromjpeg('./assets/sertifikat/kosong.jpg');
      $src = imagecreatefrompng($dataUri);
      // Copy and merge
      imagecopymerge($dest, $src, 300, 1700, 0, 0, 450, 450, 100);
      // Output and free from memory
      header('Content-Type: image/jpeg');
      imagejpeg($dest);

      imagedestroy($dest);
      imagedestroy($src);
   }
}
