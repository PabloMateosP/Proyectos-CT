<?php

class projectsModel extends Model
{

    # ---------------------------------------------------------------------------------  
    #
    #    _____ ______ _______ 
    #   / ____|  ____|__   __|
    #  | |  __| |__     | |   
    #  | | |_ |  __|    | |   
    #  | |__| | |____   | |   
    #   \_____|______|  |_|   
    #                         
    # ---------------------------------------------------------------------------------
    # Method get 
    # Select form table project for the view except employee
    public function get()
    {
        try {
            $sql = "
                SELECT 
                    pr.id,
                    pr.project,
                    pr.description,
                    concat_ws(', ', prM.last_name, prM.name) manager_name,
                    c.name customerName,
                    pr.finish_date
                FROM 
                    projects pr
                JOIN 
                    projectManager prM ON pr.id_projectManager = prM.id
                JOIN 
                    customer c ON pr.id_customer = c.id
                ORDER by pr.id asc;";

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
    #    ____   _____   _____   ______  _____  
    #   / __ \ |  __ \ |  __ \ |  ____||  __ \ 
    #  | |  | || |__) || |  | || |__   | |__) |
    #  | |  | ||  _  / | |  | ||  __|  |  _  / 
    #  | |__| || | \ \ | |__| || |____ | | \ \ 
    #   \____/ |_|  \_\|_____/ |______||_|  \_\
    #
    # ---------------------------------------------------------------------------------
    # Method order
    # Allows you to sort the workingHours table by any of the main columns
    # The sort order is set by the select column number
    public function order(int $criterio)
    {
        try {

            $sql = "
                SELECT 
                    pr.id,
                    pr.project,
                    pr.description,
                    concat_ws(', ', prM.last_name, prM.name) manager_name,
                    c.name customerName,
                    pr.finish_date
                FROM 
                    projects pr
                JOIN 
                    projectManager prM ON pr.id_projectManager = prM.id
                JOIN 
                    customer c ON pr.id_customer = c.id
                ORDER by :criterio;";

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

    # ---------------------------------------------------------------------------------
    #    
    #    _____ ______ _______    _____ _    _  _____ _______ ____  __  __ ______ _____   _____ 
    #   / ____|  ____|__   __|  / ____| |  | |/ ____|__   __/ __ \|  \/  |  ____|  __ \ / ____|
    #  | |  __| |__     | |    | |    | |  | | (___    | | | |  | | \  / | |__  | |__) | (___  
    #  | | |_ |  __|    | |    | |    | |  | |\___ \   | | | |  | | |\/| |  __| |  _  / \___ \ 
    #  | |__| | |____   | |    | |____| |__| |____) |  | | | |__| | |  | | |____| | \ \ ____) |
    #   \_____|______|  |_|     \_____|\____/|_____/   |_|  \____/|_|  |_|______|_|  \_\_____/ 
    #
    # ---------------------------------------------------------------------------------
    # get_customers
    # Select from get_customers
    public function get_customers()
    {
        try {
            $sql = "SELECT id, name, description FROM customer";
            $conexion = $this->db->connect();
            $result = $conexion->prepare($sql);
            $result->setFetchMode(PDO::FETCH_OBJ);
            $result->execute();

            return $result->fetchAll();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #    
    #   _____ ______ _______   _____  _____   ____       _ ______ _____ _______   __  __          _   _          _____ ______ _____  
    #   / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __| |  \/  |   /\   | \ | |   /\   / ____|  ____|  __ \ 
    #  | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | |    | \  / |  /  \  |  \| |  /  \ | |  __| |__  | |__) |
    #  | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |    | |\/| | / /\ \ | . ` | / /\ \| | |_ |  __| |  _  / 
    #  | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |    | |  | |/ ____ \| |\  |/ ____ \ |__| | |____| | \ \ 
    #   \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_|    |_|  |_/_/    \_\_| \_/_/    \_\_____|______|_|  \_\
    #                                                                                                                                
    # ---------------------------------------------------------------------------------
    # get_customers
    # Select from get_customers
    public function get_projectManagers()
    {
        try {
            $sql = "SELECT id, name, description FROM customer";
            $conexion = $this->db->connect();
            $result = $conexion->prepare($sql);
            $result->setFetchMode(PDO::FETCH_OBJ);
            $result->execute();

            return $result->fetchAll();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }                                                                                                                        
 

}