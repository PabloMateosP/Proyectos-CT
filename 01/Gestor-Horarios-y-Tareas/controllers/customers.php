<?php

class Customers extends Controller
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
        # Began or continue session
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

            $this->view->title = "Customers Table";
            $this->view->customers = $this->model->get();
            $this->view->render("customers/main/index");

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
    # "New" Method. Show a formulary to add new customers
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
            header("location:" . URL . "customers");

        } else {

            # Create and instance of classcustomer
            $this->view->customer = new classCustomer();

            # Check if there are errors -> this variable is created when a validation error occurs
            if (isset($_SESSION['error'])) {
                # Let's retrieve the message
                $this->view->error = $_SESSION['error'];

                # Autopopulate the form
                $this->view->customer = unserialize($_SESSION['customer']);

                # Retrieve array of specific errors
                $this->view->errores = $_SESSION['errores'];

                # We must unset the session variables as their purpose has been resolved
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['customers']);

                # If these variables exist when there are no errors, we will enter the error blocks in the conditionals
            }

            $this->view->title = "Form new customer";
            $this->view->render("customers/new/index");
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
    # Allows adding a new customer based on the form details.
    public function create($param = [])
    {
        # Start Session
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['message'] = "User must authenticate";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])) {

            $_SESSION['message'] = "Operation without privileges";
            header("location:" . URL . "customers");

        } else {

            # --
            # 1. Security. Sanitize form data
            # --

            $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $phone = filter_var($_POST['phone'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $city = filter_var($_POST['city'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $address = filter_var($_POST['address'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);

            # --
            # 2. Create customer with sanitized data
            # --

            $customer = new classcustomer(
                null,
                $name,
                $phone,
                $city,
                $address,
                $email,
                null,
                null
            );

            # --
            # 3. Validation
            # --

            $errores = array();

            # name: max 20 characters
            if (empty($name)) {
                $errores['name'] = 'The name field is required';
            } else if (strlen($name) > 20) {
                $errores['name'] = 'The name field is too long';
            }

            # phone: max 9 characters
            if (empty($phone)) {
                $errores['phone'] = 'The phone field is required';
            } else if (strlen($phone) > 9) {
                $errores['phone'] = 'The phone field is too long';
            } else if (!$this->model->validateUniquePhone($phone)) {
                $errores['phone'] = 'The phone is already registered';
            }

            # City: max 20 characters
            if (empty($city)) {
                $errores['city'] = 'The city field is required';
            } else if (strlen($city) > 20) {
                $errores['city'] = 'The city field is too long';
            }

            # address
            if (empty($address)) {
                $errores['address'] = 'The Last Name field is required';
            } else if (strlen($address) > 45) {
                $errores['address'] = 'The Last Name field is too long';
            }

            # Email: must be validated and unique
            if (empty($email)) {
                $errores['email'] = 'The email field is required';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = 'The format entered is incorrect';
            } else if (!$this->model->validateUniqueEmail($email)) {
                $errores['email'] = 'The email is already registered';
            }

            # --
            # 4. Check Validation
            # --

            if (!empty($errores)) {

                # Validation errors 
                $_SESSION['customer'] = serialize($customer);
                $_SESSION['error'] = 'Invalid form';
                $_SESSION['errores'] = $errores;

                header('location:' . URL . 'customers/new');

            } else {

                # Add customer
                $this->model->create($customer);

                # Message
                $_SESSION['message'] = "customer created correctly";

                # Redirect
                header('location:' . URL . 'customers');

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
    # Allow the elimination of an employee
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "customers");
        } else {
            $id = $param[0];

            $this->model->deleteRelation($id);

            $this->model->deleteRelationProj($id);

            $this->model->delete($id);

            $_SESSION['mensaje'] = 'Customer delete correctly';

            header("Location:" . URL . "customers");
        }
    }

    # ---------------------------------------------------------------------------------
    #   ______  _____  _____  _______ 
    #  |  ____||  __ \|_   _||__   __|
    #  | |__   | |  | | | |     | |   
    #  |  __|  | |  | | | |     | |   
    #  | |____ | |__| |_| |_    | |   
    #  |______||_____/|_____|   |_|
    #
    # ---------------------------------------------------------------------------------
    # Method edit. 
    # Show a form to edit a customer
    public function edit($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']))) {
            $_SESSION['mensaje'] = "Operation without privileges";

            header('location:' . URL . 'customers');

        } else {
            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Form customer edit";
            $this->view->customer = $this->model->read($id);

            // Check if there are errors -> this variable is created when a validation error is thrown
            if (isset($_SESSION['error'])) {
                // Let's rescue the message
                $this->view->error = $_SESSION['error'];

                // We autofill the form
                $this->view->customer_ = unserialize($_SESSION['customer']);

                // I recover array of specific errors
                $this->view->errores = $_SESSION['errores'];

                // We must free the session variables since their purpose has been resolved
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['customer']);
                // If these variables exist when there are no errors, we will enter the error blocks in the conditionals
            }

            $this->view->render("customers/edit/index");
        }
    }


    public function update($param = [])
    {
        // Start Session
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "User must be authenticated";
            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']))) {
            $_SESSION['mensaje'] = "Operation without privileges";
            header("location:" . URL . "customers");
        } else {
            // Sanitize data
            $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            $phone = filter_var($_POST['phone'] ?? '', FILTER_SANITIZE_NUMBER_INT);
            $city = filter_var($_POST['city'] ?? '', FILTER_SANITIZE_STRING);
            $address = filter_var($_POST['address'] ?? '', FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            
            
            // Create a project object
            $customer = new classCustomer(
                null,
                $name,
                $phone,
                $city,
                $address,
                $email,
                null,
                null
            );

            $id = $param[0];

            // Get original project data
            $customer_orig = $this->model->read($id);

            // 3. Validation
            // Only if necessary
            // Only in case when the field is modified 

            $errors = [];

            // name
            if (strcmp($customer->name, $customer_orig->name) !== 0) {
                if (empty($name)) {
                    $errors['customer'] = 'The field name is required';
                } else if (strlen($name) > 20) {
                    $errors['customer'] = 'The field name is too long';
                }
            }

            // phone
            if (strcmp($customer->phone, $customer_orig->phone) !== 0) {
                if (empty($phone)) {
                    $errors['phone'] = 'The field phone is required';
                } else if (strlen($phone) > 9) {
                    $errors['phone'] = 'The field phone is too long';
                }
            }

            // city
            if (strcmp($customer->city, $customer_orig->city) !== 0) {
                if (empty($city)) {
                    $errors['city'] = 'The field city is required';
                } else if (strlen($city) > 20) {
                    $errors['city'] = 'The field city is too long';
                }
            }

            // address
            if (strcmp($customer->address, $customer_orig->address) !== 0) {
                if (empty($address)) {
                    $errors['address'] = 'The field address is required';
                } else if (strlen($address) > 10) {
                    $errors['address'] = 'The field address is too long';
                }
            }

            // email
            if (strcmp($customer->email, $customer_orig->email) !== 0) {
                if (empty($email)) {
                    $errors['email'] = 'The field email is required';
                } else if (strlen($email) > 45) {
                    $errors['email'] = 'The field email is too long';
                }
            }

            if (!empty($errors)) {

                // Validation's error
                $_SESSION['customer'] = serialize($customer);
                $_SESSION['error'] = 'Form not validated';
                $_SESSION['errores'] = $errors;

                // Redirect to workingHour's main
                header('location:' . URL . 'customers/edit/' . $id);

            } else {

                // Update project data
                $this->model->update($customer, $id);

                // Message
                $_SESSION['mensaje'] = "Customer updated correctly";

                // Redirect to projects main
                header('location:' . URL . 'customers');

            }
        }
    }


}