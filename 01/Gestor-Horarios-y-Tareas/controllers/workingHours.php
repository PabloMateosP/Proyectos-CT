<?php

class WorkingHours extends Controller
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
            $_SESSION['notify'] = "Unauthenticated user";

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

            $this->view->title = "Working Hours";

            if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['employee'])) {

                $email = $this->view->email_account = $this->model->get_userEmailById($_SESSION['id']);

                $this->view->workingHours = $this->model->get_employeeHours($email);

                if ($this->model->getTotalHours() == null) {

                    $this->view->total_hours = 0;

                } else {

                    $this->view->total_hours = $this->model->getTotalHours();

                }


            } else {

                $this->view->workingHours = $this->model->get();

            }

            $this->view->render("workingHours/main/index");
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

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee'])) && (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "workingHours");
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

            $this->view->time_Codes = $this->model->get_times_codes();

            if (in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])) {

                $this->view->projects = $this->model->get_projects();
                $this->view->tasks = $this->model->get_tasks();

            } else {

                // Obtener proyectos relacionados
                $projects = $this->model->get_projectsRelated($_SESSION['employee_id']);

                // Inicializar un array para almacenar todas las tareas
                $all_tasks = [];

                // Para cada proyecto relacionado, obtener tareas relacionadas y agregarlas al array
                foreach ($projects as $project) {
                    $project_id = $project->id;
                    $tasks = $this->model->get_tasksRelated($project_id);
                }

                // Guardar los proyectos y todas las tareas en las vistas
                $this->view->projects = $projects;
                $this->view->tasks = $tasks;

            }

            $this->view->render("workingHours/new/index");
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
            $id_time_code = filter_var($_POST['id_time_code'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_project = filter_var($_POST['id_project'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_task = filter_var($_POST['id_task'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $description = filter_var($_POST['description'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $duration = filter_var($_POST['duration'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $date_worked = filter_var($_POST['date_worked'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            # Check if id_time_code is different from 1, then set id_project and id_task to null
            if ($id_time_code != 1) {
                $id_project = null;
                $id_task = null;
            }

            # 2. Create an object of the class
            $workingHours = new classWorkingHours(
                null,
                $_SESSION['employee_id'],
                $id_time_code,
                $id_project,
                $id_task,
                $description,
                $duration,
                $date_worked,
                null,
                null
            );

            # 3. Validation
            $errores = [];

            # Id_time_code
            if (empty($id_time_code)) {
                $errores['id_time_code'] = 'The field id_time_code is required';
            } else if (strlen($id_time_code) > 10) {
                $errores['id_time_code'] = 'The field id_time_code is too long';
            }

            # Id_project
            if (!is_null($id_project) && empty($id_project)) {
                $errores['id_project'] = 'The field project is required';
            } else if (!is_null($id_project) && strlen($id_project) > 10) {
                $errores['id_project'] = 'The field project is too long';
            }

            # Id_task
            if (!is_null($id_task) && empty($id_task)) {
                $errores['id_task'] = 'The field task is required';
            } else if (!is_null($id_task) && strlen($id_task) > 10) {
                $errores['id_task'] = 'Field task too long';
            }

            # Description
            if (empty($description)) {
                $errores['description'] = 'The field description is required';
            } else if (strlen($description) > 50) {
                $errores['description'] = 'Description too long';
            }

            # Date Worked
            if (empty($date_worked)) {
                $errores['date_worked'] = 'The field date_worked is required';
            } else if (strlen($date_worked) > 20) {
                $errores['date_worked'] = 'Date worked too long';
            }

            #4. Verify Validation

            if (!empty($errores)) {
                # Validation's error
                $_SESSION['workingHours'] = serialize($workingHours);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;
                header('location:' . URL . 'workingHours/new');
            } else {
                # Suma de horas totales desde la tabla employees + nuevas horas trabajadas
                $this->model->sumTHoursWHour($duration, $_SESSION['employee_id']);
                #Create workingHours
                # Añadir registro a la tabla
                $this->model->create($workingHours);
                #Mensaje
                $_SESSION['mensaje'] = "Working Hour create correctly";
                # Redirigimos al main de workingHours
                header('location:' . URL . 'workingHours');
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
    # Allow to delete the working hour
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {

            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "workingHours");

        } else {

            $id_workingHour = $param[0];

            $id = $_SESSION['employee_id'];

            # We take the working hour by employee id
            $duration = $this->model->getWHours($id_workingHour);

            # We delete the working hour from the total hours in the table employee
            $this->model->subtractTH($duration, $id);

            # We delete the working hour
            $this->model->delete($id_workingHour);

            $_SESSION['mensaje'] = 'Working hour delete correctly';

            header("Location:" . URL . "workingHours/");
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
    # Show a form to edit a workingHours
    public function edit($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee'])) && (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Operation without privileges";

            header('location:' . URL . 'workingHours');

        } else {

            # obtengo el id del workingHours que voy a editar
            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Form edit Working Hour";
            $this->view->workingHours = $this->model->read($id);

            $this->view->employees = $this->model->getEmployeeDetails($this->view->id)->fetch(PDO::FETCH_OBJ);

            $this->view->time_codes = $this->model->get_times_codes($this->view->id);

            $this->view->projects = $this->model->get_projects($this->view->id);

            $this->view->tasks = $this->model->get_tasks($this->view->id);

            # Comprobamos si hay errores -> esta variable se crea al lanzar un error de validacion
            if (isset($_SESSION['error'])) {
                # rescatemos el mensaje
                $this->view->error = $_SESSION['error'];

                # Autorellenamos el formulario
                $this->view->workingHours = unserialize($_SESSION['employee']);

                # Recupero array de errores específicos
                $this->view->errores = $_SESSION['errores'];

                # debemos liberar las variables de sesión ya que su cometido ha sido resuelto
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['employee']);
                # Si estas variables existen cuando no hay errores, entraremos en los bloques de error en las condicionales
            }

            $this->view->render("workingHours/edit/index");
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

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {

            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "workingHours");

        } else {

            #1.Security. 
            $id_time_code = filter_var($_POST['id_time_code'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $id_project = filter_var($_POST['id_project'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $id_task = filter_var($_POST['id_task'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $description = filter_var($_POST['description'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $duration = filter_var($_POST['duration'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            $date_worked = filter_var($_POST['date_worked'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            $workingHours = new classWorkingHours(
                null,
                null,
                $id_time_code,
                $id_project,
                $id_task,
                $description,
                $duration,
                $date_worked,
                null,
                null
            );

            $id = $param[0];

            #Take the object workinHours original
            $workingHours_orig = $this->model->read($id);

            # 3. Validation
            # Only if is necessary
            # Only in case when the field is modified 

            $errores = [];

            # id_time_code
            if (strcmp($workingHours->id_time_code, $workingHours_orig->id_time_code) !== 0) {
                if (empty($id_time_code)) {
                    $errores['id_time_code'] = 'The field id_time_code is required';
                } else if (strlen($id_time_code) > 10) {
                    $errores['id_time_code'] = 'The field id_time_code is too long';

                }
            }

            # id_project
            if (strcmp($workingHours->id_project, $workingHours_orig->id_project) !== 0) {

                if (empty($id_project)) {
                    $errores['id_project'] = 'The field id_project is required ';
                } else if (strlen($id_project) > 10) {
                    $errores['id_project'] = 'The field id_project is too long';
                }
            }

            # id_task
            if (strcmp($workingHours->id_task, $workingHours_orig->id_task) !== 0) {

                if (empty($id_task)) {
                    $errores['id_task'] = 'The field id_task is required';
                } else if (strlen($id_task) > 10) {
                    $errores['id_task'] = 'The field id_task is too long';
                }
            }

            # decription
            if (strcmp($workingHours->description, $workingHours_orig->description) !== 0) {

                if (empty($description)) {
                    $errores['email'] = 'The field description is required';
                } else if (strlen($description) > 50) {
                    $errores['description'] = 'The field description is too long';
                }
            }

            # duration
            if (strcmp($workingHours->duration, $workingHours_orig->duration) !== 0) {

                if (empty($duration)) {
                    $errores['duration'] = 'The field duration is required';
                } else if (strlen($duration) > 50) {
                    $errores['duration'] = 'The field duration is too long';
                }
            }

            # date_worked
            if (strcmp($workingHours->date_worked, $workingHours_orig->date_worked) !== 0) {

                if (empty($date_worked)) {
                    $errores['date_worked'] = 'The field date_worked is required';
                } else if (strlen($date_worked) > 50) {
                    $errores['date_worked'] = 'The field date_worked is too long';
                }
            }

            #4. Validation check

            if (!empty($errores)) {

                # Validation's error
                $_SESSION['workingHours'] = serialize($workingHours);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                # Redirect to workingHour's main
                header('location:' . URL . 'workingHours/edit/' . $id);

            } else {

                $employee_id = $_SESSION['employee_id'];

                $email = $this->model->get_employee_email($employee_id);

                # Obtengo las horas totales del empleado
                $employee_total_hours_result = $this->model->get_employeeHours($email);

                # Extraer el valor numérico real de la consulta
                $employee_total_hours_row = $employee_total_hours_result->fetch(PDO::FETCH_ASSOC);
                $employee_total_hours = $employee_total_hours_row['total_hours'];

                # Calcula la diferencia entre la duración original y la nueva duración
                $difference = $workingHours->duration - $workingHours_orig->duration;

                # Si la nueva duración es mayor que la original, suma la diferencia a las horas totales del empleado
                # Si la nueva duración es menor que la original, resta la diferencia de las horas totales del empleado
                $new_employee_total_hours = $employee_total_hours + $difference;

                # Actualizamos las horas totales
                $this->model->sumTHoursWHour($new_employee_total_hours, $employee_id);

                # Adding the data to the table working hours
                $this->model->update($workingHours, $id);

                # Message
                $_SESSION['mensaje'] = "Working hour update correctly";

                # Redirect to Working Hours main
                header('location:' . URL . 'workingHours');
            }
        }
    }


    // Not necessary now --------------------------- 
    # Método mostrar
    # Show all the data from the table working hours
    // ---------------------------------------------
    public function mostrar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee'])) && (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "workingHours");
        } else {
            $id = $param[0];
            $this->view->title = "Form workingHours Show";
            $this->view->workingHours = $this->model->getworkingHours($id);
            $this->view->render("workingHours/mostrar/index");
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
    # Allow order the table working Hours
    public function order($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee'])) && (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "workingHours");
        } else {
            $criterio = $param[0];

            $this->view->title = "Working Hours";

            if ((in_array($_SESSION['id_rol'], $GLOBALS['employee']))) {

                $this->view->workingHours = $this->model->orderEmp($criterio, $_SESSION['employee_id']);
                $this->view->total_hours = $this->model->getTotalHours();


            } else if ((in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {

                $this->view->workingHours = $this->model->order($criterio);

            }

            $this->view->render("workingHours/main/index");
        }

    }

    # ---------------------------------------------------------------------------------
    #
    #    _____  ______            _____    _____  _    _ 
    #    / ____||  ____|    /\    |  __ \  / ____|| |  | |
    #   | (___  | |__      /  \   | |__) || |     | |__| |
    #    \___ \ |  __|    / /\ \  |  _  / | |     |  __  |
    #    ____) || |____  / ____ \ | | \ \ | |____ | |  | |
    #   |_____/ |______|/_/    \_\|_|  \_\ \_____||_|  |_|
    #
    # ---------------------------------------------------------------------------------
    # Método buscar
    # Permite buscar los registros de workingHours que cumplan con el patrón especificado en la expresión
    # de búsqueda
    public function search($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "workingHours");
        } else {
            $expresion = $_GET["expresion"];
            $this->view->title = "Working Hours";

            if ((in_array($_SESSION['id_rol'], $GLOBALS['employee']))) {

                $this->view->workingHours = $this->model->filterEmp($_SESSION['employee_id'], $expresion);
                $this->view->total_hours = $this->model->getTotalHours();


            } else if ((in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {

                $this->view->workingHours = $this->model->filter($expresion);

            }

            $this->view->render("workingHours/main/index");
        }
    }

    # ---------------------------------------------------------------------------------
    #
    #   ______ __   __ _____    ____   _____  _______ 
    #  |  ____|\ \ / /|  __ \  / __ \ |  __ \|__   __|
    #  | |__    \ V / | |__) || |  | || |__) |  | |   
    #  |  __|    > <  |  ___/ | |  | ||  _  /   | |   
    #  | |____  / . \ | |     | |__| || | \ \   | |   
    #  |______|/_/ \_\|_|      \____/ |_|  \_\  |_|   
    #
    # ---------------------------------------------------------------------------------
    # Method export
    # Allow to export the data to a csv
    public function export($param = [])
    {
        # Validar la sesión del usuario
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";
            header("location:" . URL . "login");
            exit();  # Terminar la ejecución para evitar procesar la exportación sin autenticación
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "workingHours");
            exit();  # Terminar la ejecución para evitar procesar la exportación sin privilegios
        }

        # Nombre del archivo CSV exportado
        $csvExportado = 'exportWH.csv';

        # Establecer las cabeceras para la descarga del archivo
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csvExportado . '"');

        # Abrir el puntero al archivo de salida
        $archivo = fopen('php:#output', 'w');

        # Escribir la primera fila con los encabezados
        fputcsv($archivo, ['Identification', 'Nombre Empleado', 'Código de Tiempo', 'Proyecto', 'Tarea', 'Fecha Trabajada', 'Duración'], ';');

        # Take all the working hours for the admin privileges, manager and organiser
        if ((in_array($_SESSION['id_rol'], $GLOBALS['employee']))) {

            # Obtener el correo electrónico del empleado actual
            $employee_email = $_SESSION['email'];

            # Obtener las horas trabajadas del empleado actual
            $workingHoursEmployee = $this->model->get_employeeHoursExport($employee_email)->fetchAll(PDO::FETCH_ASSOC);

            # Iterar sobre las horas trabajadas del empleado y escribir cada fila en el archivo
            foreach ($workingHoursEmployee as $workingHour) {
                fputcsv($archivo, [
                    $workingHour['identification'],
                    $workingHour['employee_name'],
                    $workingHour['time_code'],
                    $workingHour['project_name'],
                    $workingHour['task_description'],
                    $workingHour['date_worked'],
                    $workingHour['duration']
                ], ';');
            }

        } else if ((in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {

            $workingHours = $this->model->getExport()->fetchAll(PDO::FETCH_ASSOC);

            # Iterar sobre los workingHours y escribir cada fila en el archivo
            foreach ($workingHours as $workingHour) {
                # Escribir la fila en el archivo
                fputcsv($archivo, [
                    $workingHour['identification'],
                    $workingHour['employee_name'],
                    $workingHour['time_code'],
                    $workingHour['project_name'],
                    $workingHour['task_description'],
                    $workingHour['date_worked'],
                    $workingHour['duration']
                ], ';');
            }
        }

        # Cerramos el archivo
        fclose($archivo);

        # Enviar el contenido del archivo al navegador
        readfile('php:#output');
    }
}
