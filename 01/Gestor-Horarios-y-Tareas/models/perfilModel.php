<?php

class PerfilModel extends Model
{
    # ---------------------------------------------------------------------------------    
    #    _____ ______ _______   _    _  _____ ______ _____    _____ _____  
    #   / ____|  ____|__   __| | |  | |/ ____|  ____|  __ \  |_   _|  __ \ 
    #  | |  __| |__     | |    | |  | | (___ | |__  | |__) |   | | | |  | |
    #  | | |_ |  __|    | |    | |  | |\___ \|  __| |  _  /    | | | |  | |
    #  | |__| | |____   | |    | |__| |____) | |____| | \ \   _| |_| |__| |
    #   \_____|______|  |_|     \____/|_____/|______|_|  \_\ |_____|_____/ 
    #
    # --------------------------------------------------------------------------------- 
    public function getUserId($id)
    {
        try {

            $sql = "SELECT * FROM users WHERE id= :id LIMIT 1";
            $conexion = $this->db->connect();
            $result = $conexion->prepare($sql);
            $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'classUser');
            $result->bindParam(":id", $id, PDO::PARAM_INT);
            $result->execute();

            return $result->fetch();

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }

    }

    # ---------------------------------------------------------------------------------
    #    
    #   _    _ _____  _____       _______ ______   _____         _____ _____ 
    #  | |  | |  __ \|  __ \   /\|__   __|  ____| |  __ \ /\    / ____/ ____|
    #  | |  | | |__) | |  | | /  \  | |  | |__    | |__) /  \  | (___| (___  
    #  | |  | |  ___/| |  | |/ /\ \ | |  |  __|   |  ___/ /\ \  \___ \\___ \ 
    #  | |__| | |    | |__| / ____ \| |  | |____  | |  / ____ \ ____) |___) |
    #   \____/|_|    |_____/_/    \_\_|  |______| |_| /_/    \_\_____/_____/ 
    #
    # ---------------------------------------------------------------------------------
    # Update password
    public function updatePass(classUser $user)
    {
        try {

            $password_encriptado = password_hash($user->password, CRYPT_BLOWFISH);
            $update = "
                        UPDATE users SET
                            password = :password
                        WHERE id = :id      
                        ";

            $conexion = $this->db->connect();
            $result = $conexion->prepare($update);

            $result->bindParam(':password', $password_encriptado, PDO::PARAM_STR, 50);
            $result->bindParam(':id', $user->id, PDO::PARAM_INT);

            $result->execute();

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }

    # ---------------------------------------------------------------------------------
    # __      __     _      _____ _____          _____    _   _          __  __ ______ 
    # \ \    / /\   | |    |_   _|  __ \   /\   |  __ \  | \ | |   /\   |  \/  |  ____|
    #  \ \  / /  \  | |      | | | |  | | /  \  | |__) | |  \| |  /  \  | \  / | |__   
    #   \ \/ / /\ \ | |      | | | |  | |/ /\ \ |  _  /  | . ` | / /\ \ | |\/| |  __|  
    #    \  / ____ \| |____ _| |_| |__| / ____ \| | \ \  | |\  |/ ____ \| |  | | |____ 
    #     \/_/    \_\______|_____|_____/_/    \_\_|  \_\ |_| \_/_/    \_\_|  |_|______|
    #
    # ---------------------------------------------------------------------------------
    public function validarName($name)
    {

        try {
            $sql = "SELECT * FROM users WHERE name = :name";

            # Conectamos con la base de datos
            $conexion = $this->db->connect();

            # Ejecutamos mediante prepare la consulta SQL
            $result = $conexion->prepare($sql);
            $result->bindParam(':name', $name, PDO::PARAM_STR);
            $result->execute();

            if ($result->rowCount() == 0)
                return TRUE;
            return FALSE;

        } catch (PDOException $e) {
            include_once ('template/partials/errorDB.php');
            exit();
        }


    }

    # ---------------------------------------------------------------------------------
    #    
    # __      __     _      _____ _____          _____    ______ __  __          _____ _      
    # \ \    / /\   | |    |_   _|  __ \   /\   |  __ \  |  ____|  \/  |   /\   |_   _| |     
    #  \ \  / /  \  | |      | | | |  | | /  \  | |__) | | |__  | \  / |  /  \    | | | |     
    #   \ \/ / /\ \ | |      | | | |  | |/ /\ \ |  _  /  |  __| | |\/| | / /\ \   | | | |     
    #    \  / ____ \| |____ _| |_| |__| / ____ \| | \ \  | |____| |  | |/ ____ \ _| |_| |____ 
    #     \/_/    \_\______|_____|_____/_/    \_\_|  \_\ |______|_|  |_/_/    \_\_____|______|
    #
    # ---------------------------------------------------------------------------------
    # Validate user by email
    public function validarEmail($email)
    {

        try {
            $sql = "SELECT * FROM users WHERE email = :email";

            # Conectamos con la base de datos
            $conexion = $this->db->connect();

            # Ejecutamos mediante prepare la consulta SQL
            $result = $conexion->prepare($sql);
            $result->bindParam(':email', $email, PDO::PARAM_STR);
            $result->execute();

            if ($result->rowCount() == 0)
                return TRUE;
            return FALSE;

        } catch (PDOException $e) {
            include_once ('template/partials/errorDB.php');
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
    # Update the profile
    public function update(classUser $user)
    {
        try {

            $update = "UPDATE users SET name = :name, email = :email WHERE id = :id LIMIT 1";

            $conexion = $this->db->connect();
            $result = $conexion->prepare($update);

            $result->bindParam(':name', $user->name, PDO::PARAM_STR, 50);
            $result->bindParam(':email', $user->email, PDO::PARAM_STR, 50);
            $result->bindParam(':id', $user->id, PDO::PARAM_INT);

            $result->execute();

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
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
    public function delete($id)
    {

        try {
            $delete = "DELETE FROM users WHERE id = :id";

            $conexion = $this->db->connect();
            $result = $conexion->prepare($delete);

            $result->bindParam(':id', $id, PDO::PARAM_INT);

            $result->execute();

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }
}