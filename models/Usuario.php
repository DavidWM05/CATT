<?php 

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuario';
    protected static $columnasDB = ['user', 'password', 'idPersona', 'token'];

    public $user;
    public $password;
    public $idPersona;
    public $token;

    public function __construct($args = []) {
        $this->user = $args['user'] ?? null;
        $this->password = $args['password'] ?? null;
        $this->idPersona = $args['idPersona'] ?? null;
        $this->token = $args['token'] ?? null;
    }

    public function validarLogin() {
        if(!$this->user) {
            self::$alertas['error'][] = 'El usuario es obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        return self::$alertas;
    }

    public function validarUsuarioPersona() {
        // Consulta SQL
        $query = "SELECT * FROM " . static::$tabla . " u";
        $query .= " INNER JOIN persona p on p.idPersona = u.idPersona";
        $query .= " WHERE user = '" . $this->user . "'";
        $query .= " and p.idStatus = " . 1;

        //debuguear($query);
        $resultado = self::consultarSQL($query);
        
        if(array_shift( $resultado ) == null) {
            self::$alertas['error'][] = 'El usuario no esta registrado o no esta activo';
        }

        return self::$alertas;
    }

    public function comprobarPassword($password) {
        $resultado = password_verify($password, $this->password);
        
        if(!$resultado) {
            self::$alertas['error'][] = 'Contraseña Incorrecta';
        } else {
            return true;
        }
    }

    public function validarUsuario() {
        if(!$this->user) {
            self::$alertas['error'][] = 'El Usuario es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    public function validarRecuperaPassword($password, $password2) {

        if(strcmp($password, $password2) !== 0) {
            self::$alertas['error'][] = 'Las contraseñas no coinciden';
        }

        return self::$alertas;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password.'@', PASSWORD_BCRYPT);
    }
    
    public function crearToken() {
        $this->token = uniqid();
    }

    public function guardarToken() {

        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .= " token = '" . $this->token . "' ";
        $query .= " WHERE user = '" . self::$db->escape_string($this->user) . "' ";
        $query .= " LIMIT 1 "; 

        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public function guardarPassword() {
        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .= " password = '" . $this->password . "', ";
        $query .= " token = null";
        $query .= " WHERE user = '" . self::$db->escape_string($this->user) . "' ";
        $query .= " LIMIT 1 "; 

        //debuguear($query);
        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public function llenarVariablesGlobales() {
        $_SESSION['user'] = $this->user;
        $_SESSION['login'] = true;
    }

    private function generarContrasena($length){
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        $contrasena = '';
        
        $caracteresLength = strlen($caracteres);
        
        // Asegurar al menos un carácter de cada tipo
        $contrasena .= $caracteres[mt_rand(0, 25)]; // Letra minúscula
        $contrasena .= $caracteres[mt_rand(26, 51)];// Letra mayúscula
        $contrasena .= $caracteres[mt_rand(52, 61)];// Número
        $contrasena .= $caracteres[mt_rand(62, 75)];// Carácter especial
        
        // Generar el resto de la contraseña aleatoriamente
        for ($i = 4; $i < $length; $i++) {
            $indice = mt_rand(0, $caracteresLength - 1);
            $caracter = $caracteres[$indice];
            $contrasena .= $caracter;
        }
        
        // Mezclar los caracteres de forma aleatoria
        $contrasena = str_shuffle($contrasena);
        
        return $contrasena;
    }
}

