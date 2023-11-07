<?php
    $administrador = "1";
    $rol = $_SESSION['rol'];
    $login = $_SESSION['login'];

    if ($rol != $administrador) {
        header('Location: /');
    }

    //  Variables
    $estados=array( 'activo' =>'<option selected>activo</option> <option>pausado</option> <option>finalizado</option> <option>baja</option>',
                    'pausado'=>'<option>activo</option> <option selected>pausado</option> <option>finalizado</option> <option>baja</option>',
                    'finalizado'=>'<option>activo</option> <option>pausado</option> <option selected>finalizado</option> <option>baja</option>',
                    'baja'=>'<option>activo</option> <option>pausado</option> <option>finalizado</option> <option selected>baja</option>');

    $tipos=array(   'TT1' => '<option selected>TT1</option> <option>TT2</option> <option>TTR</option>',
                    'TT2' => '<option>TT1</option> <option selected>TT2</option> <option>TTR</option>',
                    'TT2' => '<option>TT1</option> <option>TT2</option> <option selected>TTR</option>');
    
    $ciclos=array(  '1'=>'<option selected>1</option> <option>2</option>',
                    '2'=>'<option>1</option> <option selected>2</option>');

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
?>

<!-- Contenedor -->
<div class="container mt-4 mb-4" style="min-width: 90vw; margin-bottom: 40vh !important;">
    <div class="row g-3"  id="contenedor">
        <!-- Titulo -->
        <div class="col-md-12">
            <div class="container text-center">
                <b class="texto-formulario">Lista de Trabajos Terminales</b>
            </div>
        </div>

        <!-- Nuevo Registro -->
        <div class="col-md-2">
            <button type='button' class='btn btn-outline-success' style="width: 100%;" data-bs-toggle='modal' data-bs-target='#nuevo'>
                Nuevo Registro
                <!-- Icono -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                </svg>
            </button>
        </div>

        <!-- Botón recargar -->
        <div class="col-md-1 text-center">
            <form action="/administrador/crud_tt" method="post">
                <button type='submit' class='btn btn-outline-primary' style="width: 100%;">                
                    <!-- Icono -->
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </form>
        </div>

        <!-- Modal Nuevo -->
        <div class="modal fade" id="nuevo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="nuevoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoLabel">Nuevo Registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/administrador/crud_tt" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="create" class="col-form-label">Número de TT:</label>
                                <input type="text" class="form-control" name="create" id="create" required>
                            </div>
                            <div class="mb-3">
                                <label for="tt_titulo" class="col-form-label">Titulo:</label>
                                <input type="text" class="form-control" name="tt_titulo" id="tt_titulo" required>
                            </div>

                            <div class="mb-3">                                
                                <label for="tt_tipo" class="form-label">Tipo:</label>
                                <select class="form-select" id="tt_tipo" name = "tt_tipo" required>
                                    <option>TT1</option>
                                    <option>TT2</option>
                                    <option>TTR</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tt_anio" class="col-form-label">Año:</label>
                                <input type="text" class="form-control" name="tt_anio" id="tt_anio" required>
                            </div>

                            <div class="mb-3">
                                <label for = "tt_ciclo" class = "form-label">Ciclo:</label>
                                <select class="form-select" id = "tt_ciclo" name = "tt_ciclo" required>
                                    <option>1</option>
                                    <option>2</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="archivo" class="col-form-label">Archivo:</label>
                                <input type="file" class="form-control" name="archivo" id="archivo" required>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buscador local-->
        <div class="col-md-6">
            <div class="input-group rounded">
                <input name="buscador" id="buscador" type="search" class="form-control rounded" placeholder="Buscar en lista actual..." aria-label="Search" aria-describedby="search-addon"/>
            </div>
        </div>

        <!-- Buscador en la BD -->
        <div class="col-md-3">
            <form  method="POST" action="/administrador/crud_tt">
                <div class="input-group rounded">
                    <input type="search" class="form-control rounded" name="buscar" id="buscar" placeholder="Por número de TT en la bd..." aria-label="Search" aria-describedby="search-addon"/>
                    <button type='submit' class='btn btn-outline-primary'>
                        <!-- Icono -->
                        <i class="bi bi-search"></i>                        
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de Búsqueda -->
        <?php if(isset($_SESSION['TT'])){ ?>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm" id="tabla_busqueda">
                            <thead class="table-info">
                                <tr>
                                    <th>Número</th>
                                    <th>Titulo</th>
                                    <th>Tipo</th>
                                    <th>Año</th>
                                    <th>Ciclo</th>
                                    <th>Estado</th>
                                    <th>Archivo</th>                                                                
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach ($_SESSION['TT'] as $valor) {               
                                    echo'<tr id="tr'.$valor->getNumeroTT().'">';
                                        echo "<td class='small articulo' align='center' style='white-space: nowrap;'>".$valor->getNumeroTT()."</td>";
                                        echo "<td class='small articulo' align='justify'>".$valor->getTituloTT()."</td>";
                                        echo "<td class='small articulo' align='center'>".$valor->getTipoTT()."</td>";
                                        echo "<td class='small articulo' align='center'>".$valor->getAnio()."</td>";
                                        echo "<td class='small articulo' align='center'>".$valor->getCiclo()."</td>";
                                        echo "<td class='small articulo' align='center'>".$valor->getStatus()."</td>";
                                        echo "<td class='text-center'> 
                                                <a href='/".$valor->getArchivo()."' class = 'btn-outline-danger' target='_blank'>
                                                    <button type='button' class='btn btn-sm btn-danger'>
                                                        <!-- Icono pdf -->
                                                        <i class='bi bi-filetype-pdf'></i>                                                        
                                                    </button>
                                                </a>
                                            </td>";

                                        echo "<td class='text-center'><button type='button' class='btn btn-sm btn-outline-primary' data-bs-toggle='modal' data-bs-target='#BE".$valor->getNumeroTT()."'>
                                                    <!-- Icono editar -->
                                                    <i class='bi bi-pencil-square'></i>                                                    
                                                </button>
                                            </td>";
                                        echo "<td class='text-center'><button type='button' class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#BD".$valor->getNumeroTT()."'>
                                                    <!-- Icono basura -->
                                                    <i class='bi bi-trash'></i>                                                    
                                                </button>
                                          </td>";
                                    echo "</tr>";

                                    // ==> Modal Editar<==
                                    echo '<div class="modal fade" id="BE'.$valor->getNumeroTT().'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="BE'.$valor->getNumeroTT().'Label" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="BE'.$valor->getNumeroTT().'Label">Editar</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/administrador/crud_tt" enctype="multipart/form-data">
                                                            <div class="mb-3">
                                                                <label for="update" class="col-form-label">Numero:</label>
                                                                <input type="text" class="form-control" id="update" name="update" value="'.$valor->getNumeroTT().'" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="tt_titulo" class="col-form-label">Titulo:</label>
                                                                <input type="text" class="form-control" name="tt_titulo" id="tt_titulo" value="'.$valor->getTituloTT().'">
                                                            </div>
                                                            <div class="mb-3">                                
                                                                <label for="tt_tipo" class="form-label">Tipo:</label>
                                                                <select class="form-select" id="tt_tipo" name = "tt_tipo" required>';
                                                                    echo $tipos[$valor->getTipoTT()];
                                            echo '              </select>
                                                            </div>                                                            
                                                            <div class="mb-3">
                                                                <label for = "idStatus" class = "form-label">Estado:</label>
                                                                <select class="form-select" id = "idStatus" name = "idStatus" required>';
                                                                    echo $estados[$valor->getStatus()];
                                            echo '              </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="archivo" class="col-form-label">Archivo:</label>
                                                                <input type="file" class="form-control" name="archivo" id="archivo">
                                                            </div>

                                                            <div class="mb-3">
                                                                <div>
                                                                    <strong> [Estudiantes] </strong>
                                                                </div>
                                                                <ul>';
                                                                    foreach ($valor->getEstudiantes() as $estudiante) { echo '<li class="persona-editar">'.$estudiante.'</li>'; }
                                            echo '              </ul>
                                                            </div>

                                                            <div class="mb-3">
                                                                <div> <strong> [Directores] </strong> </div>
                                                                <ul>';
                                                                    foreach ($valor->getGrupoDocente() as $nombre => $tipo) {
                                                                        if($tipo == 'director') echo '<li class="persona-editar">'.$nombre.'</li>';
                                                                    }
                                            echo '              </ul>
                                                                
                                                                <div> <strong> [Sinodales] </strong> </div>
                                                                <ul>';
                                                                    foreach ($valor->getGrupoDocente() as $nombre => $tipo) {
                                                                        if($tipo == 'sinodal') echo '<li class="persona-editar">'.$nombre.'</li>';
                                                                    }
                                            echo '              </ul>

                                                                <div> <strong> [Seguimiento] </strong> </div>
                                                                <ul>';
                                                                    foreach ($valor->getGrupoDocente() as $nombre => $tipo) {
                                                                        if($tipo == 'seguimiento') echo '<li class="persona-editar">'.$nombre.'</li>';
                                                                    }
                                            echo '              </ul>
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
                                    // ==> Modal Eliminar<==                        
                        echo '      <div class="modal fade" id="BD'.$valor->getNumeroTT().'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="BD'.$valor->getNumeroTT().'Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="BD'.$valor->getNumeroTT().'Label">Eliminar</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="/administrador/crud_tt">

                                                        <input type="text" class="filtro" id="delete" name="delete" value="'.$valor->getNumeroTT().'">
                                                        <div class="mb-3">
                                                            <label class="col-form-label"> <h3>¿Quieres Eliminar el TT '.$valor->getNumeroTT().'?</h3> </label>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-success">Eliminar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';               
                            }
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
        <?php unset($_SESSION['TT']);   }     ?>

        <!-- Tabla de Resultados -->
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm" id="tabla_resultados">
                    <thead class="table-success table-striped" >
                        <tr>
                            <th>Número</th>
                            <th>Titulo</th>
                            <th>Tipo</th>
                            <th>Año</th>
                            <th>Ciclo</th>
                            <th>Estado</th>
                            <th>Archivo</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($_SESSION['listatts'] as $valor) {                                
                                echo '<tr id="tr'.$valor->getNumeroTT().'">';
                                    echo "<td class='small articulo' align='center' style='white-space: nowrap;'>".$valor->getNumeroTT()."</td>";
                                    echo "<td class='small articulo' align='justify'>".$valor->getTituloTT()."</td>";
                                    echo "<td class='small articulo' align='center'>".$valor->getTipoTT()."</td>";
                                    echo "<td class='small articulo' align='center'>".$valor->getAnio()."</td>";
                                    echo "<td class='small articulo' align='center'>".$valor->getCiclo()."</td>";
                                    echo "<td class='small articulo' align='center'>".$valor->getStatus()."</td>";                                    
                                    echo "<td class='text-center'>
                                            <a href='/".$valor->getArchivo()."' class = 'btn-outline-danger' target='_blank'>
                                                <button type='button' class='btn btn-sm btn-danger'>
                                                    <!-- Icono pdf -->
                                                    <i class='bi bi-filetype-pdf'></i>                                                    
                                                </button>
                                            </a>
                                         </td>";

                                    echo "<td class='text-center'><button type='button' class='btn btn-sm btn-outline-primary' data-bs-toggle='modal' data-bs-target='#E".$valor->getNumeroTT()."'>
                                                <!-- Icono editar -->
                                                <i class='bi bi-pencil-square'></i>                                                
                                              </button>
                                          </td>";
                                    echo "<td class='text-center'><button type='button' class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#D".$valor->getNumeroTT()."'>
                                                    <!-- Icono basura -->
                                                    <i class='bi bi-trash'></i>                                                    
                                                </button>
                                          </td>";
                                echo "</tr>";

                                // ==> Modal Editar<==
                                echo '      <div class="modal fade" id="E'.$valor->getNumeroTT().'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="E'.$valor->getNumeroTT().'Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="E'.$valor->getNumeroTT().'Label">Editar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="/administrador/crud_tt" enctype="multipart/form-data">
                                                <div class="mb-3">
                                                    <label for="update" class="col-form-label">Numero:</label>
                                                    <input type="text" class="form-control" id="update" name="update" value="'.$valor->getNumeroTT().'" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tt_titulo" class="col-form-label">Titulo:</label>
                                                    <input type="text" class="form-control" name="tt_titulo" id="tt_titulo" value="'.$valor->getTituloTT().'">
                                                </div>
                                                <div class="mb-3">                              
                                                    <label for="tt_tipo" class="form-label">Tipo:</label>
                                                    <select class="form-select" id="tt_tipo" name = "tt_tipo" required>';
                                                        echo $tipos[$valor->getTipoTT()];
                                            echo '  </select>
                                                </div>                                                
                                                <div class="mb-3">
                                                    <label for = "idStatus" class = "form-label">Estado:</label>
                                                    <select class="form-select" id = "idStatus" name = "idStatus" required>';
                                                        echo $estados[$valor->getStatus()];
                                            echo '  </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="archivo" class="col-form-label">Archivo:</label>
                                                    <input type="file" class="form-control" name="archivo" id="archivo">
                                                </div>

                                                <div class="mb-3">
                                                    <div>
                                                        <strong> [Estudiantes] </strong>
                                                    </div>
                                                    <ul>';
                                                        foreach ($valor->getEstudiantes() as $estudiante) { echo '<li class="persona-editar">'.$estudiante.'</li>'; }
                                echo '              </ul>
                                                </div>

                                                <div class="mb-3">
                                                    <div> <strong> [Directores] </strong> </div>
                                                    <ul>';
                                                        foreach ($valor->getGrupoDocente() as $nombre => $tipo) {
                                                            if($tipo == 'director') echo '<li class="persona-editar">'.$nombre.'</li>';
                                                        }
                                echo '              </ul>
                                                    
                                                    <div> <strong> [Sinodales] </strong> </div>
                                                    <ul>';
                                                        foreach ($valor->getGrupoDocente() as $nombre => $tipo) {
                                                            if($tipo == 'sinodal') echo '<li class="persona-editar">'.$nombre.'</li>';
                                                        }
                                echo '              </ul>

                                                    <div> <strong> [Seguimiento] </strong> </div>
                                                    <ul>';
                                                        foreach ($valor->getGrupoDocente() as $nombre => $tipo) {
                                                            if($tipo == 'seguimiento') echo '<li class="persona-editar">'.$nombre.'</li>';
                                                        }
                                echo '              </ul>
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
                            // ==> Modal Eliminar<==
                        echo '  <div class="modal fade" id="D'.$valor->getNumeroTT().'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="D'.$valor->getNumeroTT().'Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="D'.$valor->getNumeroTT().'Label">Eliminar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="/administrador/crud_tt">

                                                    <input type="text" class="filtro" id="delete" name="delete" value="'.$valor->getNumeroTT().'">
                                                    <div class="mb-3">
                                                        <label class="col-form-label"> <h3>¿Quieres Eliminar el TT '.$valor->getNumeroTT().'?</h3> </label>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-success">Eliminar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';                                
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
</div>

<script>
    //  Variables Buscador: Obtener la tabla y sus filas
    var table = document.getElementById('tabla_resultados');    //  Objeto tabla
    var rows = table.getElementsByTagName('tr');    //  Filas
    var tts = [];                                   //  Arreglo para guardar tts

    // Recorrer las filas y obtener su contenido en una lista
    if(rows.length > 1){
        for(var i = 1; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName('td');
            var tt = [];
            var datos = "";

            for (var j = 0; j < cells.length-2; j++) {  //  Se omiten las columnas de botones
                if(j == 0) tt['tt_numero'] = cells[j].innerHTML;
                else    datos = datos + " " +cells[j].innerHTML;
            }            
            tt['datos'] = tt['tt_numero']+datos;

            tts.push(tt);
        }
    }

    //  ===> Eventos <===
    document.addEventListener("keyup", e =>{
        if(e.target.matches("#buscador")){
            tts.forEach(tt =>{
                tt['datos'].toLowerCase().includes(e.target.value.toLowerCase())
                ?document.querySelector("#tr"+tt['tt_numero']).classList.remove("filtro")
                :document.querySelector("#tr"+tt['tt_numero']).classList.add("filtro");
            })
        }
    })
</script>