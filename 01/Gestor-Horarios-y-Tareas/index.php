<?php

require_once 'libs/database.php';
require_once 'libs/controller.php';
require_once 'libs/model.php';
require_once 'libs/view.php';

require_once 'class/class.employee.php';
require_once 'class/class.workinghours.php';
require_once 'class/class.project.php';

require_once 'fpdf/fpdf.php';
require_once 'class/class.user.php';
require_once "libs/lib.php";
require_once 'libs/app.php';
require_once 'config/config.php';
require_once 'config/privileges.php';

$app = new App();