<?php
  $administrador = "1";
  $rol = $_SESSION['rol'];
  $login = $_SESSION['login'];

  if ($rol != $administrador) {
    header('Location: /');
  }
?>

<!-- Contenedor -->
<div class="container mt-4 mb-4 px-4">
  <div class="row g-3"  id="contenedor">
    <!-- Titulo -->
    <div class="col-md-12">
      <div class="container text-center">
          <b class="texto-formulario">Registro de Ciclo</b>
      </div>
    </div>
  </div>
</div>

<!-- Contenedor -->
<div class="container" style="margin-bottom: 40vh !important;">
  <form class="form-label justify-content-center row" action="/administrador/registrartts" method="POST" enctype="multipart/form-data">
      <div class="row col-md-12 justify-content-center mb-3">
          <div class="col-md-6">
              <input class="form-control" type="file" name="archivo" id="archivo" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
          </div>
      </div>      
      <button type="submit" class="btn btn-success col-md-3">Subir archivo</button>
  </form>
</div>
