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
    <h2 class="text-center">Horarios de Presentación</h2>
</div>

<div class="container mb-5" <?php if($horariosP == null) { ?> style="margin-bottom: 50vh !important" <?php } else { ?> style="margin-bottom: 40vh !important" <?php } ?> >
    <table class="table table-striped text-center">
        <thead>
            <tr>
                <th>Número TT</th>
                <th>Docente</th>
                <th>Salón</th>
                <th>Fecha</th>
                <th>Horario</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if($horariosP == null) {
            ?>
                    <tr>
                        <td colspan="5"><h5 class="text-center">No existen datos para mostrar</h5></td>
                    </tr>
            <?php
                } else {

                foreach( $horariosP as $horarioP) {
            ?>
            <tr>
                <td><?php echo $horarioP->numeroTT ?></td>
                <td><?php echo $horarioP->rolDocente ?></td>
                <td><?php echo $horarioP->salon ?></td>
                <td><?php echo $horarioP->fecha ?></td>
                <td><?php echo $horarioP->horarioHora ?></td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
