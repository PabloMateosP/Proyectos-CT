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
            $id_project_manager = isset($_POST['id_project_manager']) ? filter_var($_POST['id_project_manager'], FILTER_SANITIZE_NUMBER_INT) : null;
            $id_customer = isset($_POST['id_customer']) ? filter_var($_POST['id_customer'], FILTER_SANITIZE_NUMBER_INT) : null;
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

            # We update the line id_projectManager and customer from the project table to delete the relations
            $this->model->updatePMyC($id);

            # We delete the relation between employee and project
            $this->model->deleteRelationE($id);

            # We delete the relation between project and project manager
            $this->model->deleteRelationPM($id);

            # We delete the relation between project and customer
            $this->model->deleteRelationC($id);

            # Check if there are tasks associated with the project
            $tasks = $this->model->getProjTask($id);

            if (!empty($tasks)) {

                foreach ($tasks as $task) {
                    # Update the relation between the task and the working hour
                    $this->model->updateTaskWH($task['id']);
                }

                # If there are tasks associated with the project, delete the tasks
                $this->model->deleteTasks($id);
            }

            # Update the relation between the working hour and the project 
            $this->model->updateProjectWH($id);

            # We delete the project
            $this->model->delete($id);

            $_SESSION['mensaje'] = 'Project deleted correctly';
            header("Location:" . URL . "projects/");
        }
    }



    # ---------------------------------------------------------------------------------
    #   ______  _____  _____  _______ 
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

            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Form project edit";
            $this->view->project_ = $this->model->read($id);

            $this->view->projectEmployees = $this->model->getProjectEmployees($id);
            $this->view->project_managers = $this->model->get_projectManagers();
            $this->view->customers = $this->model->get_customers();

            $this->view->employees = $this->model->get_Employees();

            if (isset($_SESSION['error'])) {

                $this->view->error = $_SESSION['error'];

                $this->view->project_ = unserialize($_SESSION['employee']);

                $this->view->errores = $_SESSION['errores'];

                # We must release the variables because their purpose was carried out
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['projects']);

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
        // Start Session
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
            // 3. Validation
            // Only if necessary
            // Only in case when the field is modified 

            $errors = [];

            // project
            if (strcmp($project->project, $project_orig->project) !== 0) {
                if (empty($project_)) {
                    $errors['project'] = 'The field project_ is required';
                } else if (strlen($project_) > 10) {
                    $errors['project'] = 'The field project_ is too long';
                }
            }

            // description
            if (strcmp($project->description, $project_orig->description) !== 0) {
                if (empty($description)) {
                    $errors['description'] = 'The field description is required';
                } else if (strlen($description) > 50) {
                    $errors['description'] = 'The field description is too long';
                }
            }

            // id_projectManager
            if (strcmp($project->id_projectManager, $project_orig->id_projectManager) !== 0) {
                if (empty($id_Manager)) {
                    $id_Manager = null;
                } else if (strlen($id_Manager) > 10) {
                    $errors['id_project_manager'] = 'The field id_Manager is too long';
                }
            }

            // id_customer
            if (strcmp($project->id_customer, $project_orig->id_customer) !== 0) {
                if (empty($id_customer)) {
                    $id_customer = null;
                } else if (strlen($id_customer) > 10) {
                    $errors['id_customer'] = 'The field id_customer is too long';
                }
            }

            // finish_date
            if (strcmp($project->finish_date, $project_orig->finish_date) !== 0) {
                if (empty($finish_date)) {
                    $errors['finish_date'] = 'The field finish_date is required';
                } else if (strlen($finish_date) > 20) {
                    $errors['finish_date'] = 'The field finish_date is too long';
                }
            }

            if (!empty($errors)) {

                // Validation's error
                $_SESSION['project'] = serialize($project);
                $_SESSION['error'] = 'Form not validated';
                $_SESSION['errores'] = $errors;

                // Redirect to workingHour's main
                header('location:' . URL . 'projects/edit/' . $id);

            } else {

                if (isset($_POST['employees'])) {
                    $formEmployees = $_POST['employees'];
                } else {
                    $formEmployees = [];
                }

                $projectEmployeeRelated = $this->model->getProjectEmployees($id);

                $employeesToDelete = array_diff($projectEmployeeRelated, $formEmployees);

                $employeesToCreate = array_diff($formEmployees, $projectEmployeeRelated);

                foreach ($formEmployees as $employeeId) {
                    if (in_array($employeeId, $projectEmployeeRelated)) {
                        // This employee was already related, we remove it from the employees to create relation
                        unset($employeesToCreate[array_search($employeeId, $employeesToCreate)]);
                    }
                }

                // Delete employee relationships that are no longer in the form
                foreach ($employeesToDelete as $employeeId) {
                    $this->model->deleteRelationEP($id, $employeeId);
                }

                // Create relationships for the employees of the form that were not previously related
                foreach ($employeesToCreate as $employeeId) {
                    $this->model->insertProjectEmployeeRelationship($employeeId, $id);
                }

                // Update project data
                $this->model->update($project, $id);

                // Message
                $_SESSION['mensaje'] = "Project updated correctly";

                // Redirect to projects main
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

            if (in_array($_SESSION['id_rol'], $GLOBALS['employee'])) {

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