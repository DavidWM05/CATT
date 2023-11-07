<?php
namespace Classes;

class Horario {
    public $fecha;
    public $hora;
    public $salon;

    public function __construct($fecha,$salon,$hora) {
        $this->fecha = $fecha ?? null;
        $this->salon = $salon ?? null;
        $this->hora =  $hora ?? null;
    }

    // Geters and Seters
    public function setFecha($parametro) { $this->fecha = $parametro; }
    public function getFecha(){ return $this->fecha; }

    public function setHora($parametro) { $this->hora = $parametro; }
    public function getHora(){ return $this->hora; }

    public function setSalon($parametro) { $this->salon = $parametro; }
    public function getSalon(){ return $this->salon; }    
}