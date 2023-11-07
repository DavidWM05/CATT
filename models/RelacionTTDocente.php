<?php 

namespace Model;

class RelacionTTDocente extends ActiveRecord {
    protected static $tabla = 'ttdocente';

    public $numeroTT;
    public $tt_titulo;
    public $nombre;
    public $tipo;
    public $estatus;
    public $docente_horaInicio;
    public $docente_horaFin;
    public $idDocente;
    
    public function __construct($args = []) {
        $this->tipo = $args['tipo'] ?? null;
        $this->numeroTT = $args['numeroTT'] ?? null;
        $this->tt_titulo = $args['tt_titulo'] ?? null;
        $this->estatus = $args['estatus'] ?? null;
        $this->nombre = $args['nombre'] ?? null;
        $this->docente_horaInicio = $args['docente_horaInicio'] ?? null;
        $this->docente_horaFin = $args['docente_horaFin'] ?? null;
        $this->idDocente = $args['idDocente'] ?? null;
    }
}