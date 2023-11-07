<?php

function obtenerDias($fechainicio,$fechafin): array{
    // Array para guardar los días entre las fechas
    $listadias = array();

    // Convertir las fechas a objetos de fecha
    $fecha1_obj = strtotime($fechainicio);
    $fecha2_obj = strtotime($fechafin);

    // Calcular la diferencia de tiempo en segundos
    $segundos_entre_fechas = $fecha2_obj - $fecha1_obj;

    // Convertir la diferencia de tiempo en días
    $dias_entre_fechas = round($segundos_entre_fechas / 86400);    

    // Iterar por cada día entre las fechas y guardarlos en el array
    for ($i = 0; $i <= $dias_entre_fechas; $i++) {
        $fecha = date('Y-m-d', strtotime("+$i day", $fecha1_obj));
        $listadias[] = $fecha;
    }

    return $listadias;
}

function freeElementSession(){
    foreach ($_SESSION as $key => $value) {
        // Eliminar el elemento si no es usuario ni permisos
        if ($key != 'idPersona' && $key != 'nombre' && $key != 'apellidoP' && $key != 'apellidoM' && $key != 'email' && $key != 'user' && $key != 'rol' && $key != 'login') {
            unset($_SESSION[$key]);
        }
    }
}

function siExisteSessionCount($elemento){
    return isset($_SESSION[$elemento]) ? count($_SESSION[$elemento]) : null;
}

function siExisteSession($elemento){
    return isset($_SESSION[$elemento]) ? true : false;
}

function siExisteSessionGroup($elementos){
    foreach ($elementos as $elemento)
        if(!isset($_SESSION[$elemento]))
            return false;

    return true;
}

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $actual, string $proximo): bool {

    if($actual !== $proximo) {
        return true;
    }
    return false;
}

// Función que revisa que el usuario este autenticado
function isAuth() : void {
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

function isAdmin() : void {
    if(!isset($_SESSION['admin'])) {
        header('Location: /');
    }
}

//  =====> Funciones del AG <=====
function encontrarHO($horarios_docentes,$lista_horarios){
    /**
     * Esta funcion recibe una lista de docentes junto con sus horarios y se
     * buscara el horario optimo en cada grupo docente de tt. Para que el horario
     * sea optimo se tiene que repetir 4 o 5 veces dependiendo del numero de
     * docentes ya que todos tienen que tener el horario a evaluar.
     * 
     * Nota: tomar en cuenta si no existe un horario optimo en el grupo docente.
    */

    $Docentes_size = count($horarios_docentes);
    $horas = array();       // Lista para almacenar las horas

    //=====> Recorrido de horario de los docentes
    foreach ($horarios_docentes as $horario) {
        // Definir el rango de horas
        $hora = explode(" - ", $horario);       // Arreglo de par de horas del rango

        $hora_inicio = substr($hora[0],3,1) == '3' ? new DateTime(substr_replace($hora[0],'0', 3, 1)) : new DateTime($hora[0]);
        $hora_fin = new DateTime($hora[1]);

        while ($hora_inicio <= $hora_fin) {
                $horas[] = $hora_inicio->format('H:i:s'); // Agregar la hora actual al arreglo
                $hora_inicio->add(new DateInterval('PT1H')); // Incrementar la hora de inicio en una hora
        }

    }

    $ocurrencias = array_count_values($horas);  // Ocurrencia de cada hora
    //$horas = array_unique($horas);              //limpiamos elementos repetidos
    $horas = array();

    if($Docentes_size == 5){
        foreach ($ocurrencias as $key => $value) { if($value == 5) $horas[] = $key; }
    }else if($Docentes_size == 4){
        foreach ($ocurrencias as $key => $value) { if($value == 4) $horas[] = $key; }
    }
    
    $horas = array_intersect($horas,$lista_horarios);   //Interseccion con horarios de presentacion

    return count($horas) >= 1 ? $horas: ['00:00:00'];
}

function imprimirDocentes($parametro) {

    if(count($parametro) == 5){

        foreach ($parametro as $key => $value) {
            if($value == 'director'){
                echo '<td class="small">'.$key.'</td>';
            }
        }

        foreach ($parametro as $key => $value) {                
            if($value == 'sinodal'){
                echo '<td class="small">'.$key.'</td>';
            }
        }
    }else{
        foreach ($parametro as $key => $value) {
            if($value == 'director'){
                echo '<td class="small">'.$key.'</td>';
            }
        }

        echo '<td class="small"></td>';

        foreach ($parametro as $key => $value) {              
            if($value == 'sinodal'){
                echo '<td class="small">'.$key.'</td>';
            }
        }
    }
}

// =====> Funciones de Controladores <=====
function encontarDocente(array $docentes,$idDocente){
    foreach ($docentes as $docente) {
        if($docente->idDocente == $idDocente){            
            return 'si';
            break;
        }        
    }
    return 'no';
}