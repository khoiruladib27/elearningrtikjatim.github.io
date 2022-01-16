<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="col-md-9 mt-4">
   <div class="card">
      <div class="card-header">
         <i class="fas fa-home"></i> Dashboard
      </div>
      <div class="card-body">
         <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
               <div class="info-box">
                  <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Jumlah Kelas</span>
                     <span class="info-box-number"><?= $jml['kelas'] ?></span>
                  </div>
               </div>
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
               <div class="info-box mb-3">
                  <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tasks"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Progres Kelas</span>
                     <span class="info-box-number"><?= $jml['progres'] ?></span>
                  </div>
               </div>
            </div>
            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
               <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-thumbs-up"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Kelas Selesai</span>
                     <span class="info-box-number"><?= $jml['selesai'] ?></span>
                  </div>
               </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
               <div class="info-box mb-3">
                  <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-award"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Sertifikat</span>
                     <span class="info-box-number"><?= $jml['sertifikat'] ?></span>
                  </div>
               </div>
            </div>
         </div>
         <!-- <div class="row mt-3">
            <div class="col-md-12" id="chart">
            </div>
         </div> -->
      </div>
   </div>
</div>
<!-- <script>
   var options = {
      title: {
         text: 'Progres Belajar',
         align: 'center'
      },
      series: [{
         name: 'Programming',
         data: [1, 2, 1, 2, 3, 2, 1]
      }, {
         name: 'Desain Grafis',
         data: [2, 1, 2, 2, 1, 1, 3]
      }],
      chart: {
         height: 350,
         type: 'area'
      },
      dataLabels: {
         enabled: false
      },
      stroke: {
         curve: 'smooth'
      },
      xaxis: {
         type: 'text',
         categories: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul"]
      },
      tooltip: {
         x: {
            format: 'dd/MM/yy HH:mm'
         },
      },
   };

   var chart = new ApexCharts(document.querySelector("#chart"), options);
   chart.render();
</script> -->