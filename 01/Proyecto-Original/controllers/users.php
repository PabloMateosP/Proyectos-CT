<?php

class Users extends Controller
{

    # Método principal. Muestra todos los users registrados
    public function render()
    {

        # Inicio sesión o continuo sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['notify'] = "Usuario sin autentificar";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']['mostrar']))) {
            $_SESSION['mensaje'] = "Usuario sin autentificar";
            header("location:" . URL . "index");

        } else {
            #comprobar si existe mensaje
            if (isset($_SESSION['mensaje'])) {
                $this->view->mensaje = $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);

            }

            $this->view->title = "Tabla users";
            $this->view->users = $this->model->get();
            $this->view->render("users/main/index");
        }
    }

    # Método mostrar
    # Mostramos los detalles de un usuario seleccionado
    function mostrar($param = [])
    {

        //Iniciar o continuar sesión
        session_start();

        # id del usuario
        $id = $param[0];

        //Comprobar si el usuario está identificado
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario No Autentificado";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']['mostrar']))) {
            $_SESSION['mensaje'] = "Operación sin privilegios";
            header('location:' . URL . 'users');
        } else {

            $this->view->title = "Formulario Mostrar Usuario";
            $this->view->user = $this->model->getUser($id);
            $this->view->rol = $this->model->getUserRole($id);

            $this->view->render("users/mostrar/index");
        }
    }

    # Método nuevo
    # Cargamos formulario nuevo usuario
    function nuevo($param = [])
    {
        # Iniciamos o continuamos la sesión
        session_start();

        //Comprobar si el usuario está identificado
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario No Autentificado";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']['nuevo']))) {
            $_SESSION['mensaje'] = "Operación sin privilegios";
            header('location:' . URL . 'users');
        } else {

            # Creamos un objeto vacío
            $this->view->usuario = new classUser();

            # Comprobamos si existen errores
            if (isset($_SESSION['error'])) {
                //Añadimos a la vista el mensaje de error
                $this->view->error = $_SESSION['error'];

                //Autorellenamos el formulario
                $this->view->usuario = unserialize($_SESSION['usuario']);
                $this->view->roles = $this->model->getRoles();

                //Recuperamos el array con los errores
                $this->view->errores = $_SESSION['errores'];

                //Recuperamos el valor del rol de la sesión y lo pasamos a la vista
                $this->view->rolSeleccionado = isset($_SESSION['roles']) ? $_SESSION['roles'] : null;

                //Una vez usadas las variables de sesión, las liberamos
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['usuario']);
            }

            //Añadimos a la vista la propiedad title
            $this->view->title = "Añadir - Gestión users";
            //Para generar la lista select dinámica de clientes
            $this->view->roles = $this->model->getRoles();

            //Cargamos la vista del formulario para añadir un nuevo usuario
            $this->view->render("users/nuevo/index");
        }
    }

    # Método create
    # Creamos una nuevo usuario
    function create($param = [])
    {
        //Iniciar sesión
        session_start();

        //Comprobar si el usuario está identificado
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario No Autentificado";

            header("location:" . URL . "login");
        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']['nuevo']))) {
            $_SESSION['mensaje'] = "Operación sin privilegios";
            header('location:' . URL . 'users');
        } else {

            //1. Seguridad. Saneamos los datos del formulario

            //Si se introduce un campo vacío, se le otorga "nulo"
            $nombre = filter_var($_POST['nombre'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($_POST['email'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $roles = filter_var($_POST['roles'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $contraseña = filter_var($_POST['contraseña'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);
            $confirmarContraseña = filter_var($_POST['confirmarContraseña'] ??= '', FILTER_SANITIZE_SPECIAL_CHARS);

            //2. Creamos el cliente con los datos saneados
            //Cargamos los datos del formulario
            $usuario = new classUser(
                null,
                $nombre,
                $email,
                $contraseña,
                $confirmarContraseña
            );

            # 3. Validación
            $errores = [];

            //Nombre: Obligatorio
            if (empty($nombre)) {
                $errores['nombre'] = 'El campo nombre es obligatorio';
            }

            //Email: Obligatorio, debe ser un email	, debe ser único	
            if (empty($email)) {
                $errores['email'] = 'El campo email es obligatorio';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = 'El formato del email no es correcto';
            } else if (!$this->model->validateUniqueEmail($email)) {
                $errores['email'] = 'El email ya existe';
            }

            //Contraseña: Obligatorio
            if (empty($contraseña)) {
                $errores['contraseña'] = 'El campo contraseña es obligatorio';
            } else if ($contraseña != $confirmarContraseña) {
                $errores['contraseña'] = 'Las contraseñas no coinciden';
            }

            //confirmarContraseña: Obligatorio, tiene que coincidir con el campo contraseña
            if (empty($confirmarContraseña)) {
                $errores['confirmarContraseña'] = 'El campo confirmar contraseña es obligatorio';
            } else if ($contraseña != $confirmarContraseña) {
                $errores['confirmarContraseña'] = 'Las contraseñas no coinciden';
            }

            # 4. Comprobar validación
            if (!empty($errores)) {
                //Errores de validación
                $_SESSION['usuario'] = serialize($usuario);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;
                $_SESSION['roles'] = $roles;

                //Redireccionamos de nuevo al formulario
                header('location:' . URL . 'users/nuevo/index');
            } else {
                # Añadimos el registro a la tabla
                $this->model->create($nombre, $email, $contraseña, $roles);

                $_SESSION['mensaje'] = "Se ha creado el usuario correctamente.";

                // Redireccionamos a la vista users
                header("Location:" . URL . "users");
            }
        }
    }


    # Método eliminar. Eliminamos el usuario elegido 
    public function delete($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']['delete']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {
            $id = $param[0];
            $this->model->delete($id);
            $_SESSION['mensaje'] = 'Usuario eliminado correctamente';

            header("Location:" . URL . "users");
        }
    }

    public function editar($param = [])
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if (!in_array($_SESSION['id_rol'], $GLOBALS['admin']['editar'])) {
            $_SESSION['mensaje'] = "Operación sin privilegios";

            header('location:' . URL . 'users');

        } else {

            $this->view->roles = $this->model->getRoles();

            # obtengo el id del usuario que voy a editar
            $id = $param[0];

            $this->view->id = $id;
            $this->view->title = "Formulario  editar usuario";
            $this->view->user = $this->model->read($id);

            $this->view->user = $this->model->getUser($this->view->id);

            $this->view->rol = $this->model->getUserRole($this->view->id);

            # Comprobamos si hay errores -> esta variable se crea al lanzar un error de validacion
            if (isset($_SESSION['error'])) {
                // rescatemos el mensaje
                $this->view->error = $_SESSION['error'];

                // Autorellenamos el formulario
                $this->view->user = unserialize($_SESSION['user']);

                // Recupero array de errores específicos
                $this->view->errores = $_SESSION['errores'];

                // debemos liberar las variables de sesión ya que su cometido ha sido resuelto
                unset($_SESSION['error']);
                unset($_SESSION['errores']);
                unset($_SESSION['user']);
                // Si estas variables existen cuando no hay errores, entraremos en los bloques de error en las condicionales
            }

            $this->view->render("users/editar/index");
        }
    }

    # Método update.
    # Actualiza los detalles de un cliente a partir de los datos del formulario de edición
    public function update($param = [])
    {

        #Iniciar Sesión
        session_start();

        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['clientes']['edit']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {

            #1.Seguridad. Saneamos los datos del formulario
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

            #Obtengo el objeto cliente original
            $user_orig = $this->model->read($id);

            $errores = [];

            //Name: obligatorio, maximo 20 caracteres
            if (strcmp($user->name, $user_orig->name) !== 0) {

                if (empty($name)) {
                    $errores['name'] = 'El campo nombre es obligatorio';
                } else if (strlen($name) > 20) {
                    $errores['name'] = 'El campo nombre es demasiado largo';

                }
            }

            //Email: obligatorio, formato válido y clave secundaria
            if (strcmp($user->email, $user_orig->email) !== 0) {

                if (empty($email)) {
                    $errores['email'] = 'El campo email es obligatorio';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errores['email'] = 'El formato introducido es incorrecto';
                } else if (!$this->model->validateUniqueEmail($email)) {
                    $errores['email'] = 'Email ya registrado';

                }
            }

            # Obtenemos el id del rol del usuario
            $idRol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);

            #4. Comprobar validacion

            if (!empty($errores)) {
                //errores de validacion
                $_SESSION['user'] = serialize($user);
                $_SESSION['error'] = 'Formulario no validado';
                $_SESSION['errores'] = $errores;

                # Redirigimos al main de clientes
                header('location:' . URL . 'users/editar/' . $id);
            } else {
                //crear alumno
                # Añadir registro a la tabla
                $this->model->update($user, $id, $idRol);

                #Mensaje
                $_SESSION['mensaje'] = "Usuario actualizado correctamente";

                # Redirigimos al main de alumnos
                header('location:' . URL . 'users');
            }
        }
    }

    public function ordenar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']['order']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {
            $criterio = $param[0];
            $this->view->title = "Tabla users";
            $this->view->users = $this->model->order($criterio);
            $this->view->render("users/main/index");
        }

    }

    # Método buscar
    # Permite buscar los registros de users que cumplan con el patrón especificado en la expresión
    # de búsqueda
    public function buscar($param = [])
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $_SESSION['mensaje'] = "Usuario debe autentificarse";

            header("location:" . URL . "login");

        } else if ((!in_array($_SESSION['id_rol'], $GLOBALS['admin']['filter']))) {
            $_SESSION['mensaje'] = "Operación sin privilegio";
            header("location:" . URL . "clientes");
        } else {
            $expresion = $_GET["expresion"];
            $this->view->title = "Tabla users";
            $this->view->users = $this->model->filter($expresion);
            $this->view->render("users/main/index");
        }
    }

}