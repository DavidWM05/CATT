<?php

namespace Controllers;

use Classes\Email;
use Model\Comentario;
use Model\Estudiante;
use Model\Entrega;
use Model\HorarioPresentacion;
use Model\Noticias;
use Model\TT;
use Model\TTDocente;
use MVC\Router;

class EstudianteController {

    public static function index(Router $router) {

        session_start();

        $alertas = [];

        //obtenemos noticias
        $sql = "SELECT * FROM noticias;";
        $resultado = Noticias::SQL($sql);
        $_SESSION['noticias'] = $resultado;

        $router->render('alumno/index', [
            'alertas' => $alertas
        ]);
    }

    public static function cuenta(Router $router) {

        session_start();

        $alertas = [];

        $router->render('alumno/cuenta', [
            'alertas' => $alertas
        ]);
    }

    public static function presentacionEstudiante(Router $router) {
        session_start();

        $alertas = [];

        // obtener la información del docente
        $estudiante = Estudiante::where('idPersona', $_SESSION['idPersona']);
        //debuguear($estudiante);

        // obtener la información de los horarios de presentación
        $horariosP = HorarioPresentacion::obtenerHorarioEstudiante($estudiante->numeroTT);
        //debuguear($horariosP);

        $router->render('alumno/presentacionEstudiante', [
            'alertas' => $alertas,
            'horariosP' => $horariosP
        ]);
    }

    public static function infott(Router $router) {
        session_start();

        $alertas = [];

        // obtener la información del estudiante
        $estudiante = Estudiante::where('idPersona', $_SESSION['idPersona']);

        // obtener la información del tt
        $tt = TT::where('tt_numero', $estudiante->numeroTT);

        // obtener la información de los docentes
        $ttdocentes = TTDocente::estructuraTTConDocente('numeroTT', $tt->tt_numero);

        $router->render('alumno/infott', [
            'alertas' => $alertas,
            'tt' => $tt,
            'ttdocentes' => $ttdocentes
        ]);
    }

    public static function seguimientott(Router $router) {
        session_start();

        $alertas = [];

        $_SESSION['alerta'] = null;

        $router->render('alumno/seguimientott', [
            'alertas' => $alertas
        ]);
    }

    public static function entrega(Router $router) {
        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET') {

            $alertas = $_SESSION['alerta'] ?? [];

            $numeroEntrega = $_GET['entrega'];

            // obtener la información del estudiante
            $estudiante = Estudiante::where('idPersona', $_SESSION['idPersona']);

            // obtener la información del tt
            $tt = TT::where('tt_numero', $estudiante->numeroTT);           

            // obtener datos de la entrega
            $entrega = Entrega::obtenerEntrega($tt->tt_numero, $numeroEntrega);
            
            // obtener comentarios
            $comentarios = Comentario::obtenerComentarios($tt->tt_numero, $numeroEntrega);
            
            $router->render('alumno/entrega', [
                'alertas' => $alertas,
                'entrega' => $entrega,
                'numeroEntrega' => $numeroEntrega,
                'comentarios' => $comentarios
            ]);

        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $numeroEntrega = $_POST['numeroEntrega'];
            
            if($_FILES) {

                // obtener la información del estudiante
                $estudiante = Estudiante::where('idPersona', $_SESSION['idPersona']);

                // obtener la información del tt
                $tt = TT::where('tt_numero', $estudiante->numeroTT);
        
                // se obtiene la extensión del archivo
                $extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
                
                // se cambia el nombre del archivo
                $nombre_archivo = "" . $tt->tt_numero . "-" . $numeroEntrega . "." . $extension;

                // se obtiene información del archivo
                $tipo_archivo = $_FILES['archivo']['type'];
                $ruta_archivo = $_FILES['archivo']['tmp_name'];

                if($tipo_archivo == "application/pdf") {

                    // Movemos el archivo a la carpeta de subidas
                    $ruta_destino = "archivos/" . $nombre_archivo;
                    move_uploaded_file($ruta_archivo, $ruta_destino);

                    // obtener la información del docente de seguimiento
                    $ttdocenteS = TTDocente::obtenerDocenteSeguimiento('numeroTT', $tt->tt_numero);
                    
                    // Guardamos los datos a gurdar en un array
                    $array = array(
                        "numeroEntrega" => $numeroEntrega,
                        "rutaDocumento" => $ruta_destino,
                        "numeroTT"  => $tt->tt_numero,
                        "idDocente" => $ttdocenteS->idDocente
                    );

                    //creamos el objeto con los datos
                    $entrega = new Entrega($array);
                    $resultado = $entrega->guardarEntrega();
                    
                    if(!$resultado) {
                        TT::setAlerta('error', 'Ocurrió un error al cargar el documento');
                    }

                    // Enviar el email
                    $email = new Email($ttdocenteS->correoDocente, $ttdocenteS->nombreDocente, null);
                    //$email = new Email("rotciv568@gmail.com", $ttdocenteS->nombreDocente, null);
                    $email->enviarMailEntrega($tt->tt_numero, $numeroEntrega);

                } else {
                    TT::setAlerta('error', 'El archivo no es pdf');
                }
            } else {

                // obtener la información del estudiante
                $estudiante = Estudiante::where('idPersona', $_SESSION['idPersona']);

                // obtener la información del tt
                $tt = TT::where('tt_numero', $estudiante->numeroTT);

                // obtener la informacion de la entrega en cuestion
                $entrega = Entrega::obtenerEntrega($tt->tt_numero, $numeroEntrega);

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

            }
            
            $alertas = TT::getAlertas();
            $_SESSION['alerta'] = $alertas;

            header('Location: /entrega?entrega=' . $numeroEntrega);
        }
    }

    public static function solicitarReunion(Router $router) {
        session_start();

        $alertas = [];

        $_SESSION['alerta'] = null;

        $router->render('alumno/solicitarReunion', [
            'alertas' => $alertas
        ]);
    }
}