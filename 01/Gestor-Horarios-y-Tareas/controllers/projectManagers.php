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
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Unauthenticated user";
            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            $_SESSION['mensaje'] = "Unauthenticated user";
            header("location:" . URL . "index");
        } else {
            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }

            $this->view->title = "Project Managers";

            $this->view->projectManagers = $this->model->get();

            $projectManagers = $this->model->get();

            $allProjects = []; // Array to store all the projects

            foreach ($projectManagers as $projectManager) {
                $projects = $this->model->getProjectsByManager($projectManager->id);
                $allProjects = array_merge($allProjects, $projects); // Merge the projects in a same array
            }

            $this->view->projects = $allProjects;

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

            $this->view->projects = $this->model->get_projects();

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
            } else if (strlen($last_name) > 45) {
                $errores['last_name'] = 'The field last_name is too long';
            }

            # Name
            if (empty($name)) {
                $errores['name'] = 'The field name is required';
            } else if (strlen($name) > 20) {
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

                $projectManager_id = $this->model->create($projectManager);

                if (isset($_POST['projects'])) {
                    $projects = $_POST['projects'];
                    foreach ($projects as $project_id) {
                        # We update the project manager's relation with the project
                        $this->model->insertProjectManagerRelationship($project_id, $projectManager_id);

                        # We update the id in the table project with the project manager id
                        $this->model->updateProjectId($project_id, $projectManager_id);
                    }
                }

                $_SESSION['mensaje'] = "Working Hour create correctly";

                header('location:' . URL . 'projectManagers');

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
    # Show a form to edit a project Manager
    public function edit($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            $_SESSION['mensaje'] = "Operation without privileges";

            header('location:' . URL . 'projectManagers');

        } else {


            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Form project manager edit";
            $this->view->projectManager = $this->model->read($id);
            $this->view->projects = $this->model->get_projects();

            if (isset($_SESSION['error'])) {

                $this->view->error = $_SESSION['error'];

                $this->view->projectManager = unserialize($_SESSION['projectManager']);

                $this->view->errores = $_SESSION['errores'];

                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['projectManager']);
            }

            $this->view->render("projectManagers/edit/index");
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
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";
            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee'])) && (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "project");
        } else {
            $last_name = filter_var($_POST['last_name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            $projectManager = new classProjectManagers(null, $last_name, $name, null, null);
            $id = $param[0];
            $projectManager_orig = $this->model->read($id);

            $errores = [];

            if (strcmp($projectManager->last_name, $projectManager_orig->last_name) !== 0) {
                if (empty($last_name)) {
                    $errores['projectManager'] = 'The field project Manager is required';
                } else if (strlen($last_name) > 45) {
                    $errores['projectManager'] = 'The field project Manager is too long';
                }
            }

            if (strcmp($projectManager->name, $projectManager_orig->name) !== 0) {
                if (empty($name)) {
                    $errores['name'] = 'The field name is required';
                } else if (strlen($name) > 20) {
                    $errores['name'] = 'The field name is too long';
                }
            }

            if (!empty($errores)) {
                $_SESSION['project'] = serialize($projectManager);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;
                header('location:' . URL . 'projectManagers/edit/' . $id);
            } else {
                if (isset($_POST['projects'])) {
                    $formProjects = $_POST['projects'];
                } else {
                    $formProjects = [];
                }

                $projectManagerRelated = $this->model->getProjectsManager($id);

                $projectsToDelete = array_diff($projectManagerRelated, $formProjects);
                $projectsToCreate = array_diff($formProjects, $projectManagerRelated);

                $tempProjectsToDelete = $projectsToDelete;
                foreach ($tempProjectsToDelete as $projectId) {
                    $this->model->deleteRelationPmP($id, $projectId);
                    $this->model->deleteIdProjectMProjec($projectId);
                }

                $tempProjectsToCreate = $projectsToCreate;
                foreach ($tempProjectsToCreate as $projectId) {
                    $this->model->insertProjectManagerRelationship($projectId, $id);
                    $this->model->insertProjectManagerIdProject($projectId, $id);
                }

                $this->model->update($projectManager, $id);
                $_SESSION['mensaje'] = "Project Manager update correctly";
                header('location:' . URL . 'projectManagers');
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
    # Allow to delete the project Manager
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {
            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "projectManagers");
        } else {
            $id = $param[0];

            # We delete the relation between the project Manager and the project
            $this->model->deleteRelationPM($id);

            $this->model->updateIdProject($id);

            # We delete the projectManagers
            $this->model->delete($id);

            $_SESSION['mensaje'] = 'Project Manager delete correctly';

            header("Location:" . URL . "projectManagers/");
        }
    }
}