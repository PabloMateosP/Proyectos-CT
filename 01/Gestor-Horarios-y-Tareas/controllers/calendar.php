<?php 

class Calendar extends Controller
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
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {
            $_SESSION['mensaje'] = "Unauthenticated User";
            header("location:" . URL . "index");

        } else {

            # Check if message exists
            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }

            $this->view->title = "Calendar";
            $this->view->schedules = $this->model->get();
            $this->view->render("calendar/main/index");
        }
    }

    public function handleRequest() {

        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Unauthenticated User";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['all']))) {

            $_SESSION['mensaje'] = "Unauthenticated User";
            header("location:" . URL . "index");

        } else {

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                $_SESSION['mensaje'] = "No hay datos para guardar";
                header("location:" . URL . "index");
            }
    
            $data = [
                'id' => $_POST['id'] ?? null,
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'start_datetime' => date('Y-m-d H:i:s', strtotime($_POST['start_datetime'])),
                'end_datetime' => date('Y-m-d H:i:s', strtotime($_POST['end_datetime']))
            ];
    
            if ($this->model->checkIfExists($data['start_datetime'])) {
                $_SESSION['mensaje'] = "Ya existe un registro en la misma fecha y hora, dentro del rango de 15 minutos";
                header("location:" . URL . "index");
            }
    
            if ($this->model->saveEvent($data)) {
                $_SESSION['mensaje'] = "Evento Guardado Correctamente";
                $this->view->render("calendar/main/index");
            } else {
                $_SESSION['error'] = "Error al guardar el evento";
                $this->view->render("calendar/main/index");
            }

            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }
        }
    }
}