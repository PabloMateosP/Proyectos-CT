<?php

class usersModel extends Model
{

    # ---------------------------------------------------------------------------------
    #
    #     _____ ______ _______ 
    #    / ____|  ____|__   __|
    #   | |  __| |__     | |   
    #   | | |_ |  __|    | |   
    #   | |__| | |____   | |   
    #    \_____|______|  |_|   
    #
    # ---------------------------------------------------------------------------------
    # Method get 
    # Select from table users
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
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------    
    #
    #    _____ ______ _______   _    _  _____ ______ _____     _____   ____  _      ______ 
    #   / ____|  ____|__   __| | |  | |/ ____|  ____|  __ \   |  __ \ / __ \| |    |  ____|
    #  | |  __| |__     | |    | |  | | (___ | |__  | |__) |  | |__) | |  | | |    | |__   
    #  | | |_ |  __|    | |    | |  | |\___ \|  __| |  _  /   |  _  /| |  | | |    |  __|  
    #  | |__| | |____   | |    | |__| |____) | |____| | \ \   | | \ \| |__| | |____| |____ 
    #   \_____|______|  |_|     \____/|_____/|______|_|  \_\  |_|  \_\\____/|______|______|
    #                                                                                     
    # --------------------------------------------------------------------------------- 
    # Método getUserRole
    # Select to take the role of an employee
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

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #    _____ _____  ______       _______ ______ 
    #   / ____|  __ \|  ____|   /\|__   __|  ____|
    #  | |    | |__) | |__     /  \  | |  | |__   
    #  | |    |  _  /|  __|   / /\ \ | |  |  __|  
    #  | |____| | \ \| |____ / ____ \| |  | |____ 
    #   \_____|_|  \_\______/_/    \_\_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Method create
    # Allow to create a new user
    public function create($nombre, $email, $password, $id_rol)
    {
        try {

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

            $id_usuario = $pdo->lastInsertId();

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

            require_once ("template/partials/errorDB.php");
            exit();

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
    # Method delete
    # Permit execute command DELETE at the table users
    public function delete($id)
    {
        try {

            $sql = "DELETE FROM users WHERE id = :id; ";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $error) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #   _    _ _____  _____       _______ ______ 
    #  | |  | |  __ \|  __ \   /\|__   __|  ____|
    #  | |  | | |__) | |  | | /  \  | |  | |__   
    #  | |  | |  ___/| |  | |/ /\ \ | |  |  __|  
    #  | |__| | |    | |__| / ____ \| |  | |____ 
    #   \____/|_|    |_____/_/    \_\_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Method update
    # Update the user's data
    public function update(classUser $user, $id, $id_Rol)
    {

        try {

            $sql = "UPDATE users 
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

            $pdoSt->bindParam(":name", $user->name, PDO::PARAM_STR, 30);
            $pdoSt->bindParam(":email", $user->email, PDO::PARAM_INT);
            $pdoSt->bindValue(":password", $user->password, PDO::PARAM_STR);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);

            $pdoSt->execute();

            $sql = "UPDATE 
                        roles_users 
                    SET
                        role_id = :role_id,
                        update_at = NOW()
                    WHERE
                        user_id = :user_id";

            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":role_id", $id_Rol, PDO::PARAM_INT);
            $pdoSt->bindParam(":user_id", $id, PDO::PARAM_INT);

            $pdoSt->execute();

        } catch (PDOException $error) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # --------------------------------------------------------------------------------- 
    #    _____ ______ _______   _    _  _____ ______ _____  
    #   / ____|  ____|__   __| | |  | |/ ____|  ____|  __ \ 
    #  | |  __| |__     | |    | |  | | (___ | |__  | |__) |
    #  | | |_ |  __|    | |    | |  | |\___ \|  __| |  _  / 
    #  | |__| | |____   | |    | |__| |____) | |____| | \ \ 
    #   \_____|______|  |_|     \____/|_____/|______|_|  \_\                                                   
    #                                                      
    # ---------------------------------------------------------------------------------
    # Método getUser
    # Get the user details
    public function getUser($id)
    {
        try {
            $sql = " 
                    SELECT     
                        id,
                        name,
                        email
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
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }


    # ---------------------------------------------------------------------------------
    #    
    #   _____  ______          _____  
    #  |  __ \|  ____|   /\   |  __ \ 
    #  | |__) | |__     /  \  | |  | |
    #  |  _  /|  __|   / /\ \ | |  | |
    #  | | \ \| |____ / ____ \| |__| |
    #  |_|  \_\______/_/    \_\_____/ 
    #
    # ---------------------------------------------------------------------------------
    # Method read 
    # Get the data of a user
    public function read($id)
    {

        try {
            $sql = " SELECT
                        id,
                        name,
                        email
                    FROM 
                        users
                    WHERE id =  :id;";

            # Conectar con la base de datos
            $conexion = $this->db->connect();

            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(':id', $id, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();

            return $pdoSt->fetch();

        } catch (PDOException $e) {
            include_once ('template/partials/errorDB.php');
            exit();
        }

    }


    # ---------------------------------------------------------------------------------
    #    
    #   __      __     _      _____ _____       _______ ______    _    _ _   _ _____ ____  _    _ ______   ______ __  __          _____ _      
    #   \ \    / /\   | |    |_   _|  __ \   /\|__   __|  ____|  | |  | | \ | |_   _/ __ \| |  | |  ____| |  ____|  \/  |   /\   |_   _| |     
    #    \ \  / /  \  | |      | | | |  | | /  \  | |  | |__     | |  | |  \| | | || |  | | |  | | |__    | |__  | \  / |  /  \    | | | |     
    #     \ \/ / /\ \ | |      | | | |  | |/ /\ \ | |  |  __|    | |  | | . ` | | || |  | | |  | |  __|   |  __| | |\/| | / /\ \   | | | |     
    #      \  / ____ \| |____ _| |_| |__| / ____ \| |  | |____   | |__| | |\  |_| || |__| | |__| | |____  | |____| |  | |/ ____ \ _| |_| |____ 
    #       \/_/    \_\______|_____|_____/_/    \_\_|  |______|   \____/|_| \_|_____\___\_\\____/|______| |______|_|  |_/_/    \_\_____|______|
    #
    # ---------------------------------------------------------------------------------
    # Validate the user email
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

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #    ____  _____  _____  ______ _____  
    #   / __ \|  __ \|  __ \|  ____|  __ \ 
    #  | |  | | |__) | |  | | |__  | |__) |
    #  | |  | |  _  /| |  | |  __| |  _  / 
    #  | |__| | | \ \| |__| | |____| | \ \ 
    #   \____/|_|  \_\_____/|______|_|  \_\
    #                             
    # ---------------------------------------------------------------------------------
    # Method order
    # Permit execute command ORDER BY at the table users
    public function order(int $criterio)
    {
        try {
            $sql = "SELECT 
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
            require_once ("template/partials/errorDB.php");
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
            require_once ("template/partials/errorDB.php");
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

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }

}