<?php

require_once 'libs/database.php';
require_once 'libs/controller.php';
require_once 'libs/model.php';
require_once 'libs/view.php';

// Clases
require_once 'class/class.employee.php';
require_once 'class/class.workinghours.php';
require_once 'class/class.project.php';
require_once 'class/class.projectManagers.php';
require_once 'class/class.task.php';
require_once 'class/class.customer.php';
require_once 'class/class.timeCode.php';
require_once 'class/class.user.php';

// --- Exportar PDF ---
require_once 'fpdf/fpdf.php';
require_once 'class/class.pdfWorkingHours.php';

require_once 'libs/app.php';
require_once 'config/config.php';
require_once 'config/privileges.php';

$app = new App();