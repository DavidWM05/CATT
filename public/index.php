<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\EstudianteController;
use Controllers\LoginController;
use Controllers\DocenteController;
use Controllers\AdministrativoController;

use MVC\Router;
$router = new Router();

// Iniciar Sesión
$router->get('/', [LoginController::class, "login"]);
$router->post('/', [LoginController::class, "login"]);
$router->get('/cerrar-sesion', [LoginController::class, "cerrarSesion"]);

// Recuperar password
$router->get('/olvide', [LoginController::class, "olvide"]);
$router->post('/olvide', [LoginController::class, "olvide"]);
$router->get('/recuperar', [LoginController::class, "recuperar"]);
$router->post('/recuperar', [LoginController::class, "recuperar"]);


// Administrador
$router->get('/administrador', [AdministrativoController::class, "index"]);

$router->get('/administrador/formulario1_AG', [AdministrativoController::class, "formulario1_AG"]);     //  Formulario Algoritmo Genetico
$router->post('/administrador/formulario1_AG', [AdministrativoController::class, "formulario1_AG"]);    //  Formulario Algoritmo Genetico

$router->get('/administrador/resultados_AG', [AdministrativoController::class, "resultados_AG"]);       //  Resultados del Algoritmo Genetico
$router->post('/administrador/resultados_AG', [AdministrativoController::class, "resultados_AG"]);      //  Resultados del Algoritmo Genetico

$router->get('/administrador/crud_docente', [AdministrativoController::class, "crud_docente"]);         //  CRUD docente
$router->post('/administrador/crud_docente', [AdministrativoController::class, "crud_docente"]);        //  CRUD docente

$router->get('/administrador/crud_estudiante', [AdministrativoController::class, "crud_estudiante"]);   //  CRUD estudiante
$router->post('/administrador/crud_estudiante', [AdministrativoController::class, "crud_estudiante"]);  //  CRUD estudiante

$router->get('/administrador/crud_tt', [AdministrativoController::class, "crud_tt"]);                   //  CRUD tt
$router->post('/administrador/crud_tt', [AdministrativoController::class, "crud_tt"]);                  //  CRUD tt

$router->get('/administrador/presentaciones', [AdministrativoController::class, "presentaciones"]);     //  CRUD presentaciones
$router->post('/administrador/presentaciones', [AdministrativoController::class, "presentaciones"]);    //  CRUD presentaciones

$router->get('/administrador/registrartts', [AdministrativoController::class, "registrartts"]);         //  CRUD registrartts
$router->post('/administrador/registrartts', [AdministrativoController::class, "registrartts"]);        //  CRUD registrartts

// Docente
$router->get('/docente', [DocenteController::class, "index"]);
$router->get('/cuenta-docente', [DocenteController::class, "cuenta"]);

$router->get('/director-docente', [DocenteController::class, "director"]);
$router->get('/tt-docente-director', [DocenteController::class, "ttDocenteDirector"]);
$router->get('/entrega-tt-docente-director', [DocenteController::class, "entregaTTDocenteDirector"]);
$router->post('/entrega-tt-docente-director', [DocenteController::class, "entregaTTDocenteDirector"]);

$router->get('/sinodal-docente', [DocenteController::class, "sinodal"]);
$router->get('/tt-docente-sinodal', [DocenteController::class, "ttDocenteSinodal"]);
$router->get('/entrega-tt-docente-sinodal', [DocenteController::class, "entregaTTDocenteSinodal"]);
$router->post('/entrega-tt-docente-sinodal', [DocenteController::class, "entregaTTDocenteSinodal"]);

$router->get('/seguimiento-docente', [DocenteController::class, "seguimiento"]);
$router->get('/tt-docente-seguimiento', [DocenteController::class, "ttDocenteSeguimiento"]);
$router->get('/entrega-tt-docente-seguimiento', [DocenteController::class, "entregaTTDocenteSeguimiento"]);
$router->post('/entrega-tt-docente-seguimiento', [DocenteController::class, "entregaTTDocenteSeguimiento"]);

$router->get('/presentaciones-docente', [DocenteController::class, "presentacionesDocente"]);

// Estudiante
$router->get('/estudiante', [EstudianteController::class, "index"]);
$router->get('/cuenta', [EstudianteController::class, "cuenta"]);
$router->get('/infott', [EstudianteController::class, "infott"]);
$router->get('/seguimientott', [EstudianteController::class, "seguimientott"]);
$router->get('/entrega', [EstudianteController::class, "entrega"]);
$router->post('/entrega', [EstudianteController::class, "entrega"]);
$router->get('/presentacion-estudiante', [EstudianteController::class, "presentacionEstudiante"]);
$router->get('/solicitar-reunion', [EstudianteController::class, "solicitarReunion"]);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();