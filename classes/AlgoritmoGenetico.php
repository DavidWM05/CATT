<?php
/**
 * Cromosoma ==> [tt|horario|salon|fecha]
 * 
*/
namespace Classes;

use Classes\TrabajoTerminal;
use Classes\Cromosoma;
use Classes\Horario;
use Classes\Poblacion;

class AlgoritmoGenetico{
    private $lista_TTs;             //  Lista con Trabajos Terminales
    private $lista_fechahorario;    //  Lista de relacion [Horario <=> Fecha <=> Salon]
    private $respaldo_fechahorario; //  Lista de respaldo [Horario <=> Fecha <=> Salon]
    private $poblacion;             //  Lista auxiliar para guardar la poblacion
    private $poblacion_aux;         //  Lista auxiliar para guardar cromosomas hijos
    private $valor_cromosoma;       //  Valor en porcentaje de 1 cromosoma de poblacion actual
    private $valor_condicion;       //  Valor en porcentaje de 1 condicion de poblacion actual
    private $porcentaje;            //  Variable auxiliar para porcentaje de poblacion actual
    private $n;                     //  Numero de iteraciones en condicion de parada [1]
    private $efectividad;           //  Efectividad del mejor valor alcanzado
    private $epoca;                 //  Epoca de la poblacion padre

    public function __construct($lista_tt,$listatt_docente,$lista_horarios,$lista_salones,$lista_fechas){
        $this->lista_TTs = array();
        $this->poblacion = array();
        $this->poblacion_aux = array();
        $this->lista_fechahorario = array();
        $this->respaldo_fechahorario = array();
        $this->n = 2000;
        $this->efectividad = 0.0;
        //=====> proceso [insertar datos a listas]
        $this->inicioDeListas($lista_tt,$listatt_docente,$lista_horarios,$lista_salones,$lista_fechas);
    }

    //=====> obtener horarios de presentaciones <=====
    public function obtenerHorarios(){
        // Variables
        $iterador = 0;  //Variable de parada [3]: iteraciones de poblaciones

        // =====> Poblacione padre <=====
        $this->poblacionInicial();                              //  Generar: poblacion inicial
        $padre = $this->obtenerPoblacion();                     //  Se obtiene la primera poblacion
        $valorCromosomaInicial = $this->valor_cromosoma;        //  Guardar: primer valor de cromosoma
        $epoca = $this->epoca;                                  //  Guardar: epoca del padre

        // =====> Poblaciones hijo <=====
        $poblaciones = array();                                 //  Lista para guardar poblaciones optimas
        $poblaciones [] = $padre;                               //  Guardar: poblacion padre
        $tam_poblacion = 0;

        do {            
            $this->relacionarTT_Espacios();                     //  Reasignacion de espacios
            $tam_poblacion = count($this->poblacion);
            if( $tam_poblacion >= 1){
                $auxiliar = $this->obtenerPoblacion();              //  Generar: poblacion n                
                $poblaciones[] = $auxiliar;                         //  Guardar: poblacion n
                $iterador++;
            }
        } while ($iterador < 20 && $tam_poblacion > 0);                               //  Condicion de parada [3]

        $this->valor_cromosoma = $valorCromosomaInicial;        //  Guardamos valor de cromosoma de la poblacion padre
        $listaOptimos = $this->orderPoblaciones($poblaciones);  //  Se ordenan los cromosomas por fecha
        $listaNoOptimos = $this->poblacion;

        //Retornamos la poblacion como clase
        return new Poblacion($listaOptimos,$listaNoOptimos,$this->efectividad,$epoca);
    }

    //=====> Iniciar todos las variables a utilizar <=====
    private function inicioDeListas($lista_tt,$listatt_docente,$lista_horarios,$lista_salones,$lista_fechas) {        
        //=====> Lista [Relacion: Horario <=> Salon <=> Docente]
        foreach ($lista_fechas as $fecha){
            foreach ($lista_horarios as $hora){
                foreach ($lista_salones as $salon){
                    $this->lista_fechahorario[] = new Horario($fecha,$salon,$hora);
                }
            }
        }

        //Respaldo para ordenar poblacion final
        $this->respaldo_fechahorario = $this->lista_fechahorario;

        //=====> Lista [Relación: TT <=> Docente]
        foreach ($lista_tt as $tt) {                //Recorrido de lista de tts
            $auxiliar = new TrabajoTerminal();                // Objeto tt
            $auxiliar->setNumeroTT($tt->tt_numero); // Nombre tt
            $auxiliar->setTituloTT($tt->tt_titulo); // Titulo tt
            $horariosDocentes = array();

            foreach ($listatt_docente as $docente) {        // Recorrido de docentes <=> tt
                if($docente->numeroTT == $tt->tt_numero){
                    $auxiliar->setDocente($docente->nombre,$docente->tipo);     // Asignamos docente y tipo docente
                    $horariosDocentes [] = $docente->docente_horaInicio.' - '.$docente->docente_horaFin;
                }else if(count($auxiliar->getGrupoDocente()) != 0 ){            // Aun no encuentra los docentes relacionados
                    $auxiliar->setHorarioOptimo(encontrarHO($horariosDocentes,$lista_horarios));  // Encuentra el Horario Optimo
                    break;
                }
            }
            
            array_push($this->lista_TTs,$auxiliar); //  Guardamos el objeto en la lista de Trabajos Terminales
        }
    }

    //=====> Paso [0]: Inicio de algoritmo [poblaciones] <=====
    private function obtenerPoblacion() {
        //Variables Locales
        $this->valor_cromosoma = (100/count($this->poblacion));        // [Valor % cromosoma]
        $this->valor_condicion = ($this->valor_cromosoma/2);    // [Valor % condicion]
        $iteraciones = $this->n;    // Variable de parada [1]: n iteraciones de combinaciones
        $mejorpoblacion = null;     // Variable de mejor poblacion
        $mejorporcentaje = 0.0;     // Auxiliar de porcentaje de la poblacion local
        $epoca = 0;                 // Variable de epoca en la que se encontro la mejor poblacion
        $optimos = 0;

        //======> Proceso [Generar: epocas]
        for ($i = 0; $i < $iteraciones; $i++) {
            $variable = $this->prueba($this->poblacion);//Variable de parada [2]: calificar poblacion

            if($this->porcentaje > $mejorporcentaje){   // Evaluamos si el porcentaje actual es mejor que el de la poblacion anterior
                $mejorporcentaje = $this->porcentaje;   // Se guarda el nuevo porcentaje
                $mejorpoblacion = $this->poblacion;     // Guardamos la nueva poblacion
                $epoca = $i;                            // Epoca de la mejor poblacion
            }

            /** Condicion de parada [2]:
             *  1. calificacion poblacion = valor optima minima
             *  2. Fin de iteraciones
             */
            if($variable == 1 || $i == ($iteraciones-1)){
                // =====> Busqueda de cromosomas optimos
                $poblacionOptima = array();     // Lista de cromosomas optimos
                $poblacionNoOptima = array();   // Lista de cromosomas no optimos

                foreach ($mejorpoblacion as $cromosoma) {   //Recorrido de mejorpoblacion
                    // Condicion optimo: no traslape docente | no traslape horario
                    $condicionOptimo = $cromosoma->getEvaluacion_CHOP() != null && $cromosoma->getEvaluacion_CDT() != null;
                    if($condicionOptimo){   // Evaluacion de condicion
                        $cromosoma->setOptimo(1);
                        $poblacionOptima[] = $cromosoma;  //Guardamos solo cromosomas optimos
                        $optimos++;
                    }else{
                        $poblacionNoOptima[] = $cromosoma;
                    }         
                }                
                $this->poblacion = array();             //  Se limpia poblacion global
                $this->poblacion = $poblacionNoOptima;  //  Se asigna poblacion no optima a la poblacion global                

                //Reingreso de espacios
                $this->reingresoEspacios();             //  Asignamos de nuevo los espacios a la poblacion no optima
                $this->porcentaje = $mejorporcentaje;   //  Guardamos el porcentaje local en el global
                $this->epoca = $epoca;                  //  Guardamos la epoca local en la global
                return $poblacionOptima;
            }else{                
                //Paso 4: Seleccion de padres;
                $this->seleccionPadres();
                //Paso 2: Fitness
                $this->porcentaje = $this->aptitud();
            }
        }
    }
    
    //=====> Paso [1]: Poblacion inicial <=====
    private function poblacionInicial() {            
        // Variables
        $lista_TTs_auxiliar = $this->lista_TTs; //Clonamos la lista de TTs
        
        //====| Generacion: Poblacion Inicial (tts al azar)|====
        while(!empty($lista_TTs_auxiliar)) {                //condicion: lista_TTs vacio
                $indice = array_rand($lista_TTs_auxiliar);  //Extraemos nombre de TT al azar
                $tt = $lista_TTs_auxiliar[$indice];         //Extraemos objeto TT de la lista

                $indice2 = array_rand($this->lista_fechahorario);
                $value = $this->lista_fechahorario[$indice2];
                
                $this->poblacion[] = new Cromosoma($tt->getNumeroTT(),$tt->getTituloTT(),$value->getFecha(),$value->getHora(),$value->getSalon(),
                                                   $tt->getHorarioOptimo(),$tt->getGrupoDocente());
                unset($lista_TTs_auxiliar[$indice]);        //Eliminamos ese objeto
                unset($this->lista_fechahorario[$indice2]);
        }

        //=====> Proceso [Encontrar: Fitness]
        $this->porcentaje = $this->aptitud();
    }

    //=====> PASO [2]: Evaluacion, Fitness o aptitud <=====
    private function aptitud() {
        /* 
        *   Condiciones para prueba de aptitud (fitness)
        *   
        *   1. Condicion de Horario Optimo Presentacion (CHOP).
        *   2. Condicion Docente Traslape (CDT)
        */
        $porcentaje = 0.0;  //Procentaje de efectividad de la problacion

        // =====> Proceso [Obtener: CHOP]
        foreach ($this->poblacion as $cromosoma) {
            $horarioO = $cromosoma->getHorarioOptimo();                         // Obtiene los horarios optimos del cromosoma
            $horarioP = substr($cromosoma->getHorario(),0,8);                   // Obtiene el horario de presentacion asignado
            $cromosoma->setEvaluacion_CHOP($this->CHOP($horarioO,$horarioP));   // Asigna evaluacion CHOP
            $calificacion = $cromosoma->getEvaluacion_CHOP();                   // Obtiene calificacion CHOP
            $porcentaje += $calificacion;                                       // Incrementa el porcentaje de la poblacion
        }

        // =====> Proceso [Obtener: CDT]
        $porcentaje += $this->CDT();                                            // Incrementa el porcentaje de la poblacion

        return $porcentaje;
    }

    //=====> PASO [3]: Prueba de condicion parada <=====
    private function prueba($poblacion) {
        $valor_aceptado = 70.00;
        
        if($this->porcentaje >= $valor_aceptado){
            return 1;
        }else{
            return 0;
        }
    }

    //=====> PASO [4]: Seleccion de padres cromosomas <=====
    private function seleccionPadres() {
        $numeroPadres = count($this->poblacion);
        
        if(($numeroPadres % 2) == 0){       //Total tts Par
            while (!empty($this->poblacion)) {
                //Cromosoma padre 1
                $index = array_rand($this->poblacion);
                $padre_1 = $this->poblacion[$index];
                unset($this->poblacion[$index]);
                //Cromosoma padre 2
                $index = array_rand($this->poblacion);
                $padre_2 = $this->poblacion[$index];
                unset($this->poblacion[$index]);
                
                //Paso [5]: Cruza
                $this->cruza($padre_1,$padre_2);
            }
            //Paso [7]: Sustitucion de poblacion
            $this->Sustitucion();
        }else{                              //Total tts Impar
            
            $index = array_rand($this->poblacion);
            $cromosoma = $this->poblacion[$index];
            unset($this->poblacion[$index]);

            while (!empty($this->poblacion)) {
                //Cromosoma padre 1
                $index = array_rand($this->poblacion);
                $padre_1 = $this->poblacion[$index];
                unset($this->poblacion[$index]);
                //Cromosoma padre 2
                $index = array_rand($this->poblacion);
                $padre_2 = $this->poblacion[$index];
                unset($this->poblacion[$index]);
                
                //Paso [5]: Cruza
                $this->cruza($padre_1,$padre_2);
            }
            $this->poblacion_aux[] = $cromosoma;
            //Paso [7]: Sustitucion de poblacion
            $this->Sustitucion();   
        }
    }

    //=====> PASO [5]: Cruza de genes <=====
    private function cruza($padre_1,$padre_2) {
        $genes1 = array($padre_1->getFecha(),$padre_1->getHorario(),$padre_1->getSalon());
        $genes2 = array($padre_2->getFecha(),$padre_2->getHorario(),$padre_2->getSalon());

        $padre_1->setFecha($genes2[0]);
        $padre_1->setHorario($genes2[1]);
        $padre_1->setSalon($genes2[2]);

        $padre_2->setFecha($genes1[0]);
        $padre_2->setHorario($genes1[1]);
        $padre_2->setSalon($genes1[2]);

        //Proceso [Paso[6]: Mutacion]
        //$padre_1 = $this->mutacion($padre_1);
        //$padre_2 = $this->mutacion($padre_2);

        array_push($this->poblacion_aux,$padre_1);
        array_push($this->poblacion_aux,$padre_2);
    }

    //=====> PASO [7]: Sustitucion <=====
    private function Sustitucion() {
        $this->poblacion = $this->poblacion_aux;
        $this->limpiarListasYVariables();
    }

    //=====> Condiciones <=====
    private function CHOP($horarioO,$horarioP){ 
        return in_array($horarioP,$horarioO) ? $this->valor_condicion : 0; 
    }
   
    private function CDT(){
        $size_poblacion = count($this->poblacion);
        $porcentaje = 0.0;
        
        for($i = 0; $i < $size_poblacion; $i++) {       // Recorrido de cromosoma 1x1
            for ($j = 0; $j < $size_poblacion; $j++) {  // Comparacion con los demas cromosomas
                $condicion_dia = ($this->poblacion[$i]->getFecha() == $this->poblacion[$j]->getFecha());        // True si es el mismo dia
                $condicion_hora = ($this->poblacion[$i]->getHorario() == $this->poblacion[$j]->getHorario());   // True si es el mismo horario

                if(($i != $j) && $condicion_hora && $condicion_dia){
                    $cromosoma1 = $this->poblacion[$i]->getGrupoDocente();          // Obtiene grupo docente cromosoma 1
                    $cromosoma2 = $this->poblacion[$j]->getGrupoDocente();          // Obtiene grupo docente cromosoma 2
                    $interseccion = $this->hayInterseccion($cromosoma1,$cromosoma2);// Verifica si hay intersecciones de docentes

                    if($interseccion == 1){
                        $this->poblacion[$i]->setEvaluacion_CDT(0);                 // Asigna evaluacion CDT del cromosoma
                        break;
                    }
                }
            }               

            if(is_null($this->poblacion[$i]->getEvaluacion_CDT())){     // si no hay intersecciones asigna calificacion
                $porcentaje += $this->valor_condicion;
                $this->poblacion[$i]->setEvaluacion_CDT($this->valor_condicion);
            }
        }

        return $porcentaje;
    }   

    //=====> Funciones auxiliares <=====
    private function relacionarTT_Espacios(){
        // Variables
        $lista_TTs_auxiliar = $this->poblacion; //Clonamos la lista de TTs
        $this->poblacion = array();
        
        //====| Generacion: Poblacion Inicial (tts al azar)|====
        while(!empty($lista_TTs_auxiliar)) {                //condicion: lista_TTs vacio
            $indice = array_rand($lista_TTs_auxiliar);  //Extraemos nombre de TT al azar
            $tt = $lista_TTs_auxiliar[$indice];         //Extraemos objeto TT de la lista

            $indice2 = array_rand($this->lista_fechahorario);
            $value = $this->lista_fechahorario[$indice2];
            
            $this->poblacion[] = new Cromosoma($tt->getNumeroTT(),$tt->getTitulo(),$value->getFecha(),$value->getHora(),$value->getSalon(),
                                               $tt->getHorarioOptimo(),$tt->getGrupoDocente());
            unset($lista_TTs_auxiliar[$indice]);        //Eliminamos ese objeto
            unset($this->lista_fechahorario[$indice2]);
        }

        //=====> Proceso [Encontrar: Fitness]
        $this->porcentaje = $this->aptitud();

    }

    private function reingresoEspacios(){
        foreach ($this->poblacion as $cromosoma) {
            $this->lista_fechahorario[] = new Horario($cromosoma->getFecha(),$cromosoma->getSalon(),$cromosoma->getHorario());
        }        
    }

    private function orderPoblaciones($poblaciones){
        $mejorValorAlcanzado = array();
        foreach ($this->respaldo_fechahorario as $valor) {
            foreach ($poblaciones as $poblacion) {
                foreach ($poblacion as $cromosoma) {
                    if($cromosoma->getFecha() == $valor->getFecha() && $cromosoma->getHorario() == $valor->getHora() && $cromosoma->getSalon() == $valor->getSalon()){
                        $mejorValorAlcanzado [] = $cromosoma;
                        break;
                    }
                }
            }
        }

        $this->efectividad = count($mejorValorAlcanzado)*$this->valor_cromosoma;

        //  Retornamos el mejor valor alcanzado
        return $mejorValorAlcanzado;
    }
    
    private function hayInterseccion($padre1, $padre2) {
        $arreglo1 = array_keys($padre1);
        $arreglo2 = array_keys($padre2);

        foreach ($arreglo1 as $valor1) {
          foreach ($arreglo2 as $valor2) {
            if ($valor1 == $valor2) {
              return 1; // Se encontró una intersección, devolver true
            }
          }
        }
        return 0; // No se encontró ninguna intersección, devolver false
    }

    private function imprimirPoblacion($poblacion){

        echo '<p> Efectividad ['.$this->efectividad.'] número: '.count($poblacion).'</p>';
        echo '<table>';
        echo "<tr>
                <td>Nombre</td>
                <td>Fecha</td>
                <td>Horario</td>
                <td>Salon</td>
                <td>CHOP</td>
                <td>DCT</td>
                <td>optimo</td>
                <td>Director 1</td>
                <td>Director 2</td>
                <td>Sinodal 1</td>
                <td>Sinodal 2</td>
                <td>Sinodal 3</td>
              </tr>";
        
        foreach ($poblacion as $cromosoma){
            echo '<tr>';
                echo '<td>'.$cromosoma->getNumeroTT().'</td>
                      <td>'.$cromosoma->getFecha().'</td>
                      <td>'.$cromosoma->getHorario().'</td>
                      <td>'.$cromosoma->getSalon().'</td>
                      <td>'.number_format($cromosoma->getEvaluacion_CHOP(),2).'</td>
                      <td>'.number_format($cromosoma->getEvaluacion_CDT(),2).'</td>
                      <td>'.$cromosoma->getOptimo().'</td>';
                $this->imprimirDocentes($cromosoma->getGrupoDocente());
            echo '</tr>';
        }
        echo '</table>';
    }

    private function imprimirDocentes($parametro) {

        if(count($parametro) == 5){

            foreach ($parametro as $key => $value) {
                if($value == 'director'){
                    echo '<td>'.$key.'</td>';
                }
            }

            foreach ($parametro as $key => $value) {                
                if($value == 'sinodal'){
                    echo '<td>'.$key.'</td>';
                }
            }
        }else{
            foreach ($parametro as $key => $value) {
                if($value == 'director'){
                    echo '<td>'.$key.'</td>';
                }
            }

            echo '<td></td>';

            foreach ($parametro as $key => $value) {              
                if($value == 'sinodal'){
                    echo '<td>'.$key.'</td>';
                }
            }
        }
    }

    private function limpiarListasYVariables(){
        //foreach($this->lista_TD as $key => $elemento){ unset($this->lista_TD[$key]); }
        //foreach($this->lista_TH as $key => $elemento){ unset($this->lista_TH[$key]); }
        //foreach($this->poblacion_aux as $key => $elemento){ unset($this->poblacion_aux[$key]); }
        //array_splice($this->lista_TD,0);
        //array_splice($this->lista_TH,0);
        array_splice($this->poblacion_aux,0);

        foreach($this->poblacion as $key => $value) {
            $this->poblacion[$key]->setEvaluacion_CHOP(null);
            $this->poblacion[$key]->setEvaluacion_CDT(null);
        }
    }
}