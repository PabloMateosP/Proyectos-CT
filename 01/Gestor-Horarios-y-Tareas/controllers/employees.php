<?php

class Employees extends Controller
{

    # "Render" Method. That show all the employees
    public function render($param = [])
    {
        # Began or continuo session
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Unauthenticated User";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['employees']['main']))) {
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

    # "New" Method. Show a formulary to add new employees
    public function new($param = [])
    {
        # Continue session
        session_start();

        # Authenticated user?
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "User must authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['employees']['new']))) {
            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "employees");
        } else {

            # Create and instance of classEmployee
            $this->view->employee = new classEmployee();

            // Check if there are errors -> this variable is created when a validation error occurs
            if (isset($_SESSION['error'])) {
                // Let's retrieve the message
                $this->view->error = $_SESSION['error'];

                // Autopopulate the form
                $this->view->employee = unserialize($_SESSION['employee']);

                // Retrieve array of specific errors
                $this->view->errors = $_SESSION['errors'];

                // We must unset the session variables as their purpose has been resolved
                unset($_SESSION['error']);
                unset($_SESSION['errors']);
                unset($_SESSION['employees']);
                // If these variables exist when there are no errors, we will enter the error blocks in the conditionals
            }

            $this->view->title = "Form new employee";
            $this->view->render("employees/new/index");
        }
    }

    # Method create.
    # Allows adding a new employee based on the form details.
    public function create($param = [])
    {
        # Start Session
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['message'] = "User must authenticate";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['employees']['new'])) {

            $_SESSION['message'] = "Operation without privileges";
            header("location:" . URL . "employees");

        } else {

            # --
            #1. Security. Sanitize form data
            # --

            $last_name = filter_var($_POST['last_name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $phone = filter_var($_POST['phone'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $city = filter_var($_POST['city'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $dni = filter_var($_POST['dni'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);
            $total_hours = filter_var($_POST['total_hours'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            # --
            #2. Create employee with sanitized data
            # --

            $employee = new classEmployee(
                null,
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
            #3. Validation
            # --

            $errors = [];

            // last_name: max 45 characters
            if (empty($last_name)) {
                $errors['last_name'] = 'The Last Name field is required';
            } else if (strlen($last_name) > 45) {
                $errors['last_name'] = 'The Last Name field is too long';

            }

            // name: max 20 characters
            if (empty($name)) {
                $errors['name'] = 'The name field is required';
            } else if (strlen($name) > 20) {
                $errors['name'] = 'The name field is too long';
            }

            // phone: max 9 characters
            if (empty($phone)) {
                $errors['phone'] = 'The phone field is required';
            } else if (strlen($phone) > 9) {
                $errors['phone'] = 'The phone field is too long';
            }

            // City: max 20 characters
            if (empty($city)) {
                $errors['city'] = 'The city field is required';
            } else if (strlen($city) > 20) {
                $errors['city'] = 'The city field is too long';

            }

            // Email: must be validated and unique
            if (empty($email)) {
                $errors['email'] = 'The email field is required';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'The format entered is incorrect';
            } else if (!$this->model->validateUniqueEmail($email)) {
                $errors['email'] = 'The email is already registered';

            }

            // Dni: must be validated and unique
            $options = [
                'options' => [
                    'regexp' => '/^(\d{8})([A-Z])$/'
                ]
            ];

            if (empty($dni)) {
                $errors['dni'] = 'The dni field is required';
            } else if (!filter_var($dni, FILTER_VALIDATE_REGEXP, $options)) {
                $errors['dni'] = 'Wrong format entered';
            } else if (!$this->model->validateUniqueDni($dni)) {
                $errors['dni'] = 'DNI already registered';

            }

            # --
            # 4. Check Validation
            # --

            if (!empty($errors)) {

                // Validation errors 
                $_SESSION['employee'] = serialize($employee);
                $_SESSION['error'] = 'Invalid form';
                $_SESSION['errors'] = $errors;

                header('location:' . URL . 'employees/new');

            } else {

                // Create employee

                # Add employee
                $this->model->create($employee);

                # Message
                $_SESSION['message'] = "Employee created correctly";

                # Redirect
                header('location:' . URL . 'employees');

            }
        }
    }


    # Método delete. 
    # Permite la eliminación de un employee
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['employees']['delete']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "employees");
        } else {
            $id = $param[0];
            $this->model->delete($id);
            $_SESSION['mensaje'] = 'Alumno eliminado correctamente';

            header("Location:" . URL . "employees");
        }
    }

    # Método editar. 
    # Muestra un formulario que permita editar los detalles de un employee
    public function editar($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['employees']['edit'])) {
            $_SESSION['mensaje'] = "Operación sin privilegios";

            header('location:' . URL . 'employees');

        } else {

            # obtengo el id del employee que voy a editar

            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Formulario  editar employee";
            $this->view->employee = $this->model->read($id);

            $this->view->employee = $this->model->getemployee($this->view->id);

            # Creamos un objeto vacio
            // $this->view->employee = new classemployee();

            # Comprobamos si hay errores -> esta variable se crea al lanzar un error de validacion
            if (isset($_SESSION['error'])) {
                // rescatemos el mensaje
                $this->view->error = $_SESSION['error'];

                // Autorellenamos el formulario
                $this->view->employee = unserialize($_SESSION['employee']);

                // Recupero array de errores específicos
                $this->view->errores = $_SESSION['errores'];

                // debemos liberar las variables de sesión ya que su cometido ha sido resuelto
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['employees']);
                // Si estas variables existen cuando no hay errores, entraremos en los bloques de error en las condicionales
            }

            $this->view->render("employees/editar/index");
        }
    }
    # Método update.
    # Actualiza los detalles de un employee a partir de los datos del formulario de edición
    public function update($param = [])
    {

        #Iniciar Sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['employees']['edit']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "employees");
        } else {

            #1.Seguridad. Saneamos los datos del formulario
            $last_name = filter_var($_POST['last_name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $telefono = filter_var($_POST['telefono'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $ciudad = filter_var($_POST['ciudad'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $dni = filter_var($_POST['dni'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);

            $employee = new classemployee(
                null,
                $last_name,
                $name,
                $telefono,
                $ciudad,
                $dni,
                $email,
                null,
                null
            );
            $id = $param[0];

            #Obtengo el objeto employee original
            $employee_orig = $this->model->read($id);

            #3. Validación
            //Sólo si es necesario
            //Sólo en caso de modificación del campo

            $errores = [];

            //last_name: obligatorio, maximo 45 caracteres
            if (strcmp($employee->last_name, $employee_orig->last_name) !== 0) {
                if (empty($last_name)) {
                    $errores['last_name'] = 'El campo last_name es obligatorio';
                } else if (strlen($last_name) > 45) {
                    $errores['last_name'] = 'El campo last_name es demasiado largo';

                }
            }


            //name: obligatorio, maximo 20 caracteres
            if (strcmp($employee->name, $employee_orig->name) !== 0) {

                if (empty($name)) {
                    $errores['name'] = 'El campo name es obligatorio';
                } else if (strlen($name) > 20) {
                    $errores['name'] = 'El campo name es demasiado largo';

                }
            }



            //Teléfono: no obligatorio, 9 caracteres numéricos
            // if (strcmp($employee->telefono, $employee_orig->telefono) !== 0) {
            // $options_tlf=[
            //     'options_tlf'=> [
            //         'regexp' => '/^\d{9}$/'
            //     ]
            // ];
            // if(!filter_var($telefono, FILTER_VALIDATE_REGEXP, $options_tlf)){
            //     $errores['telefono'] = 'El formato introducido es incorrecto';
            // }
            // }

            //Ciudad: obligatorio, maximo 20 caracteres
            if (strcmp($employee->ciudad, $employee_orig->ciudad) !== 0) {

                if (empty($ciudad)) {
                    $errores['ciudad'] = 'El campo ciudad es obligatorio';
                } else if (strlen($ciudad) > 20) {
                    $errores['ciudad'] = 'El campo ciudad es demasiado largo';

                }
            }
            //Email: obligatorio, formato válido y clave secundaria
            if (strcmp($employee->email, $employee_orig->email) !== 0) {

                if (empty($email)) {
                    $errores['email'] = 'El campo email es obligatorio';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errores['email'] = 'El formato introducido es incorrecto';
                } else if (!$this->model->validateUniqueEmail($email)) {
                    $errores['email'] = 'Email ya registrado';

                }
            }
            //Dni: obligatorio, formato válido y clave secundaria
            if (strcmp($employee->dni, $employee_orig->dni) !== 0) {
                $options = [
                    'options' => [
                        'regexp' => '/^(\d{8})([A-Z])$/'
                    ]
                ];


                if (empty($dni)) {
                    $errores['dni'] = 'El campo dni es obligatorio';
                } else if (!filter_var($dni, FILTER_VALIDATE_REGEXP, $options)) {
                    $errores['dni'] = 'El formato introducido es incorrecto';
                } else if (!$this->model->validateUniqueDni($dni)) {
                    $errores['dni'] = 'Dni ya registrado';

                }
            }

            #4. Comprobar validacion

            if (!empty($errores)) {
                //errores de validacion
                $_SESSION['employee'] = serialize($employee);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                # Redirigimos al main de employees
                header('location:' . URL . 'employees/editar/' . $id);
            } else {
                //crear alumno
                # Añadir registro a la tabla
                $this->model->update($employee, $id);

                #Mensaje
                $_SESSION['mensaje'] = "employee actualizado correctamente";

                # Redirigimos al main de alumnos
                header('location:' . URL . 'employees');
            }
        }
    }


    # Método mostrar
    # Muestra en un formulario de solo lectura los detalles de un employee
    public function mostrar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['employees']['show']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "employees");
        } else {
            $id = $param[0];
            $this->view->title = "Formulario employee Mostar";
            $this->view->employee = $this->model->getemployee($id);
            $this->view->render("employees/mostrar/index");
        }
    }

    # Método ordenar
    # Permite ordenar la tabla de employees por cualquiera de las columnas de la tabla
    public function ordenar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['employees']['order']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "employees");
        } else {
            $criterio = $param[0];
            $this->view->title = "Tabla employees";
            $this->view->employees = $this->model->order($criterio);
            $this->view->render("employees/main/index");
        }

    }

    # Método buscar
    # Permite buscar los registros de employees que cumplan con el patrón especificado en la expresión
    # de búsqueda
    public function buscar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['employees']['filter']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "employees");
        } else {
            $expresion = $_GET["expresion"];
            $this->view->title = "Tabla employees";
            $this->view->employees = $this->model->filter($expresion);
            $this->view->render("employees/main/index");
        }
    }

    public function exportar($param = [])
    {
        // Validar la sesión del usuario
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";
            header("location:" . URL . "login");
            exit();  // Terminar la ejecución para evitar procesar la exportación sin autenticación
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['employees']['export'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "employees");
            exit();  // Terminar la ejecución para evitar procesar la exportación sin privilegios
        }

        // Obtener datos de employees
        $employees = $this->model->get()->fetchAll(PDO::FETCH_ASSOC);

        // name del archivo CSV
        $csvExportado = 'export_employees.csv';

        // Establecer las cabeceras para la descarga del archivo
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csvExportado . '"');

        // Abrir el puntero al archivo de salida
        $archivo = fopen('php://output', 'w');

        // Escribir la primera fila con los encabezados
        fputcsv($archivo, ['last_name', 'name', 'telefono', 'ciudad', 'dni', 'email', 'create_at', 'update_at'], ';');

        // Iterar sobre los employees y escribir cada fila en el archivo
        foreach ($employees as $employee) {
            // Separar el campo "employee" en "last_name" y "name"
            list($last_name, $name) = explode(', ', $employee['employee']);

            // Construir el array del employee con los datos necesarios
            $employeeData = [
                'last_name' => $last_name,
                'name' => $name,
                'telefono' => $employee['telefono'],
                'ciudad' => $employee['ciudad'],
                'dni' => $employee['dni'],
                'email' => $employee['email'],
                'create_at' => date('Y-m-d H:i:s'),
                'update_at' => null
            ];

            // Escribir la fila en el archivo
            fputcsv($archivo, $employeeData, ';');
        }

        // Cerramos el archivo
        fclose($archivo);

        // Enviar el contenido del archivo al navegador
        readfile('php://output');
    }

    public function importar($param = [])
    {
        // Validar la sesión del usuario
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";
            header("location:" . URL . "login");
            exit();
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['employees']['import'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "employees");
            exit();
        }


        // Validar si se ha subido un archivo
        if (!isset($_FILES['archivos']) || $_FILES['archivos']['error'] != UPLOAD_ERR_OK) {
            $_SESSION['mensaje'] = "Error al subir el archivo CSV. ";
            header("location:" . URL . "employees");
            exit();
        }

        // Obtener el name del archivo temporal
        $archivo_temporal = $_FILES['archivos']['tmp_name'];

        // Abrir el archivo temporal
        $archivo = fopen($archivo_temporal, 'r');

        // Validar que se pudo abrir el archivo
        if (!$archivo) {
            $_SESSION['mensaje'] = "Error al abrir el archivo CSV.";
            header("location:" . URL . "employees");
            exit();
        }

        // Iterar sobre las filas del archivo CSV
        while (($fila = fgetcsv($archivo, 150, ';')) !== false) {
            // Crear un array asociativo con los datos de la fila
            $employee = new classemployee();

            $employee->name = $fila[1];
            $employee->last_name = $fila[0];
            $employee->email = $fila[5];
            $employee->telefono = $fila[2];
            $employee->ciudad = $fila[3];
            $employee->dni = $fila[4];

            $this->model->create($employee);

        }

        // Cerrar el archivo
        fclose($archivo);

        // Redirigir después de importar
        $_SESSION['mensaje'] = "Datos importados correctamente.";
        header("location:" . URL . "employees");
        exit();
    }

    function pdf($param = [])
    {
        // Validar la sesión del usuario
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";
            header("location:" . URL . "login");
            exit();
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['employees']['export'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
            exit();
        }

        $data = $this->model->get()->fetchAll(PDO::FETCH_ASSOC);

        $columnas = [
            ['header' => 'employee', 'field' => 'employee', 'width' => 60],
            ['header' => 'Telefono', 'field' => 'telefono', 'width' => 40],
            ['header' => 'Ciudad', 'field' => 'ciudad', 'width' => 30],
            ['header' => 'DNI', 'field' => 'dni', 'width' => 25],
            ['header' => 'Email', 'field' => 'email', 'width' => 40],
        ];

        $pdf = new PDFemployees();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->TituloInforme();
        $pdf->EncabezadoListado($columnas);
        $pdf->ContenidoListado($data, $columnas);
        $pdf->Output();
    }
}
