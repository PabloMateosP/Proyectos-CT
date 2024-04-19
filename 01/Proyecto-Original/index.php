<?php

require_once 'libs/database.php';
require_once 'libs/controller.php';
require_once 'libs/model.php';
require_once 'libs/view.php';
require_once 'class/class.cliente.php';
require_once 'class/class.cuenta.php';
require_once 'class/class.movimiento.php';

// --- Enviar mail ----------------------
// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';

require_once 'fpdf/fpdf.php';
require_once 'class/class.pdfCuentas.php';
require_once 'class/class.pdfClientes.php';
require_once 'class/class.movimiento.php';
require_once 'class/class.user.php';
require_once "libs/lib.php";
require_once 'libs/app.php';
require_once 'config/config.php';
require_once 'config/privileges.php';

$app = new App();


?>