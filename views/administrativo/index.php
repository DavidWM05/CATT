<?php
  $administrador = "1";
  $rol = $_SESSION['rol'];
  $login = $_SESSION['login'];

  if ($rol != $administrador) {
    header('Location: /');
  }

  if( (isset($_SESSION['index_inicio']) && $_SESSION['index_inicio'] != 0) || (isset($_SESSION['index_fin']) && $_SESSION['index_fin'] != 0) ){ ?>
    <script>
      var mensaje = "<?php echo $_SESSION['index_msg']; ?>";

      Swal.fire({       
        imageUrl: '/build/img/burro_exitoso.jpg',
        imageAlt: 'Burrito exitoso',
        title: 'Éxito',
        text: mensaje,
        confirmButtonText: 'Genial!',
      })
    </script>
  <?php
    if(isset($_SESSION['index_inicio'])){ $_SESSION['index_inicio'] = 0; }
    if(isset($_SESSION['index_fin'])){ $_SESSION['index_fin'] = 0; }
    
  }
  ?>

<!-- Noticias -->
<section id="testimonios" class="seccion-clara">
  <div id="testimonios-carrusel" class="carousel carousel-dark slide" data-bs-ride="carousel">
    <div class="carousel-inner">
  <!-- Carrusel de Noticias-->
  <?php
    $contador = 1;  
    if(isset($_SESSION['noticias']) && count($_SESSION['noticias'])){
      foreach ($_SESSION['noticias'] as $noticia) {
        if($contador == 1){ echo '<div class="carousel-item active">'; $contador++;}
        else{ echo '<div class="carousel-item">'; }
  ?>
        <div class="container">          
            <img class="testimonio-imagen" src="/build/img/noticias/<?php echo $noticia->rutaimagen; ?>" alt="Noticia escom">          
          <div class="testimonio-info">
            <p class="noticia">Link</p>
            <p class="cargo">
              <a class="link-info link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="<?php echo $noticia->rutadestino; ?>" target="_blank" rel="noopener noreferrer">
                <?php echo $noticia->titulo; ?> 
              </a>
            </p>
          </div>
        </div>
      </div>
    <?php
        }
      }
    ?>      
    </div>
    
    <button class="carousel-control-prev" type="button" data-bs-target="#testimonios-carrusel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#testimonios-carrusel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
  </div>
</section>

<!-- Repositorios -->
<section class="experiencia seccion-clara">
  <div class="container text-center">
    <div class="row">
      <!-- Repositorios -->
      <div class="columna col-12 col-md-4">
        <a href="https://tesis.ipn.mx" target="_blank" rel="noopener noreferrer">
          <i class="bi bi-file-earmark-post"></i>
        </a>
        <p class="experiencia-titulo">Repositorios TTs</p>
        <p class="experiencia-descripcion">En este repositorio se encuentran proyectos terminales que puedes usar como referencias para la elaboración de tu documento.</p>
      </div>
      <!-- UTEYCV -->
      <div class="columna col-12 col-md-4">        
        <a href="https://uteycv.escom.ipn.mx/?fbclid=IwAR1r-uZh0VftwGpGmVvk1nn5xXdWWZH-iaBj_ck0S8P9PNnGbEM2rmwDyvc" target="_blank" rel="noopener noreferrer">
          <i class="bi bi-braces"></i>
        </a>
        <p class="experiencia-titulo">UTEYCV</p>
        <p class="experiencia-descripcion">Pagina uteycv en la cual podrás subir tus constancias para acreditar la UA Electiva a mediados del ciclo escolar en curso.</p>
      </div>
      <!-- Calendario -->
      <div class="columna col-12 col-md-4">
        <a href="/build/img/noticias/calendarioCatt.jpg" target="_blank" rel="noopener noreferrer">
          <i class="bi bi-calendar-check"></i>
        </a>
        <p class="experiencia-titulo">Calendario CATT</p>
        <p class="experiencia-descripcion">Calendario de actividades de la Comisión Académica de Trabajos Terminales la cual podrás consultar para estar al pendiente de los tramites correspondientes.</p>
      </div>
    </div>
  </div>
</section>

<!-- Recursos Oficiales -->
<section class="articulos justify-content-start">
  <h2 class="titulo-AG texto-negro">Plantillas de solicitudes</h2>
  <div class="card">
    <div class="card-header">
      Más recientes
    </div>
    <ul class="list-group list-group-flush">
      <a href="https://www.escom.ipn.mx/docs/escomunidad/catt/solicitudModificacionTT.pdf" target="_blank" rel="noopener noreferrer">
        <li class="list-group-item">
          Solicitud de Modificación de TT
        </li>
      </a>
      <a href="https://www.escom.ipn.mx/docs/escomunidad/catt/normasReferenciaAPA.pdf" target="_blank" rel="noopener noreferrer">
        <li class="list-group-item">
          Normas de Referencia APA
        </li>
      </a>
      <a href="https://www.escom.ipn.mx/docs/escomunidad/catt/normasReferenciaIEEE.pdf" target="_blank" rel="noopener noreferrer">
        <li class="list-group-item">
          Normas de Referencia IEEE
        </li>
      </a>
      <a href="https://www.escom.ipn.mx/docs/escomunidad/catt/evaluacionSitiosWeb.pdf" target="_blank" rel="noopener noreferrer">
        <li class="list-group-item">
          Evaluacion de Sitios Web
        </li>
      </a>      
    </ul>
  </div>
  <a href="https://www.escom.ipn.mx/htmls/escomunidad/catt.php" target="_blank" rel="noopener noreferrer">
    <button type="button" class="btn btn-info">
      Ver más artículos
      <i class="bi bi-arrow-right-circle-fill"></i>
    </button>
  </a>
</section>

<!-- Modal acerca de -->
<div class="modal fade" id="acercade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="acercadeLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title titulo-modal text-center" id="acercadeLabel">Acerca de esta plataforma</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">            
          <input type="text" class="filtro" id="enable" name="enable" value="'.$valor->idPersona.'">
          
          <div class="mb-3">
              <label class="col-form-label"> Desarrollada por: </label>
              <ul>
                <li>Peralta Gonzalez Luis David</li>
                <li>Guerrero Vilardell Manuel Alejandro</li>
                <li>Lopez Salazar Victor Manuel</li>
              </ul>
              <p> Lugar: Escuela Superior de Cómputo </p>
              <p> Version: 1.0 </p>
              <p> Fecha: 12/06/2023 </p>
              
          </div>
          <div class="modal-footer">
              <div class="mb-3 text-center">
                <img class="" src="/build/img/logo_ipn_web.png" alt="Instituto Politécnico Nacional" style="width: 80%;">
              </div>
              <div class="mb-3">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background-color: #800040; color: aliceblue;">¡Huélum!</button>
              </div>
          </div>            
        </div>
    </div>
  </div>
</div>
