<?php 

namespace Model;

class Noticias extends ActiveRecord {
    protected static $tabla = 'salon';

    public $titulo;
    public $rutaimagen;
    public $rutadestino;

    public function __construct($args = []) {
        $this->titulo = $args['titulo'] ?? null;
        $this->rutaimagen = $args['rutaimagen'] ?? null;
        $this->rutadestino = $args['rutadestino'] ?? null;
    }
}