<?php
  $administrador = "1";
  $rol = $_SESSION['rol'];
  $login = $_SESSION['login'];

  if ($rol != $administrador) {
    header('Location: /');
  }

  //  Mensaje emergente
  if(isset($_SESSION['msg'])){ ?>
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
  }

  if(!isset($alertas['msgtt1']) && !isset($alertas['msgtt2']) && !isset($alertas['msg'])){  //Entra si: no encuentra errores por datos faltantes
    // Trabajos Terminales 1
    $optimostt1 = $_SESSION['presentaciones_tt1']->getOptimos();     //  Obtenemos Población de óptimos
    $noOptimostt1 = $_SESSION['presentaciones_tt1']->getNoOptimos(); // Obtenemos Población de no óptimos

    // Trabajos Terminales 2
    $optimostt2 = $_SESSION['presentaciones_tt2']->getOptimos();     //  Obtenemos Población de óptimos
    $noOptimostt2 = $_SESSION['presentaciones_tt2']->getNoOptimos(); // Obtenemos Población de no óptimos

?>
    <!-- Resultados del AG -->
    <div class="container mt-4 mb-4" style="min-width: 90vw;">
      <!-- Fechas de presentación TT1 -->
      <div class="row g-3" >
        <div class="col-md-12">
          <div class="container text-center">
            <hr>
            <b class="texto-formulario">Horarios de Presentación de TT1</b>
          </div>
          <div class="container">
            <!-- Componente accordion -->
            <div class="accordion accordion-flush" id="accordionFlushExample">
              <!-- Trabajos Terminales 1 [Óptimos] -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                      Trabajos Terminales 1 [Óptimos = <?php echo count($optimostt1); ?> ] 
                  </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    <div class="table-responsive">
                    <?php
                      foreach ($_SESSION['listadias_tt1'] as $fecha) {              //  Recorrido de fechas tt1 <=> óptimos
                        echo '<table class="table table-bordered table-striped" >
                        <thead>';
                        echo '<tr><td align="center" colspan="9" class="table-active">'.$fecha.'</td></tr>
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
                          </tr>
                        </thead>
                        <tbody>';

                        foreach ($optimostt1 as $cromosoma) {    //  Recorrido de óptimos
                          if($fecha == $cromosoma->getFecha()){
                            echo "<tr>";
                            echo    "<td>".$cromosoma->getHorario()."</td>";
                            echo    "<td>".$cromosoma->getSalon()."</td>";
                            echo    "<td>".$cromosoma->getNumeroTT()."</td>";
                            echo    "<td>".$cromosoma->getTitulo()."</td>";
                            imprimirDocentes($cromosoma->getGrupoDocente());
                            echo "</tr>";
                          }
                        }

                        echo '</tbody>
                        </table>';
                      } // Fin Foreach de fechas tt1
                    ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Trabajos Terminales 1 [No Óptimos] -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingTwo">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                      Trabajos Terminales 1 [No Óptimos = <?php echo count($noOptimostt1); ?> ]
                  </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    <div class="table-responsive">
                    <?php                  
                      if(count($noOptimostt1) > 0){
                        foreach ($_SESSION['listadias_tt1'] as $fecha) {  //  Recorrido de fechas tt1 <=> no óptimos
                          echo '<table class="table table-danger table-bordered" >
                          <thead>';
                          echo '<tr><td align="center" colspan="9" class="table-active">'.$fecha.'</td></tr>
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
                            </tr>
                          </thead>
                          <tbody>';

                          foreach ($noOptimostt1 as $cromosoma) {    //  Recorrido de óptimos
                            if($fecha == $cromosoma->getFecha()){
                              echo "<tr>";
                              echo    "<td>".$cromosoma->getHorario()."</td>";
                              echo    "<td>".$cromosoma->getSalon()."</td>";
                              echo    "<td>".$cromosoma->getNumeroTT()."</td>";
                              echo    "<td>".$cromosoma->getTitulo()."</td>";
                              imprimirDocentes($cromosoma->getGrupoDocente());
                              echo "</tr>";
                            }
                          }

                          echo '</tbody>
                          </table>';
                        } // Fin Foreach de fechas tt1
                      }else{
                        echo '<p> Todos los horarios son óptimos </p>';
                      }


                    ?>
                    </div>
                  </div>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>

      <!-- Fechas de presentacion TT2 y TTR -->
      <div class="row g-3" >
        <div class="col-md-12">
          <div class="container text-center">
            <hr>
            <b class="texto-formulario">Horarios de Presentacion de TT2 y TTR</b>
          </div>
          <div class="container">
            <!-- Componente accordion -->
            <div class="accordion accordion-flush" id="accordionFlushExample">
              <!-- Trabajos Terminales 2 [Óptimos] -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingThree">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                      Trabajos Terminales 2 y R [Óptimos = <?php echo count($optimostt2); ?> ] 
                  </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    <div class="table-responsive">
                    <?php
                      foreach ($_SESSION['listadias_tt2yr'] as $fecha) {              //  Recorrido de fechas tt2 <=> óptimos
                        echo '<table class="table table-bordered table-striped" >
                        <thead>';
                        echo '<tr><td align="center" colspan="9" class="table-active">'.$fecha.'</td></tr>
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
                          </tr>
                        </thead>
                        <tbody>';

                        foreach ($optimostt2 as $cromosoma) {    //  Recorrido de óptimos
                          if($fecha == $cromosoma->getFecha()){
                            echo "<tr>";
                            echo    "<td>".$cromosoma->getHorario()."</td>";
                            echo    "<td>".$cromosoma->getSalon()."</td>";
                            echo    "<td>".$cromosoma->getNumeroTT()."</td>";
                            echo    "<td>".$cromosoma->getTitulo()."</td>";
                            imprimirDocentes($cromosoma->getGrupoDocente());
                            echo "</tr>";
                          }
                        }

                        echo '</tbody>
                        </table>';
                      } // Fin Foreach de fechas tt2
                    ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Trabajos Terminales 2 [No Óptimos] -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFour">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                      Trabajos Terminales 2 y R [No Óptimos = <?php echo count($noOptimostt2); ?> ]
                  </button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    <div class="table-responsive">
                    <?php                  
                      if(count($noOptimostt2) > 0){
                        foreach ($_SESSION['listadias_tt2yr'] as $fecha) {  //  Recorrido de fechas tt2 <=> no óptimos
                          echo '<table class="table table-danger table-bordered" >
                          <thead>';
                          echo '<tr><td align="center" colspan="9" class="table-active">'.$fecha.'</td></tr>
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
                            </tr>
                          </thead>
                          <tbody>';

                          foreach ($noOptimostt2 as $cromosoma) {    //  Recorrido de óptimos
                            if($fecha == $cromosoma->getFecha()){
                              echo "<tr>";
                              echo    "<td>".$cromosoma->getHorario()."</td>";
                              echo    "<td>".$cromosoma->getSalon()."</td>";
                              echo    "<td>".$cromosoma->getNumeroTT()."</td>";
                              echo    "<td>".$cromosoma->getTitulo()."</td>";
                              imprimirDocentes($cromosoma->getGrupoDocente());
                              echo "</tr>";
                            }
                          }

                          echo '</tbody>
                          </table>';
                        } // Fin Foreach de fechas tt2
                      }else{
                        echo '<p> Todos los horarios son óptimos </p>';
                      }


                    ?>
                    </div>
                  </div>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>  
    
      <!-- Botones -->
      <div class="row g-3 mt-2" >
        <div class="col-md-5 text-center">
          <div class="btn-group" style="width: 100%;" role="group" aria-label="Basic mixed styles example">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalGenerar">Generar de nuevo</button>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalGuardar">Guardar</button>             
          </div>
        </div>
        <!-- Info -->
        <div class="col-md-7">
          <ul>            
            <li class="info_AG"><i class="bi bi-info-circle"></i> ¡Cuidado! Al recargar o dar flecha hacia atras se ejecutara de nuevo el algoritmo.</li>
            <li class="info_AG"><i class="bi bi-info-circle"></i> Para modificar los horarios no óptimos da clic <a href="/administrador/presentaciones">aqui</a> </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Modal generar de nuevo -->
    <div class="modal fade" id="modalGenerar" tabindex="-1" aria-labelledby="modalGenerarLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="titulo-modal modal-title"> ¿Quieres ejecutar de nuevo el algoritmo? </h3>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">   
              <div class="d-flex justify-content-center">
                <ul>
                  <li class="info_AG"><i class="bi bi-info-circle"></i> Al ejecutar de nuevo el algoritmo se podrian obtener resultados más eficientes o menos eficientes</li>
                <ul>
              </div>           
              <img src="/build/img/burro.jpg" alt="Burro blanco">';
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
              <button type="button" onclick="window.location.href = '/administrador/resultados_AG';" class="btn btn-primary">Ejecutar</button>
            </div>
        </div>
      </div>
    </div>

    <!-- Modal guardar -->
    <div class="modal fade" id="modalGuardar" tabindex="-1" aria-labelledby="modalGuardarLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="titulo-modal modal-title"> ¿En verdad quieres guardar los resultados? </h3>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            
              <div class="modal-body text-center">                
                <div class="d-flex justify-content-center">
                  <ul>
                    <li class="info_AG"><i class="bi bi-info-circle"></i> Si ya tienes registrados horarios de presentacion para este ciclo se sustituiran</li>
                  <ul>
                </div>
                <img src="/build/img/burro.jpg" alt="Burro blanco">';
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="/administrador/resultados_AG">
                  <input type="text" class="filtro" name="guardar">
                  <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
              </div>            
        </div>
      </div>
    </div>
<?php
  }elseif(isset($alertas['msgtt1']) || isset($alertas['msgtt2'])){
    echo '<div class="container seccion-error mt-4 mb-4 px-4">';
    echo '  <div class="container text-center pt-2 pb-2">';
    echo '    <h3 class="titulo-AG">ERROR: SE INGRESARON MAL LOS DATOS</h3>';
    echo '  </div>';
    echo '  <div class="d-flex justify-content-center">
              <ul>';
    if(isset($alertas['msgtt1'])) echo '<li class="aviso_AG">'.$alertas['msgtt1'].'</li>';
    if(isset($alertas['msgtt2'])) echo '<li class="aviso_AG">'.$alertas['msgtt2'].'</li>';
    echo '    <ul>
            </div>';
    echo '  <div class="text-center pb-2">';
    echo '    <img src="/build/img/burro_error.jpg" alt="Error burro blanco">';
    echo '  </div>';
    echo '  <div class="text-center">';
    echo '    <a href="/administrador/formulario1_AG"">
                <button type="button" class="btn btn-outline-danger">Reintentar</button>
              </a>';
    echo '  </div>';
    echo '</div>';
  }else{
    echo '<div class="container seccion-clara mt-4 mb-4 px-4">';
    echo '  <div class="container text-center pt-2 pb-2">';
    echo '    <h3 class="titulo-AG">Intente meter los datos de nuevo</h3>';
    echo '  </div>';    
    echo '  <div class="text-center pb-2">';
    echo '    <img src="/build/img/burro.jpg" alt="Burro blanco">';
    echo '  </div>';
    echo '  <div class="text-center">';
    echo '    <a href="/administrador/formulario1_AG"">
                <button type="button" class="btn btn-outline-danger">Reintentar</button>
              </a>';
    echo '  </div>';
    echo '</div>';
  }
?>