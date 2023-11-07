<?php 

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    $estudiante = "3";
    $rol = $_SESSION['rol'];
    $login = $_SESSION['login'];

    if($rol != $estudiante) {
        header('Location: /');
    }
?>

<div class="container mt-3 mb-5">
    <h2 class="text-center">Mi TT</h2>
</div>

<div class="container">
    <form>
        <div class="form-group row mb-3">
            <label for="staticNombre" class="col-sm-4 col-form-label text-end">Nombre: </label>
            <div class="col-sm-8">
                <textarea type="text" readonly  class="form-control " id="staticNombre" style="resize: none;"><?php echo $tt->tt_titulo?></textarea>
            </div>
        </div>
        <div class="form-group row mb-3">
            <label for="staticNumero" class="col-sm-4 col-form-label text-end">Número: </label>
            <div class="col-sm-8">
                <input type="text" readonly class="form-control w-50" id="staticNumero" value="<?php echo $tt->tt_numero?>">
            </div>
        </div>
        <div class="form-group row mb-3">
            <label for="staticTipo" class="col-sm-4 col-form-label text-end">Tipo: </label>
            <div class="col-sm-8">
                <input type="text" readonly class="form-control w-50" id="staticTipo" value="<?php echo $tt->tt_tipo?>">
            </div>
        </div>
        <div class="form-group row mb-3">
            <label for="staticPeriodo" class="col-sm-4 col-form-label text-end">Periodo: </label>
            <div class="col-sm-8">
                <input type="text" readonly class="form-control w-50" id="staticPeriodo" value="<?php echo $tt->tt_anio . " - " . $tt->tt_ciclo ?>">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="staticDirector" class="col-sm-4 col-form-label text-end">Director: </label>
            <div class="col-sm-8">
                <?php
                    foreach ($ttdocentes as $ttdocente) {
                        if($ttdocente->idRolDocente == 1) {
                ?>
                            <input type="text" readonly class="form-control w-50 mb-1" id="staticDirector<?php echo $ttdocente->idDocente ?>" value="<?php echo $ttdocente->nombreDocente ?>">
                <?php
                        }
                    }
                ?>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="staticSinodal" class="col-sm-4 col-form-label text-end">Sinodales: </label>
            <div class="col-sm-8">
                <?php
                    foreach ($ttdocentes as $ttdocente) {
                        if($ttdocente->idRolDocente == 2) {
                ?>
                            <input type="text" readonly class="form-control w-50 mb-1" id="staticSinodal<?php echo $ttdocente->idDocente ?>" value="<?php echo $ttdocente->nombreDocente ?>">
                <?php
                        }
                    }
                ?>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="staticSeguimiento" class="col-sm-4 col-form-label text-end">Profesor de Seguimiento: </label>
            <div class="col-sm-8">
                <?php
                    foreach ($ttdocentes as $ttdocente) {
                        if($ttdocente->idRolDocente == 3) {
                ?>
                            <input type="text" readonly class="form-control w-50 mb-1" id="staticSeguimiento<?php echo $ttdocente->idDocente ?>" value="<?php echo $ttdocente->nombreDocente ?>">
                <?php
                        }
                    }
                ?>
            </div>
        </div>

    </form>
</div>