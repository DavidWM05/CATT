<?php

namespace Controllers;

use MVC\Router;
use Model\TT;
use Model\Salon;
use Model\Horario;
use Model\RelacionTTDocente;
use Model\RelacionTTEstudiante;
use Model\HorarioPresentacion;
use Model\Persona;
use Model\Administrativo;
use Model\Docente;
use Model\Estudiante;
use Model\TTDocente;
use Model\Usuario;
use Model\UsuarioPersona;
use Model\Noticias;

use Classes\AlgoritmoGenetico;
use Classes\Email;
use Classes\TrabajoTerminal;
use Classes\Poblacion;
use Classes\Excel;


class AdministrativoController {
    public static function index(Router $router) {
        $alertas = [];
        session_start();
        
        //  obtenemos noticias
        $sql = "SELECT * FROM noticias;";
        $resultado = Noticias::SQL($sql);
        $_SESSION['noticias'] = $resultado;

        //debuguear($_SESSION['noticias']);

        if(isset($_GET['ciclo']) && $_GET['ciclo'] == '1'){
            $email = new Email(null,null,null);     //  Objeto mail
            //  Obtenemos usuarios
            //if(!isset($_SESSION['index_inicio'])){
                $sql = "SELECT * FROM persona a 
                        INNER JOIN usuario b ON a.idPersona = b.idPersona
                        WHERE  a.idStatus = 1 AND a.idRol != 1;";
                $resultado = UsuarioPersona::SQL($sql);

                foreach ($resultado as $usuario) {
                    if($usuario->idPersona == "2400" || $usuario->idPersona == "2401" || $usuario->idPersona == "16")  //  Solo para pruebas
                        $email->enviarCredenciales($usuario->correo,$usuario->nombre,$usuario->user);
                }

            //    $_SESSION['index_inicio'] = 1;
            //    $_SESSION['index_msg'] = 'Ciclo iniciado';
            //}            
        }elseif(isset($_GET['ciclo']) && $_GET['ciclo'] == '0'){
            //if(!isset($_SESSION['index_fin'])){
                //  Se inactivan tts
                $sql = "UPDATE tt SET idStatus = 2";
                $resultado = TT::update($sql);

                //  Se inactivan personas tts
                $sql = "UPDATE persona SET idStatus = 2 WHERE idRol != 1";
                $resultado = Persona::update($sql);

                //  Se inactivan personas roldocentes
                $sql = "UPDATE ttdocente SET estatus = 0";
                $resultado = TTDocente::update($sql);

                unset($_SESSION['docentes']);
                unset($_SESSION['estudiantes']);
                unset($_SESSION['listatts']);

                $_SESSION['index_fin'] = 1;
                $_SESSION['index_msg'] = 'Ciclo terminado';
            //}
        }

        $router->render('administrativo/index', [
            'alertas' => $alertas
        ]);
    }

    //  =====> CRUD Docente <=====
    public static function crud_docente(Router $router) {
        //  Variables Auxiliares
        session_start();
        $alertas = [];        

        if(($_SERVER['REQUEST_METHOD'] === 'GET')){
            if(!isset($_SESSION['doc_recargar'])) $_SESSION['doc_recargar'] = false;    //  Verdadero: si no existe SESSION['cambio']
            $condicion  = !isset($_SESSION['docentes']);                    //  Verdadero: si no existe SESSION['docentes']
            $condicion2 = !isset($_SESSION['doc_inhabilitados']);               //  Verdadero: si no existe SESSION['inhabilitados']           

            // =====> Proceso [Consulta: docente]
            if($condicion || $_SESSION['doc_recargar'] == true) {     //  Entra si: no existe 'docentes'
                $consulta ="SELECT * FROM persona a INNER JOIN docente b ON a.idPersona = b.idPersona WHERE a.idStatus = 1 ORDER BY a.idPersona ASC;";
                $docentes = Docente::SQL($consulta);
                $_SESSION['docentes'] = $docentes;      //Agregamos al Session
                if($_SESSION['doc_recargar'] == true) $_SESSION['doc_recargar'] = false;
            }
        } elseif($_SERVER['REQUEST_METHOD'] === 'POST'){            
            //  Condiciones
            $create = isset($_POST['create']);  //  Verdadero: si existe POST['create']
            $update = isset($_POST['update']);  //  Verdadero: si existe POST['update']
            $delete = isset($_POST['delete']);  //  Verdadero: si existe POST['delete']
            $search = isset($_POST['search']);  //  Verdadero: si existe POST['search']
            $enable = isset($_POST['enable']);  //  Verdadero: si existe POST['enable']            

            if ($create) { // ==> Entra si: existe 'create'
                // =====> Paso 1: Se inserta elemento a tabla 'persona'
                $sql = "INSERT INTO persona (nombre,apellidoPaterno,apellidoMaterno,correo,idRol,idStatus)
                        VAlUES ('".$_POST['nombre']."','".$_POST['APaterno']."','".$_POST['AMaterno']."','".$_POST['correo']."',2,1)";
                $alertas['msg'] = Persona::create($sql);

                if($alertas['msg'] == 'registrado'){    //  Entra si: se registro correctamente Persona
                    // =====> Paso 2: Cambiamos el formato de horas
                    $horaInicio = date('H:i:s',strtotime($_POST['horaInicio']));
                    $horaFin = date('H:i:s',strtotime($_POST['horaFin']));

                    // =====> Paso 3: Obtenemos el idPersona del ultimo registro
                    $sql = "SELECT idPersona FROM persona ORDER BY idPersona DESC LIMIT 1;";
                    $persona = Persona::SQL($sql);
                    $idPersona = $persona[0]->idPersona;

                    // =====> Paso 4: Se inserta como docente
                    $sql = "INSERT INTO docente (idDocente,docente_escuela,docente_area,docente_horaInicio,docente_horaFin,idPersona,docente_tipo)
                            VALUES ('".$_POST['create']."','".$_POST['escuela']."','".$_POST['area']."','".$horaInicio."','".$horaFin."',".$idPersona.",'".$_POST['docente_tipo']."');";
                    $alertas['msg'] = Docente::create($sql);

                    if($alertas['msg'] == 'registrado'){    //  Entra si: se registro correctamente docente
                        // =====> Creamos usuario
                        $usuario = new Usuario(array('password' => $_POST['create']));
                        $usuario->hashPassword();

                        // =====> Se inserta usuario
                        $sql = "INSERT INTO usuario VALUES ('".$_POST['create']."','".$usuario->password."',".$idPersona.",NULL);";
                        $resultado = Usuario::create($sql);

                        $_SESSION['msg'] = 'Docente Registrado Correctamente';
                        $_SESSION['estado'] = 'exitoso';
                        $_SESSION['doc_recargar'] = true;
                    }elseif($alertas['msg'] === "ya existe"){                                  //  No se registro docente
                        $_SESSION['msg'] = 'Error: idDocente ya existe';
                        $_SESSION['estado'] = 'no exitoso';
                    }else {
                        $_SESSION['msg'] = $alertas['msg'];
                        $_SESSION['estado'] = 'no exitoso';
                    }                    
                }else{                                  //  No se registro persona
                    $_SESSION['msg'] = $alertas['msg'];
                    $_SESSION['estado'] = 'no exitoso';                    
                }                
            }elseif ($update) { // ==> Entra si: existe 'update'
                // =====> Paso 0: Cambiamos el formato de horas
                $horaInicio = date('H:i:s',strtotime($_POST['horaInicio']));
                $horaFin = date('H:i:s',strtotime($_POST['horaFin']));

                // =====> Paso 1: Consulta update
                $sql = "UPDATE persona AS a
                        INNER JOIN docente AS b ON a.idPersona = b.idPersona
                        SET a.nombre = '".$_POST['nombre']."',
                        a.apellidoPaterno = '".$_POST['APaterno']."',
                        a.apellidoMaterno = '".$_POST['AMaterno']."',
                        a.correo = '".$_POST['correo']."',
                        b.docente_escuela = '".$_POST['escuela']."',
                        b.docente_area = '".$_POST['area']."',
                        b.docente_horaInicio = '".$horaInicio."',
                        b.docente_horaFin = '".$horaFin."',
                        b.docente_tipo = '".$_POST['docente_tipo']."'
                        WHERE a.idPersona = ".$_POST['idPersona'].";";
                $alertas['msg'] = Docente::update($sql);

                if($alertas['msg'] == 'actualizado'){
                    $_SESSION['msg'] = 'Docente Actualizado Correctamente';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['doc_recargar'] = true;
                }else {
                    $_SESSION['msg'] = $alertas['msg'];
                    $_SESSION['estado'] = 'no exitoso';
                }
            }elseif ($delete) { // ==> Entra si: existe 'delete'
                // =====> Paso 1: Consulta update
                $sql = "UPDATE persona AS a INNER JOIN docente AS b ON a.idPersona = b.idPersona SET a.idStatus = 2 WHERE a.idPersona = ".$_POST['delete'].";";
                $alertas['msg'] = Docente::update($sql);

                if($alertas['msg'] == 'actualizado'){
                    $_SESSION['msg'] = 'Docente Eliminado Correctamente';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['doc_recargar'] = true;
                }else {
                    $_SESSION['msg'] = $alertas['msg'];
                    $_SESSION['estado'] = 'no exitoso';
                }
            }elseif ($search) {  // ==> Entra si: existe 'search'
                // =====> Paso 1: Consulta update
                $sql = "SELECT * FROM persona AS a
                        INNER JOIN docente AS b ON a.idPersona = b.idPersona                        
                        WHERE b.idDocente = '".$_POST['search']."';";
                $alertas['docente'] = Docente::SQL($sql);

                if(count($alertas['docente']) == 1){
                    $_SESSION['docente'] = $alertas['docente'];
                    $_SESSION['msg'] = 'Docente Encontrado';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['est_recargar'] = true;
                }elseif(count($alertas['docente']) == 0) {
                    $_SESSION['msg'] = 'Docente No Encontrado';
                    $_SESSION['estado'] = 'no exitoso';
                }
            }elseif ($enable) { // ==> Entra si: existe 'enable'
                // =====> Paso 1: Consulta update
                $sql = "UPDATE persona AS a INNER JOIN docente AS b ON a.idPersona = b.idPersona SET a.idStatus = 1 WHERE a.idPersona = ".$_POST['enable'].";";
                $alertas['msg'] = Docente::update($sql);

                if($alertas['msg'] == 'actualizado'){
                    $_SESSION['msg'] = 'Docente Habilitado Correctamente';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['doc_recargar'] = true;
                }else {
                    $_SESSION['msg'] = $alertas['msg'];
                    $_SESSION['estado'] = 'no exitoso';
                }
            }else {             // ==> Recarga
                $_SESSION['doc_recargar'] = true;
            }

            header("Location: /administrador/crud_docente");
            exit();     //  Se sale del script
        }

        $router->render('administrativo/crud_docente', [
            'alertas' => $alertas
        ]);
    }

    //  =====> CRUD Estudiante <=====
    public static function crud_estudiante(Router $router) {
        //  Variables Auxiliares
        session_start();
        $alertas = [];        

        if(($_SERVER['REQUEST_METHOD'] === 'GET')){
            if(!isset($_SESSION['est_recargar'])) { $_SESSION['est_recargar'] = false; }//  Verdadero: si no existe SESSION['cambio']
            $condicion  = !isset($_SESSION['estudiantes']);                             //  Verdadero: si no existe SESSION['estudiantes']

            // =====> Proceso [Consulta: estudiante]
            if($condicion || $_SESSION['est_recargar'] == true) {     //  Entra si: no existe 'estudiantes'
                $consulta ="SELECT * FROM persona a INNER JOIN estudiante b ON a.idPersona = b.idPersona WHERE a.idStatus = 1 ORDER BY b.numeroTT ASC;";
                $estudiantes = Estudiante::SQL($consulta);
                $_SESSION['estudiantes'] = $estudiantes;        //Agregamos al Session

                if($_SESSION['est_recargar'] == true) $_SESSION['est_recargar'] = false;
            }        
        } elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
            //  Condiciones
            $create = isset($_POST['create']);  //  Verdadero: si existe POST['create']
            $update = isset($_POST['update']);  //  Verdadero: si existe POST['update']
            $delete = isset($_POST['delete']);  //  Verdadero: si existe POST['delete']
            $search = isset($_POST['search']);  //  Verdadero: si existe POST['search']
            $enable = isset($_POST['enable']);  //  Verdadero: si existe POST['enable']

            if ($create) {  // ==> Entra si: existe 'create'
                // =====> Paso 1: Buscamos si existe el estudiante y el tt
                $sql = "SELECT * FROM estudiante WHERE boleta = ".$_POST['create'].";";
                $estudiante = Estudiante::SQL($sql);
                
                $sql = "SELECT * FROM tt WHERE tt_numero = '".$_POST['numeroTT']."';";
                $tt = TT::SQL($sql);

                $n_estudiante = count($estudiante);
                $n_tt = count($tt);

                if($n_estudiante == 0 && $n_tt == 1){       // ==> Entra si: no existe el estudiante && existe el numero de tt
                    // =====> Paso 2: Se inserta elemento a tabla 'persona'
                    $sql = "INSERT INTO persona (nombre,apellidoPaterno,apellidoMaterno,correo,idRol,idStatus)
                    VAlUES ('".$_POST['nombre']."','".$_POST['APaterno']."','".$_POST['AMaterno']."','".$_POST['correo']."',3,1)";
                    $alertas['msg'] = Persona::create($sql);

                    if($alertas['msg'] == 'registrado'){    //  Entra si: se registro correctamente Persona
                        // =====> Paso 3: Obtenemos el idPersona del ultimo registro
                        $sql = "SELECT idPersona FROM persona ORDER BY idPersona DESC LIMIT 1;";
                        $persona = Persona::SQL($sql);
                        $idPersona = $persona[0]->idPersona;

                        // =====> Paso 4: Se inserta como estudiante
                        $sql = "INSERT INTO estudiante (boleta,numeroTT,idPersona) VALUES ('".$_POST['create']."','".$_POST['numeroTT']."','".$idPersona."');";
                        $alertas['msg'] = Estudiante::create($sql);                            
                        
                        // =====> Paso 5: Verificamos que se inserto bien estudiante
                        if($alertas['msg'] == 'registrado'){    //  Entra si: se registro correctamente estudiante
                            $_SESSION['msg'] = 'Estudiante Registrado Correctamente';

                            // =====> Creamos usuario
                            $usuario = new Usuario(array('password' => $_POST['create']));
                            $usuario->hashPassword();

                            // =====> Se inserta usuario
                            $sql = "INSERT INTO usuario VALUES ('".$_POST['create']."','".$usuario->password."',".$idPersona.",NULL);";
                            $resultado = Usuario::create($sql);

                            $_SESSION['estado'] = 'exitoso';
                            $_SESSION['est_recargar'] = true;
                        }else {
                            $_SESSION['msg'] = $alertas['msg'];
                            $_SESSION['estado'] = 'no exitoso';
                        }
                    }else{                                  //  No se registro persona
                        $_SESSION['msg'] = $alertas['msg'];
                        $_SESSION['estado'] = 'no exitoso';
                    }
                }elseif($n_estudiante >= 1 && $n_tt == 0){  // ==> Entra si: existe el estudiante y no existe tt
                    $_SESSION['msg'] = 'Error: El estudiante ya esta registrado y tt no existe';
                    $_SESSION['estado'] = 'no exitoso';
                }elseif($n_estudiante >= 1){                // ==> Entra si: existe el estudiante
                    $_SESSION['msg'] = 'Error: El estudiante ya registrado';
                    $_SESSION['estado'] = 'no exitoso';
                }elseif($n_tt == 0){                        // ==> Entra si: no existe tt
                    $_SESSION['msg'] = 'Error: tt no existe';
                    $_SESSION['estado'] = 'no exitoso';
                }else{
                    $_SESSION['msg'] = 'Error: Algo salio mal';
                    $_SESSION['estado'] = 'no exitoso';
                }
            }elseif ($update) {  // ==> Entra si: existe 'update'
                // =====> Paso 1: Consulta update                
                $sql = "UPDATE persona AS a
                        INNER JOIN estudiante AS b ON a.idPersona = b.idPersona
                        SET a.nombre = '".$_POST['nombre']."',
                        a.apellidoPaterno = '".$_POST['APaterno']."',
                        a.apellidoMaterno = '".$_POST['AMaterno']."',
                        a.correo = '".$_POST['correo']."',
                        b.boleta = '".$_POST['update']."',
                        b.numeroTT = '".$_POST['numeroTT']."'
                        WHERE a.idPersona = ".$_POST['idPersona'].";";
                
                $alertas['msg'] = Estudiante::update($sql);

                if($alertas['msg'] == 'actualizado'){
                    $_SESSION['msg'] = 'Estudiante Actualizado Correctamente';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['est_recargar'] = true;
                }else {
                    if(strpos($alertas['msg'],'Code[1452]') !== false){
                        $_SESSION['msg'] = 'Error: numero de TT no existe';
                    }else{
                        $_SESSION['msg'] = $alertas['msg'];
                    }                        
                    $_SESSION['estado'] = 'no exitoso';
                }
            }elseif ($delete) {  // ==> Entra si: existe 'delete'
                // =====> Paso 1: Consulta update
                $sql = "UPDATE persona AS a
                        INNER JOIN estudiante AS b ON a.idPersona = b.idPersona
                        SET a.idStatus = 2
                        WHERE a.idPersona = ".$_POST['delete'].";";

                $alertas['msg'] = Estudiante::update($sql);

                //debuguear($alertas['msg']);

                if($alertas['msg'] == 'actualizado'){
                    $_SESSION['msg'] = 'Estudiante Eliminado Correctamente';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['est_recargar'] = true;
                }else {
                    $_SESSION['msg'] = $alertas['msg'];
                    $_SESSION['estado'] = 'no exitoso';
                }
            }elseif ($search) {  // ==> Entra si: existe 'search'
                // =====> Paso 1: Consulta update
                $sql = "SELECT * FROM persona AS a
                        INNER JOIN estudiante AS b ON a.idPersona = b.idPersona                        
                        WHERE b.boleta = ".$_POST['search'].";";

                $alertas['estudiante'] = Estudiante::SQL($sql);                

                if(count($alertas['estudiante']) == 1){
                    $_SESSION['estudiante'] = $alertas['estudiante'];
                    $_SESSION['msg'] = 'Estudiante Encontrado';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['est_recargar'] = true;
                }elseif(count($alertas['estudiante']) == 0) {
                    $_SESSION['msg'] = 'Estudiante No Encontrado';
                    $_SESSION['estado'] = 'no exitoso';
                }
            }elseif ($enable) {  // ==> Entra si: existe 'enable'
                // =====> Paso 1: Consulta update
                $sql = "UPDATE persona AS a
                        INNER JOIN estudiante AS b ON a.idPersona = b.idPersona
                        SET a.idStatus = 1
                        WHERE a.idPersona = ".$_POST['enable'].";";

                $alertas['msg'] = Estudiante::update($sql);

                if($alertas['msg'] == 'actualizado'){
                    $_SESSION['msg'] = 'Estudiante Habilitado Correctamente';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['est_recargar'] = true;
                }else {
                    $_SESSION['msg'] = $alertas['msg'];
                    $_SESSION['estado'] = 'no exitoso';
                }
            }else {             // ==> Recarga
                $_SESSION['est_recargar'] = true;
            }

            header("Location: /administrador/crud_estudiante");
            exit(); //Salimos del scrip para que no se ejecute algo mas
        }

        $router->render('administrativo/crud_estudiante', [
            'alertas' => $alertas
        ]);
    }

    //  =====> CRUD TT <=====
    public static function crud_tt(Router $router) {
        //  Variables Auxiliares
        session_start();
        $alertas = [];        

        if($_SERVER['REQUEST_METHOD'] === 'GET'){           
            if(!isset($_SESSION['tt_recargar'])) $_SESSION['tt_recargar'] = false;    //  Verdadero: si no existe SESSION['cambio']
            $condicion  = !isset($_SESSION['listatts']);                 //  Verdadero: si no existe SESSION['tts']            

            // =====> Proceso [Consulta: tt]
            if($condicion || $_SESSION['tt_recargar'] == true) {     //  Entra si: no existe 'tts'
                //  Consulta: Datos de trabajo terminal
                $consulta ="SELECT a.tt_numero,a.tt_titulo,a.tt_tipo,a.tt_anio,a.tt_ciclo,a.tt_ruta,b.tipo AS idStatus
                            FROM tt a INNER JOIN status b ON a.idStatus = b.idStatus
                            WHERE b.tipo = 'activo'
                            ORDER BY a.tt_tipo ASC;";
                $tts = TT::SQL($consulta);

                //  Consulta: Datos de docentes <=> tts
                $consulta ="SELECT a.numeroTT,CONCAT(d.nombre,' ',d.apellidoPaterno,' ',d.apellidoMaterno) AS nombre,e.tipo,c.idDocente
                            FROM ttdocente a 
                            INNER JOIN tt b ON a.numeroTT = b.tt_numero 
                            INNER JOIN docente c ON c.idDocente = a.idDocente 
                            INNER JOIN persona d ON d.idPersona = c.idPersona 
                            INNER JOIN roldocente e ON e.idRolDocente = a.idRolDocente 
                            WHERE b.idStatus = 1 AND a.estatus = 1 ORDER BY a.numeroTT ASC;";
                
                $docentes = RelacionTTDocente::SQL($consulta);

                //  Consulta: Datos de estudiante <=> tts
                $consulta ="SELECT a.numeroTT,CONCAT(b.nombre,' ',b.apellidoPaterno,' ',b.apellidoMaterno) AS nombre,a.boleta from estudiante a
                            inner join persona b on a.idPersona = b.idPersona
                            inner join tt c on a.numeroTT = c.tt_numero
                            where c.idStatus = 1 and b.idStatus = 1
                            order by a.numeroTT asc;";
                $estudiantes = RelacionTTEstudiante::SQL($consulta);

                $listatts = array();
                foreach ($tts as $valor) {
                    $ttauxiliar = new TrabajoTerminal();
                    $numeroTT = $valor->tt_numero;

                    $ttauxiliar->setNumeroTT($numeroTT);
                    $ttauxiliar->setTituloTT($valor->tt_titulo);
                    $ttauxiliar->setTipoTT($valor->tt_tipo);
                    $ttauxiliar->setAnio($valor->tt_anio);
                    $ttauxiliar->setCiclo($valor->tt_ciclo);
                    $ttauxiliar->setArchivo($valor->tt_ruta);
                    $ttauxiliar->setStatus($valor->idStatus);

                    //  Agrega grupo de docentes
                    $contador = 0;
                    foreach ($docentes as $docente) {
                        if($docente->numeroTT == $numeroTT){ 
                            $ttauxiliar->setDocente($docente->nombre,$docente->tipo);
                            $contador++; 
                        }elseif($contador != 0 && $docente->numeroTT != $numeroTT){
                            break;
                        }
                    }

                    //  Agrega grupo de estudiantes
                    $contador = 0;
                    foreach ($estudiantes as $estudiante){
                        if($estudiante->numeroTT == $numeroTT){ 
                            $ttauxiliar->setEstudiante($estudiante->nombre);
                            $contador++; 
                        }elseif($contador != 0 && $estudiante->numeroTT != $numeroTT){
                            break;
                        }
                    }

                    $listatts[] = $ttauxiliar;
                }                

                $_SESSION['listatts'] = $listatts;      //Agregamos al Session

                if($_SESSION['tt_recargar'] == true) { $_SESSION['tt_recargar'] == false; }
            }                     
        } elseif($_SERVER['REQUEST_METHOD'] === 'POST'){            
            //  Condiciones
            $create = isset($_POST['create']);  //  Verdadero: si existe POST['create']
            $update = isset($_POST['update']);  //  Verdadero: si existe POST['update']
            $delete = isset($_POST['delete']);  //  Verdadero: si existe POST['delete']
            $buscar = isset($_POST['buscar']);  //  Verdadero: si existe POST['buscar']

            if ($create) {  // ==> Entra si: existe 'create'
                if($_FILES['archivo']['type'] === 'application/pdf'){   //Verificamos que el archivo sea pdf
                    // =====> Paso 1: Buscamos si existe el tt
                    $sql = "SELECT * FROM `tt` WHERE tt_numero = '".$_POST['create']."';";
                    $existe = TT::SQL($sql);

                    if(count($existe) == 0){    // ==> Entra si: no existe el tt
                        $numeroTT = $_POST['create'];
                        // =====> Paso 2: obtenermos el idAdministrativo que lo registro
                        $sql = "SELECT * FROM administrativo WHERE idPersona = ".$_SESSION['idPersona'].";";
                        $resultado = Administrativo::SQL($sql);
                        $admin = $resultado[0];
                        
                        // =====> Paso 3: Se inserta elemento a tabla 'tt'
                        $sql = "INSERT INTO tt
                        VAlUES ('".$_POST['create']."','".$_POST['tt_titulo']."','".$_POST['tt_tipo']."',".$_POST['tt_anio'].",".$_POST['tt_ciclo'].",".$admin->idAdministrativo.",'archivos/protocolos/".$numeroTT.".pdf',1)";
                        $alertas['msg'] = TT::create($sql);

                        // =====> Paso 4: Subida de Archivo
                        $ruta_archivo = $_FILES['archivo']['tmp_name'];
                        $ruta_destino = "archivos/protocolos/" . $numeroTT . '.pdf';                        
                        move_uploaded_file($ruta_archivo, $ruta_destino);

                        if($alertas['msg'] == 'registrado'){    //  Entra si: se registro correctamente TT                        
                                $_SESSION['msg'] = 'TT Registrado Correctamente';
                                $_SESSION['estado'] = 'exitoso';
                                $_SESSION['tt_recargar'] = true;        
                        }else{                                  //  No se registro TT
                            $_SESSION['msg'] = $alertas['msg'];
                            $_SESSION['estado'] = 'no exitoso';
                        }
                    }else{                      // ==> Entra si: existe el tt
                        $_SESSION['msg'] = 'Error: El TT ya esta registrado';
                        $_SESSION['estado'] = 'no exitoso';
                    }                    
                }else{
                    $_SESSION['msg'] = 'Error: El archivo no es pdf';
                    $_SESSION['estado'] = 'no exitoso';
                }
            }elseif ($update) {  // ==> Entra si: existe 'update'
                $numeroTT = $_POST['update'];
                $estado = array('activo' => '1', 'inactivo' => '2', 'finalizado' => '3', 'baja' => '4', 'pausado' => '5');
                // =====> Paso 1: Consulta update                
                if(strlen($_FILES['archivo']['name'] > 0)){ //  Verifica si existe un archivo
                    if ($_FILES['archivo']['type'] === 'application/pdf') { //  Verifica la extension
                        $sql = "UPDATE tt SET tt_titulo = '".$_POST['tt_titulo']."', tt_tipo = '".$_POST['tt_tipo']."', tt_ruta = 'archivos/protocolos/".$numeroTT.".pdf', idStatus = '".$estado[$_POST['idStatus']]."' WHERE tt_numero = '".$_POST['update']."';";
                        $alertas['msg'] = TT::update($sql);

                        if($alertas['msg'] == 'actualizado'){   // Se sube el archivo solo si se registro el tt
                            // =====> Paso 2: Subida de Archivo                            
                            $ruta_archivo = $_FILES['archivo']['tmp_name'];
                            $ruta_destino = "archivos/protocolos/" . $numeroTT . '.pdf';                            
                            move_uploaded_file($ruta_archivo, $ruta_destino); }
                    }else {
                        $alertas['msg'] = 'Error: El archivo no es pdf';
                    }
                }else{  //  No se cambiara el archivo
                    $sql = "UPDATE tt SET tt_titulo = '".$_POST['tt_titulo']."', tt_tipo = '".$_POST['tt_tipo']."', idStatus = ".$estado[$_POST['idStatus']]." WHERE tt_numero = '".$_POST['update']."';"; 
                    $alertas['msg'] = TT::update($sql);                    
                }

                //  Mensaje final
                if($alertas['msg'] == 'actualizado'){
                    $_SESSION['msg'] = 'Trabajo Terminal Actualizado Correctamente';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['tt_recargar'] = true;
                }else {
                    $_SESSION['msg'] = $alertas['msg'];
                    $_SESSION['estado'] = 'no exitoso';
                }
            }elseif ($delete) {  // ==> Entra si: existe 'delete'                
                // =====> Paso 1: Consulta update
                $sql = "UPDATE tt SET idStatus = 4
                        WHERE tt_numero = '".$_POST['delete']."';";

                $alertas['msg'] = TT::update($sql);

                if($alertas['msg'] == 'actualizado'){
                    $_SESSION['msg'] = 'Trabajo Terminal Dado de Baja Correctamente';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['tt_recargar'] = true;
                }else {
                    $_SESSION['msg'] = $alertas['msg'];
                    $_SESSION['estado'] = 'no exitoso';
                }
            }elseif ($buscar) {  // ==> Entra si: existe 'buscar'

                //  Consulta: Datos de trabajo terminal
                $consulta ="SELECT a.tt_numero,a.tt_titulo,a.tt_tipo,a.tt_anio,a.tt_ciclo,a.tt_ruta,b.tipo AS idStatus
                            FROM tt a INNER JOIN status b ON a.idStatus = b.idStatus
                            WHERE a.tt_numero = '".$_POST['buscar']."';";
                $tts = TT::SQL($consulta);               

                if(count($tts) == 1){
                    //  Consulta: Datos de docentes <=> tts
                    $consulta ="SELECT a.numeroTT,CONCAT(d.nombre,' ',d.apellidoPaterno,' ',d.apellidoMaterno) AS nombre,e.tipo,c.idDocente
                                FROM ttdocente a 
                                INNER JOIN tt b ON a.numeroTT = b.tt_numero 
                                INNER JOIN docente c ON c.idDocente = a.idDocente 
                                INNER JOIN persona d ON d.idPersona = c.idPersona 
                                INNER JOIN roldocente e ON e.idRolDocente = a.idRolDocente 
                                WHERE b.idStatus = 1 AND a.estatus = 1 AND b.tt_numero = '".$_POST['buscar']."';";
        
                    $docentes = RelacionTTDocente::SQL($consulta);

                    //  Consulta: Datos de estudiante <=> tts
                    $consulta ="SELECT a.numeroTT,CONCAT(b.nombre,' ',b.apellidoPaterno,' ',b.apellidoMaterno) AS nombre,a.boleta from estudiante a
                                inner join persona b on a.idPersona = b.idPersona
                                inner join tt c on a.numeroTT = c.tt_numero
                                where c.idStatus = 1 and b.idStatus = 1
                                AND c.tt_numero = '".$_POST['buscar']."';";
                    $estudiantes = RelacionTTEstudiante::SQL($consulta);

                    $listatts = array();
                    foreach ($tts as $valor) {
                        $ttauxiliar = new TrabajoTerminal();
                        $numeroTT = $valor->tt_numero;

                        $ttauxiliar->setNumeroTT($numeroTT);
                        $ttauxiliar->setTituloTT($valor->tt_titulo);
                        $ttauxiliar->setTipoTT($valor->tt_tipo);
                        $ttauxiliar->setAnio($valor->tt_anio);
                        $ttauxiliar->setCiclo($valor->tt_ciclo);
                        $ttauxiliar->setArchivo($valor->tt_ruta);
                        $ttauxiliar->setStatus($valor->idStatus);

                        //  Agrega grupo de docentes
                        $contador = 0;
                        foreach ($docentes as $docente) {
                            if($docente->numeroTT == $numeroTT){ 
                                $ttauxiliar->setDocente($docente->nombre,$docente->tipo);
                                $contador++; 
                            }elseif($contador != 0 && $docente->numeroTT != $numeroTT){
                                break;
                            }
                        }

                        //  Agrega grupo de estudiantes
                        $contador = 0;
                        foreach ($estudiantes as $estudiante){
                            if($estudiante->numeroTT == $numeroTT){ 
                                $ttauxiliar->setEstudiante($estudiante->nombre);
                                $contador++; 
                            }elseif($contador != 0 && $estudiante->numeroTT != $numeroTT){
                                break;
                            }
                        }

                        $listatts[] = $ttauxiliar;
                    }                

                    $_SESSION['TT'] = $listatts;      //Agregamos al Session


                    $_SESSION['msg'] = 'Trabajo Terminal Encontrado';
                    $_SESSION['estado'] = 'exitoso';
                    //$_SESSION['TT'] = $alertas['TT'];
                }elseif(count($tts) > 1) {
                    $_SESSION['msg'] = 'Trabajo Terminal Duplicado';
                    $_SESSION['estado'] = 'no exitoso';
                }else{
                    $_SESSION['msg'] = 'Trabajo Terminal No Registrado';
                    $_SESSION['estado'] = 'no exitoso';
                }
            }else {             // ==> Recarga
                $_SESSION['tt_recargar'] = true;
            }

            header("Location: /administrador/crud_tt");
            exit(); //Salimos del scrip
        }

        $router->render('administrativo/crud_tt', [
            'alertas' => $alertas
        ]);
    }

    //  =====> CRUD presentaciones de tt<=====
    public static function presentaciones(Router $router) {
        //  Variables Auxiliares
        session_start();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            //  Variables locales

            $tt1_optimo = array();
            $tt2_optimo = array();

            $fechastt1 = array();
            $fechastt2 = array();            

            if(!isset($_GET['anio']) && !isset($_GET['ciclo'])){    //  Entra si: no existe año ni ciclo elegidos
                $anioActual = date('Y');    //  Año actual
                $mesActual = date('m');     //  Mes actual
                $ciclo = 1;                 //  Ciclo default

                //  Obtiene el ciclo actual
                if((int)$mesActual <= 6){ $ciclo = 2; }
                else{ $anioActual = (int)$anioActual + 1; }
            }else{                                                  //  Entra si: existen los get anio y ciclo
                $anioActual = $_GET['anio'];//  Año ingresado
                $ciclo = $_GET['ciclo'];    //  Ciclo ingresado      
            }    

            //  Consultas de TT1 y TT2            
            $resultado1 = HorarioPresentacion::obtenerHorarioTT("c.tt_tipo = 'TT1'",$anioActual,$ciclo);
            $resultado2 = HorarioPresentacion::obtenerHorarioTT("c.tt_tipo != 'TT1'",$anioActual,$ciclo);

            /*
            if(count($resultado1) > 0){   //  Entra si: existe algun resultado1
                foreach ($resultado1 as $valor ) {
                    // =====> Proceso [Consulta: relacion tt <=> docente]
                    $consulta ="SELECT a.numeroTT,b.tt_titulo,CONCAT(d.nombre,' ',d.apellidoPaterno,' ',d.apellidoMaterno) AS nombre,e.tipo
                                FROM ttdocente a 
                                INNER JOIN tt b ON a.numeroTT = b.tt_numero 
                                INNER JOIN docente c ON c.idDocente = a.idDocente 
                                INNER JOIN persona d ON d.idPersona = c.idPersona 
                                INNER JOIN roldocente e ON e.idRolDocente = a.idRolDocente 
                                WHERE a.numeroTT = '".$valor->numeroTT."' AND b.idStatus = 1;";

                    $relacionTT_Docente = RelacionTTDocente::SQL($consulta);

                    $TT = new TrabajoTerminal();
                    $TT->setNumeroTT($valor->numeroTT);
                    $TT->setTituloTT($relacionTT_Docente[0]->tt_titulo);
                    $TT->setFecha($valor->fecha);
                    $TT->setSalon($valor->salon);
                    $TT->setHorario($valor->horario);
                    $TT->setOptimo($valor->optimo);

                    // =====> Recorrido de docentes
                    foreach ($relacionTT_Docente as $docente) { $TT->setDocente($docente->nombre,$docente->tipo); }

                    // =====> Guardamos en el arreglo
                    $tt1_optimo[] = $TT;                        
                }
            }
            if(count($resultado2) > 0){   //  Entra si: existe algun resultado2
                foreach ($resultado2 as $valor ) {
                    // =====> Proceso [Consulta: relacion tt <=> docente]
                    $consulta ="SELECT a.numeroTT,b.tt_titulo,CONCAT(d.nombre,' ',d.apellidoPaterno,' ',d.apellidoMaterno) AS nombre,e.tipo
                                FROM ttdocente a 
                                INNER JOIN tt b ON a.numeroTT = b.tt_numero 
                                INNER JOIN docente c ON c.idDocente = a.idDocente 
                                INNER JOIN persona d ON d.idPersona = c.idPersona 
                                INNER JOIN roldocente e ON e.idRolDocente = a.idRolDocente 
                                WHERE a.numeroTT = '".$valor->numeroTT."' AND b.idStatus = 1;";

                    $relacionTT_Docente = RelacionTTDocente::SQL($consulta);

                    $TT = new TrabajoTerminal();
                    $TT->setNumeroTT($valor->numeroTT);
                    $TT->setTituloTT($relacionTT_Docente[0]->tt_titulo);
                    $TT->setFecha($valor->fecha);
                    $TT->setSalon($valor->salon);
                    $TT->setHorario($valor->horario);
                    $TT->setOptimo($valor->optimo);

                    // =====> Recorrido de docentes
                    foreach ($relacionTT_Docente as $docente) { $TT->setDocente($docente->nombre,$docente->tipo); }

                    // =====> Guardamos en el arreglo
                    $tt2_optimo[] = $TT;                        
                }
            }*/

            if(($resultado1) > 0 && count($resultado2) > 0){
                //  Recorrido de resultados tt1
                foreach ($resultado1 as $valor ) {
                    $TT = new TrabajoTerminal();
                    $TT->setNumeroTT($valor->numeroTT);
                    $TT->setTituloTT($valor->titulo);
                    $TT->setFecha($valor->fecha);
                    $TT->setSalon($valor->salon);
                    $TT->setHorario($valor->horario);
                    $TT->setOptimo($valor->optimo);

                    // =====> Recorrido de docentes
                    $TT->setDocente($valor->director1,'director');
                    if(!is_null($valor->director2)){ $TT->setDocente($valor->director2,'director'); }
                    $TT->setDocente($valor->sinodal1,'sinodal');
                    $TT->setDocente($valor->sinodal2,'sinodal');
                    $TT->setDocente($valor->sinodal3,'sinodal');

                    //foreach ($relacionTT_Docente as $docente) { $TT->setDocente($docente->nombre,$docente->tipo); }

                    // =====> Guardamos en el arreglo
                    $tt1_optimo[] = $TT;                        
                }

                //  Recorrido de resultados tt2
                foreach ($resultado2 as $valor ) {                    
                    $TT = new TrabajoTerminal();
                    $TT->setNumeroTT($valor->numeroTT);
                    $TT->setTituloTT($valor->titulo);
                    $TT->setFecha($valor->fecha);
                    $TT->setSalon($valor->salon);
                    $TT->setHorario($valor->horario);
                    $TT->setOptimo($valor->optimo);

                    // =====> Recorrido de docentes
                    $TT->setDocente($valor->director1,'director');
                    if(!is_null($valor->director2)){ $TT->setDocente($valor->director2,'director'); }
                    $TT->setDocente($valor->sinodal1,'sinodal');
                    $TT->setDocente($valor->sinodal2,'sinodal');
                    $TT->setDocente($valor->sinodal3,'sinodal');

                    // =====> Guardamos en el arreglo
                    $tt2_optimo[] = $TT;                        
                }

                //  Consulta para ortener las fechas de tt1
                $resultado = HorarioPresentacion::obtenerFechas("c.tt_tipo = 'TT1'",$anioActual,$ciclo);
                foreach ($resultado as $valor) { $fechastt1[] = $valor->fecha; }

                //  Consulta para ortener las fechas de tt2
                $resultado = HorarioPresentacion::obtenerFechas("c.tt_tipo != 'TT1'",$anioActual,$ciclo);
                foreach ($resultado as $valor) { $fechastt2[] = $valor->fecha; }

                //  Consulta para obtener horarios
                if(!isset($_SESSION['pre_horarios'])){
                    $sql = "SELECT * FROM horario";
                    $resultado = Horario::SQL($sql);
                    //$auxiliar = array();

                    //foreach ($resultado as $valor) { $auxiliar[] = $valor->horario_inicio; }
                    //$_SESSION['pre_horarios'] = $auxiliar;

                    $_SESSION['pre_horarios'] = $resultado;
                }
                
                //  Consulta para obtener salones
                if(!isset($_SESSION['pre_salones'])){ 
                    $sql = "SELECT numeroSalon FROM salon";
                    $resultado = Salon::SQL($sql);
                    $auxiliar = array();

                    foreach ($resultado as $valor) {
                        $auxiliar[] = $valor->numeroSalon;
                    }
                    $_SESSION['pre_salones'] = $auxiliar;
                }
                
                // Se agrega al session
                $_SESSION['tt1'] = $tt1_optimo ;
                $_SESSION['tt2'] = $tt2_optimo;

                $_SESSION['fechastt1'] = $fechastt1;
                $_SESSION['fechastt2'] = $fechastt2;            

                $_SESSION['anio'] = $anioActual;
                $_SESSION['ciclo'] = $ciclo;

            }else{
                $alertas['msg'] = 'nada';
                $alertas['info'] = 'No se encontraron horarios de presentacion. Intenta de nuevo';
            }
        }elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {   // ====================================>    POST
            //  Condicion
            $update = isset($_POST['update']);

            if($update){    //  Actualizar presentacion
                //  Obtenemos id de horario
                $idHorario = null;
                foreach ($_SESSION['pre_horarios'] as $valor) {
                    if($valor->horario_inicio == $_POST['horario']){ $idHorario = $valor->idhorario; break; }                    
                }

                //  Consulta para actualizar
                $sql = "UPDATE horariopresentacion 
                        SET salon = ".$_POST['salon'].", fecha = '".$_POST['fecha']."', horario = ".$idHorario.", optimo =".$_POST['optimo'].
                        " WHERE anio = ".$_SESSION['anio']." AND ciclo = ".$_SESSION['ciclo']." AND numeroTT = '".$_POST['update']."';";
                $alertas['msg'] = HorarioPresentacion::update($sql);

                //  Mensaje final
                if($alertas['msg'] == 'actualizado'){
                    $_SESSION['msg'] = 'Presentacion de Trabajo Terminal Actualizada Correctamente';
                    $_SESSION['estado'] = 'exitoso';
                    $_SESSION['cambio'] = true;
                }else {
                    $_SESSION['msg'] = $alertas['msg'];
                    $_SESSION['estado'] = 'no exitoso';
                }
            }else{          //  Reporte de excel
                //  Se crea el reporte
                $hoja = new Excel();
                $hoja->crearReportePresentaciones($_SESSION['presentaciones']);

                //  Mandamos a descargas
                $file = 'archivos/reporte/reporte.xlsx';
                $filename = 'reporte_'.$_SESSION['anio'].'-'.$_SESSION['ciclo'].'.xlsx';

                // Establecer las cabeceras
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Content-Length: ' . filesize($file));

                // Enviar el contenido del archivo
                readfile($file);
            }

            //  Redireccionamiento
            header("Location: /administrador/presentaciones?anio=".$_SESSION['anio']."&ciclo=".$_SESSION['ciclo']);
            exit(); //Salimos del scrip para que no se ejecute algo mas
        }

        $router->render('administrativo/presentaciones', [
            'alertas' => $alertas
        ]);
    }

    //  =====> Formulario del AG <=====
    public static function formulario1_AG(Router $router) {
        $alertas = [];
        session_start();

        //Condicion de valores ingresados
        $condicion = isset($_GET['cb_salon']) && isset($_GET['cb_horario']) &&
                     isset($_GET['fechainicio']) && isset($_GET['fechafin']);

        if($condicion){
            $fecha1 = $_GET['fechainicio'];
            $fecha2 = $_GET['fechafin'];

            $listadias = obtenerDias($fecha1, $fecha2);

            $_SESSION['listasalones'] = $_GET['cb_salon'];
            $_SESSION['listahorarios'] = $_GET['cb_horario'];
            $_SESSION['listadias'] = $listadias;

            //persistencia
            $_SESSION['fechainicio'] = $fecha1;
            $_SESSION['fechafin'] = $fecha2;

        }else{
            //Eliminamos elemtos de session en caso de 'reset'
            //freeElementSession();
            if(isset($_SESSION['fechainicio'])){ unset($_SESSION['fechainicio']); }
            if(isset($_SESSION['fechafin'])){ unset($_SESSION['fechafin']); }
            if(isset($_SESSION['listasalones'])){ unset($_SESSION['listasalones']); }
            if(isset($_SESSION['listahorarios'])){ unset($_SESSION['listahorarios']); }
            if(isset($_SESSION['listadias'])){ unset($_SESSION['listadias']); }
            
            //Consulta Salones
            $consulta = "SELECT * FROM salon";
            $listaSalones = Salon::SQL($consulta);

            //Consulta Horario
            $consulta = "SELECT * FROM horario";
            $listaHorarios = Horario::SQL($consulta);

            //Consulta TT1 y Consulta TT2
            $consulta = "SELECT * FROM tt WHERE tt_tipo = 'TT1' AND idStatus = 1;";
            $listaTT1 = TT::SQL($consulta);
            
            $consulta = "SELECT * FROM tt WHERE (tt_tipo ='TT2' OR tt_tipo = 'TTR') AND idStatus = 1;";
            $listaTT2 = TT::SQL($consulta);

            //Agregamos los datos a SESSION
            if(isset($listaTT1)){ $_SESSION['listatt1'] = $listaTT1; }
            if(isset($listaTT2)){ $_SESSION['listatt2'] = $listaTT2; }
            if(isset($listaSalones)){ $_SESSION['salones'] = $listaSalones; }
            if(isset($listaHorarios)){ $_SESSION['horarios'] = $listaHorarios; }
        }

        //Render
        $router->render('administrativo/formulario1_AG', [
            'alertas' => $alertas
        ]);
    }

    //  =====> Resultados del AG <=====
    public static function resultados_AG(Router $router) {
        //  Variables
        session_start();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $totaltt1 = null;
            $totaltt2 = null;
            $totalhorarios = null;
            $totalsalones = null;
            $totaldiastt1 = null;
            $totaldiastt2 = null;

            //  Validacion de Checkbox horarios y salones para guardarlos en $_session
            if(isset($_GET['tt1_cb_dia'])){ $_SESSION['listadias_tt1'] = $_GET['tt1_cb_dia']; }  //Fechas TT1
            if(isset($_GET['tt2_cb_dia'])){ $_SESSION['listadias_tt2yr'] = $_GET['tt2_cb_dia']; }//Fechas TT2

            //  Validacion de relacion [espacios <==> tts]
            if(isset($_SESSION['listatt1'])) { $totaltt1 = count($_SESSION['listatt1']); }
            if(isset($_SESSION['listatt2'])) { $totaltt2 = count($_SESSION['listatt2']); }
            if(isset($_SESSION['listahorarios'])) { $totalhorarios = count($_SESSION['listahorarios']); }
            if(isset($_SESSION['listasalones'])) { $totalsalones = count($_SESSION['listasalones']); }
            if(isset($_SESSION['listadias_tt1'])) { $totaldiastt1 = count($_SESSION['listadias_tt1']); }
            if(isset($_SESSION['listadias_tt2yr'])) { $totaldiastt2 = count($_SESSION['listadias_tt2yr']); }

            if($totaltt1 != null && $totaltt2 != null && $totalhorarios != null && $totalsalones != null && $totaldiastt1 != null && $totaldiastt2 != null){    //  Entra si: no existe una variable
                //  Espacios
                $espaciostt1 = $totalhorarios * $totalsalones * $totaldiastt1;
                $espaciostt2 = $totalhorarios * $totalsalones * $totaldiastt2;

                //  Mensajes de alerta por espacios
                if($espaciostt1 < $totaltt1){ $alertas['msgtt1'] = 'Los espacios para presentaciones son menores al total de TT1. Hay '.$espaciostt1.' espacios para '.$totaltt1.' TTs 1'; }
                if($espaciostt2 < $totaltt2){ $alertas['msgtt2'] = 'Los espacios para presentaciones son menores al total de TT2. Hay '.$espaciostt2.' espacios para '.$totaltt2.' TTs 2 y R'; }

                //  Proceso del Algoritmo Genetico
                if(!isset($alertas['msgtt1']) && !isset($alertas['msgtt2'])){ //  Entra si: no existen los mensajes de alerta
                    // =====> Proceso [Consulta: relacion tt <=> docente]
                    $consulta ="SELECT a.estatus,a.numeroTT,CONCAT(d.nombre,' ',d.apellidoPaterno,' ',d.apellidoMaterno) AS nombre,e.tipo,c.docente_horaInicio,c.docente_horaFin
                    FROM ttdocente a 
                    INNER JOIN tt b ON a.numeroTT = b.tt_numero 
                    INNER JOIN docente c ON c.idDocente = a.idDocente 
                    INNER JOIN persona d ON d.idPersona = c.idPersona 
                    INNER JOIN roldocente e ON e.idRolDocente = a.idRolDocente 
                    WHERE b.idStatus = 1 AND a.estatus = 1 and a.idRolDocente != 3 ORDER BY a.numeroTT ASC;";
                    

                    $relacionTT_Docente = RelacionTTDocente::SQL($consulta);

                    $_SESSION['tt_docente'] = $relacionTT_Docente; //Agregamos al Session

                    // =====> Proceso [Algoritmo Genetico]
                    $tiempo_inicio = microtime(true);
                    set_time_limit(300);

                    $AG1 = new AlgoritmoGenetico($_SESSION['listatt1'], $_SESSION['tt_docente'], $_SESSION['listahorarios'],$_SESSION['listasalones'], $_SESSION['listadias_tt1']);
                    $AG2 = new AlgoritmoGenetico($_SESSION['listatt2'], $_SESSION['tt_docente'], $_SESSION['listahorarios'],$_SESSION['listasalones'], $_SESSION['listadias_tt2yr']);

                    $presentacionesTT1 = $AG1->obtenerHorarios();
                    $presentacionesTT2 = $AG2->obtenerHorarios();

                    //  $presentacionesTT1->imprimir();
                    //  $presentacionesTT2->imprimir();
                    unset($AG1);
                    unset($AG2);

                    $_SESSION['presentaciones_tt1'] = $presentacionesTT1;
                    $_SESSION['presentaciones_tt2'] = $presentacionesTT2;

                    $tiempo_fin = microtime(true);
                    $tiempo_total = $tiempo_fin - $tiempo_inicio;
                    //echo '<p> El AG tardo '.$tiempo_total.' segundos en ejecutarse';
                }
            }else {
                $alertas['msg'] = 'nada';
            }
        }elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['guardar'])){
                //  Variables Locales
                $optimostt1 = $_SESSION['presentaciones_tt1']->getOptimos();     //  Obtenemos poblacion de optimos
                $noOptimostt1 = $_SESSION['presentaciones_tt1']->getNoOptimos(); // Obtenemos poblacion de no optimos
                $optimostt2 = $_SESSION['presentaciones_tt2']->getOptimos();     //  Obtenemos poblacion de optimos
                $noOptimostt2 = $_SESSION['presentaciones_tt2']->getNoOptimos(); // Obtenemos poblacion de no optimos

                $anioActual = date('Y');
                $mesActual = date('m');
                $ciclo = 1;
                            
                $sql = "SELECT horario_inicio FROM horario;";   //  Obtenermos los horarios
                $resultado = Horario::SQL($sql);

                $horarios = array();
                $indice = 1;
                foreach ($resultado as $horario) {
                    $horarios[$horario->horario_inicio] = $indice;
                    $indice++;
                }

                //  Verificar en que ciclo se hace el registro
                if((int)$mesActual <= 6){ $ciclo = 2; }
                else{ $anioActual = (int)$anioActual + 1; }
                //else{ $aux = (int)$anioActual + 1; $anioActual = $aux; }


                //$resultado = HorarioPresentacion::insertarHorario('2023-A040','2023-05-20',1,1205,$anioActual,$ciclo,0);   
                foreach ($optimostt1 as $valor) {   //  Horarios optimos tt1
                    $resultado = HorarioPresentacion::insertarHorario($valor,$horarios[$valor->getHorario()],$anioActual,$ciclo,1);
                    //$resultado = HorarioPresentacion::insertarHorario($valor->getNumeroTT(),$valor->getFecha(),$horarios[$valor->getHorario()],$valor->getSalon(),$anioActual,$ciclo,1);
                }

                foreach ($noOptimostt1 as $valor) {   //  Horarios NO optimos tt1
                    $resultado = HorarioPresentacion::insertarHorario($valor,$horarios[$valor->getHorario()],$anioActual,$ciclo,0);
                }
                
                foreach ($optimostt2 as $valor) {   //  Horarios optimos tt2
                    $resultado = HorarioPresentacion::insertarHorario($valor,$horarios[$valor->getHorario()],$anioActual,$ciclo,1);
                }

                foreach ($noOptimostt2 as $valor) {   //  Horarios NO optimos tt2
                    $resultado = HorarioPresentacion::insertarHorario($valor,$horarios[$valor->getHorario()],$anioActual,$ciclo,0);
                }
                
                $_SESSION['msg'] = 'Horarios de presentacion registrados';
                /*
                //  Consulta para revisar si existe ya un registro con el numero de tt y el ciclo
                $sql = "SELECT * FROM horariopresentacion WHERE numeroTT = '2023-A041' AND anio = 2023 AND ciclo = 2";
                $horarioP = HorarioPresentacion::SQL($sql);

                if(count($horarioP) == 1){          //  Entra si: existe ya un registro, se hace un update
                    $sql = "UPDATE horariopresentacion SET salon = 1205, horario = 3, fecha = '2023-05-10'
                            WHERE numeroTT = '2023-A041' AND anio = 2023 AND ciclo = 2;";
                    $horarioP = HorarioPresentacion::update($sql);
                }elseif(count($horarioP) == 0) {    //  Entra si: no existe un registro, se hace un insert
                    $sql = "INSERT INTO horariopresentacion VALUES (1205,'2023-05-04','2023-A041',2," . $anioActual . "," . $ciclo . ")";
                    $horarioP = HorarioPresentacion::create($sql);
                } */
            }
        }

        //Render
        $router->render('administrativo/resultados_AG', [
            'alertas' => $alertas
        ]);
    }

    //  =====> Registro masivo <=====
    public static function registrartts(Router $router) {
        //  Variables
        session_start();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //            debuguear($_FILES);
            if($_FILES['archivo']['type'] === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                $tiempo_inicio = microtime(true);

                // =====> Paso: Subida de Archivo
                $ruta_archivo = $_FILES['archivo']['tmp_name'];
                $ruta_destino = 'archivos/registros/registro.xlsx';
                move_uploaded_file($ruta_archivo, $ruta_destino);

                // =====> Obtenemos datos del administrativo que hara el registro
                $sql = "SELECT * FROM `administrativo` WHERE idPersona = ".$_SESSION['idPersona'];
                $resultado = Administrativo::SQL($sql);
                $admin = $resultado[0];

                // =====> Lectura de datos
                $lectura = new Excel();
                $listatts = $lectura->leerTTs();        //  Recibimos la lista de objetos tts                
                
                //  Obtiene el ciclo actual
                $anioActual = date('Y');    //  Año actual
                $mesActual = date('m');     //  Mes actual
                $ciclo = 1;                 //  Ciclo default

                $rol = array('director' => 1,'sinodal' => 2, 'seguimiento' => 3);

                if((int)$mesActual <= 6){ $ciclo = 2; }
                else{ $anioActual = (int)$anioActual + 1; }

                //  Recorrido de listatts para registrar  TTs
                $D_noregistrado = array();
                $E_noregistrado = array();

                foreach ($listatts as $tt) {                    
                    // =====> Busca si existe el tt
                    $numeroTT = $tt->getNumeroTT();
                    $sql = "SELECT * FROM `tt` WHERE tt_numero = '".$numeroTT."';";
                    $existe = TT::SQL($sql);

                    if(count($existe) == 0){    // ==> Entra si: no existe el tt
                        // =====>Se inserta elemento a tabla 'tt'
                        $sql = "INSERT INTO tt
                                VAlUES ('".$numeroTT."','".$tt->getTituloTT()."','".$tt->getTipoTT()."',".$anioActual.",".$ciclo.",".$admin->idAdministrativo.",'archivos/protocolos/".$numeroTT.".pdf',1)";
                        $resultado = TT::create($sql);
                    }else{                      // ==> Entra si: existe el tt
                        // =====>Se vuelve cambia el status del tt a activo
                        $sql = "UPDATE tt SET tt_titulo = '".$tt->getTituloTT()."',tt_tipo = '".$tt->getTipoTT()."', idStatus = 1 WHERE tt_numero = '".$numeroTT."';"; 
                        $alertas['msg'] = TT::update($sql);
                    }

                    //  Grupo docente
                    $grupoDocente = $tt->getGrupoDocente();
                    //  Recorrido grupo docente
                    foreach ($grupoDocente as $nombre => $tipo) {
                        //  Verifica si existe el docente
                        $sql = "SELECT * FROM docente a
                                INNER JOIN persona b ON a.idPersona = b.idPersona
                                WHERE CONCAT(b.nombre,' ',b.apellidoPaterno,' ',b.apellidoMaterno) = '".$nombre."'";
                        $resultado = Docente::SQL($sql);

                        if(count($resultado) == 1){    //  Entra si: el docente esta registrado
                            $idDocente = $resultado[0]->idDocente;
                            $idPersona = $resultado[0]->idPersona;
                            
                            //  Verifica si ya el docente ya tiene un rol para el tt
                            $sql = "SELECT * FROM ttdocente a 
                            INNER JOIN docente b ON a.idDocente = b.idDocente
                            WHERE a.numeroTT = '".$numeroTT."' AND a.idDocente = '".$idDocente."' AND a.idRolDocente = ".$rol[$tipo].";";
                            $resultado = TTDocente::SQL($sql);

                            if(count($resultado) == 1){ //  Si ya tenia un rol se activa de nuevo
                                if($resultado[0]->estatus == '0'){  //  Entra si: estatus es 0
                                    $sql = "UPDATE ttdocente SET estatus = 1
                                            WHERE idDocente = '".$idDocente."' AND numeroTT = '".$numeroTT."'";
                                    $resultado = TTDocente::update($sql);                                    
                                }
                            }else {                     //  Si no tiene un rol se inserta                                
                                $sql = "INSERT INTO ttdocente VALUES ('".$numeroTT."','".$idDocente."',".$rol[$tipo].",1)";
                                $resultado = TTDocente::update($sql);                             
                            }

                            $sql = "UPDATE persona SET idStatus = 1 WHERE idPersona = ".$idPersona.";";
                            $resultado = Persona::update($sql);

                        }else{
                            if(!in_array($nombre,$D_noregistrado)) { $D_noregistrado[] = $nombre.' <=>'.$tipo.' => '.$numeroTT; }
                        }
                    }

                    //  Grupo estudiantes
                    $Estudiantes = $tt->getEstudiantes();
                    //  Recorrido grupo estudiantes
                    foreach ($Estudiantes as $estudiante) {
                        //  Verifica si existe el estudiante
                        $sql = "SELECT * FROM estudiante a
                                INNER JOIN persona b ON a.idPersona = b.idPersona
                                WHERE a.boleta = ".$estudiante->boleta.";";
                        $resultado = Estudiante::SQL($sql);

                        if(count($resultado) == 1){         //  Entra si: el estudiante esta registrado                            
                            $sql = "UPDATE persona SET idStatus = 1 WHERE idPersona = ".$resultado[0]->idPersona."";
                            $resultado = Estudiante::update($sql);                            
                        }elseif(count($resultado) == 0){    //  Entra si: el estudiante no esta registrado                            
                            // =====> Se inserta elemento a tabla 'persona'
                            $sql = "INSERT INTO persona (nombre,apellidoPaterno,apellidoMaterno,correo,idRol,idStatus)
                            VAlUES ('".$estudiante->nombre."','".$estudiante->apellidoPaterno."','".$estudiante->apellidoMaterno."','".$estudiante->correo."',3,1)";
                            $resultado = Persona::create($sql);
                            
                            // =====> Obtenemos el idPersona del ultimo registro
                            $sql = "SELECT idPersona FROM persona ORDER BY idPersona DESC LIMIT 1;";
                            $resultado = Persona::SQL($sql);
                            $idPersona = $resultado[0]->idPersona;
                            
                            // =====> Se inserta como estudiante
                            $sql = "INSERT INTO estudiante (boleta,numeroTT,idPersona) VALUES ('".$estudiante->boleta."','".$numeroTT."','".$idPersona."');";
                            $resultado = Estudiante::create($sql);                            
                            
                            // =====> Creamos usuario
                            $usuario = new Usuario(array('password' => $estudiante->boleta));
                            $usuario->hashPassword();

                            // =====> Se inserta usuario
                            $sql = "INSERT INTO usuario VALUES ('".$estudiante->boleta."','".$usuario->password."',".$idPersona.",NULL);";
                            $resultado = Usuario::create($sql);
                        }else{
                            $E_noregistrado[] = $estudiante->boleta .' <=> '.$resultado;
                        }                  
                    }
                }
                
                $tiempo_fin = microtime(true);
                $tiempo_total = $tiempo_fin - $tiempo_inicio;
                //echo '<p> El registro tardo '.$tiempo_total.' segundos en ejecutarse';
            }else{
                $_SESSION['msg'] = 'Error: El archivo no es excel';
                $_SESSION['estado'] = 'no exitoso';
            }
        }

        //Render
        $router->render('administrativo/registrartts', [
            'alertas' => $alertas
        ]);
    }
}