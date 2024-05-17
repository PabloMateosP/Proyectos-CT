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
            $this->view->render("calendar/main/index");
        }
    }
}