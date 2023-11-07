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

<link href="/build/css/entrega.css" rel="stylesheet">
<script src="/build/js/entrega.js"></script>

<div class="container mt-3 mb-5">
    <h2 class="text-center">Entrega N° <?php echo $numeroEntrega ?></h2>
</div>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php 
    if(!isset($entrega->idEntrega)) {
?>
    <!-- Formulario para subir archivos -->
    <div class="container my-5" style="margin-bottom: 40vh !important;">

        <form class="form-label justify-content-center row" action="/entrega" method="POST" enctype="multipart/form-data">
            <div class="row col-md-12 justify-content-center mb-3">
                <div class="col-md-6">
                    <input class="form-control" type="file" name="archivo" id="archivo">
                </div>
            </div>
            <input type="text" name="numeroEntrega" id="numeroEntrega" value="<?php echo $numeroEntrega ?>" hidden>
            <button type="submit" class="btn btn-success col-md-3">Subir archivo</button>
        </form>

    </div>
<?php 
    } else {
        
?>

<div class="row my-5 mx-auto">
    <div class="col-md-8">
        <embed src="<?php echo $entrega->rutaDocumento?>#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="600px"/>
    </div>
    <div class="col-md-4">
        <h4 class="text-center">Información de la entrega</h4>

        <p class="mt-5">Fecha de entrega: <?php echo $entrega->fechaEntrega ?></p>

        <?php if($entrega->avance == null) { ?>
            <div class="form-group mb-3 d-flex">
                <label for="staticAvance" class="form-label">Avance: No disponible</label>
            </div>
        <?php } else { ?>
            <div class="form-group mb-3 d-flex">
                <label for="staticAvance" class="form-label">Avance: <?php echo $entrega->avance . "%"?></label>
            </div>
        <?php } ?>
        
        <div class="border rounded-top">
            <h5 class="text-center mt-2">Comentarios</h5>
        </div>

        <div class="border rounded-bottom" style="height: 300px; overflow-y: scroll; overflow-x: hidden;" >
            

            <?php 
                foreach( $comentarios as $comentario) {
            ?>

            <div class="border-bottom p-2">
                <div class="row" style="font-size: 12px;">
                    <p class="col-md-8"><?php echo $comentario->nombreDocente?></p>
                    <p class="col-md-4 text-end"><?php echo $comentario->fecha?></p>
                </div>

                <p><?php echo $comentario->descripcion?></p>
            </div>

            <?php 
                }
            ?>
        </div>

        <div class="mt-2">
            <form action="/entrega" method="POST">
                <input type="text" name="numeroEntrega" id="numeroEntrega" value="<?php echo $numeroEntrega ?> " hidden>
                <textarea class="form-control" name="comentario" id="comentario" placeholder="Escribe tu comentario..." style="resize: none;"></textarea>
                <button type="submit" class="btn btn-sm btn-success text-center mt-2">Enviar Comentario</button>
            </form>
        </div>
    </div>
</div>

<?php   
    }
?>