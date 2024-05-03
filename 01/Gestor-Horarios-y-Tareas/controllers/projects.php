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
            $this->view->project = new classproject();

            # We check if there are errors -> this variable is created to throw an validation error
            if (isset($_SESSION['error'])) {

                # Rescue the message
                $this->view->error = $_SESSION['error'];

                # We autofill the form
                $this->view->project = unserialize($_SESSION['project']);

                # We rescue the array of errors
                $this->view->errores = $_SESSION['errores'];

                # We must release the session variables
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['project']);
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
            $id_Manager = filter_var($_POST['id_project_manager'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $id_customer = filter_var($_POST['id_customer'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $finish_date = filter_var($_POST['finish_date'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            # 2. Create an object of the class
            $project = new classProject(
                null,
                $project_,
                $description,
                $id_Manager,
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

            # id_ManagerManager
            if (empty($id_Manager)) {
                $errores['id_project_manager'] = 'The field project Manager is required';
            } else if (strlen($id_Manager) > 10) {
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
                $_SESSION['projects'] = serialize($project);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'projects/new');

            } else {

                #Create project
                # Añadir registro a la tabla
                $this->model->create($project);

                #Mensaje
                $_SESSION['mensaje'] = "Project create correctly";

                # Redirigimos al main de project
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
    #  ______  _____  _____  _______ 
    #  |  ____||  __ \|_   _||__   __|
    #  | |__   | |  | | | |     | |   
    #  |  __|  | |  | | | |     | |   
    #  | |____ | |__| |_| |_    | |   
    #  |______||_____/|_____|   |_|
    #
    # ---------------------------------------------------------------------------------
    # Method edit. 
    # Show a form to edit a project
    public function edit($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            $_SESSION['mensaje'] = "Operation without privileges";

            header('location:' . URL . 'projects');

        } else {

            # obtengo el id del project que voy a editar

            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Formulario editar project";
            $this->view->project_ = $this->model->read($id);

            $this->view->project_managers = $this->model->get_projectManagers();
            $this->view->customers = $this->model->get_customers();

            # Comprobamos si hay errores -> esta variable se crea al lanzar un error de validacion
            if (isset($_SESSION['error'])) {
                # rescatemos el mensaje
                $this->view->error = $_SESSION['error'];

                # Autorellenamos el formulario
                $this->view->project_ = unserialize($_SESSION['employee']);

                # Recupero array de errores específicos
                $this->view->errores = $_SESSION['errores'];

                # debemos liberar las variables de sesión ya que su cometido ha sido resuelto
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['projects']);
                # Si estas variables existen cuando no hay errores, entraremos en los bloques de error en las condicionales
            }

            $this->view->render("projects/edit/index");
        }
    }
    
    # ---------------------------------------------------------------------------------
    #
    #   _    _  _____   _____         _______  ______ 
    #  | |  | ||  __ \ |  __ \    /\ |__   __||  ____|
    #  | |  | || |__) || |  | |  /  \   | |   | |__   
    #  | |  | ||  ___/ | |  | | / /\ \  | |   |  __|  
    #  | |__| || |     | |__| |/ ____ \ | |   | |____ 
    #   \____/ |_|     |_____//_/    \_\|_|   |______|
    #                                               
    # ---------------------------------------------------------------------------------
    # Método update.
    # Update the table of the table workinhours 
    public function update($param = [])
    {

        #Iniciar Sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee'])) && (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            
            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "project");

        } else {

            # 1. Security: We sanitize the data that is sent by the user
            $project_ = filter_var($_POST['project'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $description = filter_var($_POST['description'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_Manager = filter_var($_POST['id_project_manager'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $id_customer = filter_var($_POST['id_customer'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $finish_date = filter_var($_POST['finish_date'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            # 2. Create an object of the class
            $project = new classProject(
                null,
                $project_,
                $description,
                $id_Manager,
                $id_customer,
                null,
                $finish_date,
                null
            );

            $id = $param[0];

            #Take the original data
            $project_orig = $this->model->read($id);

            # 3. Validation
            # Only if is necessary
            # Only in case when the field is modified 

            $errores = [];

            # project
            if (strcmp($project->project, $project_orig->project) !== 0) {
                if (empty($project_)) {
                    $errores['project'] = 'The field project_ is required';
                } else if (strlen($project_) > 10) {
                    $errores['project'] = 'The field project_ is too long';

                }
            }

            # description
            if (strcmp($project->description, $project_orig->description) !== 0) {

                if (empty($description)) {
                    $errores['description'] = 'The field description is required';
                } else if (strlen($description) > 50) {
                    $errores['description'] = 'The field description is too long';
                }
            }

            # id_projectManager
            if (strcmp($project->id_projectManager, $project_orig->id_projectManager) !== 0) {

                if (empty($id_Manager)) {
                    $errores['id_project_manager'] = 'The field id_Manager is required ';
                } else if (strlen($id_Manager) > 10) {
                    $errores['id_project_manager'] = 'The field id_Manager is too long';
                }
            }

            # id_customer
            if (strcmp($project->id_customer, $project_orig->id_customer) !== 0) {

                if (empty($id_customer)) {
                    $errores['id_customer'] = 'The field id_customer is required';
                } else if (strlen($id_customer) > 10) {
                    $errores['id_customer'] = 'The field id_customer is too long';
                }
            }

            # finish_date
            if (strcmp($project->finish_date, $project_orig->finish_date) !== 0) {

                if (empty($finish_date)) {
                    $errores['finish_date'] = 'The field finish_date is required ';
                } else if (strlen($finish_date) > 20) {
                    $errores['finish_date'] = 'The field finish_date is too long';

                }
            }

            if (!empty($errores)) {

                # Validation's error
                $_SESSION['project'] = serialize($project);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                # Redirect to workingHour's main
                header('location:' . URL . 'projects/edit/' . $id);

            } else {

                # Adding the data to the table working hours
                $this->model->update($project, $id);

                # Message
                $_SESSION['mensaje'] = "project actualizado correctamente";

                # Redirect to Working Hours main
                header('location:' . URL . 'projects');
            }
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