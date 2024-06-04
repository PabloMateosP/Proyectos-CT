<?php
    class Errores extends Controller {
        function __construct() {

            parent ::__construct();
            $this->view->mensaje = "Error To Charge the resource";
            $this->view->render('error/index');
        }
    }