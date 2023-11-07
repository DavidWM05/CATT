<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use Model\Persona;
use MVC\Router;

class LoginController {
    
    public static function login(Router $router) {
        
        session_start();
        
        $alertas = [];

        //proceso
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas  = $auth->validarLogin();
            $alertas = $auth->validarUsuarioPersona();
            
            if(empty($alertas)) {

                // Comprobar que exista el usuario
                $usuario = Usuario::where('user', $auth->user);

                if($usuario) {

                    // Verificar el password
                    if( $usuario->comprobarPassword($auth->password) ) {

                        //Obtener info persona loggeada
                        $persona = Persona::where('idPersona', $usuario->idPersona);

                        // Autenticar el usuario
                        $persona->llenarVariablesGlobales();
                        $usuario->llenarVariablesGlobales();

                        // Redireccionamiento
                        if($persona->idRol === "1") {
                            $_SESSION['rol'] = $persona->idRol ;
                            header('Location: /administrador');
                        } else if ($persona->idRol === "2") {
                            $_SESSION['rol'] = $persona->idRol ;
                            header('Location: /docente');
                        } else if ($persona->idRol === "3") {
                            $_SESSION['rol'] = $persona->idRol ;
                            header('Location: /estudiante');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }

        }

        $alertas = Usuario::getAlertas();
        

        if(isset($_SESSION['login']) && $_SESSION['login'] == true) {
            // Si el usuario tiene sesión iniciada
            if($_SESSION['rol'] === "1") {
                header('Location: /administrador');
            } else if ($_SESSION['rol'] === "2") {
                header('Location: /docente');
            } else if ($_SESSION['rol'] === "3") {
                header('Location: /estudiante');
            }
        } else {
            // Si el usuario no tiene sesión iniciada
            $router->render('auth/login', [
                'alertas' => $alertas
            ]);
        }
    }

    public static function cerrarSesion(Router $router) {
        session_start();

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router) {
        session_start();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $auth = new Usuario($_POST);
            $alertas = $auth->validarUsuario();

            if(empty($alertas)) {

                $usuario = Usuario::where('user', $auth->user);

                if($usuario) {

                    $persona = Persona::where('idPersona', $usuario->idPersona);
                    
                    // Generar un token
                    $usuario->crearToken();
                    $usuario->guardarToken();

                    //  Enviar el email
                    $email = new Email($persona->correo, $persona->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');

                } else {

                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                
                }

            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        session_start();
        
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Leer el nuevo password y guardarlo
            //debuguear($_POST);
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            $alertas = $password->validarRecuperaPassword($_POST['password'] ,$_POST['password2']);

            if(empty($alertas)) {
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardarPassword();
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar', [
            'alertas' => $alertas, 
            'error' => $error
        ]);
    }

}