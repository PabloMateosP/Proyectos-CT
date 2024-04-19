<?php

class Movimientos extends Controller
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

            $this->view->title = "Tabla Movimientos";
            $this->view->movimientos = $this->model->get();
            $this->view->render("movimientos/main/index");
        }
    }

    function nuevo($param = [])
    {

        session_start();

        if (!isset($_SESSION['id'])) {

            $_SESSION["mensaje"] = "Usuario debe autentificarse";
            header('Location:' . URL . "login");
        } else if (!in_array($_SESSION["id_rol"], $GLOBALS["movimientos"]['new'])) {

            $_SESSION['mensaje'] = "Usuario sin privilegios";
            header("location:" . URL . "cuentas");
        } else {
            $this->view->mov = new Movimiento();

            if (isset($_SESSION["error"])) {

                $this->view->error = $_SESSION["error"];

                unset($_SESSION["error"]);

                $this->view->mov = unserialize($_SESSION["mov"]);
                unset($_SESSION["mov"]);

                $this->view->errores = $_SESSION["errores"];
                unset($_SESSION["errores"]);
            }

            $this->view->title = "Formulario Movimiento nuevo";
            $this->view->cuentas = $this->model->getAllCuentas();
            $this->view->render("movimientos/nuevo/index");
        }
    }

    public function create($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION["mensaje"] = "Usuario debe autenticarse";
            header('Location:' . URL . "login");
        } else if (!in_array($_SESSION["id_rol"], $GLOBALS["movimientos"]['new'])) {
            $_SESSION['mensaje'] = "Usuario sin privilegios";
            header("location:" . URL . "cuentas");
        } else {
            // Saneamos los datos del formulario con FILTER_SANITIZE_SPECIAL_CHARS
            $id_cuenta = filter_input(INPUT_POST, 'id_cuenta', FILTER_SANITIZE_NUMBER_INT);
            $fecha_hora = filter_input(INPUT_POST, 'fecha_hora', FILTER_SANITIZE_SPECIAL_CHARS);
            $concepto = filter_input(INPUT_POST, 'concepto', FILTER_SANITIZE_SPECIAL_CHARS);
            $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
            $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_SANITIZE_SPECIAL_CHARS);
            $cantidad = floatval($cantidad);

            if($tipo == 'R') {
                $cantidad = -$cantidad;
            } 

            $mov = new Movimiento(
                null,
                $id_cuenta,
                $fecha_hora,
                $concepto,
                $tipo,
                $cantidad,
                null,
                null,
                null
            );

            // Validación del formulario
            $errores = [];

            // Id_cuenta: Obligatorio
            if (empty($id_cuenta)) {
                $errores["id_cuenta"] = "Seleccione una cuenta.";
            }

            // Fecha_hora: Obligatorio
            if (empty($fecha_hora)) {
                $errores["fecha_hora"] = "Campo obligatorio.";
            }

            // Concepto: Obligatorio, tamaño máximo 50 caracteres
            if (empty($concepto)) {
                $errores["concepto"] = "Campo obligatorio.";
            } else if (strlen($concepto) > 50) {
                $errores["concepto"] = "Concepto superior a 50 caracteres.";
            }

            // Tipo: Obligatorio, podrá ser 'I' o 'R'
            if (empty($tipo)) {
                $errores["tipo"] = "Campo obligatorio.";
            } else if (!in_array($tipo, ["R", "I"])) {
                $errores["tipo"] = "Tipo no permitido";
            }

            // Cantidad: Obligatorio, valor de tipo float
            if (empty($cantidad) || !is_float($cantidad)) {
                $errores["cantidad"] = "Cantidad formato incorrecto";
            }

            // Comprobar validación
            if (!empty($errores)) {
                // Si hay errores, almacenamos el objeto $mov en la sesión y redirigimos
                $_SESSION["mov"] = serialize($mov);
                $_SESSION["error"] = "Formulario no ha sido validado";
                $_SESSION["errores"] = $errores;

                // Redireccionamos a nuevo movimiento
                header('Location:' . URL . 'movimientos/nuevo/' . $id_cuenta);
            } else {
                // Si no hay errores, procedemos a crear el movimiento
                $this->view->title = "Tabla Movimientos";

                $this->model->create($mov, $id_cuenta);
                $_SESSION["mensaje"] = "Movimiento creado correctamente";
                header("Location:" . URL . "movimientos");
            }
        }
    }

    public function ordenar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['movimientos']['order']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "movimientos");
        } else {
            $criterio = $param[0];
            $this->view->title = "Tabla Movimientos";
            $this->view->movimientos = $this->model->order($criterio);
            $this->view->render("movimientos/main/index");
        }

    }

    # Método buscar
    # Permite buscar los registros de movimientos que cumplan con el patrón especificado en la expresión
    # de búsqueda
    public function buscar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['movimientos']['filter']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {
            $expresion = $_GET["expresion"];
            $this->view->title = "Tabla Movimientos";
            $this->view->movimientos = $this->model->filter($expresion);
            $this->view->render("movimientos/main/index");
        }
    }

}