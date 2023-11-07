<?php
namespace Classes;

class Cromosoma{
    public $numeroTT;
    public $titulo;
    public $grupoDocente;
    public $fecha;
    public $horario;
    public $salon;
    public $horarioOptimo;
    public $evaluacionCHOP;
    public $evaluacionCDT;
    public $optimo;

    public function __construct($numeroTT,$titulo,$fecha,$horario,$salon,$horarioOptimo,$grupoDocente){
        $this->numeroTT = $numeroTT;
        $this->titulo = $titulo;
        $this->fecha = $fecha;
        $this->horario = $horario;
        $this->salon = $salon;
        $this->grupoDocente= $grupoDocente;
        $this->horarioOptimo= $horarioOptimo;
        $this->evaluacionCHOP = null;
        $this->evaluacionCDT = null;
        $this->optimo = 0;
    }

    public function setNumeroTT($parametro) { $this->numeroTT = $parametro;}
    public function getNumeroTT(){ return $this->numeroTT; }
    
    public function getGrupoDocente(){ return $this->grupoDocente; }
    public function setGrupoDocente($parametro){ array_push($this->grupoDocente,$parametro); }

    public function setFecha($parametro) { $this->fecha = $parametro; }
    public function getFecha(){ return $this->fecha; }

    public function setHorario($parametro) { $this->horario = $parametro; }
    public function getHorario(){ return $this->horario; }

    public function setSalon($parametro) { $this->salon = $parametro; }
    public function getSalon(){ return $this->salon; }

    public function setHorarioOptimo($parametro) { $this->horarioOptimo = $parametro; }
    public function getHorarioOptimo(){ return $this->horarioOptimo; }

    public function setEvaluacion_CHOP($parametro) { $this->evaluacionCHOP = $parametro; }
    public function getEvaluacion_CHOP(){ return $this->evaluacionCHOP; }

    public function setEvaluacion_CDT($parametro) { $this->evaluacionCDT = $parametro; }
    public function getEvaluacion_CDT(){ return $this->evaluacionCDT; }

    public function setOptimo($parametro) { $this->optimo = $parametro; }
    public function getOptimo(){ return $this->optimo; }

    public function setTitulo($parametro) { $this->titulo = $parametro; }
    public function getTitulo(){ return $this->titulo; }
}