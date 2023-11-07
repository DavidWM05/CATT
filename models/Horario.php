<?php 

namespace Model;

class Horario extends ActiveRecord {
    protected static $tabla = 'horario';

    public $idhorario;
    public $horario_inicio;
    public $horario_fin;

    public function __construct($args = []) {
        $this->idhorario = $args['idhorario'] ?? null;
        $this->horario_inicio = $args['horario_inicio'] ?? null;
        $this->horario_fin = $args['horario_fin'] ?? null;
    }

    //Geters and Seters
}