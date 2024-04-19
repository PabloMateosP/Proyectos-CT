<?php

class Clientes extends Controller
{

    # Método principal. Muestra todos los clientes
    public function render($param = [])
    {
        #inicio o continuo sesion
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Usuario sin autentificar";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['main']))) {
            $_SESSION['mensaje'] = "Usuario sin autentificar";
            header("location:" . URL . "index");

        } else {
            #comprobar si existe mensaje
            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);

            }


            $this->view->title = "Tabla Clientes";
            $this->view->clientes = $this->model->get();
            $this->view->render("clientes/main/index");
        }
    }

    # Método nuevo. Muestra formulario añadir cliente
    public function nuevo($param = [])
    {
        # Continuamos la sesion
        session_start();

        # compruebo usuario autentificado
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['new']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {

            # Creamos un objeto vacio
            $this->view->cliente = new classCliente();

            # Comprobamos si hay errores -> esta variable se crea al lanzar un error de validacion
            if (isset($_SESSION['error'])) {
                // rescatemos el mensaje
                $this->view->error = $_SESSION['error'];

                // Autorellenamos el formulario
                $this->view->cliente = unserialize($_SESSION['cliente']);

                // Recupero array de errores específicos
                $this->view->errores = $_SESSION['errores'];

                // debemos liberar las variables de sesión ya que su cometido ha sido resuelto
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['clientes']);
                // Si estas variables existen cuando no hay errores, entraremos en los bloques de error en las condicionales
            }

            $this->view->title = "Formulario cliente nuevo";
            $this->view->render("clientes/nuevo/index");
        }
    }
    # Método create. 
    # Permite añadir nuevo cliente a partir de los detalles del formuario
    public function create($param = [])
    {
        #Iniciar Sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['new'])) {

            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");

        } else {

            #1.Seguridad. Saneamos los datos del formulario
            $apellidos = filter_var($_POST['apellidos'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $nombre = filter_var($_POST['nombre'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $telefono = filter_var($_POST['telefono'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $ciudad = filter_var($_POST['ciudad'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $dni = filter_var($_POST['dni'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);

            #2. Creamos cliente con los datos saneados
            $cliente = new classCliente(
                null,
                $apellidos,
                $nombre,
                $telefono,
                $ciudad,
                $dni,
                $email,
                null,
                null
            );

            #3.Validacion
            $errores = [];

            //Apellidos: obligatorio, maximo 45 caracteres

            if (empty($apellidos)) {
                $errores['apellidos'] = 'El campo apellidos es obligatorio';
            } else if (strlen($apellidos) > 45) {
                $errores['apellidos'] = 'El campo apellidos es demasiado largo';

            }

            //Nombre: obligatorio, maximo 20 caracteres
            if (empty($nombre)) {
                $errores['nombre'] = 'El campo nombre es obligatorio';
            } else if (strlen($nombre) > 20) {
                $errores['nombre'] = 'El campo nombre es demasiado largo';

            }



            //Teléfono: no obligatorio, 9 caracteres numéricos
            // $options_tlf=[
            //     'options_tlf'=> [
            //         'regexp' => '/^\d{9}$/'
            //     ]
            // ];
            // if(!filter_var($telefono, FILTER_VALIDATE_REGEXP, $options_tlf)){
            //     $errores['telefono'] = 'El formato introducido es incorrecto';

            // }

            //Ciudad: obligatorio, maximo 20 caracteres
            if (empty($ciudad)) {
                $errores['ciudad'] = 'El campo ciudad es obligatorio';
            } else if (strlen($ciudad) > 20) {
                $errores['ciudad'] = 'El campo ciudad es demasiado largo';

            }
            //Email: obligatorio, formato válido y clave secundaria
            if (empty($email)) {
                $errores['email'] = 'El campo email es obligatorio';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = 'El formato introducido es incorrecto';
            } else if (!$this->model->validateUniqueEmail($email)) {
                $errores['email'] = 'Email ya registrado';

            }
            //Dni: obligatorio, formato válido y clave secundaria
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



            #4. Comprobar validacion

            if (!empty($errores)) {
                //errores de validacion
                $_SESSION['cliente'] = serialize($cliente);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'clientes/nuevo');

            } else {
                //crear cliente
                # Añadir registro a la tabla
                $this->model->create($cliente);

                #Mensaje
                $_SESSION['mensaje'] = "Cliente creado correctamente";

                # Redirigimos al main de clientes
                header('location:' . URL . 'clientes');
            }
        }
    }

    # Método delete. 
    # Permite la eliminación de un cliente
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['delete']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {
            $id = $param[0];
            $this->model->delete($id);
            $_SESSION['mensaje'] = 'Alumno eliminado correctamente';

            header("Location:" . URL . "clientes");
        }
    }

    # Método editar. 
    # Muestra un formulario que permita editar los detalles de un cliente
    public function editar($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['edit'])) {
            $_SESSION['mensaje'] = "Operación sin privilegios";

            header('location:' . URL . 'clientes');

        } else {

            # obtengo el id del cliente que voy a editar

            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Formulario  editar cliente";
            $this->view->cliente = $this->model->read($id);

            $this->view->cliente = $this->model->getCliente($this->view->id);

            # Creamos un objeto vacio
            // $this->view->cliente = new classCliente();

            # Comprobamos si hay errores -> esta variable se crea al lanzar un error de validacion
            if (isset($_SESSION['error'])) {
                // rescatemos el mensaje
                $this->view->error = $_SESSION['error'];

                // Autorellenamos el formulario
                $this->view->cliente = unserialize($_SESSION['cliente']);

                // Recupero array de errores específicos
                $this->view->errores = $_SESSION['errores'];

                // debemos liberar las variables de sesión ya que su cometido ha sido resuelto
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['clientes']);
                // Si estas variables existen cuando no hay errores, entraremos en los bloques de error en las condicionales
            }

            $this->view->render("clientes/editar/index");
        }
    }
    # Método update.
    # Actualiza los detalles de un cliente a partir de los datos del formulario de edición
    public function update($param = [])
    {

        #Iniciar Sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['edit']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {

            #1.Seguridad. Saneamos los datos del formulario
            $apellidos = filter_var($_POST['apellidos'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $nombre = filter_var($_POST['nombre'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $telefono = filter_var($_POST['telefono'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $ciudad = filter_var($_POST['ciudad'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $dni = filter_var($_POST['dni'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);

            $cliente = new classCliente(
                null,
                $apellidos,
                $nombre,
                $telefono,
                $ciudad,
                $dni,
                $email,
                null,
                null
            );
            $id = $param[0];

            #Obtengo el objeto cliente original
            $cliente_orig = $this->model->read($id);

            #3. Validación
            //Sólo si es necesario
            //Sólo en caso de modificación del campo

            $errores = [];

            //Apellidos: obligatorio, maximo 45 caracteres
            if (strcmp($cliente->apellidos, $cliente_orig->apellidos) !== 0) {
                if (empty($apellidos)) {
                    $errores['apellidos'] = 'El campo apellidos es obligatorio';
                } else if (strlen($apellidos) > 45) {
                    $errores['apellidos'] = 'El campo apellidos es demasiado largo';

                }
            }


            //Nombre: obligatorio, maximo 20 caracteres
            if (strcmp($cliente->nombre, $cliente_orig->nombre) !== 0) {

                if (empty($nombre)) {
                    $errores['nombre'] = 'El campo nombre es obligatorio';
                } else if (strlen($nombre) > 20) {
                    $errores['nombre'] = 'El campo nombre es demasiado largo';

                }
            }



            //Teléfono: no obligatorio, 9 caracteres numéricos
            // if (strcmp($cliente->telefono, $cliente_orig->telefono) !== 0) {
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
            if (strcmp($cliente->ciudad, $cliente_orig->ciudad) !== 0) {

                if (empty($ciudad)) {
                    $errores['ciudad'] = 'El campo ciudad es obligatorio';
                } else if (strlen($ciudad) > 20) {
                    $errores['ciudad'] = 'El campo ciudad es demasiado largo';

                }
            }
            //Email: obligatorio, formato válido y clave secundaria
            if (strcmp($cliente->email, $cliente_orig->email) !== 0) {

                if (empty($email)) {
                    $errores['email'] = 'El campo email es obligatorio';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errores['email'] = 'El formato introducido es incorrecto';
                } else if (!$this->model->validateUniqueEmail($email)) {
                    $errores['email'] = 'Email ya registrado';

                }
            }
            //Dni: obligatorio, formato válido y clave secundaria
            if (strcmp($cliente->dni, $cliente_orig->dni) !== 0) {
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
                $_SESSION['cliente'] = serialize($cliente);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                # Redirigimos al main de clientes
                header('location:' . URL . 'clientes/editar/' . $id);
            } else {
                //crear alumno
                # Añadir registro a la tabla
                $this->model->update($cliente, $id);

                #Mensaje
                $_SESSION['mensaje'] = "Cliente actualizado correctamente";

                # Redirigimos al main de alumnos
                header('location:' . URL . 'clientes');
            }
        }
    }


    # Método mostrar
    # Muestra en un formulario de solo lectura los detalles de un cliente
    public function mostrar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['show']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {
            $id = $param[0];
            $this->view->title = "Formulario Cliente Mostar";
            $this->view->cliente = $this->model->getCliente($id);
            $this->view->render("clientes/mostrar/index");
        }
    }

    # Método ordenar
    # Permite ordenar la tabla de clientes por cualquiera de las columnas de la tabla
    public function ordenar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['order']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {
            $criterio = $param[0];
            $this->view->title = "Tabla Clientes";
            $this->view->clientes = $this->model->order($criterio);
            $this->view->render("clientes/main/index");
        }

    }

    # Método buscar
    # Permite buscar los registros de clientes que cumplan con el patrón especificado en la expresión
    # de búsqueda
    public function buscar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['filter']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {
            $expresion = $_GET["expresion"];
            $this->view->title = "Tabla Clientes";
            $this->view->clientes = $this->model->filter($expresion);
            $this->view->render("clientes/main/index");
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
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['export'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
            exit();  // Terminar la ejecución para evitar procesar la exportación sin privilegios
        }

        // Obtener datos de clientes
        $clientes = $this->model->get()->fetchAll(PDO::FETCH_ASSOC);

        // Nombre del archivo CSV
        $csvExportado = 'export_clientes.csv';

        // Establecer las cabeceras para la descarga del archivo
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csvExportado . '"');

        // Abrir el puntero al archivo de salida
        $archivo = fopen('php://output', 'w');

        // Escribir la primera fila con los encabezados
        fputcsv($archivo, ['apellidos', 'nombre', 'telefono', 'ciudad', 'dni', 'email', 'create_at', 'update_at'], ';');

        // Iterar sobre los clientes y escribir cada fila en el archivo
        foreach ($clientes as $cliente) {
            // Separar el campo "cliente" en "apellidos" y "nombre"
            list($apellidos, $nombre) = explode(', ', $cliente['cliente']);

            // Construir el array del cliente con los datos necesarios
            $clienteData = [
                'apellidos' => $apellidos,
                'nombre' => $nombre,
                'telefono' => $cliente['telefono'],
                'ciudad' => $cliente['ciudad'],
                'dni' => $cliente['dni'],
                'email' => $cliente['email'],
                'create_at' => date('Y-m-d H:i:s'),
                'update_at' => null
            ];

            // Escribir la fila en el archivo
            fputcsv($archivo, $clienteData, ';');
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
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['import'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
            exit();
        }


        // Validar si se ha subido un archivo
        if (!isset($_FILES['archivos']) || $_FILES['archivos']['error'] != UPLOAD_ERR_OK) {
            $_SESSION['mensaje'] = "Error al subir el archivo CSV. ";
            header("location:" . URL . "clientes");
            exit();
        }

        // Obtener el nombre del archivo temporal
        $archivo_temporal = $_FILES['archivos']['tmp_name'];

        // Abrir el archivo temporal
        $archivo = fopen($archivo_temporal, 'r');

        // Validar que se pudo abrir el archivo
        if (!$archivo) {
            $_SESSION['mensaje'] = "Error al abrir el archivo CSV.";
            header("location:" . URL . "clientes");
            exit();
        }

        // Iterar sobre las filas del archivo CSV
        while (($fila = fgetcsv($archivo, 150, ';')) !== false) {
            // Crear un array asociativo con los datos de la fila
            $cliente = new classCliente();

            $cliente->nombre = $fila[1];
            $cliente->apellidos = $fila[0];
            $cliente->email = $fila[5]; 
            $cliente->telefono = $fila[2];
            $cliente->ciudad = $fila[3];
            $cliente->dni = $fila[4];

            $this->model->create($cliente);

        }

        // Cerrar el archivo
        fclose($archivo);

        // Redirigir después de importar
        $_SESSION['mensaje'] = "Datos importados correctamente.";
        header("location:" . URL . "clientes");
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
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['export'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
            exit();
        }

        $data = $this->model->get()->fetchAll(PDO::FETCH_ASSOC);

        $columnas = [
            ['header' => 'Cliente', 'field' => 'cliente', 'width' => 60],
            ['header' => 'Telefono', 'field' => 'telefono', 'width' => 40],
            ['header' => 'Ciudad', 'field' => 'ciudad', 'width' => 30],
            ['header' => 'DNI', 'field' => 'dni', 'width' => 25],
            ['header' => 'Email', 'field' => 'email', 'width' => 40],
        ];

        $pdf = new PDFClientes();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->TituloInforme();
        $pdf->EncabezadoListado($columnas);
        $pdf->ContenidoListado($data, $columnas);
        $pdf->Output();
    }
}
