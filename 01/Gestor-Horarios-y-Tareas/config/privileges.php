<?php

// Variables only for admin use
$GLOBALS['admin'] = [1];

// Variables for admin and manager use
$GLOBALS['admin_manager'] = [1,2];

// Variables para uso de coordinador 
$GLOBALS['manager'] = [2];

// Variable for manager and organiser use
$GLOBALS['manager_organiser'] = [2,3];

// Variable for organiser use
$GLOBALS['organiser'] = [3];

// Variable for organiser and employee use
$GLOBALS['organiser_employee'] = [3,4];

// Variable for employee use
$GLOBALS['employee'] = [4];

# ------------------------------
// Everyone except employees
$GLOBALS['exceptEmp'] = [1,2,3];
$GLOBALS['exceptManager'] = [1,3,4];

// Variable for all 
$GLOBALS['all'] = [1,2,3,4];

// Variable for employee and admin 
$GLOBALS['emp_admin'] = [1,4];
# ------------------------------