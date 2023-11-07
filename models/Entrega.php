<?php 

namespace Model;

class Entrega extends ActiveRecord {
    protected static $tabla = 'entrega';
    protected static $columnasDB = ['idEntrega', 'numeroEntrega', 'fechaEntrega', 'avance', 'rutaDocumento', 'asistencia', 'numeroTT', 'idDocente'];

    public $idEntrega;
    public $numeroEntrega;
    public $fechaEntrega;
    public $avance;
    public $rutaDocumento;
    public $asistencia;
    public $numeroTT;
    public $idDocente;

    public function __construct($args = []) {
        $this->idEntrega = $args['idEntrega'] ?? null;
        $this->numeroEntrega = $args['numeroEntrega'] ?? null;
        $this->fechaEntrega = $args['fechaEntrega'] ?? null;
        $this->avance = $args['avance'] ?? null;
        $this->rutaDocumento = $args['rutaDocumento'] ?? null;
        $this->asistencia = $args['asistencia'] ?? null;
        $this->numeroTT = $args['numeroTT'] ?? null;
        $this->idDocente= $args['idDocente'] ?? null;
    }

    public function guardarEntrega() {
        // Consulta SQL
        $query = "INSERT INTO " . static::$tabla ."(numeroEntrega, fechaEntrega, rutaDocumento, numeroTT, idDocente)";
        $query .= " VALUES(" . $this->numeroEntrega . ", NOW(), '" . $this->rutaDocumento . "', '" . $this->numeroTT . "', '" . $this->idDocente . "')"; 

        // Insertar en BD
        $resultado = self::$db->query($query);
        
        return $resultado;
    }

    public static function obtenerEntrega($numeroTT, $numeroEntrega) {
        // Consulta SQL
        $query = "SELECT e.*, date_format(e.fechaEntrega, \"%d/%m/%Y %H:%i\") as fechaEntrega  FROM " . static::$tabla . " e";
        $query .= " WHERE e.numeroTT = '" . $numeroTT . "'";
        $query .= " and e.numeroEntrega = " . $numeroEntrega;

        //debuguear($query);
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ); 
    }

    public function guardarAvance($idEntrega, $avance) {
        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." set avance = " . $avance;
        $query .= " where idEntrega = " . $idEntrega; 

        //debuguear($query);
        // Insertar en BD
        $resultado = self::$db->query($query);
        
        return $resultado;
    }

    
}