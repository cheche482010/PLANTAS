 <!-- Preloader -->
 
 <!-- Barra superior -->
 <nav class="main-header navbar navbar-expand navbar-dark">
     <!-- Left navbar links -->
     <ul class="navbar-nav">
         <li class="nav-item">
             <a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button"><i
                     class="fas fa-bars"></i></a>
         </li>
         <li class="nav-item d-none d-sm-inline-block">
             <a href=">" class="nav-link" onclick="cambio_modulo('Inicio')">Inicio</a>
         </li>
         <li class="nav-item d-none d-sm-inline-block">
             <a href="" class="nav-link" onclick="cambio_modulo('Contacto')">Contacto</a>
         </li>
     </ul>

     <!-- Right navbar links -->
     <ul class="navbar-nav ml-auto">
         <!-- Navbar Search -->
         <li class="nav-item">

             <div class="navbar-search-block">
                 <form class="form-inline">
                     <div class="input-group input-group-sm">
                         <input class="form-control form-control-navbar" type="search" placeholder="Search"
                             aria-label="Search">
                         <div class="input-group-append">
                             <button class="btn btn-navbar" type="submit">
                                 <i class="fas fa-search"></i>
                             </button>
                             <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                 <i class="fas fa-times"></i>
                             </button>
                         </div>
                     </div>
                 </form>
             </div>
         </li>
         
        <li class="nav-item dropdown">
            <div class="theme-switch-wrapper nav-link">
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox" />
                    <span class="slider round"></span>
                </label>
            </div>     
         </li>
         <!-- Dak Modo  -->
         <?php if($_SESSION['Solicitudes']['registrar']=='1'){ ?>
         <li class="nav-item dropdown">
             <a onclick="$('#solicitar_constancia').modal('show');" class="nav-link" data-toggle="dropdown"
                 href="javascript:void(0)" title='Solicitar constancia'>
                 <i class="far fa-file"></i>
             </a>

         </li>
         <?php }?>

       
      
         <li class="nav-item">
             <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
                 <i class="fas fa-expand-arrows-alt"></i>
             </a>
         </li>

         

     </ul>
 </nav>
 <!-- /.navbar -->