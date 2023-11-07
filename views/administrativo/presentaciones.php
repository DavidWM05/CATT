<?php
use Classes\Presentacion;

  $administrador = "1";
  $rol = $_SESSION['rol'];
  $login = $_SESSION['login'];

  if ($rol != $administrador) {
    header('Location: /');
  }
  //  Mensajes de éxito o error
  if(isset($_SESSION['msg']) && isset($_SESSION['estado']) && $_SESSION['estado'] == 'exitoso'){  //  Mensajes de éxito?>
    <script>
      var mensaje = "<?php echo $_SESSION['msg']; ?>";

      Swal.fire({       
      imageUrl: '/build/img/burro_exitoso.jpg',
      imageAlt: 'Burrito exitoso',
      title: 'Éxito',
      text: mensaje,
      confirmButtonText: 'Genial!',
      })
    </script>

<?php
    unset($_SESSION['msg']);
    unset($_SESSION['estado']);
  }elseif(isset($_SESSION['msg'])){  //  Mensajes de error?>
    <script>
        var mensaje = "<?php echo $_SESSION['msg']; ?>";        
        
        Swal.fire({        
        imageUrl: '/build/img/burro_error.jpg',
        imageAlt: 'Burrito exitoso',
        title: 'Error',
        text: mensaje,
        confirmButtonText: 'ok!',
        })
    </script>
<?php    
    unset($_SESSION['msg']);
    unset($_SESSION['estado']);
  }

  $presentaciones = array();  //  Arreglo para guardar lista de presentaciones
  if(!isset($alertas['msg']) || $alertas['msg'] != 'nada'){ //  Entra si: no existe mensaje de error
?>
  <!-- Horarios de Presentación -->
  <div class="container mt-4 mb-4" style="min-width: 90vw;">
    <!-- Menu y opciones -->
    <div class="row g-3 mt-2" >
      <!-- Info de tts -->
      <div class="col-md-4">
        <ul>            
          <li class="info_AG"><i class="bi bi-circle-fill" class="bi bi-info-circle" style="color: #257ba6;"></i> Trabajo Terminal 1.</li>
          <li class="info_AG"><i class="bi bi-circle-fill" class="bi bi-info-circle" style="color: #800040;"></i> Trabajo Terminal 2 y R.</li>
          <li class="info_AG"><i class="bi bi-circle-fill" class="bi bi-info-circle" style="color: red;"></i> No optimo.</li>
        </ul>
      </div>

      <!-- Formulario año y ciclo -->
      <div class="col-md-4">
        <form action="" method="get">
          <label for="anio">Año:</label>
          <input type="number" min="2000" max="3000" step="1" placeholder="Año" name="anio" value="<?php echo $_SESSION['anio'] ?>" required>

          <label for="ciclo">Ciclo:</label>
          <input type="number" min="1" max="2" step="1" placeholder="Ciclo" name="ciclo" value="<?php echo $_SESSION['ciclo'] ?>" required>

          <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-search"></i></button>
        </form>
      </div>

      <!-- Reporte excel -->
      <div class="col-md-4 text-center">
        <form action="/administrador/presentaciones" method="POST">
          <button type="submit" class="btn btn-outline-success btn-sm">Reporte <i class="bi bi-filetype-xlsx"></i></button>
        </form>
      </div>
    </div>

    <!-- Fechas de presentación TT1 -->
    <div class="row g-3" >
      <div class="col-md-12">
        <div class="container text-center">
          <hr>
          <b class="texto-formulario">Horarios de Presentación de TT1 [<?php echo count($_SESSION['tt1']) ?>]</b>
        </div>
        <div class="container">
            <div class="table-responsive">
            <?php            
              foreach ($_SESSION['fechastt1'] as $fecha) {              //  Recorrido de fechas tt1 <=> óptimos
                $presentacion = new Presentacion(); //  Objeto presentación

                echo '<table class="table table-bordered" id="tablatt">
                <thead>';
                
                $presentacion->setFecha($fecha);  //  Guardamos fecha

                echo '<tr><td align="center" colspan="10" class="table-active" style="background-color: #257ba6; color: white;">'.$fecha.'</td></tr>
                  <tr>
                      <th>Hora</th>
                      <th>Salón</th>
                      <th>Número</th>
                      <th>Titulo</th>
                      <th>Director</th>
                      <th>Director</th>
                      <th>Sinodal</th>
                      <th>Sinodal</th>
                      <th>Sinodal</th>
                      <th>Editar</th>
                  </tr>
                </thead>
                <tbody>';

                foreach ($_SESSION['tt1'] as $tt) {    //  Recorrido de óptimos
                  if($fecha == $tt->getFecha()){

                    $presentacion->setTT($tt);  //  Guardamos el tt

                    if($tt->getOptimo() == '0') echo "<tr style='color: red;'>";
                    else  echo "<tr>";
                    echo "  <td class='small'>".date('H:i',strtotime($tt->getHorario()))."</td>";
                    echo "  <td class='small'>".$tt->getSalon()."</td>";
                    echo "  <td class='small' align='center' style='white-space: nowrap;'>".$tt->getNumeroTT()."</td>";
                    echo "  <td class='small'>".$tt->getTituloTT()."</td>";
                    imprimirDocentes($tt->getGrupoDocente());
                    echo    "<td class='text-center'>
                                <button type='button' class='btn btn-sm btn-outline-primary' data-bs-toggle='modal' data-bs-target='#E".$tt->getNumeroTT()."'>
                                  <i class='bi bi-pencil-square'></i>
                                </button>
                              </td>";
                    echo "</tr>";

                    // ==> Modal Editar<==                                
                    echo '<div class="modal fade" id="E'.$tt->getNumeroTT().'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="E'.$tt->getNumeroTT().'Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="E'.$tt->getNumeroTT().'Label">Editar</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">                                    
                                        <form method="POST" action="/administrador/presentaciones">
                                            <div class="mb-3">
                                                <label for="update" class="col-form-label">Número TT:</label>
                                                <input type="text" class="form-control" id="update" name="update" value="'.$tt->getNumeroTT().'" readonly>
                                            </div>                                            
                                            <div class="mb-3">
                                                <label for="fecha" class="col-form-label">Fecha:</label>
                                                <input type="date" class="form-control" id="fecha" name="fecha" value="'.$tt->getFecha().'" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="salon" class="col-form-label">Salon:</label>
                                                <select id="salon" name="salon" class="form-select">';                                                
                                                  foreach ($_SESSION['pre_salones'] as $salon) {
                                                    if($salon == $tt->getSalon()) echo '<option selected>'.$salon.'</option>';
                                                    else echo '<option>'.$salon.'</option>';
                                                  } 
                            echo '              </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="horario" class="col-form-label">Horario:</label>
                                                <select id="horario" name="horario" class="form-select">';
                                                  foreach ($_SESSION['pre_horarios'] as $horario) {
                                                    if($horario->horario_inicio == $tt->getHorario()) echo '<option selected>'.$horario->horario_inicio.'</option>';
                                                    else echo '<option>'.$horario->horario_inicio.'</option>';
                                                  }
                            echo '              </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="optimo" class="col-form-label">Optimo:</label>
                                                <select id="optimo" name="optimo" class="form-select">';
                                                  if($tt->getOptimo() == '1') echo '<option selected>1</option> <option>0</option>';
                                                  else echo '<option>1</option> <option selected>0</option>';                                                  
                            echo '              </select>
                                                
                                            </div>                                                    
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                          </div>';
                  }
                }

                $presentaciones[] = $presentacion;  //  Guardamos presentación

                echo '</tbody>
                </table>';
              } // Fin Foreach de fechas tt1
            ?>
            </div>
        </div>
      </div>
    </div>

    <!-- Fechas de presentación TT2 y TTR -->
    <div class="row g-3" >
      <div class="col-md-12">
        <div class="container text-center">
          <hr>
          <b class="texto-formulario">Horarios de Presentación de TT2 y TTR [<?php echo count($_SESSION['tt2']) ?>]</b>
        </div>
        <div class="container">         
            <div class="table-responsive">
              <?php
                foreach ($_SESSION['fechastt2'] as $fecha) {              //  Recorrido de fechas tt2 <=> óptimos
                  $presentacion = new Presentacion(); //  Objeto presentacion

                  echo '<table class="table table-bordered" id="tablatt2">
                  <thead>';
                
                $presentacion->setFecha($fecha);  //  Guardamos fecha

                  echo '<tr><td align="center" colspan="10" class="table-active" style="background-color: #800040; color: white;">'.$fecha.'</td></tr>
                    <tr>
                        <th>Hora</th>
                        <th>Salón</th>
                        <th>Número</th>
                        <th>Titulo</th>
                        <th>Director</th>
                        <th>Director</th>
                        <th>Sinodal</th>
                        <th>Sinodal</th>
                        <th>Sinodal</th>
                        <th>Editar</th>
                    </tr>
                  </thead>
                  <tbody>';

                  foreach ($_SESSION['tt2'] as $tt) {    //  Recorrido de óptimos                    
                    if($fecha == $tt->getFecha()){

                      $presentacion->setTT($tt);  //  Guardamos el tt
                      
                      if($tt->getOptimo() == '0'){ echo "<tr style='color: red;'>"; }
                      else{ echo "<tr>"; }
                      echo    "<td class='small'>".date('H:i',strtotime($tt->getHorario()))."</td>";
                      echo    "<td class='small'>".$tt->getSalon()."</td>";
                      echo    "<td class='small' align='center' style='white-space: nowrap;'>".$tt->getNumeroTT()."</td>";
                      echo    "<td class='small'>".$tt->getTituloTT()."</td>";
                      imprimirDocentes($tt->getGrupoDocente());
                      echo    "<td class='text-center'>
                                <button type='button' class='btn btn-sm btn-outline-primary' data-bs-toggle='modal' data-bs-target='#E".$tt->getNumeroTT()."'>
                                  <i class='bi bi-pencil-square'></i>
                                </button>
                              </td>";
                      echo "</tr>";

                      // ==> Modal Editar<==                                
                    echo '<div class="modal fade" id="E'.$tt->getNumeroTT().'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="E'.$tt->getNumeroTT().'Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="E'.$tt->getNumeroTT().'Label">Editar</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">                                    
                                        <form method="POST" action="/administrador/presentaciones">
                                            <div class="mb-3">
                                                <label for="update" class="col-form-label">Número TT:</label>
                                                <input type="text" class="form-control" id="update" name="update" value="'.$tt->getNumeroTT().'" readonly>
                                            </div>                                            
                                            <div class="mb-3">
                                                <label for="fecha" class="col-form-label">Fecha:</label>
                                                <input type="date" class="form-control" id="fecha" name="fecha" value="'.$tt->getFecha().'" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="salon" class="col-form-label">Salon:</label>
                                                <select id="salon" name="salon" class="form-select">';                                                
                                                  foreach ($_SESSION['pre_salones'] as $salon) {
                                                    if($salon == $tt->getSalon()) echo '<option selected>'.$salon.'</option>';
                                                    else echo '<option>'.$salon.'</option>';
                                                  } 
                            echo '              </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="horario" class="col-form-label">Horario:</label>
                                                <select id="horario" name="horario" class="form-select">';
                                                  foreach ($_SESSION['pre_horarios'] as $horario) {                                                  
                                                    if($horario->horario_inicio == $tt->getHorario()) echo '<option selected>'.$horario->horario_inicio.'</option>';
                                                    else echo '<option>'.$horario->horario_inicio.'</option>';
                                                  }
                            echo '              </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="optimo" class="col-form-label">Optimo:</label>
                                                <select id="optimo" name="optimo" class="form-select">';
                                                  if($tt->getOptimo() == '1') echo '<option selected>1</option> <option>0</option>';
                                                  else echo '<option>1</option> <option selected>0</option>';                                                  
                            echo '              </select>
                                                
                                            </div>                                                    
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                          </div>';
                    }
                  }

                  $presentaciones[] = $presentacion;  //  Guardamos presentacion
  
                  echo '</tbody>
                  </table>';
                } // Fin Foreach de fechas tt2
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php

  /**
   * La SESSION presentaciones guarda en orden los tt1 para generar el reporte
   */
  $_SESSION['presentaciones'] = $presentaciones;

  }else{  //  Mensaje de error
?>

<div class="container seccion-error mt-4 mb-4 px-4">
      <div class="container text-center pt-2 pb-2">
        <h3 class="titulo-AG">ERROR</h3>
      </div>
      <div class="d-flex justify-content-center">
        <ul>
          <li class="aviso_AG"><?php echo $alertas['info']; ?></li>
        <ul>
      </div>
      <div class="text-center pb-2">
        <img src="/build/img/burro_error.jpg" alt="Error burro blanco">
      </div>
      <div class="text-center">
      <form action="" method="get">
          <label for="anio">Año:</label>
          <input type="number" min="2000" max="3000" step="1" placeholder="Año" name="anio" required>

          <label for="ciclo">Ciclo:</label>
          <input type="number" min="1" max="2" step="1" placeholder="Ciclo" name="ciclo" required>

          <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-search"></i></button>
        </form>
      </div>
    </div>



<?php 
  }
?>