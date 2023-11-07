<?php 

namespace Model;

class Estudiante extends Persona {
    protected static $tabla = 'estudiante';
    protected static $columnasDB = ['boleta', 'numeroTT', 'idPersona'];

    public $boleta;
    public $numeroTT;
    public $idPersona;

    public function __construct($args = []) {
        parent::__construct($args);

        $this->boleta = $args['boleta'] ?? null;
        $this->numeroTT = $args['numeroTT'] ?? null;
        $this->idPersona = $args['idPersona'] ?? null;
    }
}