<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'auth.php';

class Contactar extends Controller
{
    # Método principal. Muestra todos los clientes
    public function render($param = [])
    {
        #inicio o continuo sesion
        session_start();

        #comprobar si existe mensaje
        if (isset($_SESSION['mensaje'])) {
            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        }

        $this->view->title = "Contactar";
        $this->view->render("contactar/index");
    }

    public function validar()
    {
        // Verificar que se haya enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar campos obligatorios
            $errores = $this->validarFormulario($_POST);

            if (!empty($errores)) {
                // Mostrar errores y volver al formulario
                $this->view->errores = $errores;
                $this->view->render("contactar/index"); 
            } else {
                // Enviar correo electrónico
                $this->enviarCorreo($_POST);

                // Mostrar mensaje de éxito
                $_SESSION['mensaje'] = '¡Correo enviado con éxito!';
                $this->view->render("contactar/index"); 
            }
        } else {
            // Redirigir a la página de inicio si se accede directamente a este método
            header('Location: index.php');
        }
    }

    private function validarFormulario($datos)
    {
        // Validar campos obligatorios
        $errores = [];

        if (empty($datos['nombre'])) {
            $errores['nombre'] = 'El campo Nombre es obligatorio.';
        }

        if (empty($datos['email']) || !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El campo Email es obligatorio y debe ser un correo electrónico válido.';
        }

        if (empty($datos['asunto'])) {
            $errores['asunto'] = 'El campo Asunto es obligatorio.';
        }

        if (empty($datos['mensaje'])) {
            $errores['mensaje'] = 'El campo Mensaje es obligatorio.';
        }

        return $errores;
    }


    private function enviarCorreo($datos)
    {
        session_start();

        // Creamos un objeto de la clase PHPMailer
        $mail = new PHPMailer(true);

        // Configuración de PHPMailer
        $mail->CharSet = "UTF-8";
        $mail->Encoding = "quoted-printable";
        $mail->Username = USERNAME;
        $mail->Password = PASSWD;

        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // tls Habilita el cifrado TLS implícito
        $mail->Port = 587;

        $destinatario = "{$datos['email']}";
        $remitente = USERNAME;
        $asunto = "{$datos['asunto']}";
        $mensaje = "
        <h1>Hola!! {$datos['nombre']}</h1>
        <p>{$datos['mensaje']}</p>
        ";

        // Configuración del correo con PHPMailer
        $mail->setFrom($remitente, 'Pablo');
        $mail->addAddress($destinatario, "{$datos['nombre']}");
        $mail->addReplyTo($remitente, 'Pablo Mateos');

        // Configuración del contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        // Esta línea la he tenido que añadir para mi pc en casa porque me daba fallo el certificado SSL
        $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true)); 
        $mail->send();

        $_SESSION['mensaje'] = "Mensaje enviado correctamente.";

    } 

}