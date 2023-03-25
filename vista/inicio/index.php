<?php include(call . "Inicio.php"); ?>

<!-- Contenido de la pagina -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0">Plantas IA</h1>
            </div>
            <!-- /.col -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </div>
   <!-- /.content-header -->
   <!-- Main content -->
   <section class="content">
      <!-- Default box -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Reino plantae</h3>
            <div class="card-tools">
               <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
               </button>
            </div>
         </div>
         <div class="card-body w-100">
            <div class='w-100 text-center' style='font-size:20px; font-weight:bold'>Apuesto a que puedo adivinar en que planta piensas</div>
            <div class='chat d-flex flex-column' id='chat'>
            </div>
         <div class='user-input' id='user-input'>
         <div class='d-flex mx-auto flex-row justify-content-between'></div>
         </div>
         </div>

         <!-- /.card-footer-->
      </div>
      <!-- /.card -->
   </section>
   <!-- /.content -->
   <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<footer class="main-footer">
   <strong>
      &copy; Reino Plantae <?php echo "2021";
                           echo "-" . date('Y') ?> .
   </strong>
   <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 4.0
   </div>
</footer>
</main>
<?php include(call . "Script.php"); ?>
<?php include(call . "regitro.php"); ?>
<script type="text/javascript" src="<?php echo constant('URL') ?>vista/inicio/js/plantas-cosulta.js"></script>
<script type="text/javascript" src="<?php echo constant('URL') ?>vista/inicio/js/brain.min.js"></script>
<style>
   .chat {
      width: 90%;
      margin-left: auto;
      margin-right: auto;
      background: #343a40;
      border-radius: 30px;
      height: 400px;
      margin-top: 30px;
      overflow-y: auto;
      padding-top: 25px;
      padding-left: 60px;
      padding-right: 60px;
      padding-bottom: 90px;
   }

   .ia {
      position: relative;
      background: #DBEFDA;
      text-align: left;
      padding: 10px 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
      float: left;
      right: 20px;
      font-weight: bold;
      width: fit-content;
      margin-top: 20px;
   }

   .ia::before {
      content: '';
      position: absolute;
      visibility: visible;
      top: -1px;
      left: -10px;
      border: 10px solid transparent;
      border-top: 10px solid #ccc;
   }

   .ia::after {
      content: '';
      position: absolute;
      visibility: visible;
      top: 0px;
      left: -8px;
      border: 10px solid transparent;
      border-top: 10px solid #DBEFDA;
      clear: both;

   }

   .ia div {
      clear: left;
   }

   .ia img {
      display: block;
      height: auto;
      max-width: 100%;
   }

   .user {
      position: relative;
      background: white;
      text-align: right;
      padding: 10px 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
      float: right;
      right: 20px;
      font-weight: bold;
      width: fit-content;
      margin-left: 95%;
      margin-top: 20px;
   }

   .user::before {
      content: '';
      position: absolute;
      visibility: visible;
      top: -1px;
      right: -10px;
      border: 10px solid transparent;
      border-top: 10px solid #ccc;
   }

   .user::after {
      content: '';
      position: absolute;
      visibility: visible;
      top: 0px;
      right: -8px;
      border: 10px solid transparent;
      border-top: 10px solid white;
      clear: both;

   }

   .user div {
      clear: right;
   }

   .user img {
      display: block;
      height: auto;
      max-width: 100%;
   }


   .loader:before,
   .loader:after,
   .loader {
      border-radius: 50%;
      width: 2.5em;
      height: 2.5em;
      -webkit-animation-fill-mode: both;
      animation-fill-mode: both;
      -webkit-animation: load7 1.8s infinite ease-in-out;
      animation: load7 1.8s infinite ease-in-out;
   }

   .loader {
      margin-top: 10px;
      font-size: 10px;
      position: relative;
      text-indent: -9999em;
      -webkit-animation-delay: 0.16s;
      animation-delay: 0.16s;
   }

   .loader:before {
      left: -3.5em;
   }

   .loader:after {
      left: 3.5em;
      -webkit-animation-delay: 0.32s;
      animation-delay: 0.32s;
   }

   .loader:before,
   .loader:after {
      content: '';
      position: absolute;
      top: 0;
   }

   @-webkit-keyframes load7 {

      0%,
      80%,
      100% {
         box-shadow: 0 2.5em 0 -1.3em #ffffff;
      }

      40% {
         box-shadow: 0 2.5em 0 0 #FFF;
      }
   }

   @keyframes load7 {

      0%,
      80%,
      100% {
         box-shadow: 0 2.5em 0 -1.3em #ffffff;
      }

      40% {
         box-shadow: 0 2.5em 0 0 #FFF;
      }
   }

   .loader-user {
      font-size: 5px; 
      margin-left: 95%
   }

   .loader-ia {
      font-size: 5px; 
      margin-right: 100%
   }

   .user-input {
      width: 90%;
      height: 100px;
      margin-left: auto;
      margin-right: auto;
      margin-top: 20px;
      text-align: center;
   }

   .user-input div{
      width: 80%;
      height: 80px;
      padding-top:25px;
      text-align: center;
   }
</style>
</body>

</html>