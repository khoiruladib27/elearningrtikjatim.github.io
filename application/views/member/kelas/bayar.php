<div class="col-md-9 mt-4">
   <div class="card">
      <div class="card-header">
         <i class="fas fa-money-bill-wave"></i> Bayar Kelas (<?= $kelas['kjr_nama'] ?>)
      </div>
      <div class="card-body">
         <div class="row">
            <?php if ($kelas['kls_trf_id']) : ?>
               <script>
                  $(document).ready(function() {
                     getBayar('<?= $kelas['kjr_id'] ?>')
                  });
               </script>
            <?php else : ?>
               <div id="alert" class="col-md-12"> </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <h5 class="mb-3">Metode Pembayaran</h5>
                     <select class="form-control select2" style="width: 100%" id="trf_id" data-placeholder="Pilih Pemateri" name="trf_id">
                        <option value="">Pilih Pembayaran</option>
                        <?php foreach ($bayar as $b) : ?>
                           <option value="<?= $b['trf_id'] ?>"><?= $b['trf_nama'] ?></option>
                        <?php endforeach; ?>
                     </select>
                     <span class="text-danger" id="trf_id_error"></span>
                  </div>
                  <div class="form-group">
                     <button onclick="checkout()" class="btn btn-warning"><i class="fas fa-credit-card"></i> Checkout</button>
                  </div>
               </div>
            <?php endif; ?>
            <div id="invoice" class="col-md-7 offset-md-1">
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   var kjr_id = '<?= $kelas['kjr_id'] ?>';
</script>
<script>
   function getBayar() {
      var link = BASE_URL + 'member/kelas/getBayar';
      $.ajax({
         url: link,
         type: "POST",
         data: {
            kjr_id: kjr_id
         },
         dataType: 'json',
         beforeSend: function(data) {
            $('#invoice').html('<i class="fas fa-spinner fa-spin"></i> Proses')
         },
         success: function(data) {
            if (data.status == 1) {
               data = data.data;
               var send = 'Saya telah melakukan pembayaran di *Simuda Course* sebagai berikut : %0a%0a';
               send += 'Kode Invoice : ' + data.kls_id + '%0a';
               send += 'Kelas : ' + data.kjr_nama + '%0a';
               send += 'Member : ' + data.mbr_name + '%0a';
               send += 'Metode Pembayaran : ' + data.trf_nama + '%0a';
               send += 'No. Transfer : ' + data.trf_value + '%0a';
               send += 'Atas Nama : ' + data.trf_an + '%0a';
               send += 'Total Bayar : Rp. ' + data.kjr_harga + '%0a%0a';
               send += 'Berikut saya kirim bukti pembayarannya';
               var html = `<table class="table">
                           <tr>
                              <td colspan="3">
                                 <h5 class="mb-0">Invoice</h5>
                              </td>
                           </tr>
                           <tr>
                              <td>Kode. Invoice</td>
                              <td>:</td>
                              <td>` + data.kls_id + `</td>
                           </tr>
                           <tr>
                              <td>Kelas</td>
                              <td>:</td>
                              <td>` + data.kjr_nama + `</td>
                           </tr>
                           <tr>
                              <td>Member</td>
                              <td>:</td>
                              <td>` + data.mbr_name + `</td>
                           </tr>
                           <tr>
                              <td>Metode Pembayaran</td>
                              <td>:</td>
                              <td>` + data.trf_nama + `</td>
                           </tr>
                           <tr>
                              <td>No. Transfer</td>
                              <td>:</td>
                              <td>` + data.trf_value + `</td>
                           </tr>
                           <tr>
                              <td>Atas Nama</td>
                              <td>:</td>
                              <td>` + data.trf_an + `</td>
                           </tr>
                           <tr>
                              <td>Total Bayar</td>
                              <td>:</td>
                              <td>Rp. ` + data.kjr_harga + `</td>
                           </tr>
                           <tr>
                              <td colspan="3">
                                 <a target="_blank" href="https://wa.me/6285732775490?text=` + send + `" class="btn btn-sm btn-success"><i class="fab fa-whatsapp"></i> Konfirmasi</a>
                              </td>
                           </tr>
                           <tr>
                              <td colspan="3">
                                 <h6>Cara Bayar :</h6>
                                 <div id="cara-bayar">
                                    <ul>
                                       <li>Transfer pembayaran ke nomor transfer berdasarkan metode pembayaran</li>
                                       <li>Transfer sejumlah total bayar</li>
                                       <li>Konfirmasi pembayaran supaya pesanan segera diproses</li>
                                       <li>Setelah pembayaran telah diterima dan dikonfirmasi oleh admin, kelas dapat digunakan</li>
                                    </ul>
                                 </div>
                              </td>
                           </tr>
                        </table>`;
               $('#invoice').html(html);
            } else {
               $('#alert').html(`
                     <div class="alert alert-danger" role="alert">
                        ` + data.pesan + `
                     </div>
                  `);
            }
         }
      });
   }

   function checkout() {
      $('#alert').html('');
      $('#trf_id_error').html('');
      var trf_id = $('#trf_id').val();
      if (!trf_id) {
         $('#trf_id_error').html('Pilih metode pembayaran');
      } else {
         var link = BASE_URL + 'member/kelas/checkout';
         $.ajax({
            url: link,
            type: "POST",
            data: {
               trf_id: trf_id,
               kjr_id: kjr_id
            },
            dataType: 'json',
            beforeSend: function(data) {
               $('#invoice').html('<i class="fas fa-spinner fa-spin"></i> Proses')
            },
            success: function(data) {
               if (data.status == 1) {
                  $('#alert').html(`
                     <div class="alert alert-success" role="alert">
                        ` + data.pesan + `
                     </div>
                  `);
                  getBayar();
               } else {
                  $('#alert').html(`
                     <div class="alert alert-danger" role="alert">
                        ` + data.pesan + `
                     </div>
                  `);
               }
            }
         });
      }
   }
</script>