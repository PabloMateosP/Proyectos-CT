<?php

class WorkingHours extends Controller
{

    # Método principal. Muestra todos los workingHours
    public function render($param = [])
    {
        #inicio o continuo sesion
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Usuario sin autentificar";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['coordinador_empleado']))) {
            $_SESSION['mensaje'] = "Usuario sin autentificar";
            header("location:" . URL . "index");

        } else {
            #comprobar si existe mensaje
            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);

            }

            $this->view->title = "Working Hours";

            if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['coordinador'])) {
                $this->view->workingHours = $this->model->get();
            } elseif (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['empleado'])) {
                $email = $this->view->email_account = $this->model->get_userEmailById($_SESSION['id']);
                $this->view->workingHours = $this->model->get_employeeHours($email);
            } else {
                // You can't look that 
            }

            $this->view->render("workingHours/main/index");
        }
    }

    # "New" method. Form to add an new working Hours
    public function new($param = [])
    {
        # Continue session if exists
        session_start();

        # User authenticated?
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "User must authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['coordinador_empleado']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "workingHours");
        } else {

            # Creamos un objeto vacio
            $this->view->workingHours = new classWorkingHours();

            # Comprobamos si hay errores -> esta variable se crea al lanzar un error de validacion
            if (isset($_SESSION['error'])) {
                // rescatemos el mensaje
                $this->view->error = $_SESSION['error'];

                // Autorellenamos el formulario
                $this->view->workingHours = unserialize($_SESSION['workingHours']);

                // Recupero array de errores específicos
                $this->view->errores = $_SESSION['errores'];

                // debemos liberar las variables de sesión ya que su cometido ha sido resuelto
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['workingHours']);
                // Si estas variables existen cuando no hay errores, entraremos en los bloques de error en las condicionales
            }

            $this->view->title = "Formulario workingHours nuevo";
            $this->view->time_Codes = $this->model->get_times_codes();
            $this->view->work_Ordes = $this->model->get_work_ordes();
            $this->view->projects = $this->model->get_projects();
            $this->view->tasks = $this->model->get_tasks();
            $this->view->render("workingHours/new/index");
        }
    }
    # Método create. 
    # Permite añadir nuevo workingHours a partir de los detalles del formuario
    public function create($param = [])
    {
        #Iniciar Sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must authenticated";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['coordinador_empleado'])) {

            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "workingHours");

        } else {

            #1.Seguridad. Saneamos los datos del formulario
            $id_time_code = filter_var($_POST['id_time_code'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_work_order = filter_var($_POST['id_work_order'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_project = filter_var($_POST['id_project'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_task = filter_var($_POST['id_task'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $description = filter_var($_POST['description'] ??= '', FILTER_SANITIZE_EMAIL);
            $duration = filter_var($_POST['duration'] ??= '', FILTER_SANITIZE_EMAIL);
            $date_worked = filter_var($_POST['date_worked'] ??= '', FILTER_SANITIZE_EMAIL);

            #2. Creamos workingHours con los datos saneados
            $workingHours = new classWorkingHours(
                null,
                $_SESSION['employee_id'],
                $id_time_code,
                $id_work_order,
                $id_project,
                $id_task,
                $description,
                $duration,
                $date_worked,
                null,
                null
            );

            #3.Validacion
            $errores = [];

            // Id_time_code
            if (empty($id_time_code)) {
                $errores['id_time_code'] = 'The field id_time_code is required';
            } else if (strlen($id_time_code) > 10) {
                $errores['id_time_code'] = 'The field id_time_code is too long';
            }

            // Id_work_order
            if (empty($id_work_order)) {
                $errores['id_work_order'] = 'The field id_work_order is required';
            } else if (strlen($id_work_order) > 10) {
                $errores['id_work_order'] = 'The field id_work_order is too long';
            }

            // Id_project
            if (empty($id_project)) {
                $errores['id_project'] = 'The field project is required';
            } else if (strlen($id_project) > 10) {
                $errores['id_project'] = 'The field project is too long';
            }

            // Id_task
            if (empty($id_task)) {
                $errores['id_task'] = 'The field task is required';
            } else if (strlen($id_task) > 10) {
                $errores['email'] = 'Email ya registrado';
            }

            // Description
            if (empty($description)) {
                $errores['dni'] = 'The field description is required';
            } else if (strlen($description) > 10) {
                $errores['dni'] = 'Dni ya registrado';
            }

            #4. Verify Validation

            if (!empty($errores)) {
                //errores de validacion
                $_SESSION['workingHours'] = serialize($workingHours);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'workingHours/new');

            } else {
                //Create workingHours
                # Añadir registro a la tabla
                $this->model->create($workingHours);

                #Mensaje
                $_SESSION['mensaje'] = "Working Hour creado correctamente";

                # Redirigimos al main de workingHours
                header('location:' . URL . 'workingHours');
            }
        }
    }

    # Método delete. 
    # Permite la eliminación de un workingHours
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['delete']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "workingHours");
        } else {
            $id = $param[0];
            $this->model->delete($id);
            $_SESSION['mensaje'] = 'Alumno eliminado correctamente';

            header("Location:" . URL . "workingHours");
        }
    }

    # Método editar. 
    # Muestra un formulario que permita editar los detalles de un workingHours
    public function editar($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['edit'])) {
            $_SESSION['mensaje'] = "Operación sin privilegios";

            header('location:' . URL . 'workingHours');

        } else {

            # obtengo el id del workingHours que voy a editar

            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Formulario  editar workingHours";
            $this->view->workingHours = $this->model->read($id);

            $this->view->workingHours = $this->model->getworkingHours($this->view->id);

            # Creamos un objeto vacio
            // $this->view->workingHours = new classworkingHours();

            # Comprobamos si hay errores -> esta variable se crea al lanzar un error de validacion
            if (isset($_SESSION['error'])) {
                // rescatemos el mensaje
                $this->view->error = $_SESSION['error'];

                // Autorellenamos el formulario
                $this->view->workingHours = unserialize($_SESSION['workingHours']);

                // Recupero array de errores específicos
                $this->view->errores = $_SESSION['errores'];

                // debemos liberar las variables de sesión ya que su cometido ha sido resuelto
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['workingHours']);
                // Si estas variables existen cuando no hay errores, entraremos en los bloques de error en las condicionales
            }

            $this->view->render("workingHours/editar/index");
        }
    }
    # Método update.
    # Actualiza los detalles de un workingHours a partir de los datos del formulario de edición
    public function update($param = [])
    {

        #Iniciar Sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['edit']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "workingHours");
        } else {

            #1.Seguridad. Saneamos los datos del formulario
            $id_time_code = filter_var($_POST['id_time_code'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_work_order = filter_var($_POST['id_work_order'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $telefono = filter_var($_POST['telefono'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $ciudad = filter_var($_POST['ciudad'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $dni = filter_var($_POST['dni'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);

            $workingHours = new classworkingHours(
                null,
                $id_time_code,
                $id_work_order,
                $telefono,
                $ciudad,
                $dni,
                $email,
                null,
                null
            );
            $id = $param[0];

            #Obtengo el objeto workingHours original
            $workingHours_orig = $this->model->read($id);

            #3. Validación
            //Sólo si es necesario
            //Sólo en caso de modificación del campo

            $errores = [];

            //id_time_code: obligatorio, maximo 45 caracteres
            if (strcmp($workingHours->id_time_code, $workingHours_orig->id_time_code) !== 0) {
                if (empty($id_time_code)) {
                    $errores['id_time_code'] = 'El campo id_time_code es obligatorio';
                } else if (strlen($id_time_code) > 45) {
                    $errores['id_time_code'] = 'El campo id_time_code es demasiado largo';

                }
            }


            //id_work_order: obligatorio, maximo 20 caracteres
            if (strcmp($workingHours->id_work_order, $workingHours_orig->id_work_order) !== 0) {

                if (empty($id_work_order)) {
                    $errores['id_work_order'] = 'El campo id_work_order es obligatorio';
                } else if (strlen($id_work_order) > 20) {
                    $errores['id_work_order'] = 'El campo id_work_order es demasiado largo';

                }
            }



            //Teléfono: no obligatorio, 9 caracteres numéricos
            // if (strcmp($workingHours->telefono, $workingHours_orig->telefono) !== 0) {
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
            if (strcmp($workingHours->ciudad, $workingHours_orig->ciudad) !== 0) {

                if (empty($ciudad)) {
                    $errores['ciudad'] = 'El campo ciudad es obligatorio';
                } else if (strlen($ciudad) > 20) {
                    $errores['ciudad'] = 'El campo ciudad es demasiado largo';

                }
            }
            //Email: obligatorio, formato válido y clave secundaria
            if (strcmp($workingHours->email, $workingHours_orig->email) !== 0) {

                if (empty($email)) {
                    $errores['email'] = 'El campo email es obligatorio';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errores['email'] = 'El formato introducido es incorrecto';
                } else if (!$this->model->validateUniqueEmail($email)) {
                    $errores['email'] = 'Email ya registrado';

                }
            }
            //Dni: obligatorio, formato válido y clave secundaria
            if (strcmp($workingHours->dni, $workingHours_orig->dni) !== 0) {
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
                $_SESSION['workingHours'] = serialize($workingHours);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                # Redirigimos al main de workingHours
                header('location:' . URL . 'workingHours/editar/' . $id);
            } else {
                //crear alumno
                # Añadir registro a la tabla
                $this->model->update($workingHours, $id);

                #Mensaje
                $_SESSION['mensaje'] = "workingHours actualizado correctamente";

                # Redirigimos al main de alumnos
                header('location:' . URL . 'workingHours');
            }
        }
    }


    # Método mostrar
    # Muestra en un formulario de solo lectura los detalles de un workingHours
    public function mostrar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['show']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "workingHours");
        } else {
            $id = $param[0];
            $this->view->title = "Formulario workingHours Mostar";
            $this->view->workingHours = $this->model->getworkingHours($id);
            $this->view->render("workingHours/mostrar/index");
        }
    }

    # Método ordenar
    # Permite ordenar la tabla de workingHours por cualquiera de las columnas de la tabla
    public function ordenar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['order']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "workingHours");
        } else {
            $criterio = $param[0];
            $this->view->title = "Tabla workingHours";
            $this->view->workingHours = $this->model->order($criterio);
            $this->view->render("workingHours/main/index");
        }

    }

    # Método buscar
    # Permite buscar los registros de workingHours que cumplan con el patrón especificado en la expresión
    # de búsqueda
    public function buscar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['filter']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "workingHours");
        } else {
            $expresion = $_GET["expresion"];
            $this->view->title = "Tabla workingHours";
            $this->view->workingHours = $this->model->filter($expresion);
            $this->view->render("workingHours/main/index");
        }
    }

    // public function exportar($param = [])
    // {
    //     // Validar la sesión del usuario
    //     session_start();
    //     if (!isset($_SESSION['id'])) {
    //         $_SESSION['mensaje'] = "Usuario debe autentificarse";
    //         header("location:" . URL . "login");
    //         exit();  // Terminar la ejecución para evitar procesar la exportación sin autenticación
    //     } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee'])) {
    //         $_SESSION['mensaje'] = "Operación sin privilegio";
    //         header("location:" . URL . "workingHours");
    //         exit();  // Terminar la ejecución para evitar procesar la exportación sin privilegios
    //     }

    //     // Obtener datos de workingHours
    //     $workingHours = $this->model->get()->fetchAll(PDO::FETCH_ASSOC);

    //     // id_work_order del archivo CSV
    //     $csvExportado = 'export_workingHours.csv';

    //     // Establecer las cabeceras para la descarga del archivo
    //     header('Content-Type: text/csv');
    //     header('Content-Disposition: attachment; filename="' . $csvExportado . '"');

    //     // Abrir el puntero al archivo de salida
    //     $archivo = fopen('php://output', 'w');

    //     // Escribir la primera fila con los encabezados
    //     fputcsv($archivo, ['id_time_code', 'id_work_order', 'telefono', 'ciudad', 'dni', 'email', 'create_at', 'update_at'], ';');

    //     // Iterar sobre los workingHours y escribir cada fila en el archivo
    //     foreach ($workingHours as $workingHour) {
    //         // Separar el campo "workingHours" en "id_time_code" y "id_work_order"
    //         list($id_time_code, $id_work_order) = explode(', ', $workingHour['workingHours']);

    //         // Construir el array del workingHours con los datos necesarios
    //         $workingHoursData = [
    //             'id_time_code' => $id_time_code,
    //             'id_work_order' => $id_work_order,
    //             'telefono' => $workingHour['telefono'],
    //             'ciudad' => $workingHour['ciudad'],
    //             'dni' => $workingHour['dni'],
    //             'email' => $workingHour['email'],
    //             'create_at' => date('Y-m-d H:i:s'),
    //             'update_at' => null
    //         ];

    //         // Escribir la fila en el archivo
    //         fputcsv($archivo, $workingHoursData, ';');
    //     }

    //     // Cerramos el archivo
    //     fclose($archivo);

    //     // Enviar el contenido del archivo al navegador
    //     readfile('php://output');
    // }

    public function exportar($param = [])
    {
        // Validar la sesión del usuario
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";
            header("location:" . URL . "login");
            exit();  // Terminar la ejecución para evitar procesar la exportación sin autenticación
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "workingHours");
            exit();  // Terminar la ejecución para evitar procesar la exportación sin privilegios
        }

        // Obtener el correo electrónico del usuario actual
        $user_email = $_SESSION['email'];

        // Obtener datos de horas trabajadas del empleado
        $employee_hours = $this->model->get_employeeHours($user_email)->fetchAll(PDO::FETCH_ASSOC);

        // Nombre del archivo CSV exportado
        $csvExportado = 'export_employee_hours.csv';

        // Establecer las cabeceras para la descarga del archivo
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csvExportado . '"');

        // Abrir el puntero al archivo de salida
        $archivo = fopen('php://output', 'w');

        // Escribir la primera fila con los encabezados
        fputcsv($archivo, ['ID', 'ID Empleado', 'Nombre Empleado', 'Código de Tiempo', 'Nombre Proyecto', 'Descripción de Tarea', 'Descripción de la Orden de Trabajo', 'Fecha Trabajada', 'Duración'], ';');

        // Iterar sobre los datos de horas trabajadas y escribir cada fila en el archivo
        foreach ($employee_hours as $hour) {
            // Escribir la fila en el archivo
            fputcsv($archivo, [
                $hour['id'],
                $hour['id_employee'],
                $hour['employee_name'],
                $hour['time_code'],
                $hour['project_name'],
                $hour['task_description'],
                $hour['work_order_description'],
                $hour['date_worked'],
                $hour['duration']
            ], ';');
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
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['import'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "workingHours");
            exit();
        }


        // Validar si se ha subido un archivo
        if (!isset($_FILES['archivos']) || $_FILES['archivos']['error'] != UPLOAD_ERR_OK) {
            $_SESSION['mensaje'] = "Error al subir el archivo CSV. ";
            header("location:" . URL . "workingHours");
            exit();
        }

        // Obtener el id_work_order del archivo temporal
        $archivo_temporal = $_FILES['archivos']['tmp_name'];

        // Abrir el archivo temporal
        $archivo = fopen($archivo_temporal, 'r');

        // Validar que se pudo abrir el archivo
        if (!$archivo) {
            $_SESSION['mensaje'] = "Error al abrir el archivo CSV.";
            header("location:" . URL . "workingHours");
            exit();
        }

        // Iterar sobre las filas del archivo CSV
        while (($fila = fgetcsv($archivo, 150, ';')) !== false) {
            // Crear un array asociativo con los datos de la fila
            $workingHours = new classworkingHours();

            $workingHours->id_work_order = $fila[1];
            $workingHours->id_time_code = $fila[0];
            $workingHours->email = $fila[5];
            $workingHours->telefono = $fila[2];
            $workingHours->ciudad = $fila[3];
            $workingHours->dni = $fila[4];

            $this->model->create($workingHours);

        }

        // Cerrar el archivo
        fclose($archivo);

        // Redirigir después de importar
        $_SESSION['mensaje'] = "Datos importados correctamente.";
        header("location:" . URL . "workingHours");
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
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['export'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
            exit();
        }

        $data = $this->model->get()->fetchAll(PDO::FETCH_ASSOC);

        $columnas = [
            ['header' => 'workingHours', 'field' => 'workingHours', 'width' => 60],
            ['header' => 'Telefono', 'field' => 'telefono', 'width' => 40],
            ['header' => 'Ciudad', 'field' => 'ciudad', 'width' => 30],
            ['header' => 'DNI', 'field' => 'dni', 'width' => 25],
            ['header' => 'Email', 'field' => 'email', 'width' => 40],
        ];

        $pdf = new PDFworkingHours();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->TituloInforme();
        $pdf->EncabezadoListado($columnas);
        $pdf->ContenidoListado($data, $columnas);
        $pdf->Output();
    }
}
