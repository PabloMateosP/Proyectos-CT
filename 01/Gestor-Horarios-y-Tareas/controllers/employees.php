<?php

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Employees extends Controller
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
    # "Render" Method. That show all the employees
    public function render($param = [])
    {
        # Began or continuo session
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Unauthenticated User";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Unauthenticated User";
            header("location:" . URL . "index");

        } else {

            # Check if message exists
            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }

            $this->view->title = "Employees Table";
            $this->view->employees = $this->model->get();
            $this->view->render("employees/main/index");
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
    # "New" Method. Show a formulary to add new employees
    public function new($param = [])
    {
        # Continue session
        session_start();

        # Authenticated user?
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "User must authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {

            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "employees");

        } else {

            # Create and instance of classEmployee
            $this->view->employee = new classEmployee();

            # Check if there are errors -> this variable is created when a validation error occurs
            if (isset($_SESSION['error'])) {
                # Let's retrieve the message
                $this->view->error = $_SESSION['error'];

                # Autopopulate the form
                $this->view->employee = unserialize($_SESSION['employee']);

                # Retrieve array of specific errors
                $this->view->errores = $_SESSION['errores'];

                # We must unset the session variables as their purpose has been resolved
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['employees']);

                # If these variables exist when there are no errors, we will enter the error blocks in the conditionals
            }

            $this->view->title = "Form new employee";

            $this->view->projects = $this->model->getProjects();

            $this->view->render("employees/new/index");
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
    # Allows adding a new employee based on the form details.
    public function create($param = [])
    {
        # Start Session
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['message'] = "User must authenticate";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])) {

            $_SESSION['message'] = "Operation without privileges";
            header("location:" . URL . "employees");

        } else {

            # --
            # 1. Security. Sanitize form data
            # --

            $identification = filter_var($_POST['identification'] ??= '', FILTER_SANITIZE_STRING);
            $last_name = filter_var($_POST['last_name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $phone = filter_var($_POST['phone'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $city = filter_var($_POST['city'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $dni = filter_var($_POST['dni'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);
            $total_hours = filter_var($_POST['total_hours'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            # --
            # 2. Create employee with sanitized data
            # --

            $employee = new classEmployee(
                null,
                $identification,
                $last_name,
                $name,
                $phone,
                $city,
                $dni,
                $email,
                $total_hours,
                null,
                null
            );

            # --
            # 3. Validation
            # --

            $errores = array();

            # Identification
            if (empty($identification)) {
                $errores['identification'] = "Identification is required";
            } else if (strlen($identification) > 8) {
                $errores['identification'] = "Identification too long";
            }

            # name: max 20 characters
            if (empty($name)) {
                $errores['name'] = 'The name field is required';
            } else if (strlen($name) > 20) {
                $errores['name'] = 'The name field is too long';
            }

            # last_name: max 45 characters
            if (empty($last_name)) {
                $errores['last_name'] = 'The Last Name field is required';
            } else if (strlen($last_name) > 45) {
                $errores['last_name'] = 'The Last Name field is too long';
            }

            # City: max 20 characters
            if (empty($city)) {
                $errores['city'] = 'The city field is required';
            } else if (strlen($city) > 20) {
                $errores['city'] = 'The city field is too long';
            }

            # Email: must be validated and unique
            if (empty($email)) {
                $errores['email'] = 'The email field is required';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = 'The format entered is incorrect';
            } else if (!$this->model->validateUniqueEmail($email)) {
                $errores['email'] = 'The email is already registered';
            }

            # phone: max 9 characters
            if (empty($phone)) {
                $errores['phone'] = 'The phone field is required';
            } else if (strlen($phone) > 9) {
                $errores['phone'] = 'The phone field is too long';
            } else if (!$this->model->validateUniquePhone($phone)) {
                $errores['phone'] = 'The phone is already registered';
            }

            # Dni: must be validated and unique
            $options = [
                'options' => [
                    'regexp' => '/^(\d{8})([A-Z])$/'
                ]
            ];

            if (empty($dni)) {
                $errores['dni'] = 'The dni field is required';
            } else if (!filter_var($dni, FILTER_VALIDATE_REGEXP, $options)) {
                $errores['dni'] = 'Wrong format entered';
            } else if (!$this->model->validateUniqueDni($dni)) {
                $errores['dni'] = 'DNI already registered';

            }

            # --
            # 4. Check Validation
            # --

            if (!empty($errores)) {

                # Validation errors 
                $_SESSION['employee'] = serialize($employee);
                $_SESSION['error'] = 'Invalid form';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'employees/new');

            } else {

                # Create employee
                # Add employee
                $employee_id = $this->model->create($employee);

                if (isset($_POST['projects'])) {
                    $projects = $_POST['projects'];
                    foreach ($projects as $project_id) {
                        $this->model->insertProjectEmployeeRelationship($employee_id, $project_id);
                    }
                }

                # Message
                $_SESSION['message'] = "Employee created correctly";

                # Redirect
                header('location:' . URL . 'employees');

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
    # Allow the elimination of an employee
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "employees");
        } else {
            $id = $param[0];

            $this->model->deleteWH($id);
            $this->model->deleteRelation($id);
            $this->model->delete($id);

            $_SESSION['mensaje'] = 'Employee delete correctly';

            header("Location:" . URL . "employees");
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
    # Show a form to edit an employee
    public function edit($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must authenticated";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])) {
            $_SESSION['mensaje'] = "unprivileged operation";

            header('location:' . URL . 'employees');

        } else {

            # Taking the id of the employee that we are going to change
            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Form edit employee";

            $this->view->projectEmployees = $this->model->getProjectEmployees($id);

            # We recover the data of all the projects 
            $this->view->projects = $this->model->getProjects();

            $this->view->employee = $this->model->read($id);

            # We check for errors -> this variable is created when throwing a validation error

            if (isset($_SESSION['error'])) {

                # we rescue the message
                $this->view->error = $_SESSION['error'];

                # Autofill the form
                $this->view->employee = unserialize($_SESSION['employee']);

                # Recover the errors array
                $this->view->errores = $_SESSION['errores'];

                # We must liberate the variables
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['employees']);

            }

            $this->view->render("employees/edit/index");

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
    # Method update.
    # Update the data of an employee
    public function update($param = [])
    {
        // Start or continue session
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "employees");
        } else {

            // 1. Security. Sanitize the data

            $identification = filter_var($_POST['identification'] ??= '', FILTER_SANITIZE_STRING);
            $last_name = filter_var($_POST['last_name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $phone = filter_var($_POST['phone'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $city = filter_var($_POST['city'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $dni = filter_var($_POST['dni'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);
            $total_hours = filter_var($_POST['total_hours'] ??= '', FILTER_SANITIZE_NUMBER_INT);

            $employee = new classemployee(
                null,
                $identification,
                $last_name,
                $name,
                $phone,
                $city,
                $dni,
                $email,
                $total_hours,
                null,
                null
            );

            $id = $param[0];

            // We take the original employee
            $employee_orig = $this->model->read($id);

            // 3. Validation
            // Only if necessary 
            // Only in case of modifying some field

            $errors = [];

            // Identification 
            if (strcmp($employee->identification, $employee_orig->identification) !== 0) {
                if (empty($identification)) {
                    $errors[] = "Identification is required";
                } else if (strlen($identification) > 8) {
                    $errors[] = "Identification too long";
                }
            }

            // Last name
            if (strcmp($employee->last_name, $employee_orig->last_name) !== 0) {
                if (empty($last_name)) {
                    $errors['last_name'] = 'The field last_name is required';
                } else if (strlen($last_name) > 45) {
                    $errors['last_name'] = 'The field last_name is too long';
                }
            }

            // Name
            if (strcmp($employee->name, $employee_orig->name) !== 0) {
                if (empty($name)) {
                    $errors['name'] = 'The field name is required';
                } else if (strlen($name) > 20) {
                    $errors['name'] = 'The field name is too long';
                }
            }

            // Phone: we validate 9 numbers phone and unique 
            if (strcmp($employee->phone, $employee_orig->phone) !== 0) {
                $options_tlf = [
                    'options' => [
                        'regexp' => '/^\d{9}$/'
                    ]
                ];

                if (!filter_var($employee->phone, FILTER_VALIDATE_REGEXP, $options_tlf)) {
                    $errors['phone'] = 'The format entered is incorrect';
                }
            }

            // City
            if (strcmp($employee->city, $employee_orig->city) !== 0) {
                if (empty($city)) {
                    $errors['city'] = 'The field city is required';
                } else if (strlen($city) > 20) {
                    $errors['city'] = 'The field city is too long';
                }
            }

            // Email: we validate and unique email 
            if (strcmp($employee->email, $employee_orig->email) !== 0) {
                if (empty($email)) {
                    $errors['email'] = 'The field email is required';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'The format entered is incorrect';
                } else if (!$this->model->validateUniqueEmail($email)) {
                    $errors['email'] = 'Email already registered';
                }
            }

            // DNI: we validate a correct DNI and unique 
            if (strcmp($employee->dni, $employee_orig->dni) !== 0) {
                $options = [
                    'options' => [
                        'regexp' => '/^(\d{8})([A-Z])$/'
                    ]
                ];

                if (empty($dni)) {
                    $errors['dni'] = 'The field dni is required';
                } else if (!filter_var($dni, FILTER_VALIDATE_REGEXP, $options)) {
                    $errors['dni'] = 'The format entered is incorrect';
                } else if (!$this->model->validateUniqueDni($dni)) {
                    $errors['dni'] = 'DNI already registered';
                }
            }

            // 4. Checking Validation

            if (!empty($errors)) {

                // Validation errors
                $_SESSION['employee'] = serialize($employee);
                $_SESSION['error'] = 'Form not validated';
                $_SESSION['errors'] = $errors;

                // Redirect to the edit page 
                header('location:' . URL . 'employees/edit/' . $id);

            } else {

                // Check if $_POST['projects'] is defined and not null
                if (isset($_POST['projects'])) {
                    // If it has any value, assign it to $formProjects
                    $formProjects = $_POST['projects'];
                } else {
                    // If it has no value, assign an empty array to $formProjects
                    $formProjects = [];
                }

                // Collect the current projects of the employee
                $projectEmployeeRelated = $this->model->getProjectEmployees($id);

                // Projects to delete (those that were before but are not in the form)
                $projectsToDelete = array_diff($projectEmployeeRelated, $formProjects);

                // Projects to create (those that are in the form but were not before)
                $projectsToCreate = array_diff($formProjects, $projectEmployeeRelated);


                // Delete project relationships that are no longer in the form
                $tempProjectsToDelete = $projectsToDelete;
                foreach ($tempProjectsToDelete as $projectId) {
                    $this->model->deleteRelationEP($projectId, $id);
                }

                $tempProjectsToCreate = $projectsToCreate;
                // Create relationships for the projects of the form that were not previously related
                foreach ($tempProjectsToCreate as $projectId) {
                    $this->model->createRelationPR($id, $projectId);
                }

                // Update employee
                $this->model->update($employee, $id);

                // Message
                $_SESSION['mensaje'] = "Employee Correctly Updated";

                // Redirect to the main page 
                header('location:' . URL . 'employees');

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
    # Show a form to watch the information about an employee
    public function show($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "employees");
        } else {
            $id = $param[0];
            $this->view->title = "Form Employee Show";
            $this->view->projectEmployees = $this->model->getProjectEmployees($id);
            $this->view->projects = $this->model->getProjects();
            $this->view->employee = $this->model->read($id);
            $this->view->render("employees/show/index");
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
    # Allow order the table employee
    public function order($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "unprivileged operation";
            header("location:" . URL . "employees");
        } else {
            $criterio = $param[0];
            $this->view->title = "Employees Table";
            $this->view->employees = $this->model->order($criterio);
            $this->view->render("employees/main/index");
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
    # Search for employee records that match the pattern specified in the search expression
    public function search($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "unprivileged operation";
            header("location:" . URL . "employees");
        } else {
            $expresion = $_GET["expresion"];
            $this->view->title = "Employees Table";
            $this->view->employees = $this->model->filter($expresion);
            $this->view->render("employees/main/index");
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #  ________   _______   ____  _____ _______       _____  
    #  |  ____\ \ / /  __ \ / __ \|  __ \__   __|/\   |  __ \ 
    #  | |__   \ V /| |__) | |  | | |__) | | |  /  \  | |__) |
    #  |  __|   > < |  ___/| |  | |  _  /  | | / /\ \ |  _  / 
    #  | |____ / . \| |    | |__| | | \ \  | |/ ____ \| | \ \ 
    #  |______/_/ \_\_|     \____/|_|  \_\ |_/_/    \_\_|  \_\
    # 
    # ---------------------------------------------------------------------------------
    public function exportar($param = [])
    {
        # Validar la sesión del usuario
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must authenticated";
            header("location:" . URL . "login");
            exit();  # Terminar la ejecución para evitar procesar la exportación sin autenticación
        }

        # Obtener datos de employees
        $employees = $this->model->get()->fetchAll(PDO::FETCH_ASSOC);

        # name del archivo CSV
        $csvExportado = 'export_employees.csv';

        # Establecer las cabeceras para la descarga del archivo
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csvExportado . '"');

        # Abrir el puntero al archivo de salida
        $archivo = fopen('php:#output', 'w');

        # Escribir la primera fila con los encabezados
        fputcsv($archivo, ['Identificación', 'Apellidos', 'Nombre', 'Telefono', 'Ciudad', 'Dni', 'Email', 'Horas Totales'], ';');

        # Iterar sobre los employees y escribir cada fila en el archivo
        foreach ($employees as $employee) {
            # Separar el campo "employee" en "last_name" y "name"
            list($last_name, $name) = explode(', ', $employee['employee']);

            # Construir el array del employee con los datos necesarios
            $employeeData = [
                'identification' => $employee['identification'],
                'last_name' => $last_name,
                'name' => $name,
                'phone' => $employee['phone'],
                'city' => $employee['city'],
                'dni' => $employee['dni'],
                'email' => $employee['email'],
                'total_hours' => $employee['total_hours']
            ];

            # Escribir la fila en el archivo
            fputcsv($archivo, $employeeData, ';');
        }

        # Cerramos el archivo
        fclose($archivo);

        # Enviar el contenido del archivo al navegador
        readfile('php:#output');
    }


    # ---------------------------------------------------------------------------------  
    #  
    #  _____ __  __ _____   ____  _____ _______       _____  _____       _______ ____   _____ 
    #  |_   _|  \/  |  __ \ / __ \|  __ \__   __|/\   |  __ \|  __ \   /\|__   __/ __ \ / ____|
    #    | | | \  / | |__) | |  | | |__) | | |  /  \  | |__) | |  | | /  \  | | | |  | | (___  
    #    | | | |\/| |  ___/| |  | |  _  /  | | / /\ \ |  _  /| |  | |/ /\ \ | | | |  | |\___ \ 
    #   _| |_| |  | | |    | |__| | | \ \  | |/ ____ \| | \ \| |__| / ____ \| | | |__| |____) |
    #  |_____|_|  |_|_|     \____/|_|  \_\ |_/_/    \_\_|  \_\_____/_/    \_\_|  \____/|_____/ 
    # 
    # ---------------------------------------------------------------------------------
    function importarDatos()
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";
            header("location:" . URL . "login");
            exit();
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "employees");
            exit();
        }

        # Validate if it is an excel file
        $allowed_extensions = array('xlsx');
        $file_extension = pathinfo($_FILES['archivos']['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $_SESSION['mensaje'] = "Solo se permiten archivos Excel en formato xlsx.";
            header("location:" . URL . "employees");
            exit();
        }

        # Charge the file 
        $spreadsheet = IOFactory::load($_FILES['archivos']['tmp_name']);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];

        $isFirstRow = true;
        $row_count = 0; // Variable to count processed rows

        foreach ($worksheet->getRowIterator() as $row) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;  # Skip the first row
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            $cells = [];
            foreach ($cellIterator as $cell) {
                $sanitized_value = htmlspecialchars($cell->getValue(), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $cells[] = $sanitized_value;
            }
            $rows[] = $cells;

            $row_count++;
            if ($row_count >= 100) {
                break; // Exit the loop after processing 100 rows
            }
        }

        # Sanitize the data and handle unique constraints
        $dni_counter = 0; // Counter for assigning unique numbers to empty dni values
        $email_counter = 0; // Counter for assigning unique numbers to empty email values

        foreach ($rows as $fila) {
            $employee = new classEmployee();
            $employee->identification = isset($fila[0]) ? filter_var($fila[0], FILTER_SANITIZE_STRING) : null;

            // Stop inserting if identification is null or empty
            if (empty($employee->identification)) {
                break;
            }

            $employee->name = isset($fila[1]) ? filter_var($fila[1], FILTER_SANITIZE_STRING) : null;
            $employee->last_name = isset($fila[2]) ? filter_var($fila[2], FILTER_SANITIZE_STRING) : null;
            $employee->phone = isset($fila[3]) ? filter_var($fila[3], FILTER_SANITIZE_NUMBER_INT) : null;
            $employee->city = isset($fila[4]) ? filter_var($fila[4], FILTER_SANITIZE_STRING) : null;

            // Handle dni field
            if (isset($fila[5]) && !empty($fila[5])) {
                $employee->dni = filter_var($fila[5], FILTER_SANITIZE_STRING);
            } else {
                $employee->dni = ++$dni_counter; // Assign unique number for empty dni
            }

            // Handle email field
            if (isset($fila[6]) && !empty($fila[6])) {
                $employee->email = filter_var($fila[6], FILTER_SANITIZE_EMAIL);
            } else {
                $employee->email = "empty_email_" . ++$email_counter . "@example.com"; // Assign unique email for empty email
            }

            $employee->total_hours = isset($fila[7]) ? filter_var($fila[7], FILTER_SANITIZE_NUMBER_INT) : null;

            $this->model->create($employee);
        }

        $_SESSION['mensaje'] = "Data imported successfully";
        header("location:" . URL . "employees");
        exit();
    }


    # ---------------------------------------------------------------------------------
    #
    #   __          ______  _____  _  _______ _   _  _____    _    _  ____  _    _ _____  
    #   \ \        / / __ \|  __ \| |/ /_   _| \ | |/ ____|  | |  | |/ __ \| |  | |  __ \ 
    #    \ \  /\  / / |  | | |__) | ' /  | | |  \| | |  __   | |__| | |  | | |  | | |__) |
    #     \ \/  \/ /| |  | |  _  /|  <   | | | . ` | | |_ |  |  __  | |  | | |  | |  _  / 
    #      \  /\  / | |__| | | \ \| . \ _| |_| |\  | |__| |  | |  | | |__| | |__| | | \ \ 
    #       \/  \/   \____/|_|  \_\_|\_\_____|_| \_|\_____|  |_|  |_|\____/ \____/|_|  \_\
    #
    # ---------------------------------------------------------------------------------
    public function workingHours($param = [])
    {
        # Start or continue the session
        session_start();
        if (!isset($_SESSION['id'])) {

            $_SESSION['notify'] = "Unauthenticated user";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {

            $_SESSION['mensaje'] = "Unauthenticated user";
            header("location:" . URL . "index");

        } else {

            # Probing if exist some message
            if (isset($_SESSION['mensaje'])) {

                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);

            }

            $id = $param[0];

            $_SESSION['employee_id_export'] = $id;

            $this->view->title = "Working Hour Employee";

            if ($this->model->getTotalHours($id) == null) {

                $this->view->total_hours = 0;

            } else {

                $this->view->total_hours = $this->model->getTotalHours($id);

            }

            $this->view->workingHours = $this->model->getWHEmp($id);

            $this->view->render("employees/workingHours/index");

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
    public function exportWH($param = [])
    {
        # Validar la sesión del usuario
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";
            header("location:" . URL . "login");
            exit();
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "employees");
            exit();
        }

        # Nombre del archivo CSV exportado
        $csvExportado = 'exportWHEmployee.csv';

        # Establecer las cabeceras para la descarga del archivo
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csvExportado . '"');

        # Abrir el puntero al archivo de salida
        $archivo = fopen('php:#output', 'w');

        # Escribir la primera fila con los encabezados
        fputcsv($archivo, ['Identification', 'Nombre Empleado', 'Código de Tiempo', 'Proyecto', 'Tarea', 'Fecha Trabajada', 'Duración'], ';');

        # ------------------------------------------------------------
        # Obtener el id del empleado al que vamos a exportar las horas
        $id = $_SESSION['employee_id_export'];
        # ------------------------------------------------------------

        # Obtener las horas trabajadas del empleado actual
        $workingHoursEmployee = $this->model->get_employeeHoursExport($id)->fetchAll(PDO::FETCH_ASSOC);

        $totalHoursEmployee = $this->model->getTotalHours($id);

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

        # Agregar una fila para mostrar el total de horas
        fputcsv($archivo, ['Total Hours', $totalHoursEmployee], ';');

        # Cerramos el archivo
        fclose($archivo);

        # Enviar el contenido del archivo al navegador
        readfile('php:#output');
    }
}