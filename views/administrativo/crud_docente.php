<?php

  $administrador = "1";
  $rol = $_SESSION['rol'];
  $login = $_SESSION['login'];

  if ($rol != $administrador) {
    header('Location: /');
  }

?>
  <?php if(isset($_SESSION['msg']) && isset($_SESSION['estado']) && $_SESSION['estado'] == 'exitoso'){ ?>
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
    
  }elseif(isset($_SESSION['msg'])){ ?>
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
                <b class="texto-formulario">Lista de Docentes</b>
            </div>
        </div>

        <!-- Nuevo Registro -->
        <div class="col-md-2">
            <button type='button' class='btn btn-outline-success' style="width: 100%;" data-bs-toggle='modal' data-bs-target='#nuevo'>
                Nuevo Registro
                <!-- Icono -->
                <i class="bi bi-plus-circle-fill"></i>
            </button>
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
                        <form method="POST" action="/administrador/crud_docente">
                            <div class="mb-3">
                                <label for="create" class="col-form-label">idDocente:</label>
                                <input type="text" class="form-control" name="create" id="create" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="col-form-label">Nombre:</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="APaterno" class="col-form-label">Apellido Paterno:</label>
                                <input type="text" class="form-control" name="APaterno" id="APaterno" required>
                            </div>
                            <div class="mb-3">
                                <label for="AMaterno" class="col-form-label">Apellido Materno:</label>
                                <input type="text" class="form-control" name="AMaterno" id="AMaterno" required>
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="col-form-label">Correo:</label>
                                <input type="text" class="form-control" name="correo" id="correo" required>
                            </div>
                            <div class="mb-3">
                                <label for="escuela" class="col-form-label">escuela:</label>
                                <input type="text" class="form-control" name="escuela" id="escuela" required>
                            </div>
                            <div class="mb-3">
                                <label for="area" class="col-form-label">area:</label>
                                <input type="text" class="form-control" name="area" id="area" required>
                            </div>
                            <div class="mb-3">
                                <label for="horaInicio" class="col-form-label">Horario - Inicio:</label>
                                <input type="time" step="3600" class="form-control" name="horaInicio" id="horaInicio" required>
                            </div>
                            <div class="mb-3">
                                <label for="horaFin" class="col-form-label">Horario - Fin:</label>
                                <input type="time" step="3600" class="form-control" name="horaFin" id="horaFin" required>
                            </div>                            
                            <div class="mb-3">
                                <label for="docente_tipo" class="form-label">Tipo</label>
                                <select id="docente_tipo" name="docente_tipo" class="form-select">
                                    <option selected>interno</option>
                                    <option>externo</option>
                                </select>
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

        <!-- Botón recargar -->
        <div class="col-md-1 text-center">
            <form action="/administrador/crud_docente" method="post">
                <button type='submit' class='btn btn-outline-primary' style="width: 100%;">                
                    <!-- Icono -->
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </form>
        </div>

        <!-- Buscador Local -->
        <div class="col-md-6">
            <div class="input-group rounded">
                <input name="buscador" id="buscador" type="search" class="form-control rounded" placeholder="Buscar..." aria-label="Search" aria-describedby="search-addon"/>
            </div>
        </div>

        <!-- Buscador en la BD -->
        <div class="col-md-3">
            <form  method="POST" action="/administrador/crud_docente">
                <div class="input-group rounded">
                    <input type="search" class="form-control rounded" name="search" id="search" placeholder="Por idDocente en la bd..." aria-label="Search" aria-describedby="search-addon"/>
                    <button type='submit' class='btn btn-outline-primary'>
                        <!-- Icono -->
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de Búsqueda -->
        <?php if(isset($_SESSION['docente'])){ ?>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm" >
                        <thead class="table-info">
                            <tr>
                                <th>idDocente</th>
                                <th>Nombre</th>
                                <th>APaterno</th>
                                <th>AMaterno</th>
                                <th>Correo</th>
                                <th>Escuela</th>
                                <th>Area</th>
                                <th>HorarioI</th>
                                <th>HorarioF</th>
                                <th>tipo</th>
                                <th>Habilitar</th>
                            </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($_SESSION['docente'] as $valor) {
                            echo '<tr id="tr'.$valor->idDocente.'">';
                            echo "<td class='small articulo'>".$valor->idDocente."</td>";
                            echo "<td class='small articulo'>".$valor->nombre."</td>";
                            echo "<td class='small articulo'>".$valor->apellidoPaterno."</td>";
                            echo "<td class='small articulo'>".$valor->apellidoMaterno."</td>";
                            echo "<td class='small articulo'>".$valor->correo."</td>";
                            echo "<td class='small articulo'>".$valor->docente_escuela."</td>";
                            echo "<td class='small articulo'>".$valor->docente_area."</td>";
                            echo "<td class='small articulo'>".$valor->docente_horaInicio."</td>";
                            echo "<td class='small articulo'>".$valor->docente_horaFin."</td>";
                            echo "<td class='small articulo'>".$valor->docente_tipo."</td>";
                            echo "  <td class='text-center'><button type='button' class='btn btn-sm btn-outline-primary' data-bs-toggle='modal' data-bs-target='#H".$valor->idDocente."'>
                                                <!-- Icono editar -->
                                                <i class='bi bi-check'></i>                                                
                                            </button>
                                    </td>";
                        echo "</tr>";

                            // ==> Modal Habilitar<==
                echo '      <div class="modal fade" id="H'.$valor->idDocente.'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="H'.$valor->idDocente.'Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="H'.$valor->idDocente.'Label">Habilitar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="/administrador/crud_docente">
                                                <input type="text" class="filtro" id="enable" name="enable" value="'.$valor->idPersona.'">
                                                
                                                <div class="mb-3">
                                                    <label class="col-form-label"> <h3>¿Quieres habilitar al docente '.$valor->idDocente.'?</h3> </label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">Habilitar</button>
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
        <?php unset($_SESSION['docente']);   }     ?>

        <!-- Tabla de Resultados -->
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm" >
                    <thead class="table-success table-striped" >
                        <tr>
                            <th>idDocente</th>
                            <th>Nombre</th>
                            <th>APaterno</th>
                            <th>AMaterno</th>
                            <th>Correo</th>
                            <th>Escuela</th>
                            <th>Area</th>
                            <th>HorarioI</th>
                            <th>HorarioF</th>
                            <th>tipo</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($_SESSION['docentes'] as $valor) {
                                echo '<tr id="tr'.$valor->idDocente.'">';
                                    echo "<td class='small articulo'>".$valor->idDocente."</td>";
                                    echo "<td class='small articulo'>".$valor->nombre."</td>";
                                    echo "<td class='small articulo'>".$valor->apellidoPaterno."</td>";
                                    echo "<td class='small articulo'>".$valor->apellidoMaterno."</td>";
                                    echo "<td class='small articulo'>".$valor->correo."</td>";
                                    echo "<td class='small articulo'>".$valor->docente_escuela."</td>";
                                    echo "<td class='small articulo'>".$valor->docente_area."</td>";
                                    echo "<td class='small articulo'>".$valor->docente_horaInicio."</td>";
                                    echo "<td class='small articulo'>".$valor->docente_horaFin."</td>";
                                    echo "<td class='small articulo'>".$valor->docente_tipo."</td>";
                                    echo "<td class='text-center'><button type='button' class='btn btn-sm btn-outline-primary' data-bs-toggle='modal' data-bs-target='#E".$valor->idDocente."'>
                                                <!-- Icono editar -->
                                                <i class='bi bi-pencil-square'></i>
                                              </button>
                                          </td>";
                                    echo "<td class='text-center'><button type='button' class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#D".$valor->idDocente."'>
                                                    <!-- Icono basura -->
                                                    <i class='bi bi-trash'></i>
                                                </button>
                                          </td>";
                                echo "</tr>";

                                // ==> Modal Editar<==                                
                    echo '      <div class="modal fade" id="E'.$valor->idDocente.'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="E'.$valor->idDocente.'Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="E'.$valor->idDocente.'Label">Editar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="/administrador/crud_docente">
                                                    <input type="text" class="filtro" id="idPersona" name="idPersona" value="'.$valor->idPersona.'">
                                                    <div class="mb-3">
                                                        <label for="update" class="col-form-label">idDocente:</label>
                                                        <input type="text" class="form-control" id="update" name="update" value="'.$valor->idDocente.'" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nombre" class="col-form-label">Nombre:</label>
                                                        <input type="text" class="form-control" id="nombre" name="nombre" value="'.$valor->nombre.'">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="APaterno" class="col-form-label">Apellido Paterno:</label>
                                                        <input type="text" class="form-control" id="APaterno" name="APaterno" value="'.$valor->apellidoPaterno.'">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="AMaterno" class="col-form-label">Apellido Materno:</label>
                                                        <input type="text" class="form-control" id="AMaterno" name="AMaterno" value="'.$valor->apellidoMaterno.'">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="correo" class="col-form-label">Correo:</label>
                                                        <input type="text" class="form-control" id="correo" name="correo" value="'.$valor->correo.'">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="escuela" class="col-form-label">escuela:</label>
                                                        <input type="text" class="form-control" id="escuela" name="escuela" value="'.$valor->docente_escuela.'">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="area" class="col-form-label">area:</label>
                                                        <input type="text" class="form-control" id="area" name="area" value="'.$valor->docente_area.'">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="horaInicio" class="col-form-label">Horario - Inicio:</label>
                                                        <input type="time" step="3600" class="form-control" id="horaInicio" name="horaInicio" value="'.$valor->docente_horaInicio.'">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="horaFin" class="col-form-label">Horario - Fin:</label>
                                                        <input type="time" step="3600" class="form-control" id="horaFin" name="horaFin" value="'.$valor->docente_horaFin.'">
                                                    </div>                                                    
                                                    <div class="mb-3">
                                                        <label for="docente_tipo" class="form-label">Tipo</label>
                                                        <select id="docente_tipo" name="docente_tipo" class="form-select">';
                                                            if($valor->docente_tipo == 'interno'){
                                                                echo '  <option selected>'.$valor->docente_tipo.'</option>
                                                                        <option>externo</option>';
                                                            }else{
                                                                echo '  <option selected>'.$valor->docente_tipo.'</option>
                                                                        <option>interno</option>';
                                                            }                                                     
                    echo '                              </select>
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
                        echo '  <div class="modal fade" id="D'.$valor->idDocente.'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="D'.$valor->idDocente.'Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="D'.$valor->idDocente.'Label">Eliminar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">                                                
                                                <form method="POST" action="/administrador/crud_docente">
                                                    <input type="text" class="filtro" id="delete" name="delete" value="'.$valor->idPersona.'">

                                                    <div class="mb-3">
                                                        <label class="col-form-label"> <h3>¿Quieres Eliminar al Docente '.$valor->idDocente.'?</h3> </label>
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
    var table = document.querySelector('table');    //  Objeto tabla
    var rows = table.getElementsByTagName('tr');    //  Filas
    var docentes = [];                              //  Arreglo para guardar docentes

    // Recorrer las filas y obtener su contenido en una lista
    if(rows.length > 1){
        for(var i = 1; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName('td');
            var docente = [];
            var datos = "";

            for (var j = 0; j < cells.length-2; j++) {  //  Se omiten las columnas de botones
                if(j == 0) docente['idDocente'] = cells[j].innerHTML;
                else    datos = datos + " " +cells[j].innerHTML;
            }            
            docente['datos'] = docente['idDocente']+datos;

            docentes.push(docente);
        }
    }

    //  ===> Eventos <===
    document.addEventListener("keyup", e =>{
        if(e.target.matches("#buscador")){
            docentes.forEach(docente =>{
                docente['datos'].toLowerCase().includes(e.target.value.toLowerCase())
                ?document.querySelector("#tr"+docente['idDocente']).classList.remove("filtro")
                :document.querySelector("#tr"+docente['idDocente']).classList.add("filtro");
            })
        }
    })
</script>