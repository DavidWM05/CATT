<?php 

namespace Model;

class HorarioPresentacion extends ActiveRecord {
    protected static $tabla = 'horariopresentacion';

    public $numeroTT;
    public $titulo;
    public $salon;
    public $fecha;
    public $horario;
    public $anio;
    public $ciclo;
    public $optimo;
    public $director1;
    public $director2;
    public $sinodal1;
    public $sinodal2;
    public $sinodal3;
    public $horarioHora;
    public $rolDocente;

    public function __construct($args = []) {
        $this->numeroTT = $args['numeroTT'] ?? null;
        $this->titulo = $args['titulo'] ?? null;
        $this->salon = $args['salon'] ?? null;
        $this->fecha = $args['fecha'] ?? null;
        $this->horario = $args['horario'] ?? null;
        $this->anio = $args['anio'] ?? null;
        $this->ciclo = $args['ciclo'] ?? null;
        $this->optimo = $args['optimo'] ?? null;
        $this->director1 = $args['director1'] ?? null;
        $this->director2 = $args['director2'] ?? null;
        $this->sinodal1 = $args['sinodal1'] ?? null;
        $this->sinodal2 = $args['sinodal2'] ?? null;
        $this->sinodal3 = $args['sinodal3'] ?? null;
        $this->horarioHora = $args['horarioHora'] ?? null;
        $this->rolDocente = $args['rolDocente'] ?? null;
    }

    //Geters and Seters
    
    //  =====> Otras funciones <=====
    public static function insertarHorario($objeto,$horario,$anio,$ciclo,$estado) {
        //  Consulta para revisar si existe ya un registro con el numero de tt y el ciclo
        $sql = "SELECT * FROM horariopresentacion WHERE numeroTT = '" . $objeto->getNumeroTT() . "' AND anio = " . $anio . " AND ciclo = ". $ciclo . ";";
        $resultado = HorarioPresentacion::SQL($sql);

        $docentes = array();
        foreach ($objeto->getGrupoDocente() as $nombre => $tipo) { if($tipo == 'director'){ $docentes[]=$nombre; } }    // Directores
        foreach ($objeto->getGrupoDocente() as $nombre => $tipo) { if($tipo == 'sinodal'){ $docentes[]=$nombre; } }    // Sinodales

        $numeroDocentes = count($docentes);

        if(count($resultado) == 1){          //  Entra si: existe ya un registro, se hace un update
            if($numeroDocentes == 4){                
                $sql = "UPDATE horariopresentacion SET titulo = '".$objeto->getTitulo()."', salon = " . $objeto->getSalon() . ", horario = '" . $horario . "', fecha = '" . $objeto->getFecha() . "', optimo = " . $estado . "
                    ,director1 = '".$docentes[0]."',sinodal1 = '".$docentes[1]."',sinodal2 = '".$docentes[2]."',sinodal3 = '".$docentes[3]."'
                    WHERE numeroTT = '" . $objeto->getNumeroTT() . "' AND anio = " . $anio . " AND ciclo = ". $ciclo . ";";                
            }elseif($numeroDocentes == 5){
                $sql = "UPDATE horariopresentacion SET titulo = '".$objeto->getTitulo()."', salon = " . $objeto->getSalon() . ", horario = '" . $horario . "', fecha = '" . $objeto->getFecha() . "', optimo = " . $estado . "
                ,director1 = '".$docentes[0]."',director2 = '".$docentes[1]."',sinodal1 = '".$docentes[2]."',sinodal2 = '".$docentes[3]."',sinodal3 = '".$docentes[4]."'
                WHERE numeroTT = '" . $objeto->getNumeroTT() . "' AND anio = " . $anio . " AND ciclo = ". $ciclo . ";";
            }            
            
            $resultado = HorarioPresentacion::update($sql);
        }elseif(count($resultado) == 0) {    //  Entra si: no existe un registro, se hace un insert
            if($numeroDocentes == 4){
                $sql = "INSERT INTO horariopresentacion VALUES (" . $objeto->getSalon() . ",'" . $objeto->getFecha() . "','" . $objeto->getNumeroTT() . "'," . $horario . "," . $anio . "," . $ciclo . "," . $estado . "
                        ,'".$docentes[0]."',NULL,'".$docentes[1]."','".$docentes[2]."','".$docentes[3]."','".$objeto->getTitulo()."')";
            }elseif($numeroDocentes == 5) {
                $sql = "INSERT INTO horariopresentacion VALUES (" . $objeto->getSalon() . ",'" . $objeto->getFecha() . "','" . $objeto->getNumeroTT() . "'," . $horario . "," . $anio . "," . $ciclo . "," . $estado . "
                        ,'".$docentes[0]."','".$docentes[1]."','".$docentes[2]."','".$docentes[3]."','".$docentes[4]."','".$objeto->getTitulo()."')";
            }
            
            $resultado = HorarioPresentacion::create($sql);
        }

        return $resultado;
    }

    public static function obtenerHorario($tipo,$estado,$anio,$ciclo){
        /*$sql = "SELECT a.numeroTT,a.salon,a.fecha,b.horario_inicio AS horario,a.anio,a.ciclo,a.optimo 
                        FROM horariopresentacion a 
                        INNER JOIN horario b ON a.horario = b.idhorario 
                        INNER JOIN tt c ON c.tt_numero = a.numeroTT 
                        WHERE c.tt_tipo = '".$tipo."' AND a.optimo = ".$estado." AND a.anio = ".$anio." AND a.ciclo = ".$ciclo.";";*/
        
        $sql = "SELECT a.numeroTT,a.salon,a.fecha,b.horario_inicio AS horario,a.anio,a.ciclo,a.optimo 
                FROM horariopresentacion a 
                INNER JOIN horario b ON a.horario = b.idhorario 
                INNER JOIN tt c ON c.tt_numero = a.numeroTT 
                WHERE ".$tipo." AND a.optimo = ".$estado." AND a.anio = ".$anio." AND a.ciclo = ".$ciclo." 
                ORDER BY a.horario ASC;";

        $resultado = HorarioPresentacion::SQL($sql);

        return $resultado;
    }

    public static function obtenerHorarioTT($tipo,$anio,$ciclo){
        $sql = "SELECT a.numeroTT,a.titulo,a.salon,a.fecha,b.horario_inicio AS horario,a.anio,a.ciclo,a.optimo,a.director1,a.director2,a.sinodal1,a.sinodal2,a.sinodal3
                FROM horariopresentacion a 
                INNER JOIN horario b ON a.horario = b.idhorario 
                INNER JOIN tt c ON c.tt_numero = a.numeroTT 
                WHERE ".$tipo." AND a.anio = ".$anio." AND a.ciclo = ".$ciclo." 
                ORDER BY a.horario ASC;";

        $resultado = HorarioPresentacion::SQL($sql);

        return $resultado;
    }

    public static function obtenerFechas($tipo,$anio,$ciclo){
        $sql = "SELECT a.fecha
                FROM horariopresentacion a 
                INNER JOIN horario b ON a.horario = b.idhorario 
                INNER JOIN tt c ON c.tt_numero = a.numeroTT 
                WHERE ".$tipo." AND a.anio = ".$anio." AND a.ciclo = ".$ciclo." 
                GROUP BY a.fecha
                ORDER BY a.fecha ASC;";
        
        $resultado = HorarioPresentacion::SQL($sql);

        return $resultado;
    }

    public static function obtenerHorarios($idDocente) {

        $anioActual = date('Y');
        $mesActual = date('m');
        $ciclo = 1;

        //  Verificar en que ciclo se hace el registro
        if((int)$mesActual <= 6) { 
            $ciclo = 2; 
        }

        // Consulta SQL
        $query = "SELECT hp.*, date_format(hp.fecha, \"%d/%m/%Y\") as fecha, CONCAT(date_format(h.horario_inicio, \"%H:%i\"), ' - ', date_format(h.horario_fin, \"%H:%i\")) as horarioHora, UPPER(rd.tipo) as rolDocente FROM " . static::$tabla . " hp";
        $query .= " INNER JOIN ttdocente ttd on ttd.numeroTT = hp.numeroTT ";
        $query .= " INNER JOIN roldocente rd on rd.idRolDocente = ttd.idRolDocente ";
        $query .= " INNER JOIN horario h on h.idHorario = hp.horario";
        $query .= " INNER JOIN tt on tt.tt_numero = ttd.numeroTT";
        $query .= " WHERE ttd.idDocente = '" . $idDocente . "' and tt.idStatus = 1 and ttd.estatus = 1";
        $query .= " and hp.anio = '" . $anioActual . "' and ciclo = " . $ciclo;
        $query .= " ORDER BY hp.fecha, hp.horario";

        //debuguear($query);
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function obtenerHorarioEstudiante($numeroTT) {

        $anioActual = date('Y');
        $mesActual = date('m');
        $ciclo = 1;

        //  Verificar en que ciclo se hace el registro
        if((int)$mesActual <= 6) { 
            $ciclo = 2; 
        }

        // Consulta SQL
        $query = "SELECT hp.*, date_format(hp.fecha, \"%d/%m/%Y\") as fecha, CONCAT(date_format(h.horario_inicio, \"%H:%i\"), ' - ', date_format(h.horario_fin, \"%H:%i\")) as horarioHora, UPPER(rd.tipo) as rolDocente FROM " . static::$tabla . " hp";
        $query .= " INNER JOIN ttdocente ttd on ttd.numeroTT = hp.numeroTT ";
        $query .= " INNER JOIN roldocente rd on rd.idRolDocente = ttd.idRolDocente ";
        $query .= " INNER JOIN horario h on h.idHorario = hp.horario";
        $query .= " INNER JOIN tt on tt.tt_numero = ttd.numeroTT";
        $query .= " WHERE ttd.numeroTT = '" . $numeroTT . "' and tt.idStatus = 1 ";;
        $query .= " ORDER BY hp.fecha, hp.horario LIMIT 1";

        //debuguear($query);
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
}


/*SELECT a.numeroTT,a.salon,a.fecha,b.horario_inicio AS horario,a.anio,a.ciclo,a.optimo 
            FROM horariopresentacion a 
            INNER JOIN horario b ON a.horario = b.idhorario 
            INNER JOIN tt c ON c.tt_numero = a.numeroTT 
            WHERE c.tt_tipo = 'TT1' AND a.optimo = 1 AND a.anio = 2023 AND a.ciclo = 2
            ORDER BY a.fecha ASC;*/

    
