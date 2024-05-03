<?php

class Tasks extends Controller
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
    # Main method. Charge all the tasks in the database.
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

            $this->view->title = "Tasks";

            $this->view->tasks = $this->model->get();

            $this->view->render("tasks/main/index");
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

        # user with privileges
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "tasks");
        } else {

            # Create an empty object
            $this->view->task = new classTask();

            # We check if there are errors -> this variable is created to throw an validation error
            if (isset($_SESSION['error'])) {

                # Rescue the message
                $this->view->error = $_SESSION['error'];

                # We autofill the form
                $this->view->task = unserialize($_SESSION['tasks']);

                # We rescue the array of errors
                $this->view->errores = $_SESSION['errores'];

                # We must release the session variables
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['tasks']);
                # If these variables exist when there are no errors, we will enter the error blocks in the conditionals
            }

            $this->view->title = "Form new task";

            $this->view->projects = $this->model->get_projects();

            $this->view->render("tasks/new/index");
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
            header("location:" . URL . "workingHours");

        } else {

            # 1. Security: We sanitize the data that is sent by the user
            $task_ = filter_var($_POST['task'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_project = filter_var($_POST['id_project'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $description = filter_var($_POST['description'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            # 2. Create an object of the class
            $task = new classTask(
                null,
                $task_,
                $description,
                $id_project,
                null,
                null
            );

            # 3. Validation
            $errores = [];

            # task
            if (empty($task_)) {
                $errores['task'] = 'The field task is required';
            } else if (strlen($task_) > 10) {
                $errores['task'] = 'The field task is too long';
            }
            
            # Description
            if (empty($description)) {
                $errores['description'] = 'The field description is required';
            } else if (strlen($description) > 50) {
                $errores['description'] = 'Description too long';
            }

            # Id_project
            if (empty($id_project)) {
                $errores['id_task'] = 'The id_project is required';
            } else if (strlen($id_project) > 10) {
                $errores['id_task'] = 'Field id_project too long';
            }

            #4. Verify Validation

            if (!empty($errores)) {

                # Validation's error
                $_SESSION['task'] = serialize($task);
                $_SESSION['error'] = 'Invalid form';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'tasks/new');

            } else {

                $this->model->create($task);

                #Mensaje
                $_SESSION['mensaje'] = "Task create correctly";

                # Redirigimos al main de workingHours
                header('location:' . URL . 'tasks');
            }
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
    # Show a form to edit a task
    public function edit($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            $_SESSION['mensaje'] = "Operation without privileges";

            header('location:' . URL . 'tasks');

        } else {

            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Formulario editar task";
            $this->view->task = $this->model->read($id);
            $this->view->projects = $this->model->get_projects($this->view->id);

            if (isset($_SESSION['error'])) {
                
                $this->view->error = $_SESSION['error'];

                $this->view->task = unserialize($_SESSION['task']);

                $this->view->errores = $_SESSION['errores'];

                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['task']);

            }

            $this->view->render("tasks/edit/index");
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

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "tasks");
        } else {

            #1.Security. 
            $task_ = filter_var($_POST['task'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_project = filter_var($_POST['id_project'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $description = filter_var($_POST['description'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            $task = new classTask(
                null,
                $task_,
                $description,
                $id_project,
                null,
                null
            );

            $id = $param[0];

            #Take the object workinHours original
            $task_orig = $this->model->read($id);

            # 3. Validation
            # Only if is necessary
            # Only in case when the field is modified 

            $errores = [];

            # task
            if (strcmp($task->task, $task_orig->task) !== 0) {
                if (empty($task_)) {
                    $errores['task'] = 'The field task is required';
                } else if (strlen($task_) > 10) {
                    $errores['task'] = 'The field task is too long';

                }
            }

            # id_project
            if (strcmp($task->id_project, $task_orig->id_project) !== 0) {

                if (empty($id_project)) {
                    $errores['id_project'] = 'The field id_project is required ';
                } else if (strlen($id_project) > 10) {
                    $errores['id_project'] = 'The field id_project is too long';
                }
            }

            # decription
            if (strcmp($task->description, $task_orig->description) !== 0) {

                if (empty($description)) {
                    $errores['email'] = 'The field description is required';
                } else if (strlen($description) > 50) {
                    $errores['description'] = 'The field description is too long';
                }
            }

            #4. Validation check

            if (!empty($errores)) {

                # Validation's error
                $_SESSION['task'] = serialize($task);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                # Redirect to workingHour's main
                header('location:' . URL . 'tasks/edit/' . $id);

            } else {

                # Adding the data to the table working hours
                $this->model->update($task, $id);

                # Message
                $_SESSION['mensaje'] = "Task update correctly";

                # Redirect to Working Hours main
                header('location:' . URL . 'tasks');
            }
        }
    }

    # ---------------------------------------------------------------------------------  
    #  
    #    _____ ______ _______   _____  _____   ____       _ ______ _____ _______ _____ 
    #   / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __/ ____|
    #  | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | | | (___  
    #  | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |  \___ \ 
    #  | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |  ____) |
    #   \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_| |_____/ 
    #
    # ---------------------------------------------------------------------------------
    # function get_projects 
    # function to get the information about all the projects
    public function get_projects()
    {
        try {
            $sql = "SELECT 
                        pr.id,
                        pr.project,
                        pr.description,
                        pm.last_name AS manager_last_name,
                        pm.name AS manager_name
                    FROM 
                        projects pr
                    JOIN 
                        projectManager pm ON pr.id_projectManager = pm.id";

            $conexion = $this->db->connect();
            $result = $conexion->prepare($sql);
            $result->setFetchMode(PDO::FETCH_OBJ);
            $result->execute();

            return $result->fetchAll();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
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
    # Allow to delete the tasks
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {
            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "tasks");
        } else {
            $id = $param[0];

            $this->model->delete($id);

            $_SESSION['mensaje'] = 'Task delete correctly';

            header("Location:" . URL . "tasks/");
        }
    }
}