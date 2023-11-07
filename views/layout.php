<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadatos -->
    <meta charset="UTF-8">
    <meta name="author" content="Luis David Peralta">
    <meta name="description" content="Portafolio de desarrollo de Luis David Peralta">
    <meta name="keywords" content="Java, HTML, CSS, JavaScript, php">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
    <!-- Titulo y Favicon -->
    <title>CATT</title>
    
    <link rel="icon" type="image/x-icon" href="/build/img/logo_escom.png">

    <!-- CDN [Bootstrap, Icons, sweetalert] -->    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- CSS -->
    <link href="/build/css/login.css" rel="stylesheet">
    <link href="/build/css/dashboard.css" rel="stylesheet">
    
    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>    
        
    <link href="https://fonts.googleapis.com/css2?family=Koulen&display=swap" rel="stylesheet"> <!-- Fuente: Koulen -->
    
</head>
<body>
    <!-- Barra de navegación -->
    <?php
        if(count($_SESSION)>0 && isset($_SESSION['login'])){
            echo   '<nav class="navbar navbar-expand-md">
                        <div class="container-fluid">
                            <!-- botón que aparece cuando colapsa un breakpoint -->
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-toggler" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>';

                                //Opciones de usuario
                                if($_SESSION['rol'] == 1){

                                    //<!-- Logo y siglas -->
                                    echo   '<a class="navbar-brand" href="https://www.escom.ipn.mx" target="_blank" rel="noopener noreferrer"> <img class="logo-nav" src="/build/img/logo_escom.png" width="80" alt="Logo de la pagina"> </a>
                                            <a class="navbar-brand" href="/administrador"> <span class="navbar-text titulo-nav">CATT</span> </a>';
                                    //<!-- opciones -->
                                    echo   '<div class="collapse navbar-collapse" id="navbar-toggler">';
                                    echo   '<ul class="navbar-nav d-flex justify-content-center align-items-center">';

                                            //Combo  Crud
                                    echo   '   <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle opciones-nav" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="navbar-text opciones-nav"><b>Datos</b></span>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <li><a class="dropdown-item" href="/administrador/crud_docente">Docente</a></li>
                                                    <li><a class="dropdown-item" href="/administrador/crud_estudiante">Estudiante</a></li>
                                                    <li><a class="dropdown-item" href="/administrador/crud_tt">Trabajo Terminal</a></li>
                                                    <li><a class="dropdown-item" href="/administrador/presentaciones">Horarios de presentación</a></li>
                                                </ul>
                                            </li>';

                                            // Combo  herramientas
                                    echo   '   <li class="nav-item dropdown"> 
                                                <a class="nav-link dropdown-toggle opciones-nav" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="navbar-text opciones-nav"><b>Herramientas</b></span>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <li><a class="dropdown-item" href="/administrador/formulario1_AG">Generar Presentaciones</a></li>                                                    
                                                    <li><a class="dropdown-item" href="/administrador/registrartts">Registrar TTs (xlsx)</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#acercade">Acerca de</a></li>
                                                </ul>
                                            </li>';
                                    echo   '   <li class="nav-item dropdown"> 
                                                    <a class="nav-link dropdown-toggle opciones-nav" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span class="navbar-text opciones-nav"><b>Ciclo</b></span>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                        <li><a class="dropdown-item" href="/administrador?ciclo=1">Iniciar</a></li>
                                                        <li><a class="dropdown-item" href="/administrador?ciclo=0">Terminar</a></li>
                                                    </ul>
                                                </li>';
                                            // Combo  Usuario
                                    echo   '   <!-- dropstart button -->
                                            <div class="btn-group dropstart">
                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="opciones-nav-nombre navbar-text">'.$_SESSION['nombre'].'</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li class="dropdown-item"> <a class="nav-link" href="/cerrar-sesion"> Cerrar Sesión </a></li>                                
                                            </ul>
                                            </div>';
                                }else if($_SESSION['rol'] == 2){
                                    //<!-- Logo y siglas -->
                                    echo   '<a class="navbar-brand"> <img class="logo-nav" src="/build/img/logo_escom.png" width="80" alt="Logo de la pagina"> </a>
                                            <a class="navbar-brand" href="/docente"> <span class="navbar-text titulo-nav">CATT</span> </a>';
                                    //<!-- opciones -->
                                    echo   '<div class="collapse navbar-collapse" id="navbar-toggler">';
                                    echo   '<ul class="navbar-nav d-flex justify-content-center align-items-center">';

                                            //Combo  Crud
                                    echo   '<li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle opciones-nav" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="navbar-text opciones-nav"><b>TT\'s</b></span>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="/director-docente">Director</a></li>
                                                    <li><a class="dropdown-item" href="/sinodal-docente">Sinodal</a></li>
                                                    <li><a class="dropdown-item" href="/seguimiento-docente">Seguimiento</a></li>
                                                    <li><a class="dropdown-item" href="/presentaciones-docente">Presentaciones</a></li>
                                                </ul>
                                            </li>';

                                            // Combo  Usuario
                                    echo   '<div class="btn-group dropstart">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="opciones-nav-nombre navbar-text">'.$_SESSION['nombre'].'</span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <li class="dropdown-item"> <a class="nav-link" href="/cuenta-docente"> Mi cuenta </a></li>  
                                                    <li class="dropdown-item"> <a class="nav-link" href="/cerrar-sesion"> Cerrar Sesión </a></li>                                
                                                </ul>
                                            </div>';

                                }else if($_SESSION['rol'] == 3){
                                    //<!-- Logo y siglas -->
                                    echo   '<a class="navbar-brand"> <img class="logo-nav" src="/build/img/logo_escom.png" width="80" alt="Logo de la pagina"> </a>
                                            <a class="navbar-brand" href="/estudiante"> <span class="navbar-text titulo-nav">CATT</span> </a>';
                                    //<!-- opciones -->
                                    echo   '<div class="collapse navbar-collapse" id="navbar-toggler">';
                                    echo   '<ul class="navbar-nav d-flex justify-content-center align-items-center">';

                                            //Combo  Crud
                                    echo   '<li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle opciones-nav" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="navbar-text opciones-nav"><b>TT</b></span>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <li><a class="dropdown-item" href="/infott">TT</a></li>
                                                    <li><a class="dropdown-item" href="/seguimientott">Seguimiento</a></li>
                                                    <li><a class="dropdown-item" href="/presentacion-estudiante">Presentación</a></li>
                                                    <!--<li><a class="dropdown-item" href="/solicitar-reunion">Solicitar Reunión</a></li>-->
                                                </ul>
                                            </li>';

                                            // Combo  Usuario
                                    echo   '<div class="btn-group dropstart">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="opciones-nav-nombre navbar-text">'.$_SESSION['nombre'].'</span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <li class="dropdown-item"> <a class="nav-link" href="/cuenta"> Mi cuenta </a></li>  
                                                    <li class="dropdown-item"> <a class="nav-link" href="/cerrar-sesion"> Cerrar Sesión </a></li>                                
                                                </ul>
                                            </div>';
                                }
            echo            '</div>
                        </div>
                    </nav>';
        }
    ?>
    
    <!-- Inyección de contenido -->
    <?php echo $contenido; ?>

    <!-- Pie de pagina (footer) -->
    <footer class="seccion-oscura d-flex flex-column align-items-center justify-content-center">
        <a href="https://www.ipn.mx" target="_blank" rel="noopener noreferrer">
            <img class="footer-logo" src="/build/img/ipn_logo2.png" alt="Logo del portafolio">
        </a>
        <p class="footer-texto text-center">Comisión Académica de Trabajos Terminales.</p>
        <div class="iconos-redes-sociales d-flex flex-wrap align-items-center justify-content-center">
            <a href="https://www.facebook.com/Catt.ESCOM.Oficial" target="_blank" rel="noopener noreferrer">
            <i class="bi bi-facebook"></i>
            </a>
            <a href="mailto:catt_escom@ipn.mx" target="_blank" rel="noopener noreferrer">
            <i class="bi bi-envelope"></i>
            </a>
        </div>
        <div class="derechos-de-autor">Trabajo Terminal 2023-A040 (2023) &#169;</div>
    </footer>

    <!-- Bootstrap y scripts-->
    <?php
        echo $script ?? '';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>