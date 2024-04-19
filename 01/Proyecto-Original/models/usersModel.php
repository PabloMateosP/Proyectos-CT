<?php

class usersModel extends Model
{


    # Método Get
    # Consulta SELECT a la tabla usuarios 
    public function get()
    {
        try {
            $sql = "
                SELECT 
                    id,
                    name, 
                    email, 
                    created_at,
                    update_at
                FROM 
                    users
                ORDER BY id";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once("template/partials/errorDB.php");
            exit();
        }
    }

    # Método getUserRole
    # Consulta SELECT para recoger el rol de un usuario
    public function getUserRole($id)
    {
        try {
            $sql = "SELECT 
                        roles.id, 
                        roles.name
                    FROM 
                        roles
                    INNER JOIN 
                        roles_users 
                    ON roles.id = roles_users.role_id
                    INNER JOIN 
                        users 
                    ON roles_users.user_id = users.id
                    WHERE users.id = :id";

            $pdo = $this->db->connect();
            $pdoSt = $pdo->prepare($sql);
            $pdoSt->bindParam(':id', $id, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();

            return $pdoSt->fetch();

        } catch (PDOException $e) {

            require_once("template/partials/errorDB.php");
            exit();

        }
    }

    # Método create
    # Insertamos un nuevo registro 
    public function create($nombre, $email, $password, $id_rol)
    {
        try {

            //Encriptamos la contraseña del usuario 
            $password = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO 
                        users 
                    VALUES (
                        null,
                        :nombre,
                        :email,
                        :pass,
                        default,
                        now())";

            $pdo = $this->db->connect();
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $password, PDO::PARAM_STR);

            $stmt->execute();

            //Guardamos en una variable el valor id de este último registro insertado
            $id_usuario = $pdo->lastInsertId();

            // Asignamos rol
            $sql = "INSERT INTO 
                        roles_users 
                    VALUES (
                        null,
                        :user_id,
                        :role_id,
                        default,
                        default)";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':role_id', $id_rol, PDO::PARAM_INT);
            $stmt->execute();

        } catch (PDOException $e) {

            require_once("template/partials/errorDB.php");
            exit();
            
        }
    }

    # Método delete
    # Permite ejecutar comando DELETE de un usuario
    public function delete($id)
    {
        try {

            $sql = " DELETE FROM users WHERE id = :id; ";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $error) {
            require_once("template/partials/errorDB.php");
            exit();
        }
    }


    # Método update 
    # Actualiza los detalles del usuario 
    public function update(classUser $user, $id, $id_Rol)
    {

        try {

            $sql = "
                UPDATE users 
                SET
                    name=:name,
                    email=:email,
                    password=:password,
                    update_at = now()
                WHERE 
                    id=:id
                LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            // Vinculamos los parámetros
            $pdoSt->bindParam(":name", $user->name, PDO::PARAM_STR, 30);
            $pdoSt->bindParam(":email", $user->email, PDO::PARAM_INT);
            $pdoSt->bindValue(":password", $user->password, PDO::PARAM_STR);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);

            // Ejecutamos la consulta
            $pdoSt->execute();

            // Actualizamos el rol del usuario
            $sql = "UPDATE 
                        roles_users 
                    SET
                        role_id = :role_id,
                        update_at = NOW()
                    WHERE
                        user_id = :user_id";

            $pdoSt = $conexion->prepare($sql);

            // Vinculamos los parámetros
            $pdoSt->bindParam(":role_id", $id_Rol, PDO::PARAM_INT);
            $pdoSt->bindParam(":user_id", $id, PDO::PARAM_INT);

            $pdoSt->execute();

        } catch (PDOException $error) {
            require_once("template/partials/errorDB.php");
            exit();
        }

    }

    # Método getUser
    # Obtenemos los detalles de un cliente a partir del id
    public function getUser($id)
    {
        try {
            $sql = " 
                    SELECT     
                        id,
                        name,
                        email,
                        password
                    FROM  
                        users  
                    WHERE
                        id = :id";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt->fetch();

        } catch (PDOException $e) {
            require_once("template/partials/errorDB.php");
            exit();
        }
    }

    public function read($id)
    {

        try {
            $sql = " SELECT
            id,
            name,
            email,
            password
        FROM 
            users
        WHERE id =  :id;
                ";

            # Conectar con la base de datos
            $conexion = $this->db->connect();

            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(':id', $id, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();

            return $pdoSt->fetch();

        } catch (PDOException $e) {
            include_once('template/partials/errorDB.php');
            exit();
        }

    }

    public function validateUniqueEmail($email)
    {
        try {

            $sql = "
                SELECT * FROM users
                WHERE email = :email
            ";

            # Conectar con la base de datos
            $conexion = $this->db->connect();

            $pdostmt = $conexion->prepare($sql);

            $pdostmt->bindParam(':email', $email, PDO::PARAM_STR, 50);
            $pdostmt->execute();

            if ($pdostmt->rowCount() != 0) {
                return FALSE;
            }

            return TRUE;

        } catch (PDOException $e) {

            include_once('template/partials/errorDB.php');
            exit();

        }
    }

    public function order(int $criterio)
    {
        try {
            $sql = "
                    SELECT 
                        id,
                        name,
                        email,
                        created_at,
                        update_at
                    FROM 
                        users
                    ORDER BY
                        :criterio";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":criterio", $criterio, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);

            $pdoSt->execute();

            return $pdoSt;
        } catch (PDOException $e) {
            require_once("template/partials/errorDB.php");
            exit();
        }
    }

    # Método filter
    # Permite filtar la tabla usuarios a partir de una expresión de búsqueda
    public function filter($expresion)
    {
        try {

            $sql = "
                    SELECT 
                        id,
                        name,
                        email,
                        created_at,
                        update_at
                    FROM 
                        users
                    WHERE 
                        concat_ws(  
                                    ' ',
                                    id,
                                    name,
                                    email,
                                    created_at,
                                    update_at
                                )
                        LIKE 
                                :expresion
                    
                    ORDER BY id ASC";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            # enlazamos parámetros con variable
            $expresion = "%" . $expresion . "%";
            $pdoSt->bindValue(':expresion', $expresion, PDO::PARAM_STR);

            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once("template/partials/errorDB.php");
            exit();
        }
    }

    # Hacemos un select de los roles de un usuario 
    public function getRoles()
    {
        try {
            $sql = "SELECT * from roles";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {

            require_once("template/partials/errorDB.php");
            exit();

        }
    }

}