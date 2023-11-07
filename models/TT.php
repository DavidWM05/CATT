<?php 

namespace Model;

class TT extends ActiveRecord {
    protected static $tabla = 'tt';
    protected static $columnasDB = ['tt_numero', 'tt_titulo', 'tt_tipo', 'tt_anio', 'tt_ciclo', 'idAdministrativo', 'ruta', 'idStatus'];

    public $tt_numero;
    public $tt_titulo;
    public $tt_tipo;
    public $tt_anio;
    public $tt_ciclo;
    public $tt_ruta;
    public $idAdministrativo;
    public $idStatus;

    public function __construct($args = []) {
        $this->tt_numero = $args['tt_numero'] ?? null;
        $this->tt_titulo = $args['tt_titulo'] ?? null;
        $this->tt_tipo = $args['tt_tipo'] ?? null;
        $this->tt_anio = $args['tt_anio'] ?? null;
        $this->tt_ciclo = $args['tt_ciclo'] ?? null;
        $this->tt_ruta = $args['tt_ruta'] ?? null;
        $this->idAdministrativo = $args['idAdministrativo'] ?? null;
        $this->idStatus = $args['idStatus'] ?? null;
    }
}