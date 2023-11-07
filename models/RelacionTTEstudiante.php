<?php 

namespace Model;

class RelacionTTEstudiante extends ActiveRecord {
    protected static $tabla = 'ttdocente';

    public $numeroTT;
    public $tt_titulo;
    public $nombre;
    public $boleta;
    
    public function __construct($args = []) {        
        $this->numeroTT = $args['numeroTT'] ?? null;
        $this->tt_titulo = $args['tt_titulo'] ?? null;        
        $this->nombre = $args['nombre'] ?? null;
        $this->boleta = $args['boleta'] ?? null;
    }
}