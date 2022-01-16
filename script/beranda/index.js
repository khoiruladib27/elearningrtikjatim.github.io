$(document).ready(function() {
   $('.carousel').carousel();
   $("#carousel-mitra").owlCarousel({
      autoplay: true,
      loop: true,
      smartSpeed: 300,
      nav: false,
      dots: false,
      responsive: {
         0: {
            items: 2
         },
         480: {
            items: 2
         },
         768: {
            items: 3
         },
         1024: {
            items: 4
         },
         1366: {
            items: 5
         },
      }
   });
   $("#carousel-pembelajar").owlCarousel({
      autoplay: true,
      loop: true,
      smartSpeed: 300,
      nav: true,
      dots: false,
      responsive: {
         0: {
            items: 1
         },
         480: {
            items: 1
         },
         768: {
            items: 3
         },
         1024: {
            items: 3
         },
         1366: {
            items: 4
         },
      }
   });
});