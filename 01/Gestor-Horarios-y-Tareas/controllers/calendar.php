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
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Unauthenticated User";
            header("location:" . URL . "login");
            exit();
        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['all'])) {
            $_SESSION['mensaje'] = "Unauthenticated User";
            header("location:" . URL . "index");
            exit();
        } else {
            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }

            // Ejecuta la consulta a la base de datos
            $schedules = $this->model->getSchedules();
            $sched_res = [];
            foreach ($schedules as $row) {
                $row->sdate = date("F d, Y h:i A", strtotime($row->start_datetime));
                $row->edate = date("F d, Y h:i A", strtotime($row->end_datetime));
                $sched_res[$row->id] = $row;
            }

            $this->view->title = "Calendar";
            $this->view->sched_res = $sched_res; // Pasa los datos a la vista
            $this->view->render("calendar/main/index");
        }
    }

    public function handleRequest()
    {

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

                // Ejecuta la consulta a la base de datos
                $schedules = $this->model->getSchedules();
                $sched_res = [];
                foreach ($schedules as $row) {
                    $row->sdate = date("F d, Y h:i A", strtotime($row->start_datetime));
                    $row->edate = date("F d, Y h:i A", strtotime($row->end_datetime));
                    $sched_res[$row->id] = $row;
                }

                $this->view->title = "Calendar";
                $this->view->sched_res = $sched_res; // Pasa los datos a la vista

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

    # ---------------------------------------------------------------------------------
    #
    #    _____  ______ _      ______ _______ ______ 
    #    |  __ \|  ____| |    |  ____|__   __|  ____|
    #    | |  | | |__  | |    | |__     | |  | |__   
    #    | |  | |  __| | |    |  __|    | |  |  __|  
    #    | |__| | |____| |____| |____   | |  | |____ 
    #    |_____/|______|______|______|  |_|  |______|
    #                                                                                                  
    # ---------------------------------------------------------------------------------
    # Method delet. 
    # Allow the elimination of an event
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "calendar");
        } else {
            $id = $param[0];

            $this->model->delete($id);

            $_SESSION['mensaje'] = 'Event delete correctly';

            $this->view->render("calendar/main/index");
        }
    }
}