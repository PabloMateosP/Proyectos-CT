<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'auth.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Perfil extends Controller
{

    # Muestra los detalles del perfil antes de eliminar
    public function render()
    {

        # Iniciamos o continuamos con la sesión
        session_start();

        # Capa autentificación
        if (!isset($_SESSION['id'])) {

            header("location:" . URL . "login");
        }

        # Capa mensaje
        if (isset($_SESSION['mensaje'])) {
            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        }

        # Obtenemos objeto con los detalles del usuario
        $this->view->user = $this->model->getUserId($_SESSION['id']);
        $this->view->title = 'Perfil Usuario - Gesbank - MVC';

        $this->view->render('perfil/main/index');

    }

    # Editar los detalles name y email de usuario
    public function edit()
    {

        # Iniciamos o continuamos sesión
        session_start();

        # Capa de autentificación
        if (!isset($_SESSION['id'])) {

            header('location:' . URL . 'login');

        }

        # Comprobamos si existe mensaje
        if (isset($_SESSION['mensaje'])) {

            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);

        }

        # Obtenemos objeto User con los detalles del usuario
        $this->view->user = $this->model->getUserId($_SESSION['id']);

        # Capa no validación formulario
        if (isset($_SESSION['error'])) {

            # Mensaje de error
            $this->view->error = $_SESSION['error'];
            unset($_SESSION['error']);

            # Variables de autorrelleno
            $this->view->user = unserialize($_SESSION['user']);
            unset($_SESSION['user']);

            # Tipo de error
            $this->view->errores = $_SESSION['errores'];
            unset($_SESSION['errores']);

        }



        $this->view->title = 'Modificar Perfil Usuario - Gesbank';
        $this->view->render('perfil/edit/index');


    }

    # Valida el formulario de modificación de perfil
    public function valperfil()
    {

        # Iniciamos o continuamos con la sesión
        session_start();

        # Capa autentificación
        if (!isset($_SESSION['id'])) {

            header("location:" . URL . "login");
        }

        # Saneamos el formulario
        $name = filter_var($_POST['name'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ??= null, FILTER_SANITIZE_EMAIL);

        # Obtenemos objeto con los detalles del usuario
        $user = $this->model->getUserId($_SESSION['id']);

        # Validaciones
        $errores = [];

        // name
        if (strcmp($user->name, $name) !== 0) {
            if (empty($name)) {
                $errores['name'] = "Nombre de usuario es obligatorio";
            } else if ((strlen($name) < 5) || (strlen($name) > 50)) {
                $errores['name'] = "Nombre de usuario ha de tener entre 5 y 50 caracteres";
            } else if (!$this->model->validarName($name)) {
                $errores['name'] = "Nombre de usuario ya ha sido registrado";
            }
        }

        // email
        if (strcmp($user->email, $email) !== 0) {
            if (empty($email)) {
                $errores['email'] = "Email es un campo obligatorio";
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = "Email no válido";
            } elseif (!$this->model->validarEmail($email)) {
                $errores['email'] = "Email ya ha sido registrado";
            }
        }

        # Crear objeto user
        $user = new classUser(
            $user->id,
            $name,
            $email,
            null
        );


        # Comprobamos si hay errores
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['user'] = serialize($user);
            $_SESSION['error'] = "Formulario con errores de validación";

            header('location:' . URL . 'perfil/edit');

        } else {

            # Actualizamos perfil
            $this->model->update($user);

            // Código para enviar el correo de registro ---------------------------------------------
            // Creamos un objeto de la clase PHPMailer
            $mail = new PHPMailer(true);

            // Configuración de PHPMailer
            $mail->CharSet = "UTF-8";
            $mail->Encoding = "quoted-printable";
            $mail->Username = USERNAME;
            $mail->Password = PASSWD;

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // tls Habilita el cifrado TLS implícito
            $mail->Port = 587;

            $destinatario = $email;
            $remitente = USERNAME;
            $asunto = "Perfil editado correctamente";
            $mensaje = "
            <h1>Hola!! $name</h1>
            <p>Usuario registrado con éxito</p>
            <p>Datos: </p>
            <ul>
                <li><b>Nombre: </b>" . $name . "</li>
                <li><b>Correo Electrónico: </b>" . $email . "</li>
            </ul>";

            // Configuración del correo con PHPMailer
            $mail->setFrom($remitente, 'Pablo');
            $mail->addAddress($destinatario, $name);
            $mail->addReplyTo($remitente, 'Pablo Mateos');

            // Configuración del contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $mensaje;

            // Esta línea la he tenido que añadir para mi pc en casa porque me daba fallo el certificado SSL
            $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
            $mail->send();

            // -------------------------------------------------------------------------------

            $_SESSION['name_user'] = $name;
            $_SESSION['mensaje'] = 'Usuario modificado correctamente y notificación enviada';

            header('location:' . URL . 'perfil');

        }

    }

    # Modificación del password
    public function pass()
    {

        # Iniciamos o continuamos sesión
        session_start();

        # Capa de autentificación
        if (!isset($_SESSION['id'])) {

            header('location:' . URL . 'login');

        }

        # Comprobamos si existe mensaje
        if (isset($_SESSION['mensaje'])) {

            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);

        }

        # Capa no validación formulario
        if (isset($_SESSION['error'])) {

            # Mensaje de error
            $this->view->error = $_SESSION['error'];
            unset($_SESSION['error']);

            # Tipo de error
            $this->view->errores = $_SESSION['errores'];
            unset($_SESSION['errores']);

        }

        # título página
        $this->view->title = "Modificar password";
        $this->view->render('perfil/pass/index');


    }

    # Validación cambio password
    public function valpass()
    {

        # Iniciamos o continuamos con la sesión
        session_start();

        # Capa autentificación
        if (!isset($_SESSION['id'])) {

            header("location:" . URL . "login");
        }

        # Saneamos el formulario
        $password_actual = filter_var($_POST['password_actual'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($_POST['password'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);
        $password_confirm = filter_var($_POST['password_confirm'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);

        # Obtenemos objeto con los detalles del usuario
        $user1 = $this->model->getUserId($_SESSION['id']);

        # Validaciones

        $errores = array();

        # Validar password actual
        if (!password_verify($password_actual, $user1->password)) {
            $errores['password_actual'] = "Password actual no es correcto";
        }

        # Validar nuevo password
        if (empty($password)) {
            $errores['password'] = "Password no introducido";
        } else if (strcmp($password, $password_confirm) !== 0) {
            $errores['password'] = "Password no coincidentes";
        } else if ((strlen($password) < 5) || (strlen($password) > 60)) {
            $errores['password'] = "Password ha de tener entre 5 y 60 caracteres";
        }


        if (!empty($errores)) {

            $_SESSION['errores'] = $errores;
            $_SESSION['error'] = "Formulario con errores de validación";

            header("location:" . URL . "perfil/pass");

        } else {

            # Crear objeto user
            $user = new classUser(
                $user1->id,
                null,
                null,
                $password
            );

            # Actualiza password
            $this->model->updatePass($user);


            // Código para enviar el correo de registro ---------------------------------------------
            // Creamos un objeto de la clase PHPMailer
            $mail = new PHPMailer(true);

            // Configuración de PHPMailer
            $mail->CharSet = "UTF-8";
            $mail->Encoding = "quoted-printable";
            $mail->Username = USERNAME;
            $mail->Password = PASSWD;

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // tls Habilita el cifrado TLS implícito
            $mail->Port = 587;

            $destinatario = $user1->email;
            $remitente = USERNAME;
            $asunto = "Contraseña Modificada";
            $mensaje = "
            <h1>Hola!! $user1->name</h1>
            <p>Contraseña Modificada con éxito</p>
            <p>Datos: </p>
            <ul>
                <li><b>Password Modificada: </b>" . $user->password . "</li>
            </ul>";

            // Configuración del correo con PHPMailer
            $mail->setFrom($remitente, 'Pablo');
            $mail->addAddress($destinatario, $user->name);
            $mail->addReplyTo($remitente, 'Pablo Mateos');

            // Configuración del contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $mensaje;

            // Esta línea la he tenido que añadir para mi pc en casa porque me daba fallo el certificado SSL
            $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
            $mail->send();


            // -------------------------------------------------------------------------------

            $_SESSION['mensaje'] = "Password modificado correctamente y notificación enviada";

            #Vuelve corredores
            header("location:" . URL . "clientes");
        }



    }

    # Elimina definitivamente el perfil
    public function delete()
    {

        # Iniciamos o continuamos con la sesión
        session_start();

        # Capa autentificación
        if (!isset($_SESSION['id'])) {

            header("location:" . URL . "login");

        } else {

            # Obtenemos objeto con los detalles del usuario
            $user1 = $this->model->getUserId($_SESSION['id']);

            // Código para enviar el correo de registro ---------------------------------------------
            // Creamos un objeto de la clase PHPMailer
            $mail = new PHPMailer(true);

            // Configuración de PHPMailer
            $mail->CharSet = "UTF-8";
            $mail->Encoding = "quoted-printable";
            $mail->Username = USERNAME;
            $mail->Password = PASSWD;

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // tls Habilita el cifrado TLS implícito
            $mail->Port = 587;

            $destinatario = $user1->email;
            $remitente = USERNAME;
            $asunto = "Usuario Eliminado";
            $mensaje = "
            <p>Estimado <?php echo $user1->name; ?>,</p>

            <p>Lamentamos saber que has decidido cerrar tu cuenta en GesBank. Agradecemos tu tiempo y confianza en nuestro servicio. Si hay alguna razón específica por la que has tomado esta decisión, nos encantaría recibir tus comentarios para mejorar nuestros servicios. Estamos comprometidos en brindar la mejor experiencia a nuestros usuarios.</p>
            <p>Recuerda que siempre serás bienvenido/a de vuelta en caso de que cambies de opinión en el futuro. ¡Te deseamos mucho éxito en tus futuros proyectos! Si necesitas asistencia o tienes alguna pregunta, no dudes en ponerte en contacto con nuestro equipo de soporte.</p>
            <p>Gracias nuevamente y esperamos que tengas un excelente día.</p>

            <p>Atentamente,<br>
            El equipo de GesBank</p>";

            // Configuración del correo con PHPMailer
            $mail->setFrom($remitente, 'Pablo');
            $mail->addAddress($destinatario, $user1->name);
            $mail->addReplyTo($remitente, 'Pablo Mateos');

            // Configuración del contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $mensaje;

            // Esta línea la he tenido que añadir para mi pc en casa porque me daba fallo el certificado SSL
            $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
            $mail->send();

            $_SESSION['mensaje'] = "Mensaje enviado correctamente.";

            // -------------------------------------------------------------------------------

            # Elimino perfil de usuario
            $this->model->delete($_SESSION['id']);

            # Destruyo la sesión
            session_destroy();

            # Salgo de la aplicación
            header('location:' . URL . 'index');
        }

    }
}

?>