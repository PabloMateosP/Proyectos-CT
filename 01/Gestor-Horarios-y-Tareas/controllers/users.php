<?php

class Users extends Controller
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
    # Main method. Charge all the users in the database.
    public function render()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Unauthenticated user";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "index");

        } else {

            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);

            }

            $this->view->title = "Users";
            $this->view->users = $this->model->get();
            $this->view->render("users/main/index");
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #    _____ _    _  ______          __
    #   / ____| |  | |/ __ \ \        / /
    #  | (___ | |__| | |  | \ \  /\  / / 
    #   \___ \|  __  | |  | |\ \/  \/ /  
    #   ____) | |  | | |__| | \  /\  /   
    #  |_____/|_|  |_|\____/   \/  \/    
    #
    # ---------------------------------------------------------------------------------
    # Method show 
    # Show a form to watch the information about a user
    function show($param = [])
    {
        session_start();

        $id = $param[0];

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Unauthenticated user";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']))) {

            $_SESSION['mensaje'] = "Unprivileged operation";
            header('location:' . URL . 'users');

        } else {

            $this->view->title = "Form Show Users Detail";
            $this->view->user = $this->model->getUser($id);
            $this->view->rol = $this->model->getUserRole($id);

            $this->view->render("users/show/index");
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
    # "New" method. Form to add an new user
    # Show a form to create a new user
    function new($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Unauthenticated user";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header('location:' . URL . 'users');
        } else {

            $this->view->usuario = new classUser();

            if (isset($_SESSION['error'])) {

                $this->view->error = $_SESSION['error'];

                $this->view->usuario = unserialize($_SESSION['usuario']);
                $this->view->roles = $this->model->getRoles();

                $this->view->errores = $_SESSION['errores'];

                $this->view->rolSeleccionado = isset($_SESSION['roles']) ? $_SESSION['roles'] : null;

                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['usuario']);
            }

            $this->view->title = "Add User";

            $this->view->roles = $this->model->getRoles();

            $this->view->render("users/new/index");
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
    # Allow to add a new user
    function create($param = [])
    {
        // Start session
        session_start();

        // Check if the user is authenticated
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Unauthenticated user";
            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header('location:' . URL . 'users');
        } else {

            // 1. Security. Sanitize the form data

            // If a field is empty, it is given "null"
            $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $roles = filter_var($_POST['roles'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_var($_POST['password'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $passwordConfirm = filter_var($_POST['passwordConfirm'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            // 2. Create the user with the sanitized data
            // Load the form data
            $usuario = new classUser(
                null,
                $name,
                $email,
                $password,
                $passwordConfirm
            );

            // 3. Validation
            $errors = [];

            // name: required
            if (empty($name)) {
                $errors['name'] = 'The name field is required';
            }

            // Email: required and unique	
            if (empty($email)) {
                $errors['email'] = 'The email field is required';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'The email format is not correct';
            } else if (!$this->model->validateUniqueEmail($email)) {
                $errors['email'] = 'The email has already been registered';
            }

            // Roles: required
            if (empty($roles)) {
                $errors['roles'] = 'The roles field is required';
            }

            // password: required
            if (empty($password)) {
                $errors['password'] = 'The password field is required';
            } else if ($password != $passwordConfirm) {
                $errors['password'] = 'Both passwords do not match';
            }

            // passwordConfirm: required, must match with password Confirm
            if (empty($passwordConfirm)) {
                $errors['passwordConfirm'] = 'The confirm password field is required';
            } else if ($password != $passwordConfirm) {
                $errors['passwordConfirm'] = 'Both passwords do not match';
            }

            // 4. Check validation
            if (!empty($errors)) {

                $_SESSION['usuario'] = serialize($usuario);
                $_SESSION['error'] = 'Form not validated';
                $_SESSION['errores'] = $errors;
                $_SESSION['roles'] = $roles;

                header('location:' . URL . 'users/new/index/');

            } else {
                // Add the record to the table
                $this->model->create($name, $email, $password, $roles);

                $_SESSION['mensaje'] = "User has been created correctly.";

                // Redirect to the users view
                header("Location:" . URL . "users/");
            }
        }
    }

    # ---------------------------------------------------------------------------------   
    #  
    #   _____  ______ _      ______ _______ ______ 
    #  |  __ \|  ____| |    |  ____|__   __|  ____|
    #  | |  | | |__  | |    | |__     | |  | |__   
    #  | |  | |  __| | |    |  __|    | |  |  __|  
    #  | |__| | |____| |____| |____   | |  | |____ 
    #  |_____/|______|______|______|  |_|  |______|    
    #                               
    # ---------------------------------------------------------------------------------
    # Method delete. 
    # Allow to delete an user
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {

            $_SESSION['mensaje'] = "User must authenticated";

            header("location:" . URL . "login/");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']))) {

            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "users/");

        } else {
            $id = $param[0];
            $this->model->delete($id);
            $_SESSION['mensaje'] = 'User delete correctly';

            header("Location:" . URL . "users/");
        }
    }

    # ---------------------------------------------------------------------------------
    #  ______  _____  _____  _______ 
    #  |  ____||  __ \|_   _||__   __|
    #  | |__   | |  | | | |     | |   
    #  |  __|  | |  | | | |     | |   
    #  | |____ | |__| |_| |_    | |   
    #  |______||_____/|_____|   |_|
    #
    # ---------------------------------------------------------------------------------
    # Method edit. 
    # Show a form to edit an user
    public function edit($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Unauthenticated user";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['admin'])) {
            $_SESSION['mensaje'] = "Unprivileged operation";

            header('location:' . URL . 'users');

        } else {

            $this->view->roles = $this->model->getRoles();

            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Form edit user";

            $this->view->user = $this->model->read($id);

            $this->view->rol = $this->model->getUserRole($id);

            if (isset($_SESSION['error'])) {
                $this->view->error = $_SESSION['error'];

                $this->view->user = unserialize($_SESSION['user']);

                $this->view->errores = $_SESSION['errores'];

                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['user']);
            }

            $this->view->render("users/edit/index");
        }
    }


    # ---------------------------------------------------------------------------------
    #
    #   _    _  _____   _____         _______  ______ 
    #  | |  | ||  __ \ |  __ \    /\ |__   __||  ____|
    #  | |  | || |__) || |  | |  /  \   | |   | |__   
    #  | |  | ||  ___/ | |  | | / /\ \  | |   |  __|  
    #  | |__| || |     | |__| |/ ____ \ | |   | |____ 
    #   \____/ |_|     |_____//_/    \_\|_|   |______|
    #                                               
    # ---------------------------------------------------------------------------------
    # Método update.
    # Update the table of the table users
    public function update($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Unauthenticated user";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['edit']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {

            #1.Security. 
            $password = filter_var($_POST['password'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $name = filter_var($_POST['name'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_EMAIL);

            $password_encriptado = password_hash($password, PASSWORD_BCRYPT);

            $user = new classUser(
                null,
                $name,
                $email,
                $password_encriptado,
                null,
                null
            );
            $id = $param[0];

            $user_orig = $this->model->read($id);

            $errores = [];

            //Name: required
            if (strcmp($user->name, $user_orig->name) !== 0) {

                if (empty($name)) {
                    $errores['name'] = 'The field name is required';
                } else if (strlen($name) > 20) {
                    $errores['name'] = 'The field name is too long';

                }
            }

            //Email: required, valide format and sencondary key
            if (strcmp($user->email, $user_orig->email) !== 0) {

                if (empty($email)) {
                    $errores['email'] = 'The field email is required';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errores['email'] = 'The email format is not correct';
                } else if (!$this->model->validateUniqueEmail($email)) {
                    $errores['email'] = 'The email has already been registered';

                }
            }

            $idRol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);

            if (!empty($errores)) {

                $_SESSION['user'] = serialize($user);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;


                header('location:' . URL . 'users/edit/' . $id);
            } else {

                $this->model->update($user, $id, $idRol);

                $_SESSION['mensaje'] = "User update correctly";

                header('location:' . URL . 'users');
            }
        }
    }

    # ---------------------------------------------------------------------------------
    #    ____   _____   _____   ______  _____  
    #   / __ \ |  __ \ |  __ \ |  ____||  __ \ 
    #  | |  | || |__) || |  | || |__   | |__) |
    #  | |  | ||  _  / | |  | ||  __|  |  _  / 
    #  | |__| || | \ \ | |__| || |____ | | \ \ 
    #   \____/ |_|  \_\|_____/ |______||_|  \_\
    #
    # ---------------------------------------------------------------------------------
    # Method order
    # Allow order the table users
    public function order($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Unauthenticated user";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "users");
        } else {
            $criterio = $param[0];
            $this->view->title = "Table users";
            $this->view->users = $this->model->order($criterio);
            $this->view->render("users/main/index");
        }

    }

    # ---------------------------------------------------------------------------------
    #
    #     _____ ______          _____   _____ _    _ 
    #    / ____|  ____|   /\   |  __ \ / ____| |  | |
    #   | (___ | |__     /  \  | |__) | |    | |__| |
    #    \___ \|  __|   / /\ \ |  _  /| |    |  __  |
    #    ____) | |____ / ____ \| | \ \| |____| |  | |
    #   |_____/|______/_/    \_\_|  \_\\_____|_|  |_|
    #
    # ---------------------------------------------------------------------------------
    # Method buscar
    # Search for user records that match the pattern specified in the search expression
    public function search($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Unauthenticated user";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']))) {
            $_SESSION['mensaje'] = "Unprivileged operation";
            header("location:" . URL . "users");
        } else {
            $expresion = $_GET["expresion"];
            $this->view->title = "Users";
            $this->view->users = $this->model->filter($expresion);
            $this->view->render("users/main/index");
        }
    }

}