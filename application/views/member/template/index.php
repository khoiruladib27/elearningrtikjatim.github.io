<link rel="stylesheet" href="<?= base_url() ?>assets/member/custome.css">
<div class="container-fluid pb-5" style="background-color: #109c30;">
   <div class="row">
      <?php $this->load->view('member/include/menu') ?>
      <?php $this->load->view($memberpage) ?>
   </div>
</div>