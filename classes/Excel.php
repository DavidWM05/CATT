<?php
namespace Classes;

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Classes\TrabajoTerminal;
use Model\Estudiante;

class Excel {

    public function crearReportePresentaciones($presentaciones){
        $archivo = new Spreadsheet();                   //  Crea el archivo
        $sheet = $archivo->getActiveSheet();            //  Obtiene la hoja activa
        $fila = 1;

        foreach ($presentaciones as $presentacion) {    //  Recorrido de presentaciones
            //echo '<p>'.$presentacion->getFecha().'</p>'; // Imprimimos fecha
            $sheet->mergeCells('A'.$fila.':'.'I'.$fila);                // Fusionar las celdas A1 hasta E1
            $sheet->setCellValue('A'.$fila,$presentacion->getFecha());
            $fila++;

            $sheet->setCellValue('A'.$fila, 'Salon');  //  Escribe: salon
            $sheet->setCellValue('B'.$fila, 'Horario');  //  Escribe: horario
            $sheet->setCellValue('C'.$fila, 'Numero TT');  //  Escribe: número TT
            $sheet->setCellValue('D'.$fila, 'Titulo');  //  Escribe: titulo
            $sheet->setCellValue('E'.$fila, 'Director');  //  Escribe: director
            $sheet->setCellValue('F'.$fila, 'Director');  //  Escribe: director
            $sheet->setCellValue('G'.$fila, 'Sinodal');  //  Escribe: sinodal
            $sheet->setCellValue('H'.$fila, 'Sinodal');  //  Escribe: sinodal
            $sheet->setCellValue('I'.$fila, 'Sinodal');  //  Escribe: sinodal
            $fila++;

            $arrayTTs = $presentacion->getTTs();    //  Obtenemos el arreglo de presentaciones
            foreach ($arrayTTs as $TT) {
                $sheet->setCellValue('A'.$fila , $TT->salon);
                $sheet->setCellValue('B'.$fila , $TT->horario);
                $sheet->setCellValue('C'.$fila , $TT->numeroTT);
                $sheet->setCellValue('D'.$fila , $TT->tituloTT);

                $grupoDocente = $TT->grupoDocente;
                $auxiliar = count($grupoDocente);

                $columna = 'E';
                if($auxiliar == 5){                    
                    foreach ($grupoDocente as $key => $value) {
                        if($value == 'director'){                            
                            $sheet->setCellValue($columna.$fila,$key);
                            $columna++;
                        }
                    }
            
                    foreach ($grupoDocente as $key => $value) {                
                        if($value == 'sinodal'){
                            $sheet->setCellValue($columna.$fila,$key);
                            $columna++;
                        }
                    }
                }else {
                    foreach ($grupoDocente as $key => $value) {
                        if($value == 'director'){                            
                            $sheet->setCellValue($columna.$fila,$key);
                            $columna++;
                        }
                    }

                    $sheet->setCellValue($columna.$fila,'');
                    $columna++;
            
                    foreach ($grupoDocente as $key => $value) {                
                        if($value == 'sinodal'){
                            $sheet->setCellValue($columna.$fila,$key);
                            $columna++;
                        }
                    }
                }
                $fila++;                                    
            }
            
        }

        /*
            foreach ($arrayTTs as $TT) {
                echo '<p>número TT:'.$TT->numeroTT.'<br>';
                echo 'Titulo:'.$TT->tituloTT.'<br>';
                echo 'Salón:'.$TT->salon.'<br>';                
                echo 'horario:'.$TT->horario.'<br>';

                $grupoDocente = $TT->grupoDocente;
                $auxiliar = count($grupoDocente);

                if($auxiliar == 5){                    
                    foreach ($grupoDocente as $key => $value) {
                        if($value == 'director'){
                            echo 'Director: '.$key.'<br>';
                        }
                    }
            
                    foreach ($grupoDocente as $key => $value) {                
                        if($value == 'sinodal'){
                            echo 'Sinodal: '.$key.'<br>';
                        }
                    }
                }else {
                    foreach ($grupoDocente as $key => $value) {
                        if($value == 'director'){
                            echo 'Director: '.$key.'<br>';
                        }
                    }

                    echo 'Director: <br>';
            
                    foreach ($grupoDocente as $key => $value) {                
                        if($value == 'sinodal'){
                            echo 'Sinodal: '.$key.'<br>';
                        }
                    }                        
                }
                echo '</p>';                        
            } */

        $writer = new Xlsx($archivo);                   //  Crea un objeto writer
        $writer->save('archivos/reporte/reporte.xlsx'); //  Guarda en una ruta
    }

    public function leerTTs(){
        //Variables
        $listaTTs = array();        
        
        //============================| Carga el archivo de Excel|==========================            
        $spreadsheet = IOFactory::load('archivos/registros/registro.xlsx');

        // Obten las hojas
        $TTs = $spreadsheet->getSheetByName('TTs');                 //  Trabajos Terminales
        $Docentes = $spreadsheet->getSheetByName('Docentes');       //  Docentes
        $Estudiantes = $spreadsheet->getSheetByName('Estudiantes');    //  Estudiantes

        // Obtén el número de filas y columnas
        $highestRow = $TTs->getHighestRow();            //  Filas TTs
        $highestColumn = $TTs->getHighestColumn();      //  Columnas TTs
        $columnaDoc = $Docentes->getHighestColumn();    //  Columnas Docentes
        $columnaEst = $Estudiantes->getHighestColumn(); //  Columnas Estudiantes
                    
        // Recorre las filas de la hoja principal (TTs)
        for ($row = 2; $row <= $highestRow; $row++) {   //  Empezamos en fila 2, ignorando los encabezados.            
            $TT = new TrabajoTerminal();   //  Recorre las columnas de la fila actual

            //  Recorrido hoja 'TTs'
            for ($col = 'A'; $col <= $highestColumn; $col++) {                
                $cellValue = $TTs->getCell($col.$row)->getValue();    // Valor de la celda actual
                
                switch ($col) {
                    case 'A': $TT->setNumeroTT($cellValue); break;
                    case 'B': $TT->setTituloTT($cellValue); break;
                    case 'C': $TT->setTipoTT($cellValue); break;
                    case 'D': $TT->setArchivo($cellValue); break;
                    default: break;
                }               
            }
            
            //  Recorrido hoja 'Docentes'
            for ($col = 'A'; $col <= $columnaDoc; $col++) {
                $cellValue = $Docentes->getCell($col.$row)->getValue();         // Valor de la celda actual
                
                switch ($col) {
                    case 'A': $TT->setDocente($cellValue,'director'); break;    //  1er director
                    case 'B': if(strlen($cellValue) != 0)                       //  2do director
                                $TT->setDocente($cellValue,'director'); break;
                            break;
                    case 'C': case 'D': case 'E': $TT->setDocente($cellValue,'sinodal'); break;
                    case 'F': $TT->setDocente($cellValue,'seguimiento'); break;
                    default: break;
                }
            }

            $cabeceraTermino = array('E','J','O','T');  //  Columnas donde terminan datos de n estudiante
            $termina = false;

            //  Recorrido hoja 'Estudiantes'
            for ($col = 'A'; $col <= $columnaEst; $col++){
                //<== Obtén el valor de la celda actual
                $cellValue = $Estudiantes->getCell($col.$row)->getValue();
                
                /*
                *   Variable 'termina' cambiara cuando se encuentra un
                *   null en una celda en las cabeceras de 'nombre', esto
                *   se toma como que no existe el registro por lo que hace
                *   un 'break' para no seguir iterando en las demas cabeceras.
                *   
                *   Minimo se ingresa un integrante.
                */

                if (!$termina) {    //'termina' es falso
                    switch ($col) {
                        case 'A': case 'F': case 'K': case 'P':
                            if(is_null($cellValue)) $termina = true;
                            else $persona['nombre'] = $cellValue;

                            break;
                        case 'B': case 'G': case 'L': case 'Q':
                            if(is_null($cellValue)) $termina = true;
                            else $persona['apellidoPaterno'] = $cellValue;

                            break;
                        case 'C': case 'H': case 'M': case 'R':
                            if(is_null($cellValue)) $termina = true;
                            else $persona['apellidoMaterno'] = $cellValue;

                            break;
                        case 'D': case 'I': case 'N': case 'S':
                            if(is_null($cellValue)) $termina = true;
                            else $persona['correo'] = $cellValue;

                            break;
                        case 'E': case 'J': case 'O': case 'T':
                            if(is_null($cellValue)) $termina = true;
                            else $persona['boleta'] = $cellValue;
                            
                            break;
                        default:
                            break;
                    }
                    
                    //Agregamos integrante
                    if(in_array($col,$cabeceraTermino) && $termina == false){
                        $TT->setEstudiante(new Estudiante($persona));
                    }
                }else {  //'termina' es verdadero
                    break;
                }
            }

            $listaTTs[] = $TT;
        }
        
        return $listaTTs;
    }
}