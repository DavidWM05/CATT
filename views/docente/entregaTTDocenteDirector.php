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
    <h2 class="text-center">Seguimiento TT</h2>
</div>

<div class="row col-12 justify-content-around mb-3">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <h5 class="card-title">Primer Entrega</h5>
        <p class="card-text">En este apartado puedes subir tu archivo y recibir retroalimentación acerca de tu avance</p>
        <a href="/entrega-tt-docente-director?entrega=1&numTT=<?php echo $numTT ?>" class="col-sm-2 btn btn-primary">Ir</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <h5 class="card-title">Segunda Entrega</h5>
        <p class="card-text">En este apartado puedes subir tu archivo y recibir retroalimentación acerca de tu avance</p>
        <a href="/entrega-tt-docente-director?entrega=2&numTT=<?php echo $numTT ?>" class="col-sm-2 btn btn-primary">Ir</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <h5 class="card-title">Tercer Entrega</h5>
        <p class="card-text">En este apartado puedes subir tu archivo y recibir retroalimentación acerca de tu avance</p>
        <a href="/entrega-tt-docente-director?entrega=3&numTT=<?php echo $numTT ?>" class="col-sm-2 btn btn-primary">Ir</a>
      </div>
    </div>
  </div>
</div>

<div class="row col-12 justify-content-around mb-5">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <h5 class="card-title">Cuarta Entrega</h5>
        <p class="card-text">En este apartado puedes subir tu archivo y recibir retroalimentación acerca de tu avance</p>
        <a href="/entrega-tt-docente-director?entrega=4&numTT=<?php echo $numTT ?>" class="col-sm-2 btn btn-primary">Ir</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <h5 class="card-title">Quinta Entrega</h5>
        <p class="card-text">En este apartado puedes subir tu archivo y recibir retroalimentación acerca de tu avance</p>
        <a href="/entrega-tt-docente-director?entrega=5&numTT=<?php echo $numTT ?>" class="col-sm-2 btn btn-primary">Ir</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <h5 class="card-title">Sexta Entrega</h5>
        <p class="card-text">En este apartado puedes subir tu archivo y recibir retroalimentación acerca de tu avance</p>
        <a href="/entrega-tt-docente-director?entrega=6&numTT=<?php echo $numTT ?>" class="col-sm-2 btn btn-primary">Ir</a>
      </div>
    </div>
  </div>
</div>