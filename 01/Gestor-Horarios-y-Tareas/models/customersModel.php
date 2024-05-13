<?php


class customersModel extends Model
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
    # Method to take the information about all the customers
    public function get()
    {
        try {
            $sql = "

            SELECT 
                id,
                name,
                phone,
                city,
                address,
                email
            FROM 
                customers
            ORDER BY id;

            ";

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
    #    _____ _____  ______       _______ ______ 
    #   / ____|  __ \|  ____|   /\|__   __|  ____|
    #  | |    | |__) | |__     /  \  | |  | |__   
    #  | |    |  _  /|  __|   / /\ \ | |  |  __|  
    #  | |____| | \ \| |____ / ____ \| |  | |____ 
    #   \_____|_|  \_\______/_/    \_\_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Method create
    # Allow to create a new customer
    public function create(classCustomer $customer)
    {
        try {
            $sql = " INSERT INTO 
                        customers
                        (
                            name,  
                            phone, 
                            city, 
                            address, 
                            email
                        ) 
                        VALUES 
                        ( 
                            :name,
                            :phone,
                            :city,
                            :address,
                            :email
                        )";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            //Vinculamos los parámetros
            $pdoSt->bindParam(":name", $customer->name, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":phone", $customer->phone, PDO::PARAM_INT, 9);
            $pdoSt->bindParam(":city", $customer->city, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":address", $customer->address, PDO::PARAM_INT, 20);
            $pdoSt->bindParam(":email", $customer->email, PDO::PARAM_STR, 45);


            // Execute
            $pdoSt->execute();

        } catch (PDOException $e) {

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }

    # ---------------------------------------------------------------------------------
    #
    #     _    _ _   _ _____ ____  _    _ ______    _____  _    _  ____  _   _ ______ 
    #    | |  | | \ | |_   _/ __ \| |  | |  ____|  |  __ \| |  | |/ __ \| \ | |  ____|
    #    | |  | |  \| | | || |  | | |  | | |__     | |__) | |__| | |  | |  \| | |__   
    #    | |  | | . ` | | || |  | | |  | |  __|    |  ___/|  __  | |  | | . ` |  __|  
    #    | |__| | |\  |_| || |__| | |__| | |____   | |    | |  | | |__| | |\  | |____ 
    #     \____/|_| \_|_____\___\_\\____/|______|  |_|    |_|  |_|\____/|_| \_|______|
    #
    # ---------------------------------------------------------------------------------
    public function validateUniquePhone($phone)
    {
        try {

            $sql = "
                SELECT * FROM customers
                WHERE phone = :phone
            ";

            # Conectar con la base de datos
            $conexion = $this->db->connect();

            $pdostmt = $conexion->prepare($sql);

            $pdostmt->bindParam(':phone', $phone, PDO::PARAM_INT, 9);
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
    #     _    _ _   _ _____ ____  _    _ ______    ______ __  __          _____ _      
    #    | |  | | \ | |_   _/ __ \| |  | |  ____|  |  ____|  \/  |   /\   |_   _| |     
    #    | |  | |  \| | | || |  | | |  | | |__     | |__  | \  / |  /  \    | | | |     
    #    | |  | | . ` | | || |  | | |  | |  __|    |  __| | |\/| | / /\ \   | | | |     
    #    | |__| | |\  |_| || |__| | |__| | |____   | |____| |  | |/ ____ \ _| |_| |____ 
    #     \____/|_| \_|_____\___\_\\____/|______|  |______|_|  |_/_/    \_\_____|______|
    #
    # ---------------------------------------------------------------------------------
    public function validateUniqueEmail($email)
    {
        try {

            $sql = "
                SELECT * FROM customers
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
    #   _____  ______ _      ______ _______ ______ 
    #  |  __ \|  ____| |    |  ____|__   __|  ____|
    #  | |  | | |__  | |    | |__     | |  | |__   
    #  | |  | |  __| | |    |  __|    | |  |  __|  
    #  | |__| | |____| |____| |____   | |  | |____ 
    #  |_____/|______|______|______|  |_|  |______|
    #                                              
    # ---------------------------------------------------------------------------------                                          
    # Método delete
    # Permite ejecutar comando DELETE en la tabla customers
    public function delete($id)
    {
        try {

            $sql = " DELETE FROM customers WHERE id = :id;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function deleteRelation($id_customer)
    {
        try {
            $sql = " DELETE FROM customer_project WHERE id_customer = :id_customer;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_customer", $id_customer, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #  _____  ______          _____  
    # |  __ \|  ____|   /\   |  __ \ 
    # | |__) | |__     /  \  | |  | |
    # |  _  /|  __|   / /\ \ | |  | |
    # | | \ \| |____ / ____ \| |__| |
    # |_|  \_\______/_/    \_\_____/ 
    #
    # ---------------------------------------------------------------------------------
    # function read
    # take the info of an project
    public function read($id)
    {
        try {
            $sql = " 
                SELECT
                    name, 
                    phone,
                    city,
                    address,
                    email
                FROM 
                    customers
                WHERE id =  :id;";

            # Connect with the database
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

}