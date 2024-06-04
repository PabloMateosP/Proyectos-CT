<?php

class Perfil extends Controller
{

    # ---------------------------------------------------------------------------------
    #   _____  ______ _   _ _____  ______ _____  
    #  |  __ \|  ____| \ | |  __ \|  ____|  __ \ 
    #  | |__) | |__  |  \| | |  | | |__  | |__) |
    #  |  _  /|  __| | . ` | |  | |  __| |  _  / 
    #  | | \ \| |____| |\  | |__| | |____| | \ \ 
    #  |_|  \_\______|_| \_|_____/|______|_|  \_\
    # 
    # ---------------------------------------------------------------------------------
    # "Render" Method. That show the perfil view
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
        $this->view->title = 'User';

        $this->view->render('perfil/main/index');

    }

    # ---------------------------------------------------------------------------------
    #    
    #  ______ _____ _____ _______ 
    #  |  ____|  __ \_   _|__   __|
    #  | |__  | |  | || |    | |   
    #  |  __| | |  | || |    | |   
    #  | |____| |__| || |_   | |   
    #  |______|_____/_____|  |_|   
    #
    # ---------------------------------------------------------------------------------
    public function edit()
    {

        session_start();

        if (!isset($_SESSION['id'])) {

            header('location:' . URL . 'login');

        }

        if (isset($_SESSION['mensaje'])) {

            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);

        }

        $this->view->user = $this->model->getUserId($_SESSION['id']);

        if (isset($_SESSION['error'])) {

            $this->view->error = $_SESSION['error'];
            unset($_SESSION['error']);

            $this->view->user = unserialize($_SESSION['user']);
            unset($_SESSION['user']);

            $this->view->errores = $_SESSION['errores'];
            unset($_SESSION['errores']);

        }

        $this->view->title = 'Modify User';
        $this->view->render('perfil/edit/index');

    }

    # ---------------------------------------------------------------------------------
    #    
    # __      __     _      _____  ______ _____  ______ _____ _      
    # \ \    / /\   | |    |  __ \|  ____|  __ \|  ____|_   _| |     
    #  \ \  / /  \  | |    | |__) | |__  | |__) | |__    | | | |     
    #   \ \/ / /\ \ | |    |  ___/|  __| |  _  /|  __|   | | | |     
    #    \  / ____ \| |____| |    | |____| | \ \| |     _| |_| |____ 
    #     \/_/    \_\______|_|    |______|_|  \_\_|    |_____|______|
    #
    # ---------------------------------------------------------------------------------
    public function valperfil()
    {

        session_start();

        if (!isset($_SESSION['id'])) {

            header("location:" . URL . "login");
        }

        # Sanitize the data
        $name = filter_var($_POST['name'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ??= null, FILTER_SANITIZE_EMAIL);

        # Obtain the details
        $user = $this->model->getUserId($_SESSION['id']);

        # Validations
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

        $user = new classUser(
            $user->id,
            $name,
            $email,
            null
        );

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['user'] = serialize($user);
            $_SESSION['error'] = "Formulario con errores de validación";

            header('location:' . URL . 'perfil/edit');

        } else {

            # Update Profile
            $this->model->update($user);

            $_SESSION['name_user'] = $name;
            $_SESSION['mensaje'] = 'User modify correctly';

            header('location:' . URL . 'perfil');

        }

    }

    # ---------------------------------------------------------------------------------
    #    
    #  _____         _____ _____ 
    #  |  __ \ /\    / ____/ ____|
    #  | |__) /  \  | (___| (___  
    #  |  ___/ /\ \  \___ \\___ \ 
    #  | |  / ____ \ ____) |___) |
    #  |_| /_/    \_\_____/_____/ 
    #
    # ---------------------------------------------------------------------------------
    # Modify Password
    public function pass()
    {
        // Start or continue session
        session_start();

        // Authentication layer
        if (!isset($_SESSION['id'])) {

            header('location:' . URL . 'login');

        }

        // Check if a message exists
        if (isset($_SESSION['mensaje'])) {

            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);

        }

        // Layer for form validation failure
        if (isset($_SESSION['error'])) {

            // Error message
            $this->view->error = $_SESSION['error'];
            unset($_SESSION['error']);

            // Type of error
            $this->view->errores = $_SESSION['errores'];
            unset($_SESSION['errores']);

        }

        // Page title
        $this->view->title = "Modify Password";
        $this->view->render('perfil/pass/index');
    }


    # ---------------------------------------------------------------------------------
    #    
    # __      __     _      _____         _____ _____ 
    # \ \    / /\   | |    |  __ \ /\    / ____/ ____|
    #  \ \  / /  \  | |    | |__) /  \  | (___| (___  
    #   \ \/ / /\ \ | |    |  ___/ /\ \  \___ \\___ \ 
    #    \  / ____ \| |____| |  / ____ \ ____) |___) |
    #     \/_/    \_\______|_| /_/    \_\_____/_____/ 
    #
    # ---------------------------------------------------------------------------------
    # Validación cambio password
    public function valpass()
    {
        // Start or continue with the session
        session_start();

        // Authentication layer
        if (!isset($_SESSION['id'])) {

            header("location:" . URL . "login");
        }

        // Sanitize the form
        $password_actual = filter_var($_POST['password_actual'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($_POST['password'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);
        $password_confirm = filter_var($_POST['password_confirm'] ??= null, FILTER_SANITIZE_SPECIAL_CHARS);

        // Get object with user details
        $user1 = $this->model->getUserId($_SESSION['id']);

        // Validations

        $errors = array();

        // Validate current password
        if (!password_verify($password_actual, $user1->password)) {
            $errors['password_actual'] = "Current password is incorrect";
        }

        // Validate new password
        if (empty($password)) {
            $errors['password'] = "Password not entered";
        } else if (strcmp($password, $password_confirm) !== 0) {
            $errors['password'] = "Passwords do not match";
        } else if ((strlen($password) < 5) || (strlen($password) > 60)) {
            $errors['password'] = "Password must be between 5 and 60 characters";
        }


        if (!empty($errors)) {

            $_SESSION['errores'] = $errors;
            $_SESSION['error'] = "Form with validation errors";

            header("location:" . URL . "perfil/pass");

        } else {

            // Create user object
            $user = new classUser(
                $user1->id,
                null,
                null,
                $password
            );

            // Update password
            $this->model->updatePass($user);

            $_SESSION['mensaje'] = "Password modified correctly";

            // Return to runners
            header("location:" . URL . "workingHours");
        }
    }


    # ---------------------------------------------------------------------------------
    #    
    #   _____  ______ _      ______ _______ ______ 
    #  |  __ \|  ____| |    |  ____|__   __|  ____|
    #  | |  | | |__  | |    | |__     | |  | |__   
    #  | |  | |  __| | |    |  __|    | |  |  __|  
    #  | |__| | |____| |____| |____   | |  | |____ 
    #  |_____/|______|______|______|  |_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Delete definitely the profile
    public function delete()
    {
        session_start();

        if (!isset($_SESSION['id'])) {

            header("location:" . URL . "login");

        } else {

            # Take the user data
            $user1 = $this->model->getUserId($_SESSION['id']);

            # Delete the user profile
            $this->model->delete($_SESSION['id']);

            # Destroy the session
            session_destroy();

            # Leave the app
            header('location:' . URL . 'index');
        }
    }
}