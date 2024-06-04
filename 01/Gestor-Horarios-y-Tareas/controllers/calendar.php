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
    # "Render" Method. That show all the events in the calendar
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

            $schedules = $this->model->getSchedules();
            $sched_res = [];
            foreach ($schedules as $row) {
                $row->sdate = date("F d, Y h:i A", strtotime($row->start_datetime));
                $row->edate = date("F d, Y h:i A", strtotime($row->end_datetime));
                $sched_res[$row->id] = $row;
            }

            $this->view->title = "Calendar";
            $this->view->sched_res = $sched_res; // We send the data to the view
            $this->view->render("calendar/main/index");
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #  _    _          _   _ _____  _      ______ _____  ______ ____  _    _ ______  _____ _______ 
    #  | |  | |   /\   | \ | |  __ \| |    |  ____|  __ \|  ____/ __ \| |  | |  ____|/ ____|__   __|
    #  | |__| |  /  \  |  \| | |  | | |    | |__  | |__) | |__ | |  | | |  | | |__  | (___    | |   
    #  |  __  | / /\ \ | . ` | |  | | |    |  __| |  _  /|  __|| |  | | |  | |  __|  \___ \   | |   
    #  | |  | |/ ____ \| |\  | |__| | |____| |____| | \ \| |___| |__| | |__| | |____ ____) |  | |   
    #  |_|  |_/_/    \_\_| \_|_____/|______|______|_|  \_\______\___\_\\____/|______|_____/   |_|   
    #                                                                                           
    # ---------------------------------------------------------------------------------
    # Method to create an event 
    public function handleRequest()
    {

        session_start();

        if (!isset($_SESSION['id'])) {

            $_SESSION['notify'] = "Unauthenticated User";
            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {

            $_SESSION['mensaje'] = "Unauthenticated User";
            header("location:" . URL . "index");

        } else {

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                $_SESSION['mensaje'] = "There isn't any data to save";
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
                $_SESSION['mensaje'] = "A record already exists on the same date and time, within the 15 minute range";
                header("location:" . URL . "calendar");
            }

            if ($this->model->saveEvent($data)) {

                $_SESSION['mensaje'] = "Event Save Correctly";

                $schedules = $this->model->getSchedules();
                $sched_res = [];
                foreach ($schedules as $row) {
                    $row->sdate = date("F d, Y h:i A", strtotime($row->start_datetime));
                    $row->edate = date("F d, Y h:i A", strtotime($row->end_datetime));
                    $sched_res[$row->id] = $row;
                }

                $this->view->title = "Calendar";
                $this->view->sched_res = $sched_res; // Pasa los datos a la vista

                header('location:' . URL . 'calendar');

            } else {
                $_SESSION['error'] = "Error al guardar el evento";
                header('location:' . URL . 'calendar');
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