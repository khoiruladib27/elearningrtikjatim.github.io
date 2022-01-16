$(document).off("click", ".nav-tabs .nav-item a")
.on("click", ".nav-tabs .nav-item a", function(e) {
   var id = $(this).data('id');
   $(".nav-tabs .nav-item a").removeClass('active');
   $('.nav-tabs .nav-item a[data-id="'+id+'"]').addClass('active');
   $(".tab-content .tab-pane").removeClass('active');
   $('.tab-content .tab-pane[id="'+id+'"]').addClass('active');
});