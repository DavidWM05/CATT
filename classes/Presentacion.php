<?php
namespace Classes;

class Presentacion {
    private $fecha;
    private $grupoTT;

    public function __construct() {
        $this->fecha;
        $this->grupoTT = array();
    }

    public function getTTs(){ return $this->grupoTT; }
    public function setTT($parametro) { array_push($this->grupoTT,$parametro); }
    public function setTTs($parametro){ $this->grupoTT = $parametro; }

    public function getFecha(){ return $this->fecha; }
    public function setFecha($parametro){ $this->fecha = $parametro; }
}