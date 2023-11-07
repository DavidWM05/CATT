<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;
    
    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarInstrucciones() {
        // Intancia de PHPMailer
        $mail = new PHPMailer();
    
        // Es necesario para poder usar un servidor SMTP como gmail
        $mail->isSMTP();
    
        //Set the hostname of the mail server
        $mail->Host          = 'smtp.gmail.com';
        $mail->Port          = 465; // o 587
    
        // Propiedad para establecer la seguridad de encripción de la comunicación
        $mail->SMTPSecure    = PHPMailer::ENCRYPTION_SMTPS; // tls o ssl para gmail obligado
    
        // Para activar la autenticación smtp del servidor
        $mail->SMTPAuth      = true;
    
        // Credenciales de la cuenta
        $mail->Username     = 'rotciv568@gmail.com';
        $mail->Password     = 'ecygipvgbbphwuua';

        // Quien envía este mensaje
        $mail->setFrom($mail->Username, 'Soporte TT');

        // Destinatario
        $mail->addAddress($this->email, $this->nombre);
    
        // Asunto del correo
        $mail->Subject = 'Restablecer Contraseña';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has solicitado restablecer tu contraseña, sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:5000/recuperar?token=" . $this->token . "'>Restablecer Contraseña</a>";        
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

            //Enviar el mail
        $mail->send();
    }

    public function enviarMailEntrega($ttNumero, $numeroEntrega) {

        // Intancia de PHPMailer
        $mail = new PHPMailer();
    
        // Es necesario para poder usar un servidor SMTP como gmail
        $mail->isSMTP();
    
        //Set the hostname of the mail server
        $mail->Host          = 'smtp.gmail.com';
        $mail->Port          = 465; // o 587
    
        // Propiedad para establecer la seguridad de encripción de la comunicación
        $mail->SMTPSecure    = PHPMailer::ENCRYPTION_SMTPS; // tls o ssl para gmail obligado
    
        // Para activar la autenticación smtp del servidor
        $mail->SMTPAuth      = true;
    
        // Credenciales de la cuenta
        $mail->Username     = 'rotciv568@gmail.com';
        $mail->Password     = 'ecygipvgbbphwuua';

        // Quien envía este mensaje
        $mail->setFrom($mail->Username, 'Soporte TT');

        // Destinatario
        $mail->addAddress($this->email, $this->nombre);
    
        // Asunto del correo
        $mail->Subject = 'Notificacion de Entrega TT ' . $ttNumero;

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p>Hola! <br><br><strong>" . $this->nombre . "</strong><br><br> Se ha realizado la <strong>" . $numeroEntrega . "°</strong>";
        $contenido .= " entrega del TT " . $ttNumero . "</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        //Enviar el mail
        $mail->send();
    }

    public function enviarCredenciales($user_email,$user_nombre,$user){
        // Intancia de PHPMailer
        $mail = new PHPMailer();
    
        // Es necesario para poder usar un servidor SMTP como gmail
        $mail->isSMTP();
    
        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        $mail->Port =  465; // o 587
    
        // Propiedad para establecer la seguridad de encripción de la comunicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // tls o ssl para gmail obligado
    
        // Para activar la autenticación smtp del servidor
        $mail->SMTPAuth      = true;
    
        // Credenciales de la cuenta
        $mail->Username     = 'rotciv568@gmail.com';
        $mail->Password     = 'ecygipvgbbphwuua';

        // Quien envía este mensaje
        $mail->setFrom($mail->Username, 'Soporte TT');

        // Destinatario
        $mail->addAddress($user_email, $user_nombre);
    
        // Asunto del correo
        $mail->Subject = 'Sistema CATT activado';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p>Hola! <br><br> <strong>" . $user_nombre . 
                      "</strong><br><br> Es de nuestro agrado anunciarte que el sistema CATT ya esta activo. Si no modificaste la contraseña tus credenciales son: </p>";
        $contenido .= "<p><strong>Usuario: </strong> " . $user . "<br>";
        $contenido .= "<p><strong>Contraseña: </strong> " . $user . "@</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        //Enviar el mail
        $mail->send();   
    }
}