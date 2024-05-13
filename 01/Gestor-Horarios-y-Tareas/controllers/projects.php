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
    # Main method. Charge all the projects in the database.
    public function render($param = [])
    {
        # Start or continue the session
        session_start();
        if (!isset($_SESSION['id'])) {

            $_SESSION['notify'] = "Unauthenticated user";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {

            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "index");

        } else {

            # Probing if exist some message
            if (isset($_SESSION['mensaje'])) {

                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);

            }

            $this->view->title = "Projects";

            if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['employee'])) {

                $this->view->projects = $this->model->getEmpProj($_SESSION['employee_id']);

            } else {

                $this->view->projects = $this->model->get();

            }

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
    # "New" method. Form to add an new projects
    # Show a form to create a new project
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

            $this->view->title = "Form new Project";

            $this->view->project_managers = $this->model->get_projectManagers();
            $this->view->customers = $this->model->get_customers();
            $this->view->employees = $this->model->get_Employees();

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
    # Allow to add a new project
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
            $id_project_manager = filter_var($_POST['id_project_manager'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $id_customer = filter_var($_POST['id_customer'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $finish_date = filter_var($_POST['finish_date'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            # 2. Create an object of the class project, project_employee, project_managers and customer_project
            $project = new classProject(
                null,
                $project_,
                $description,
                $id_project_manager,
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

                $project_id = $this->model->create($project);

                if (!empty($id_customer)) {

                    $this->model->insertCustomerProjectRelationship($project_id, $id_customer);

                }

                if (!empty($id_project_manager)) {

                    $this->model->insertProjectManagerRelationship($project_id, $id_project_manager);

                }

                if (isset($_POST['employees'])) {
                    $employees = $_POST['employees'];
                    foreach ($employees as $employee_id) {
                        $this->model->insertProjectEmployeeRelationship($employee_id, $project_id);
                    }
                }

                #Mensaje
                $_SESSION['mensaje'] = "Project create correctly";

                # Redirigimos al main de project
                header('location:' . URL . 'projects');
            }
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #    _____ _    _  ______          __
    #   / ____| |  | |/ __ \ \        / /
    #  | (___ | |__| | |  | \ \  /\  / / 
    #   \___ \|  __  | |  | |\ \/  \/ /  
    #   ____) | |  | | |__| | \  /\  /   
    #  |_____/|_|  |_|\____/   \/  \/    
    #
    # ---------------------------------------------------------------------------------
    # Method show 
    # Show a form to watch the information about a project
    public function show($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must autenthicated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {
            $_SESSION['mensaje'] = "Unprivileged Operation";
            header("location:" . URL . "projects");
        } else {
            $id = $param[0];
            $this->view->title = "Form Show Project";
            $this->view->employees = $this->model->get_Employees();

            $this->view->projectEmployees = $this->model->getProjectEmployees($id);
            $this->view->projectManagers = $this->model->get_projectManagers();
            $this->view->customers = $this->model->get_customers();

            $this->view->project_ = $this->model->read($id);
            $this->view->render("projects/show/index");
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

            # We update the line id_projectManager and customer from the project table to delete te relations
            $this->model->updatePMyC($id);

            # We delete the relation between employee and project
            $this->model->deleteRelationE($id);

            # We delete the relation between project and project manager
            $this->model->deleteRelationPM($id);

            # We delete the relation between project and customer
            $this->model->deleteRelationC($id);

            # We delete the tasks from the project delete 
            $this->model->deleteTasks($id);

            # We delete the project
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
            $this->view->title = "Form project edit";
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
    # Update the table of the table project
    public function update($param = [])
    {
        // Iniciar Sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";
            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "project");
        } else {
            // Sanitize data
            $project_ = filter_var($_POST['project'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            $description = filter_var($_POST['description'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_Manager = filter_var($_POST['id_project_manager'] ?? '', FILTER_SANITIZE_NUMBER_INT);
            $id_customer = filter_var($_POST['id_customer'] ?? '', FILTER_SANITIZE_NUMBER_INT);
            $finish_date = filter_var($_POST['finish_date'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);

            // Create a project object
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

            // Get original project data
            $project_orig = $this->model->read($id);

            // Validation
            // Check if each field is modified and validate
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
                    $id_Manager = null;
                } else if (strlen($id_Manager) > 10) {
                    $errores['id_project_manager'] = 'The field id_Manager is too long';
                }
            }

            # id_customer
            if (strcmp($project->id_customer, $project_orig->id_customer) !== 0) {

                if (empty($id_customer)) {
                    $id_customer = null;
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

                // Check if there's a project manager relationship
                $hasProjectManagerRelation = !empty($project->id_projectManager) ? $this->model->hasProjectManagerRelation($id) : false;

                if (!empty($project->id_projectManager)) {
                    if ($hasProjectManagerRelation) {
                        // Update existing project manager relationship
                        $this->model->updateProjectManagerRelation($project->id_projectManager, $id);
                    } else {
                        // Create new project manager relationship
                        $this->model->insertProjectManagerRelationship($id, $project->id_projectManager);
                    }
                }

                // Check if there's a customer relationship
                $hasCustomerRelation = !empty($project->id_customer) ? $this->model->hasCustomerRelation($id) : false;

                if (!empty($project->id_customer)) {
                    if ($hasCustomerRelation) {
                        // Update existing customer relationship
                        $this->model->updateCustomerRelation($project->id_customer, $id);
                    } else {
                        // Create new customer relationship
                        $this->model->insertCustomerProjectRelationship($id, $project->id_customer);
                    }
                }

                // Update project data
                $this->model->update($project, $id);

                // Update project data
                $this->model->update($project, $id);

                # Message
                $_SESSION['mensaje'] = "project update correctly";

                # Redirect to projects main
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

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {

            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "projects/");

        } else {

            $criterio = $param[0];

            if (in_array($_SESSION['id_rol'], $GLOBALS['employee'])){

                $id_employee = $_SESSION['employee_id'];

                $this->view->projects = $this->model->orderProjEmp($id_employee, $criterio);

            } elseif (in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])) {

                $this->view->projects = $this->model->order($criterio);

            }
            $this->view->title = "Table Projects";
            
            $this->view->render("projects/main/index");

        }

    }

    # ---------------------------------------------------------------------------------
    #
    #     _____ ______          _____   _____ _    _ 
    #    / ____|  ____|   /\   |  __ \ / ____| |  | |
    #   | (___ | |__     /  \  | |__) | |    | |__| |
    #    \___ \|  __|   / /\ \ |  _  /| |    |  __  |
    #    ____) | |____ / ____ \| | \ \| |____| |  | |
    #   |_____/|______/_/    \_\_|  \_\\_____|_|  |_|
    #
    # ---------------------------------------------------------------------------------
    # Method search 
    # Search for projects records that match the pattern specified in the search expression
    public function search($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login/");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {

            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "projects/");

        } else {

            $expresion = $_GET["expresion"];
            $this->view->title = "Projects";
            $this->view->projects = $this->model->filter($expresion);
            $this->view->render("projects/main/index");

        }
    }


    # ---------------------------------------------------------------------------------
    #
    #     _______        _____ _  __ _____ 
    #    |__   __|/\    / ____| |/ // ____|
    #       | |  /  \  | (___ | ' /| (___  
    #       | | / /\ \  \___ \|  <  \___ \ 
    #       | |/ ____ \ ____) | . \ ____) |
    #       |_/_/    \_\_____/|_|\_\_____/ 
    #
    # ---------------------------------------------------------------------------------
    public function tasks($param = [])
    {
        # Start or continue the session
        session_start();
        if (!isset($_SESSION['id'])) {

            $_SESSION['notify'] = "Unauthenticated user";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {

            $_SESSION['mensaje'] = "Unauthenticated user";
            header("location:" . URL . "index");

        } else {

            # Probing if exist some message
            if (isset($_SESSION['mensaje'])) {

                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);

            }

            $id = $param[0];

            $this->view->title = "Tasks Project";

            $this->view->tasks = $this->model->getProjTask($id);

            $this->view->render("projects/tasks/index");

        }
    }

}