<?php

class ProjectManagers extends Controller
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
    # Main method. Charge all the projectManagers in the database.
    public function render($param = [])
    {
        # Start or continue the session
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Unauthenticated user";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            $_SESSION['mensaje'] = "Unauthenticated user";
            header("location:" . URL . "index");

        } else {

            # Probing if exist some message
            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }

            $this->view->title = "Project Managers";

            $this->view->projectManagers = $this->model->get();

            $this->view->render("projectManagers/main/index");
        }
    }

    # ---------------------------------------------------------------------------------    
    #   _   _ ________          __
    #  | \ | |  ____\ \        / /
    #  |  \| | |__   \ \  /\  / / 
    #  | . ` |  __|   \ \/  \/ /  
    #  | |\  | |____   \  /\  /   
    #  |_| \_|______|   \/  \/    
    #                          
    # ---------------------------------------------------------------------------------
    # "New" method. Form to add a new project Manager
    # Show a form to create a new project Manager
    public function new($param = [])
    {
        # Continue session if exists
        session_start();

        # User authenticated?
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "User must authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "projectManagers");
        } else {

            # Create an empty object
            $this->view->projectManager = new classProjectManagers();

            # We check if there are errors -> this variable is created to throw an validation error
            if (isset($_SESSION['error'])) {

                # Rescue the message
                $this->view->error = $_SESSION['error'];

                # We autofill the form
                $this->view->projectManager = unserialize($_SESSION['projectManager']);

                # We rescue the array of errors
                $this->view->errores = $_SESSION['errores'];

                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['projectManager']);
            }

            $this->view->title = "Form new Project Manager";

            $this->view->render("projectManagers/new/index");
        }
    }

    # ---------------------------------------------------------------------------------
    #    _____  _____   ______         _______  ______ 
    #   / ____||  __ \ |  ____|    /\ |__   __||  ____|
    #  | |     | |__) || |__      /  \   | |   | |__   
    #  | |     |  _  / |  __|    / /\ \  | |   |  __|  
    #  | |____ | | \ \ | |____  / ____ \ | |   | |____ 
    #   \_____||_|  \_\|______|/_/    \_\|_|   |______|
    #
    # ---------------------------------------------------------------------------------
    # Method create. 
    # Allow to add a new working hour
    public function create($param = [])
    {
        # Session start
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee'])) && (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {

            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "projectManager");

        } else {

            # 1. Security: We sanitize the data that is sent by the user
            $last_name = filter_var($_POST['last_name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            # 2. Create an object of the class
            $projectManager = new classProjectManagers(
                null,
                $last_name,
                $name,
                null,
                null
            );

            # 3. Validation
            $errores = [];

            # Last_Name
            if (empty($last_name)) {
                $errores['last_name'] = 'The field last_name is required';
            } else if (strlen($last_name) > 10) {
                $errores['last_name'] = 'The field last_name is too long';
            }

            # Name
            if (empty($name)) {
                $errores['name'] = 'The field name is required';
            } else if (strlen($name) > 10) {
                $errores['name'] = 'The field name is too long';
            }

            #4. Verify Validation

            if (!empty($errores)) {

                # Validation's error
                $_SESSION['projectManager'] = serialize($projectManager);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'projectManagers/new');

            } else {

                $this->model->create($projectManager);

                #Mensaje
                $_SESSION['mensaje'] = "Working Hour create correctly";

                # Redirigimos al main de projectManager
                header('location:' . URL . 'projectManagers');
            }
        }
    }
}