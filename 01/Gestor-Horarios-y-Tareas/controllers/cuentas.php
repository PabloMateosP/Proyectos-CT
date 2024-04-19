<?php

class Cuentas extends Controller
{

    # Método render
    # Principal del controlador Cuentas
    # Muestra los detalles de la tabla Cuentas
    function render($param = [])
    {
        #inicio o continuo sesion
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Usuario sin autentificar";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['main']))) {
            $_SESSION['mensaje'] = "Usuario sin autentificar";
            header("location:" . URL . "index");

        } else {
            #comprobar si existe mensaje
            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);

            }
            $this->view->title = "Tabla Cuentas";
            $this->view->cuentas = $this->model->get();
            $this->view->render("cuentas/main/index");
        }
    }

    # Método nuevo
    # Permite mostrar un formulario que permita añadir una nueva cuenta
    function nuevo($param = [])
    {
        # Continuamos la sesion
        session_start();
        # Creamos un objeto vacio
        $this->view->cuentas = new classCuenta();

        # Comprobamos si hay errores -> esta variable se crea al lanzar un error de validacion
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['new']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
        } else {
            if (isset($_SESSION['error'])) {
                // rescatemos el mensaje
                $this->view->error = $_SESSION['error'];

                // Autorellenamos el formulario
                $this->view->cuenta = unserialize($_SESSION['cuenta']);

                // Recupero array de errores específicos
                $this->view->errores = $_SESSION['errores'];

                // debemos liberar las variables de sesión ya que su cometido ha sido resuelto
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['cuenta']);
                // Si estas variables existen cuando no hay errores, entraremos en los bloques de error en las condicionales
            }

            $this->view->title = "Formulario añadir cuenta";

            // Para generar la lista select dinámica de cuentas
            $this->view->cuentas = $this->model->getClientes();

            $this->view->render("cuentas/nuevo/index");
        }
    }

    # Método create
    # Envía los detalles para crear una nueva cuenta
    function create($param = [])
    {
        #Iniciar Sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['new'])) {

            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");

        } else {
            #1.Seguridad. Saneamos los datos del formulario
            $num_cuenta = filter_var($_POST['num_cuenta'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_cliente = filter_var($_POST['id_cliente'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $fecha_alta = filter_var($_POST['fecha_alta'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $fecha_ul_mov = filter_var($_POST['fecha_ul_mov'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $num_movtos = filter_var($_POST['num_movtos'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $saldo = filter_var($_POST['saldo'] ??= '', FILTER_SANITIZE_NUMBER_INT);

            #2. Creamos cliente con los datos saneados
            $cuenta = new classCuenta(
                null,
                $num_cuenta,
                $id_cliente,
                $fecha_alta,
                $fecha_ul_mov,
                $num_movtos,
                $saldo,
                null,
                null
            );

            #3.Validacion
            $errores = [];

            //Cuenta. Obligatorio, formato 20 dígitos numéricos, valor con restricción unique en la tabla cuentas

            if (empty($num_cuenta)) {
                $errores['num_cuenta'] = 'El campo cuenta es obligatorio';
            } else if (strlen($num_cuenta) !== 20) {
                $errores['num_cuenta'] = 'El campo cuenta es demasiado largo o demasiado corto';

            } else if (!$this->model->validateUniqueCuenta($num_cuenta)) {
                $errores['num_cuenta'] = 'La cuenta ya existe';
            }

            //Cliente. Obligatorio, valor numérico, ha de existir en la tabla cuentas.
            if (empty($id_cliente)) {
                $errores['id_cliente'] = 'El campo cliente es obligatorio';
            } else if (!filter_var($id_cliente, FILTER_VALIDATE_INT)) {
                $errores['id_cliente'] = 'Cliente no valido';
            }

            if (!empty($errores)) {
                //errores de validacion
                $_SESSION['cuenta'] = serialize($cuenta);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'cuentas/nuevo');

            } else {
                $this->model->create($cuenta);
                #Mensaje
                $_SESSION['mensaje'] = "Cuenta creada correctamente";
                header("Location:" . URL . "cuentas");
            }

        }
    }

    # Método delete
    # Permite eliminar una cuenta de la tabla
    function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['delete']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
        } else {
            $id = $param[0];
            $this->model->delete($id);
            $_SESSION['mensaje'] = 'Alumno eliminado correctamente';

            header("Location:" . URL . "cuentas");
        }
    }

    # Método editar
    # Muestra los detalles de una cuenta en un formulario de edición
    # Sólo se podrá modificar el titular o cliente de la cuenta
    function editar($param = [])
    {

        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['edit']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");

        } else {
            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Formulario editar cuenta";
            $this->view->clientes = $this->model->getClientes();
            $this->view->cuenta = $this->model->getCuenta($id);

            // // formateamos la fecha
            // $fechaf=(str_split($this->view->cuenta->fecha_alta));
            // for ($i=0; $i <9 ; $i++) { 
            //     array_pop($fechaf);
            // }
            // $fechafort=implode($fechaf);
            // $this->view->cuenta->fecha_alta=$fechafort;

            # Comprobamos si hay errores -> esta variable se crea al lanzar un error de validacion
            if (isset($_SESSION['error'])) {
                // rescatemos el mensaje
                $this->view->error = $_SESSION['error'];

                // Autorellenamos el formulario
                $this->view->cuenta = unserialize($_SESSION['cuenta']);

                // Recupero array de errores específicos
                $this->view->errores = $_SESSION['errores'];

                // debemos liberar las variables de sesión ya que su cometido ha sido resuelto
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['cuentas']);
                // Si estas variables existen cuando no hay errores, entraremos en los bloques de error en las condicionales
            }

            $this->view->render("cuentas/editar/index");
        }
    }

    # Método update
    # Envía los detalles modificados de una cuenta para su actualización en la tabla
    function update($param = [])
    {
        #Iniciar Sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['edit']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
        } else {

            #1.Seguridad. Saneamos los datos del formulario
            $num_cuenta = filter_var($_POST['num_cuenta'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_cliente = filter_var($_POST['id_cliente'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $fecha_alta = filter_var($_POST['fecha_alta'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $fecha_ul_mov = filter_var($_POST['fecha_ul_mov'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $num_movtos = filter_var($_POST['num_movtos'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $saldo = filter_var($_POST['saldo'] ??= '', FILTER_SANITIZE_EMAIL);

            #2. Creamos cliente con los datos saneados
            $cuenta = new classCuenta(
                null,
                $num_cuenta,
                $id_cliente,
                $fecha_alta,
                $fecha_ul_mov,
                $num_movtos,
                $saldo,
                null,
                null
            );
            $id = $param[0];

            #Obtengo el objeto cliente original
            $cuenta_orig = $this->model->read($id);

            #3.Validacion
            $errores = [];

            //Cuenta. Obligatorio, formato 20 dígitos numéricos, valor con restricción unique en la tabla cuentas

            if (strcmp($cuenta->num_cuenta, $cuenta_orig->num_cuenta) !== 0) {

                if (empty($num_cuenta)) {
                    $errores['num_cuenta'] = 'El campo cuenta es obligatorio';
                } else if (strlen($num_cuenta) !== 20) {
                    $errores['num_cuenta'] = 'El campo cuenta es demasiado largo o demasiado corto';

                } else if (!$this->model->validateUniqueCuenta($num_cuenta)) {
                    $errores['num_cuenta'] = 'La cuenta ya existe';
                }
            }
            //Cliente. Obligatorio, valor numérico, ha de existir en la tabla cuentas.
            if (strcmp($cuenta->id_cliente, $cuenta_orig->id_cliente) !== 0) {

                if (empty($id_cliente)) {
                    $errores['id_cliente'] = 'El campo cliente es obligatorio';
                } else if (!filter_var($id_cliente, FILTER_VALIDATE_INT)) {
                    $errores['id_cliente'] = 'Cliente no valido';
                }
            }
            if (!empty($errores)) {
                //errores de validacion
                $_SESSION['cuenta'] = serialize($cuenta);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'cuentas/editar');

            } else {
                $this->model->update($cuenta, $id);
                #Mensaje
                $_SESSION['mensaje'] = "Cuenta editada correctamente";
                header("Location:" . URL . "cuentas");
            }
        }
    }


    # Método mostrar
    # Muestra los detalles de una cuenta en un formulario no editable
    function mostrar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['show']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
        } else {
            # id de la cuenta
            $id = $param[0];

            $this->view->title = "Formulario Cuenta Mostar";
            $this->view->cuenta = $this->model->getCuenta($id);
            $this->view->cliente = $this->model->getCliente($this->view->cuenta->id_cliente);

            // // formateamos la fecha
            // $fechaf=(str_split($this->view->cuenta->fecha_alta));
            // for ($i=0; $i <9 ; $i++) { 
            //     array_pop($fechaf);
            // }
            // $fechafort=implode($fechaf);
            // $this->view->cuenta->fecha_alta=$fechafort;

            $this->view->render("cuentas/mostrar/index");
        }
    }

    # Método ordenar
    # Permite ordenar la tabla cuenta a partir de alguna de las columnas de la tabla
    function ordenar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['order']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
        } else {
            $criterio = $param[0];
            $this->view->title = "Tabla Cuentas";
            $this->view->cuentas = $this->model->order($criterio);
            $this->view->render("cuentas/main/index");

        }
    }

    # Método buscar
    # Permite realizar una búsqueda en la tabla cuentas a partir de una expresión
    function buscar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['filter']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
        } else {
            $expresion = $_GET["expresion"];
            $this->view->title = "Tabla Cuentas";
            $this->view->cuentas = $this->model->filter($expresion);
            $this->view->render("cuentas/main/index");
        }
    }

    public function exportar($param = [])
    {

        // Validar la sesión del usuario
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";
            header("location:" . URL . "login");
            exit();
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['export'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
            exit();
        }


        // Obtener datos de cuentas
        $cuentas = $this->model->get()->fetchAll(PDO::FETCH_ASSOC);

        // Nombre del archivo CSV
        $csvExportado = 'export_cuentas.csv';

        // Establecer las cabeceras para la descarga del archivo
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csvExportado . '"');

        // Abrir el puntero al archivo de salida
        $archivo = fopen('php://output', 'w');

        // Escribir la primera fila con los encabezados
        fputcsv($archivo, ['id', 'num_cuenta', 'id_cliente', 'fecha_alta', 'fecha_ul_mov', 'num_movtos', 'saldo', 'create_at', 'update_at'], ';');

        // Iterar sobre los cuentas y escribir cada fila en el archivo
        //Escribimos los datos al archivo CSV
        foreach ($cuentas as $cuenta) {
            //Reordenar los campos de la cuenta
            $cuenta = array(
                'id' => $cuenta['id'],
                'num_cuenta' => $cuenta['num_cuenta'],
                'id_cliente' => $cuenta['id_cliente'],
                'fecha_alta' => $cuenta['fecha_alta'],
                'fecha_ul_mov' => $cuenta['fecha_ul_mov'],
                'num_movtos' => $cuenta['num_movtos'],
                'saldo' => $cuenta['saldo'],
                'create_at' => date('Y-m-d H:i:s'),
                'update_at' => null
            );

            //Escribimos la información de la cuenta al archivo CSV
            fputcsv($archivo, $cuenta, ';');
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
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['import'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
            exit();
        }


        // Validar si se ha subido un archivo
        if (!isset($_FILES['archivos']) || $_FILES['archivos']['error'] != UPLOAD_ERR_OK) {
            $_SESSION['mensaje'] = "Error al subir el archivo CSV. ";
            header("location:" . URL . "cuentas");
            exit();
        }

        // Obtener el nombre del archivo temporal
        $archivo_temporal = $_FILES['archivos']['tmp_name'];

        // Abrir el archivo temporal
        $archivo = fopen($archivo_temporal, 'r');

        // Validar que se pudo abrir el archivo
        if (!$archivo) {
            $_SESSION['mensaje'] = "Error al abrir el archivo CSV.";
            header("location:" . URL . "cuentas");
            exit();
        }


        // Iterar sobre las filas del archivo CSV
        while (($fila = fgetcsv($archivo, 0, ';')) !== false) {
            // Crear un array asociativo con los datos de la fila
            $cuenta = new classCuenta();

            // $fila[1] = filter_var($_POST['num_cuenta'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            // $fila[2] = filter_var($_POST['id_cliente'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            // $fila[3] = filter_var($_POST['fecha_alta'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            // $fila[4] = filter_var($_POST['fecha_ul_mov'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            // $fila[5] = filter_var($_POST['num_movtos'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            // $fila[6] = filter_var($_POST['saldo'] ??= '', FILTER_SANITIZE_NUMBER_INT);
            // $fila[7] = filter_var($_POST['create_at'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            $cuenta->num_cuenta = $fila[1];
            $cuenta->id_cliente = $fila[2];
            $cuenta->fecha_alta = $fila[3];
            $cuenta->fecha_ul_mov = $fila[4];
            $cuenta->num_movtos = $fila[5];
            $cuenta->saldo = $fila[6];
            $cuenta->create_at = $fila[7];
            $cuenta->update_at = null;

            // #3.Validacion
            // $errores = [];

            // if (empty($fila[1])) {
            //     $errores['num_cuenta'] = 'El campo cuenta es obligatorio';
            // } else if (strlen($fila[1]) !== 20) {
            //     $errores['num_cuenta'] = 'El campo cuenta es demasiado largo o demasiado corto';

            // } else if (!$this->model->validateUniqueCuenta($fila[1])) {
            //     $errores['num_cuenta'] = 'La cuenta ya existe';
            // }

            // //Cliente. Obligatorio, valor numérico, ha de existir en la tabla cuentas.
            // if (empty($fila[2])) {
            //     $errores['id_cliente'] = 'El campo cliente es obligatorio';
            // } else if (!filter_var($fila[2], FILTER_VALIDATE_INT)) {
            //     $errores['id_cliente'] = 'Cliente no valido';
            // }

            // if (!empty($errores)) {
            //     //errores de validacion
            //     $_SESSION['cuenta'] = serialize($cuenta);
            //     $_SESSION['error'] = 'Formulario no validado';
            //     $_SESSION['errores'] = $errores;

            //     header('location:' . URL . 'cuentas/nuevo');

            // } else {
            $this->model->create($cuenta);

        }

        // Cerrar el archivo
        fclose($archivo);

        // Redirigir después de importar
        $_SESSION['mensaje'] = "Datos importados correctamente.";
        header("location:" . URL . "cuentas");
        exit();
    }

    # Método pdf
    # Genera un informe PDF de la tabla Cuentas
    function pdf($param = [])
    {
        // Validar la sesión del usuario
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";
            header("location:" . URL . "login");
            exit();
        } elseif (!in_array($_SESSION['id_rol'], $GLOBALS['cuentas']['export'])) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "cuentas");
            exit();
        }

        $data = $this->model->get()->fetchAll(PDO::FETCH_ASSOC);

        $columnas = [
            ['header' => 'Num Cuenta', 'field' => 'num_cuenta', 'width' => 40],
            ['header' => 'ID Cliente', 'field' => 'id_cliente', 'width' => 30],
            ['header' => 'Fecha Alta', 'field' => 'fecha_alta', 'width' => 40],
            ['header' => 'Ult Mov', 'field' => 'fecha_ul_mov', 'width' => 37],
            ['header' => 'Num Mov', 'field' => 'num_movtos', 'width' => 20],
            ['header' => 'Saldo', 'field' => 'saldo', 'width' => 20],
        ];

        $pdf = new PDFCuentas();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->TituloInforme();
        $pdf->EncabezadoListado($columnas);
        $pdf->ContenidoListado($data, $columnas);
        $pdf->Output();
    }

    function movimientos($param = []){
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

            $id = $param[0];

            $this->view->title = "Tabla Movimientos";
            $this->view->movimientos = $this->model->getMovCuentas($id);
            $this->view->render("cuentas/movimientos/main/index");
        }
    }
}
