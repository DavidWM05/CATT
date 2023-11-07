<?php

namespace Controllers;

use Model\Comentario;
use Model\Docente;
use Model\Entrega;
use Model\HorarioPresentacion;
use Model\Noticias;
use Model\TT;
use Model\TTDocente;
use MVC\Router;

class DocenteController {
    public static function index(Router $router) {
        session_start();

        $alertas = [];

        //obtenemos noticias
        $sql = "SELECT * FROM noticias;";
        $resultado = Noticias::SQL($sql);
        $_SESSION['noticias'] = $resultado;

        $router->render('docente/index', [
            'alertas' => $alertas
        ]);
    }

    public static function cuenta(Router $router) {

        session_start();

        $alertas = [];

        $router->render('docente/cuenta', [
            'alertas' => $alertas
        ]);
    }

    public static function presentacionesDocente(Router $router) {

        session_start();

        $alertas = [];

        // obtener la información del docente
        $docente = Docente::where('idPersona', $_SESSION['idPersona']);
        
        // obtener la información de los horarios de presentación
        $horariosP = HorarioPresentacion::obtenerHorarios($docente->idDocente);
        //debuguear($horariosP);

        $router->render('docente/presentacionesDocente', [
            'alertas' => $alertas,
            'horariosP' => $horariosP
        ]);
    }

    public static function director(Router $router) {

        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET') {

            // obtener la información del docente
            $docente = Docente::where('idPersona', $_SESSION['idPersona']);

            // obtener la información de los tt director
            $tts = TTDocente::obtenerTTDirector('idDocente', $docente->idDocente);
            
            $router->render('docente/listaDirectorTT', [
                'alertas' => $alertas,
                'tts' => $tts
            ]);

        }
    }

    public static function ttDocenteDirector(Router $router) {

        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET') {

            $_SESSION['alerta'] = null;

            $numTT = $_GET['numTT'];

            $router->render('docente/entregaTTDocenteDirector', [
                'alertas' => $alertas,
                'numTT' => $numTT
            ]);
        }
    }

    public static function entregaTTDocenteDirector(Router $router) {
        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET') {

            $alertas = $_SESSION['alerta'] ?? [];

            $numeroEntrega = $_GET['entrega'];
            $numeroTT = $_GET['numTT'];

            // obtener datos de la entrega
            $entrega = Entrega::obtenerEntrega($numeroTT, $numeroEntrega);

            // obtener comentarios
            $comentarios = Comentario::obtenerComentarios($numeroTT, $numeroEntrega);

            $router->render('docente/verEntregaTTDocenteDirector', [
                'alertas' => $alertas,
                'entrega' => $entrega,
                'numeroEntrega' => $numeroEntrega,
                'numeroTT' => $numeroTT,
                'comentarios' => $comentarios
            ]);

        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $numeroEntrega = $_POST['numeroEntrega'];
            $numeroTT = $_POST['numeroTT'];

            // obtener la información del estudiante
            $docente = Docente::where('idPersona', $_SESSION['idPersona']);

            // obtener la informacion de la entrega en cuestion
            $entrega = Entrega::obtenerEntrega($numeroTT, $numeroEntrega);

            $descripcion = $_POST['comentario'];
            $idPersona = $_SESSION['idPersona'];
            $idEntrega = $entrega->idEntrega;

            // Guardamos los datos a gurdar en un array
            $array = array(
                "descripcion" => $_POST['comentario'],
                "idPersona" => $_SESSION['idPersona'],
                "idEntrega"  => $entrega->idEntrega
            );

            $comentario = new Comentario($array);

            $alertas = $comentario->validarComentario();

            if(empty($alertas)) {
                $comentario->guardarComentario();
            }

            $alertas = TT::getAlertas();
            $_SESSION['alerta'] = $alertas;

            header('Location: /entrega-tt-docente-director?entrega=' . $numeroEntrega . '&numTT=' . $numeroTT);
        }

        
    }

    public static function sinodal(Router $router) {

        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET') {

            // obtener la información del docente
            $docente = Docente::where('idPersona', $_SESSION['idPersona']);

            // obtener la información de los tt director
            $tts = TTDocente::obtenerTTSinodal('idDocente', $docente->idDocente);
            
            $router->render('docente/listaSinodalTT', [
                'alertas' => $alertas,
                'tts' => $tts
            ]);

        }
    }

    public static function ttDocenteSinodal(Router $router) {

        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET') {

            $_SESSION['alerta'] = null;

            $numTT = $_GET['numTT'];

            $router->render('docente/entregaTTDocenteSinodal', [
                'alertas' => $alertas,
                'numTT' => $numTT
            ]);
        }
    }

    public static function entregaTTDocenteSinodal(Router $router) {
        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET') {

            $alertas = $_SESSION['alerta'] ?? [];

            $numeroEntrega = $_GET['entrega'];
            $numeroTT = $_GET['numTT'];

            // obtener datos de la entrega
            $entrega = Entrega::obtenerEntrega($numeroTT, $numeroEntrega);

            // obtener comentarios
            $comentarios = Comentario::obtenerComentarios($numeroTT, $numeroEntrega);

            $router->render('docente/verEntregaTTDocenteSinodal', [
                'alertas' => $alertas,
                'entrega' => $entrega,
                'numeroEntrega' => $numeroEntrega,
                'numeroTT' => $numeroTT,
                'comentarios' => $comentarios
            ]);

        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $numeroEntrega = $_POST['numeroEntrega'];
            $numeroTT = $_POST['numeroTT'];

            // obtener la información del estudiante
            $docente = Docente::where('idPersona', $_SESSION['idPersona']);

            // obtener la informacion de la entrega en cuestion
            $entrega = Entrega::obtenerEntrega($numeroTT, $numeroEntrega);

            $descripcion = $_POST['comentario'];
            $idPersona = $_SESSION['idPersona'];
            $idEntrega = $entrega->idEntrega;

            // Guardamos los datos a gurdar en un array
            $array = array(
                "descripcion" => $_POST['comentario'],
                "idPersona" => $_SESSION['idPersona'],
                "idEntrega"  => $entrega->idEntrega
            );

            $comentario = new Comentario($array);

            $alertas = $comentario->validarComentario();

            if(empty($alertas)) {
                $comentario->guardarComentario();
            }

            $alertas = TT::getAlertas();
            $_SESSION['alerta'] = $alertas;

            header('Location: /entrega-tt-docente-sinodal?entrega=' . $numeroEntrega . '&numTT=' . $numeroTT);
        }

    }

    public static function seguimiento(Router $router) {

        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET') {

            // obtener la información del docente
            $docente = Docente::where('idPersona', $_SESSION['idPersona']);

            // obtener la información de los tt director
            $tts = TTDocente::obtenerTTSeguimiento('idDocente', $docente->idDocente);
            
            $router->render('docente/listaSeguimientoTT', [
                'alertas' => $alertas,
                'tts' => $tts
            ]);

        }
    }

    public static function ttDocenteSeguimiento(Router $router) {

        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET') {

            $_SESSION['alerta'] = null;

            $numTT = $_GET['numTT'];

            $router->render('docente/entregaTTDocenteSeguimiento', [
                'alertas' => $alertas,
                'numTT' => $numTT
            ]);
        }
    }

    public static function entregaTTDocenteSeguimiento(Router $router) {
        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET') {

            $alertas = $_SESSION['alerta'] ?? [];

            $numeroEntrega = $_GET['entrega'];
            $numeroTT = $_GET['numTT'];

            // obtener datos de la entrega
            $entrega = Entrega::obtenerEntrega($numeroTT, $numeroEntrega);
            //debuguear($entrega);

            // obtener comentarios
            $comentarios = Comentario::obtenerComentarios($numeroTT, $numeroEntrega);

            $router->render('docente/verEntregaTTDocenteSeguimiento', [
                'alertas' => $alertas,
                'entrega' => $entrega,
                'numeroEntrega' => $numeroEntrega,
                'numeroTT' => $numeroTT,
                'comentarios' => $comentarios
            ]);

        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //debuguear($_POST);
            $numeroEntrega = $_POST['numeroEntrega'];
            $numeroTT = $_POST['numeroTT'];

            // obtener la información del estudiante
            $docente = Docente::where('idPersona', $_SESSION['idPersona']);

            // obtener la informacion de la entrega en cuestion
            $entrega = Entrega::obtenerEntrega($numeroTT, $numeroEntrega);

            if(isset($_POST['comentario'])) {

                $idPersona = $_SESSION['idPersona'];
                $idEntrega = $entrega->idEntrega;

                // Guardamos los datos a gurdar en un array
                $array = array(
                    "descripcion" => $_POST['comentario'],
                    "idPersona" => $_SESSION['idPersona'],
                    "idEntrega"  => $entrega->idEntrega
                );

                $comentario = new Comentario($array);

                $alertas = $comentario->validarComentario();

                if(empty($alertas)) {
                    $comentario->guardarComentario();
                }

                $alertas = TT::getAlertas();
                $_SESSION['alerta'] = $alertas;

                header('Location: /entrega-tt-docente-seguimiento?entrega=' . $numeroEntrega . '&numTT=' . $numeroTT);
            }

            if(isset($_POST['avance'])) {

                $idEntrega = $entrega->idEntrega;
                $avance = $_POST['avance'];

                // guardar avance de la entrega en cuestion
                if(!$entrega->guardarAvance($idEntrega, $avance)) {
                    $alertas['error'][] = 'Ocurrió un error al guardar el avance';
                }

                $_SESSION['alerta'] = $alertas;

                header('Location: /entrega-tt-docente-seguimiento?entrega=' . $numeroEntrega . '&numTT=' . $numeroTT);
            }
        }

    }
}