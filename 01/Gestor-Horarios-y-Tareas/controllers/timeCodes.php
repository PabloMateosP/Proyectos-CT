<?php

class timeCodes extends Controller
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
    # Method render

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

            $this->view->title = "Time Codes Table";
            $this->view->timeCodes = $this->model->get();
            $this->view->render("timeCodes/main/index");

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
    # "New" Method. Show a formulary to add new timeCodes
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
            header("location:" . URL . "timeCodes");

        } else {

            # Create and instance of classTimeCodes
            $this->view->timeCodes = new classTimeCodes();

            # Check if there are errors -> this variable is created when a validation error occurs
            if (isset($_SESSION['error'])) {
                # Let's retrieve the message
                $this->view->error = $_SESSION['error'];

                # Autopopulate the form
                $this->view->timeCodes = unserialize($_SESSION['timeCodes']);

                # Retrieve array of specific errors
                $this->view->errores = $_SESSION['errores'];

                # We must unset the session variables as their purpose has been resolved
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['timeCodes']);

                # If these variables exist when there are no errors, we will enter the error blocks in the conditionals
            }

            $this->view->title = "Form new Time Codes";
            $this->view->render("timeCodes/new/index");
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
    # Allows adding a new timeCodes based on the form details.
    public function create($param = [])
    {
        # Start Session
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['message'] = "User must authenticate";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])) {

            $_SESSION['message'] = "Operation without privileges";
            header("location:" . URL . "timeCodes");

        } else {

            # --
            # 1. Security. Sanitize form data
            # --
            
            $time_code = filter_var($_POST['time_code'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $description = filter_var($_POST['description'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            # --
            # 2. Create timeCodes with sanitized data
            # --

            $timeCodes = new classTimeCodes(
                null,
                $time_code,
                $description,
                null,
                null
            );

            # --
            # 3. Validation
            # --

            $errores = array();

            # time_code
            if (empty($time_code)) {
                $errores['time_code'] = 'The time_code field is required';
            } else if (strlen($time_code) > 3) {
                $errores['time_code'] = 'The time_code field is too long';
            }

            # description
            if (empty($description)) {
                $errores['description'] = 'The description field is required';
            } else if (strlen($description) > 50) {
                $errores['description'] = 'The description field is too long';
            }

            # --
            # 4. Check Validation
            # --

            if (!empty($errores)) {

                # Validation errors 
                $_SESSION['timeCodes'] = serialize($timeCodes);
                $_SESSION['error'] = 'Invalid form';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'timeCodes/new');

            } else {

                # Create timeCodes

                # Add timeCodes
                $this->model->create($timeCodes);

                # Message
                $_SESSION['message'] = "Time Codes created correctly";

                # Redirect
                header('location:' . URL . 'timeCodes');

            }
        }
    }

}