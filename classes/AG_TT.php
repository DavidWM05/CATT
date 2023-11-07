<?php 
namespace Classes;

class AG_TT{
    //==========|Atributos|==========
    public $nombreTT;
    public $tituloTT;
    public $tipoTT;
    public $grupoDocente;
    public $estudiantes;
    public $horarioOptimo;

    //==========|Constructor|==========
    public function __construct(){            
        $this->grupoDocente= array();
        $this->estudiantes= array();
        $this->horarioOptimo = array();

        $this->nombreTT = null;
        $this->tituloTT = null;
        $this->tipoTT = null;
    }

    //==========|Geters and Seters|==========
    public function getGrupoDocente(){ return $this->grupoDocente; }
    public function setDocente($key,$value){ $this->grupoDocente[$key] = $value;}
    public function setGrupoDocente($parametro){ $this->grupoDocente = $parametro; }

    public function getEstudiantes(){ return $this->estudiantes; }
    public function setEstudiante($parametro) { array_push($this->estudiantes,$parametro); }
    public function setGrupoEstudiantes($parametro){ $this->estudiantes = $parametro; }

    public function getHorarioOptimo(){ return $this->horarioOptimo; }
    public function setHorarioOptimo($parametro){ $this->horarioOptimo = $parametro; }

    public function getNombreTT(){ return $this->nombreTT; }
    public function setNombreTT($parametro){ $this->nombreTT = $parametro; }

    public function getTipoTT(){ return $this->tipoTT; }
    public function setTipoTT($parametro){ $this->tipoTT = $parametro; }

    public function getTituloTT(){ return $this->tituloTT; }
    public function setTituloTT($parametro){ $this->tituloTT = $parametro; }
}