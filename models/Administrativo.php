<?php 

namespace Model;

class Administrativo extends Persona {
    protected static $tabla = 'administrativo';
    protected static $columnasDB = ['idAdministrativo', 'numeroEmpleado', 'idPersona'];

    public $idAdministrativo;
    public $numeroEmpleado;
    public $idPersona;

    public function __construct($args = []) {
        parent::__construct($args);

        $this->idAdministrativo = $args['idAdministrativo'] ?? null;
        $this->numeroEmpleado = $args['numeroEmpleado'] ?? null;
        $this->idPersona = $args['idPersona'] ?? null;
    }
}