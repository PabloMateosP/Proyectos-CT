<?php

class Login extends Controller
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
    # "Render" Method. That show the login view
    public function render()
    {
        // Start or continue secure session
        session_start();

        // Initialize form values
        $this->view->email = null;
        $this->view->password = null;

        // Message control
        if (isset($_SESSION['mensaje'])) {

            $this->view->mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);

            // Autofill in case of successful registration

            if (isset($_SESSION['email'])) {
                $this->view->email = $_SESSION['email'];
                unset($_SESSION['email']);
            }

            if (isset($_SESSION['password'])) {
                $this->view->password = $_SESSION['password'];
                unset($_SESSION['password']);
            }

        }

        // Error control
        if (isset($_SESSION['error'])) {

            $this->view->error = $_SESSION['error'];
            unset($_SESSION['error']);

            // Autocomplete form values
            $this->view->email = $_SESSION['email'];
            $this->view->password = $_SESSION['password'];
            unset($_SESSION['email']);
            unset($_SESSION['password']);

            // Type of error
            $this->view->errores = $_SESSION['errores'];
            unset($_SESSION['errores']);

        }

        $this->view->render('login/index');
    }


    # ---------------------------------------------------------------------------------
    #    
    # __      __     _      _____ _____       _______ ______ 
    # \ \    / /\   | |    |_   _|  __ \   /\|__   __|  ____|
    #  \ \  / /  \  | |      | | | |  | | /  \  | |  | |__   
    #   \ \/ / /\ \ | |      | | | |  | |/ /\ \ | |  |  __|  
    #    \  / ____ \| |____ _| |_| |__| / ____ \| |  | |____ 
    #     \/_/    \_\______|_____|_____/_/    \_\_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Method Validate 
    # Method to validate the login of the user 
    public function validate()
    {

        // Start or resume session
        session_start();

        // Sanitize the form
        $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

        // Validations

        $errors = array();

        // Get the user from the email
        $user = $this->model->getUserEmail($email);

        if ($user === false) {

            $errors['email'] = "Email has not been registered";
            $_SESSION['errores'] = $errors;

            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;

            $_SESSION['error'] = "Authentication Failed";

            header("location:" . URL . "login");

        } else if (!password_verify($password, $user->password)) {

            $errors['password'] = "Password is incorrect";
            $_SESSION['errores'] = $errors;

            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;

            $_SESSION['error'] = "Authentication Failed";

            header("location:" . URL . "login");

        } else {

            // Authentication completed
            $_SESSION['id'] = $user->id;
            $_SESSION['email'] = $email;
            $_SESSION['name_user'] = $user->name;
            $_SESSION['id_rol'] = $this->model->getUserIdPerfil($user->id);
            $_SESSION['name_rol'] = $this->model->getUserPerfil($_SESSION['id_rol']);

            if ((in_array($_SESSION['id_rol'], $GLOBALS['admin']))) {
                // If the user is admin, they do not need to be added in the employee table.
            } else {
                $employeee = $this->model->getEmployeeId($email);
                $_SESSION['employee_id'] = $employeee->id;
            }

            if ((in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
                // If the user is admin, redirect to the employees page
                $_SESSION['mensaje'] = "User " . $user->name . " has logged in";
                header("location:" . URL . "employees/");
            } elseif ((in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee']))) {
                // If the user is not admin, redirect to the workingHours page
                $_SESSION['mensaje'] = "User " . $user->name . " has logged in";
                header("location:" . URL . "workingHours/");
            }
        }

    }
}