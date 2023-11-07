<?php 

namespace Model;

class Persona extends ActiveRecord {
    protected static $tabla = 'persona';
    protected static $columnasDB = ['idPersona', 'nombre', 'segundoNombre', 'apellidoPaterno', 'apellidoMaterno',
                                    'correo', 'idRol','idStatus'];

    public $idPersona;
    public $nombre;
    public $apellidoPaterno;
    public $apellidoMaterno;
    public $correo;
    public $idRol;
    public $idStatus;

    public function __construct($args = []) {
        $this->idPersona = $args['idPersona'] ?? null;
        $this->nombre = $args['nombre'] ?? null;
        $this->apellidoPaterno = $args['apellidoPaterno'] ?? null;
        $this->apellidoMaterno = $args['apellidoMaterno'] ?? null;
        $this->correo = $args['correo'] ?? null;
        $this->idRol = $args['idRol'] ?? null;
        $this->idStatus = $args['idStatus'] ?? null;
    }

    public function llenarVariablesGlobales() {
        $_SESSION['idPersona'] = $this->idPersona;
        $_SESSION['nombre'] = $this->nombre;
        $_SESSION['apellidoP'] = $this->apellidoPaterno;
        $_SESSION['apellidoM'] = $this->apellidoMaterno;
        $_SESSION['email'] = $this->correo;
        $_SESSION['login'] = true;
    }
}