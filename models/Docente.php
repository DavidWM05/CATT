<?php 

namespace Model;

class Docente extends Persona {
    protected static $tabla = 'docente';
    protected static $columnasDB = ['idDocente', 'docente_escuela', 'docente_area', 'docente_horaInicio', 'docente_horaFin',
                                    'idPersona', 'docente_tipo'];

    public $idDocente;
    public $docente_escuela;
    public $docente_area;
    public $docente_horaInicio;
    public $docente_horaFin;
    public $idPersona;
    public $docente_tipo;

    public function __construct($args = []) {
        parent::__construct($args);
        $this->idDocente = $args['idDocente'] ?? null;
        $this->docente_escuela = $args['docente_escuela'] ?? null;
        $this->docente_area = $args['docente_area'] ?? null;
        $this->docente_horaInicio = $args['docente_horaInicio'] ?? null;
        $this->docente_horaFin = $args['docente_horaFin'] ?? null;
        $this->idPersona = $args['idPersona'] ?? null;
        $this->docente_tipo = $args['docente_tipo'] ?? null;
    }
}