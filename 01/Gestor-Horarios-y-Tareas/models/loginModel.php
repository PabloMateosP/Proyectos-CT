<?php
class loginModel extends Model
{

    # ---------------------------------------------------------------------------------
    #     
    #    _____ ______ _______ _    _  _____ ______ _____  __  __          _____ _      
    #   / ____|  ____|__   __| |  | |/ ____|  ____|  __ \|  \/  |   /\   |_   _| |     
    #  | |  __| |__     | |  | |  | | (___ | |__  | |__) | \  / |  /  \    | | | |     
    #  | | |_ |  __|    | |  | |  | |\___ \|  __| |  _  /| |\/| | / /\ \   | | | |     
    #  | |__| | |____   | |  | |__| |____) | |____| | \ \| |  | |/ ____ \ _| |_| |____ 
    #   \_____|______|  |_|   \____/|_____/|______|_|  \_\_|  |_/_/    \_\_____|______|
    # 
    # ---------------------------------------------------------------------------------
    # Return an user get by his email
    public function getUserEmail($email)
    {
        try {

            $sql = "SELECT * FROM Users WHERE email= :email LIMIT 1";
            $pdo = $this->db->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }


    # ---------------------------------------------------------------------------------
    #    _____ ______ _______ ______ __  __ _____  _      ______     ________ ______ _____ _____  
    #   / ____|  ____|__   __|  ____|  \/  |  __ \| |    / __ \ \   / /  ____|  ____|_   _|  __ \ 
    #  | |  __| |__     | |  | |__  | \  / | |__) | |   | |  | \ \_/ /| |__  | |__    | | | |  | |
    #  | | |_ |  __|    | |  |  __| | |\/| |  ___/| |   | |  | |\   / |  __| |  __|   | | | |  | |
    #  | |__| | |____   | |  | |____| |  | | |    | |___| |__| | | |  | |____| |____ _| |_| |__| |
    #   \_____|______|  |_|  |______|_|  |_|_|    |______\____/  |_|  |______|______|_____|_____/ 
    #
    # ---------------------------------------------------------------------------------
    
    public function getEmployeeId($email)
    {
        try {
            $sql = "SELECT * FROM Employees where email = :email LIMIT 1";
            $pdo = $this->db->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }

    # --------------------------------------------------------------------------------- 
    #   
    #   _____ ______ _______   _    _  _____ ______ _____    _____ _____    _____  ______ _____  ______ _____ _      
    #   / ____|  ____|__   __| | |  | |/ ____|  ____|  __ \  |_   _|  __ \  |  __ \|  ____|  __ \|  ____|_   _| |     
    #  | |  __| |__     | |    | |  | | (___ | |__  | |__) |   | | | |  | | | |__) | |__  | |__) | |__    | | | |     
    #  | | |_ |  __|    | |    | |  | |\___ \|  __| |  _  /    | | | |  | | |  ___/|  __| |  _  /|  __|   | | | |     
    #  | |__| | |____   | |    | |__| |____) | |____| | \ \   _| |_| |__| | | |    | |____| | \ \| |     _| |_| |____ 
    #   \_____|______|  |_|     \____/|_____/|______|_|  \_\ |_____|_____/  |_|    |______|_|  \_\_|    |_____|______|
    #
    # ---------------------------------------------------------------------------------
    public function getUserIdPerfil($id)
    {

        try {

            $selectSQL = "SELECT 
                        ru.role_id
                    FROM
                        users u
                    INNER JOIN
                        roles_users ru ON u.id = ru.user_id
                    WHERE
                        u.id = :id
                    LIMIT 1";
            $pdo = $this->db->connect();
            $resultado = $pdo->prepare($selectSQL);
            $resultado->setFetchMode(PDO::FETCH_OBJ);
            $resultado->bindParam(':id', $id, PDO::PARAM_INT);
            $resultado->execute();
            return $resultado->fetch()->role_id;

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }

    # ---------------------------------------------------------------------------------
    #
    #     _____ ______ _______   _    _  _____ ______ _____    _____  ______ _____  ______ _____ _      
    #    / ____|  ____|__   __| | |  | |/ ____|  ____|  __ \  |  __ \|  ____|  __ \|  ____|_   _| |     
    #   | |  __| |__     | |    | |  | | (___ | |__  | |__) | | |__) | |__  | |__) | |__    | | | |     
    #   | | |_ |  __|    | |    | |  | |\___ \|  __| |  _  /  |  ___/|  __| |  _  /|  __|   | | | |     
    #   | |__| | |____   | |    | |__| |____) | |____| | \ \  | |    | |____| | \ \| |     _| |_| |____ 
    #    \_____|______|  |_|     \____/|_____/|______|_|  \_\ |_|    |______|_|  \_\_|    |_____|______|
    #
    # ---------------------------------------------------------------------------------
    public function getUserPerfil($id)
    {

        try {

            $selectSQL = "SELECT 
                        name
                    FROM
                        roles
                    WHERE
                        id = :id
                    LIMIT 1";

            $pdo = $this->db->connect();
            $resultado = $pdo->prepare($selectSQL);
            $resultado->setFetchMode(PDO::FETCH_OBJ);
            $resultado->bindParam(':id', $id, PDO::PARAM_INT);
            $resultado->execute();
            return $resultado->fetch()->name;

        } catch (PDOException $e) {

            include_once ('template/partials/errorDB.php');
            exit();

        }
    }
}