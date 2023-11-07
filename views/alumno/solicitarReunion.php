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
    <h2 class="text-center">Solicitar Reunión</h2>
</div>



