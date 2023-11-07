<?php 
    
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    $docente = "2";
    $rol = $_SESSION['rol'];
    $login = $_SESSION['login'];

    if($rol != $docente) {
        header('Location: /');
    }
?>

<div class="container mt-3 mb-5">
    <h2 class="text-center">TT's Director</h2>
</div>

<div class="row col-md-12 mr-0 m-auto justify-content-around mb-3">

<?php 
  if($tts == null) {
?>
  <div class="container m-auto" style="margin-bottom: 50vh !important;">
    <h5 class="text-center">No existen datos para mostrar</h5>
  </div>
<?php
  
  } else {

  foreach( $tts as $tt) { 
?>
  <div class="row col-md-4 justify-content-around mb-3">
    <div class = "col-md-8">
      <div class="card">
        <div class="card-body text-center">
          <h5 class="card-title"><?php echo $tt->numeroTT ?></h5>
          <p class="card-text">En este apartado puedes observar todos los detalles de este trabajo terminal</p>
          <a href="/tt-docente-director?numTT=<?php echo $tt->numeroTT ?>" class="col-sm-2 btn btn-primary">Ir</a>
        </div>
      </div>
    </div>
  </div>
<?php
    }
?>

</div>

<?php
    }
?>