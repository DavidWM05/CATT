<?php 
namespace Classes;

class TrabajoTerminal{
    //==========|Atributos|==========
    public $numeroTT;
    public $tituloTT;
    public $tipoTT;
    public $archivo;
    public $grupoDocente;
    public $estudiantes;
    public $horarioOptimo;
    public $fecha;
    public $salon;
    public $anio;
    public $ciclo;
    public $status;
    public $horario;
    public $optimo;

    //==========|Constructor|==========
    public function __construct(){
        $this->numeroTT = null;
        $this->tituloTT = null;
        $this->tipoTT = null;
        $this->archivo = null;
        $this->fecha = null;
        $this->salon = null;
        $this->horario = null;
        $this->anio = null;
        $this->ciclo = null;
        $this->status = null;
        $this->optimo = null;
        $this->grupoDocente= array();
        $this->estudiantes= array();
        $this->horarioOptimo = array();
    }

    //==========|Geters and Seters|==========
    public function getNumeroTT(){ return $this->numeroTT; }
    public function setNumeroTT($parametro){ $this->numeroTT = $parametro; }

    public function getTituloTT(){ return $this->tituloTT; }
    public function setTituloTT($parametro){ $this->tituloTT = $parametro; }

    public function getTipoTT(){ return $this->tipoTT; }
    public function setTipoTT($parametro){ $this->tipoTT = $parametro; }

    public function getArchivo(){ return $this->archivo; }
    public function setArchivo($parametro){ $this->archivo = $parametro; }

    public function getEstudiantes(){ return $this->estudiantes; }
    public function setEstudiante($parametro) { array_push($this->estudiantes,$parametro); }
    public function setGrupoEstudiantes($parametro){ $this->estudiantes = $parametro; }

    public function getGrupoDocente(){ return $this->grupoDocente; }
    public function setDocente($key,$value){ $this->grupoDocente[$key] = $value;}
    public function setGrupoDocente($parametro){ $this->grupoDocente = $parametro; }

    public function getHorarioOptimo(){ return $this->horarioOptimo; }
    public function setHorarioOptimo($parametro){ $this->horarioOptimo = $parametro; }

    public function getFecha(){ return $this->fecha; }
    public function setFecha($parametro){ $this->fecha = $parametro; }

    public function getSalon(){ return $this->salon; }
    public function setSalon($parametro){ $this->salon = $parametro; }

    public function getHorario(){ return $this->horario; }
    public function setHorario($parametro){ $this->horario = $parametro; }

    public function getAnio(){ return $this->anio; }
    public function setAnio($parametro){ $this->anio = $parametro; }

    public function getCiclo(){ return $this->ciclo; }
    public function setCiclo($parametro){ $this->ciclo = $parametro; }

    public function getStatus(){ return $this->status; }
    public function setStatus($parametro){ $this->status = $parametro; }

    public function getOptimo(){ return $this->optimo; }
    public function setOptimo($parametro){ $this->optimo = $parametro; }
}