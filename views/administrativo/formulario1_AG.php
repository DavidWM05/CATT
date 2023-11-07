<?php
  $administrador = "1";
  $rol = $_SESSION['rol'];
  $login = $_SESSION['login'];

  if ($rol != $administrador) {
    header('Location: /');
  }

  $totalTT1 = siExisteSessionCount('listatt1');
  $totalTT2 = siExisteSessionCount('listatt2');
  $totaldias = siExisteSessionCount('listadias');

  $condicionDatos = siExisteSessionGroup(['listatt1','listatt2','listadias','listahorarios','listasalones'])&& $totaldias > 0 ;
?>

<!-- Formulario-->
<div class="container mt-4 mb-4" style="min-width: 90vw;">
  <form class="row g-3" method="GET" <?php if($condicionDatos) echo ' action="/administrador/resultados_AG"'; else ' action="/administrador/formulario1_AG"';?>>
    <!-- Fechas de presentacion -->
    <div class="col-md-4">
      <div class="container text-center">
        <hr>
        <b class="texto-formulario">Fechas</b>
      </div>
      <div class="container">
        <label for="fechainicio_presentacion" class="form-label text-left">del</label>
        <input type="date" class="form-control" id="fechainicio_presentacion" name="fechainicio" <?php if(isset($_SESSION['fechainicio'])) echo "value=".$_SESSION['fechainicio']." disabled"; ?> required>

        <label for="fechafin_presentacion" class="form-label">al</label>
        <input type="date" class="form-control" id="fechafin_presentacion" name="fechafin" <?php if(isset($_SESSION['fechafin'])) echo "value=".$_SESSION['fechafin']." disabled"; ?> required>
      </div>
    </div>

    <!-- Salones de presentacion -->
    <div class="col-md-4">
      <div class="container text-center">
        <hr>
        <b class="texto-formulario">Salones</b>
      </div>
      <div class="container">
        <?php
          if(siExisteSession('listasalones')){    //  Lista salones seleccionados
            foreach ($_SESSION['salones'] as $salon) {
              $valor = $salon->numeroSalon;
              $check = 'disabled>';

              echo "<div class='form-check form-check-inline'>";
              echo  "<label class='form-check-label'>".$valor."</label>";
                            
              if(in_array($valor,$_SESSION['listasalones'])){ $check = "id='cb_salon' checked disabled>"; }
              
              echo  "<input class='form-check-input' type='checkbox' value='".$valor."' name='cb_salon[]' ".$check;
              echo "</div>";
            }
          }else if(count($_SESSION['salones'])>0){  //  Lista salones por seleccionar
            foreach ($_SESSION['salones'] as $salon) {
              $valor = $salon->numeroSalon;
              
              echo "<div class='form-check form-check-inline'>";
              echo  "<label class='form-check-label'>".$valor."</label>";
              echo  "<input class='form-check-input' type='checkbox' value='".$valor."' name='cb_salon[]' checked>";
              echo "</div>";
            }
          }else{
            echo "No hay salones";
          }
        ?>
      </div>
    </div>

    <!-- Horarios de presentacion -->
    <div class="col-md-4">
      <div class="container text-center">
        <hr>
        <b class="texto-formulario">Horarios</b>
      </div>
      <div class="container">
        <?php
          if(siExisteSession('listahorarios')){
            foreach ($_SESSION['horarios'] as $horario) {
              $valor = $horario->horario_inicio;
              $check = 'disabled>';

              echo "<div class='form-check form-check-inline'>";
              echo  "<label class='form-check-label'>".$valor."</label>";

              if(in_array($valor,$_SESSION['listahorarios'])){ $check = 'checked disabled>'; }

              echo  "<input class='form-check-input' type='checkbox' value='".$valor."' name='cb_horario[]' ".$check;
              echo "</div>";              
            }
          }else if(count($_SESSION['horarios'])>0){            
            foreach ($_SESSION['horarios'] as $horario) {
              $valor = $horario->horario_inicio;

              echo "<div class='form-check form-check-inline'>";
              echo  "<label class='form-check-label'>".$valor."</label>";
              echo  "<input class='form-check-input' type='checkbox' value='".$valor."' name='cb_horario[]' checked>";
              echo "</div>";              
            }
          }else{
            echo "No hay horarios";
          }
        ?>
      </div>
    </div>

    <!-- Lista de Trabajos Terminales 1,2 y R -->
    <div class="col-md-12">
        <div class="container text-center">
            <hr>
            <b class="texto-formulario">Trabajos Terminales</b>
        </div>
        
        <div class="container">
          <!-- Componente accordion -->
          <div class="accordion accordion-flush" id="accordionFlushExample">
            <!-- Trabajos Terminales 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Trabajos Terminales 1
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <table class="table table-bordered table-striped" >
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Titulo</th>
                                    <th>Tipo</th>                                        
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    foreach ($_SESSION['listatt1'] as $tt) {
                                      echo "<tr>";
                                        echo "<td align='center' style='white-space: nowrap;'>".$tt->tt_numero."</td>";
                                        echo "<td align='justify'>".$tt->tt_titulo."</td>";
                                        echo "<td align='center'>".$tt->tt_tipo."</td>";                                              
                                      echo "</tr>";
                                    }
                                ?>
                            </tbody>                
                        </table>                    
                    </div>
                </div>
            </div>
            <!-- Trabajos Terminales 2 y R -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        Trabajos Terminales 2 y R
                    </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                    <table class="table table-bordered table-striped" >
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Titulo</th>
                                    <th>Tipo</th>                                        
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    foreach ($_SESSION['listatt2'] as $tt) {
                                      echo "<tr>";
                                        echo "<td align='center' style='white-space: nowrap;'>".$tt->tt_numero."</td>";
                                        echo "<td align='justify'>".$tt->tt_titulo."</td>";
                                        echo "<td align='center'>".$tt->tt_tipo."</td>";                                            
                                      echo "</tr>";
                                    }
                                ?>
                            </tbody>                
                        </table>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>

    <!-- Validación de Datos -->
    <?php
      if(isset($_SESSION['listadias'])){
        //Fechas de presentaciones TT1
        echo '<div class="col-md-6">
                <div class="container text-center">
                    <hr>
                    <b class="texto-formulario">Fechas TT1</b>
                </div>
                <div class="container">';
        if(count($_SESSION['listadias']) > 0){
            foreach ($_SESSION['listadias'] as $dia) {

            echo "<div class='form-check form-check-inline'>";
            echo  "<label class='form-check-label'>".$dia."</label>";
            echo  "<input class='form-check-input' type='checkbox' value='".$dia."' name='tt1_cb_dia[]'>";
            echo "</div>";
            }
        }else{
            echo "No hay días";
        }
        echo '  </div>
              </div>';
        
        //Fechas de presentaciones TT1
        echo '<div class="col-md-6">
                <div class="container text-center">
                    <hr>
                    <b class="texto-formulario">Fechas TT2 y TTR</b>
                </div>
                <div class="container">';
        if(count($_SESSION['listadias']) > 0){
            foreach ($_SESSION['listadias'] as $dia) {

            echo "<div class='form-check form-check-inline'>";
            echo  "<label class='form-check-label'>".$dia."</label>";
            echo  "<input class='form-check-input' type='checkbox' value='".$dia."' name='tt2_cb_dia[]'>";
            echo "</div>";
            }
        }else{
            echo "No hay días";
        }
        echo '  </div>
              </div>';
      } 
    ?>

    <!-- Botones -->
    <div class="col-12">
      <div class="btn-group" role="group" aria-label="Basic mixed styles example">
        <!-- Botón submit -->
        <button class="btn btn-success" type="submit">siguiente</button>
        <!-- Botón reset -->
        <button class="btn btn-primary" type="reset">reset</button>
        <!-- Botón recargar
        <button class="btn btn-warning" onclick="window.location.href = '/administrador/formulario1_AG';">recargar</button> -->
      </div>
    </div>

  </form>
</div>

<!-- js -->
<script>
  //variables y constantes
  const date_inicio = document.getElementById("fechainicio_presentacion");
  const date_fin = document.getElementById("fechafin_presentacion");
  
  //Eventos
  date_inicio.addEventListener('input',validarFechas);
  date_fin.addEventListener('input',validarFechas);

  //Funciones
  function validarFechas() {
      let contenidofi = date_inicio.value;
      let contenidoff = date_fin.value;

      let validacion_1 = contenidofi > contenidoff;
      let validacion_2 = contenidofi.length != 0 && contenidoff.length != 0;
      if( validacion_1 && validacion_2){
          date_fin.value = "";

        Swal.fire({
          title: '¡Cuidado!',
          text: 'La fecha final debe ser mayor a la de inicio',
          icon: 'warning',
          showConfirmButton: false,
          timer: 3000
        });
      }
  }

</script>