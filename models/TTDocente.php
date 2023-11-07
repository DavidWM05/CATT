<?php 

namespace Model;

class TTDocente extends ActiveRecord {
    protected static $tabla = 'ttdocente';
    protected static $columnasDB = ['numeroTT', 'idDocente', 'idRolDocente'];

    public $numeroTT;
    public $idDocente;
    public $idRolDocente;
    public $estatus;

    public $nombreDocente;
    public $correoDocente;

    public function __construct($args = []) {
        $this->numeroTT = $args['numeroTT'] ?? null;
        $this->idDocente = $args['idDocente'] ?? null;
        $this->idRolDocente = $args['idRolDocente'] ?? null;
        $this->estatus = $args['estatus'] ?? null;

        $this->nombreDocente = $args['nombreDocente'] ?? null;
        $this->correoDocente = $args['correoDocente'] ?? null;
    }

    public static function estructuraTT($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla  ." ";
        $query .= "WHERE ${columna} = '${valor}' ";
        
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function estructuraTTConDocente($columna, $valor) {
        $query = "SELECT ttd.*, CONCAT(p.nombre, ' ', p.apellidoPaterno, ' ', p.apellidoMaterno) as nombreDocente FROM " . static::$tabla  ." as ttd ";
        $query .= "INNER JOIN docente d on d.idDocente = ttd.idDocente ";
        $query .= "INNER JOIN persona p on p.idPersona = d.idPersona ";
        $query .= "WHERE ${columna} = '${valor}' and ttd.estatus = 1";

        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function obtenerDocenteSeguimiento($columna, $valor) {
        $query = "SELECT ttd.*, CONCAT(p.nombre, ' ', p.apellidoPaterno, ' ', p.apellidoMaterno) ";
        $query .= "as nombreDocente, p.correo as correoDocente FROM " . static::$tabla  ." as ttd ";
        $query .= "INNER JOIN docente d on d.idDocente = ttd.idDocente ";
        $query .= "INNER JOIN persona p on p.idPersona = d.idPersona ";
        $query .= "WHERE ${columna} = '${valor}' and idRolDocente = 3 and ttd.estatus = 1";

        $resultado = self::consultarSQL($query);
        return array_shift( $resultado );
    }

    public static function obtenerTTDirector($columna, $valor) {
        $query = "SELECT ttd.* ";
        $query .= "FROM " . static::$tabla  ." as ttd ";
        $query .= "INNER JOIN tt on tt.tt_numero = ttd.numeroTT ";
        $query .= "WHERE ${columna} = '${valor}' and idRolDocente = 1 and tt.idStatus = 1 and ttd.estatus = 1";

        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function obtenerTTSinodal($columna, $valor) {
        $query = "SELECT ttd.* ";
        $query .= "FROM " . static::$tabla  ." as ttd ";
        $query .= "INNER JOIN tt on tt.tt_numero = ttd.numeroTT ";
        $query .= "WHERE ${columna} = '${valor}' and idRolDocente = 2 and tt.idStatus = 1 and ttd.estatus = 1";

        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function obtenerTTSeguimiento($columna, $valor) {
        $query = "SELECT ttd.* ";
        $query .= "FROM " . static::$tabla  ." as ttd ";
        $query .= "INNER JOIN tt on tt.tt_numero = ttd.numeroTT ";
        $query .= "WHERE ${columna} = '${valor}' and idRolDocente = 3 and tt.idStatus = 1 and ttd.estatus = 1";

        $resultado = self::consultarSQL($query);
        return $resultado;
    }
}

