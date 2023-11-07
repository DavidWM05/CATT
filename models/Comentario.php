<?php 

namespace Model;

class Comentario extends ActiveRecord {
    protected static $tabla = 'comentario';
    protected static $columnasDB = ['idComentario', 'descripcion', 'fecha', 'idPersona', 'idEntrega'];

    public $idComentario;
    public $descripcion;
    public $fecha;
    public $idPersona;
    public $idEntrega;
    
    public $nombreDocente;

    public function __construct($args = []) {
        $this->idComentario = $args['idComentario'] ?? null;
        $this->descripcion = $args['descripcion'] ?? null;
        $this->fechaEntrega = $args['fecha'] ?? null;
        $this->idPersona = $args['idPersona'] ?? null;
        $this->idEntrega = $args['idEntrega'] ?? null;

        $this->nombreDocente = $args['nombreDocente'] ?? null;
    }

    public static function obtenerComentarios($numeroTT, $numeroEntrega) {
        // Consulta SQL
        $query = "SELECT c.*, DATE_FORMAT(c.fecha, '%d/%m/%Y %H:%i') as fecha , CONCAT(p.nombre, ' ', p.apellidoPaterno, ' ', p.apellidoMaterno) as nombreDocente FROM entrega e";
        $query .= " INNER JOIN " . static::$tabla . " c on c.idEntrega = e.idEntrega";
        $query .= " INNER JOIN persona p on p.idPersona = c.idPersona";
        $query .= " WHERE e.numeroTT = '" . $numeroTT . "'";
        $query .= " and e.numeroEntrega = " . $numeroEntrega;

        $resultado = self::consultarSQL($query);
        return $resultado; 
    }

    public function validarComentario() {
        if(!$this->descripcion) {
            self::$alertas['error'][] = 'El cometario no puede estar vacio';
        }

        return self::$alertas;
    }

    public function guardarComentario() {
        // Consulta SQL
        $query = "INSERT INTO " . static::$tabla ."(descripcion, fecha, idPersona, idEntrega)";
        $query .= " VALUES('" . $this->descripcion . "', NOW(), " . $this->idPersona . ", " . $this->idEntrega . ")"; 
        
        // Insertar en BD
        $resultado = self::$db->query($query);
        
        return $resultado;
    }
}