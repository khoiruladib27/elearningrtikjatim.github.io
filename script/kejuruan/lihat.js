$(document).off("click", ".nav-tabs .nav-item a")
.on("click", ".nav-tabs .nav-item a", function(e) {
   var id = $(this).data('id');
   $(".nav-tabs .nav-item a").removeClass('active');
   $('.nav-tabs .nav-item a[data-id="'+id+'"]').addClass('active');
   $(".tab-content .tab-pane").removeClass('active');
   $('.tab-content .tab-pane[id="'+id+'"]').addClass('active');
});

function gabung(mbr_id, kjr_id){
   if(!mbr_id){
      window.location.href = BASE_URL + "member";
   }else{
      var link = BASE_URL + 'kejuruan/gabungKelas';
      $.ajax({
         url: link,
         type: "POST",
         data: {
            mbr_id: mbr_id,
            kjr_id: kjr_id
         },
         dataType: 'json',
         success: function(data) {
            if (data.status == 1) {
               Swal.fire({
                   icon: 'success',
                   title: data.pesan,
                   showConfirmButton: false,
                   timer: 1500
               });
               setTimeout(function() {
                  window.location.href = BASE_URL + "member/kelas";
               }, 1000);
            }else {
               Swal.fire({
                   icon: 'error',
                   title: data.pesan,
                   showConfirmButton: false,
                   timer: 1500
               });
               if(data.status == 4){  
                  setTimeout(function() {
                     window.location.href = BASE_URL + "member/kelas";
                  }, 1000);
               }
            }
         }
      });
   }
}