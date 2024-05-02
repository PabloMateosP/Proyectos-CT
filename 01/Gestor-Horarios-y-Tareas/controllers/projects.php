<?php

class Projects extends Controller
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
    # Main method. Charge all the working hours in the database.
    public function render($param = [])
    {
        # Start or continue the session
        session_start();
        if (!isset($_SESSION['id'])) {

            $_SESSION['notify'] = "Usuario sin autentificar";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {

            $_SESSION['mensaje'] = "Usuario sin autentificar";
            header("location:" . URL . "index");

        } else {

            # Probing if exist some message
            if (isset($_SESSION['mensaje'])) {

                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);

            }

            $this->view->title = "Projects";

            $this->view->projects = $this->model->get();

            $this->view->render("projects/main/index");

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
    # "New" method. Form to add an new working Hours
    # Show a form to create a new working hour
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
            header("location:" . URL . "projects");
        } else {

            # Create an empty object
            $this->view->workingHours = new classWorkingHours();

            # We check if there are errors -> this variable is created to throw an validation error
            if (isset($_SESSION['error'])) {

                # Rescue the message
                $this->view->error = $_SESSION['error'];

                # We autofill the form
                $this->view->workingHours = unserialize($_SESSION['workingHours']);

                # We rescue the array of errors
                $this->view->errores = $_SESSION['errores'];

                # We must release the session variables
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['workingHours']);
                # If these variables exist when there are no errors, we will enter the error blocks in the conditionals
            }

            $this->view->title = "Form new working hour";

            $this->view->project_managers = $this->model->get_projectManagers();
            $this->view->customers = $this->model->get_customers();

            $this->view->render("projects/new/index");
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

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {

            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "projects");

        } else {

            # 1. Security: We sanitize the data that is sent by the user
            $project_ = filter_var($_POST['project'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $description = filter_var($_POST['description'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_projectManager = filter_var($_POST['id_project_manager'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $id_customer = filter_var($_POST['id_customer'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $finish_date = filter_var($_POST['finish_date'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            # 2. Create an object of the class
            $project = new classProject(
                null,
                $project_,
                $description,
                $id_projectManager,
                $id_customer,
                null,
                $finish_date,
                null
            );

            # 3. Validation
            $errores = [];

            # Project
            if (empty($project_)) {
                $errores['project'] = 'The field project is required';
            } else if (strlen($project_) > 8) {
                $errores['project'] = 'The field project is too long';
            }

            # Description
            if (empty($description)) {
                $errores['description'] = 'The field description is required';
            } else if (strlen($description) > 50) {
                $errores['description'] = 'The field description is too long';
            }

            # Id_projectManager
            if (empty($id_projectManager)) {
                $errores['id_project_manager'] = 'The field project Manager is required';
            } else if (strlen($id_projectManager) > 10) {
                $errores['id_project_manager'] = 'The field project Manager is too long';
            }

            # Id_customer
            if (empty($id_customer)) {
                $errores['id_customer'] = 'The field customer is required';
            } else if (strlen($id_customer) > 10) {
                $errores['id_customer'] = 'Field customer too long';
            }

            # Finish_date
            if (empty($finish_date)) {
                $errores['finish_date'] = 'The field finish_date is required';
            } else if (strlen($finish_date) > 20) {
                $errores['finish_date'] = 'Finish date too long';
            }

            #4. Verify Validation

            if (!empty($errores)) {

                # Validation's error
                $_SESSION['workingHours'] = serialize($project);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'projects/new');

            } else {

                #Create project
                # Añadir registro a la tabla
                $this->model->create($project);

                #Mensaje
                $_SESSION['mensaje'] = "Project create correctly";

                # Redirigimos al main de workingHours
                header('location:' . URL . 'projects');
            }
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
    # Method delete. 
    # Allow to delete the project
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            
            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "projects/");

        } else {
            $id = $param[0];

            # We delete the working hour
            $this->model->delete($id);

            $_SESSION['mensaje'] = 'Project delete correctly';

            header("Location:" . URL . "projects/");
        }
    }

    # ---------------------------------------------------------------------------------
    #    ____   _____   _____   ______  _____  
    #   / __ \ |  __ \ |  __ \ |  ____||  __ \ 
    #  | |  | || |__) || |  | || |__   | |__) |
    #  | |  | ||  _  / | |  | ||  __|  |  _  / 
    #  | |__| || | \ \ | |__| || |____ | | \ \ 
    #   \____/ |_|  \_\|_____/ |______||_|  \_\
    #
    # ---------------------------------------------------------------------------------
    # Method order
    # Allow order the table projects
    public function order($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login/");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {

            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "projects/");

        } else {

            $criterio = $param[0];
            $this->view->title = "Table Projects";
            $this->view->projects = $this->model->order($criterio);
            $this->view->render("projects/main/index");

        }

    }
}