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
    <h2 class="text-center">Mi Perfil</h2>
</div>

<div class="container">
    <form>
        <div class="form-group row mb-3">
            <label for="staticMatricula" class="col-sm-5 col-form-label text-end">Matricula: </label>
            <div class="col-sm-7">
                <input type="text" readonly  class="form-control w-50" id="staticMatricula" value="<?php echo $_SESSION['user']?>" >
            </div>
        </div>
        <div class="form-group row mb-3">
            <label for="staticNombre" class="col-sm-5 col-form-label text-end">Nombre(s): </label>
            <div class="col-sm-7">
                <input type="text" readonly class="form-control w-50" id="staticNombre" value="<?php echo $_SESSION['nombre']?>">
            </div>
        </div>
        <div class="form-group row mb-3">
            <label for="staticApellidoP" class="col-sm-5 col-form-label text-end">Apellido Paterno: </label>
            <div class="col-sm-7">
                <input type="text" readonly class="form-control w-50" id="staticApellidoP" value="<?php echo $_SESSION['apellidoP']?>">
            </div>
        </div>
        <div class="form-group row mb-3">
            <label for="staticApellidoM" class="col-sm-5 col-form-label text-end">Apellido Materno: </label>
            <div class="col-sm-7">
            <input type="text" readonly class="form-control w-50" id="staticApellidoM" value="<?php echo $_SESSION['apellidoM']?>">
            </div>
        </div>
        <div class="form-group row mb-5">
            <label for="staticEmail" class="col-sm-5 col-form-label text-end">Email: </label>
            <div class="col-sm-7">
                <input type="text" readonly class="form-control w-50" id="staticEmail" value="<?php echo $_SESSION['email']?>">
            </div>
        </div>
    </form>
</div>
