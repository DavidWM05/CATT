<?php 

namespace Model;

class UsuarioPersona extends ActiveRecord {
    protected static $tabla = 'persona';

    public $idPersona;
    public $nombre;
    public $apellidoPaterno;
    public $apellidoMaterno;
    public $correo;
    public $idRol;
    public $idStatus;

    public $user;
    public $password;

    public function __construct($args = []) {
        $this->idPersona = $args['idPersona'] ?? null;
        $this->nombre = $args['nombre'] ?? null;
        $this->apellidoPaterno = $args['apellidoPaterno'] ?? null;
        $this->apellidoMaterno = $args['apellidoMaterno'] ?? null;
        $this->correo = $args['correo'] ?? null;
        $this->idRol = $args['idRol'] ?? null;
        $this->idStatus = $args['idStatus'] ?? null;

        $this->user = $args['user'] ?? null;
        $this->password = $args['password'] ?? null;
    }
}