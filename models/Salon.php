<?php 

namespace Model;

class Salon extends ActiveRecord {
    protected static $tabla = 'salon';

    public $numeroSalon;
    public $salon_edificio;
    public $salon_piso;
    public $salon_numero;

    public function __construct($args = []) {
        $this->numeroSalon = $args['numeroSalon'] ?? null;
        $this->salon_edificio = $args['salon_edificio'] ?? null;
        $this->salon_piso = $args['salon_piso'] ?? null;
        $this->salon_numero = $args['salon_numero'] ?? null;
    }

    //Geters and Seters
}