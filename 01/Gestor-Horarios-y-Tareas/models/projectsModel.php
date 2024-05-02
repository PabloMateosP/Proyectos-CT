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
    #    _____  _____   ______         _______  ______ 
    #   / ____||  __ \ |  ____|    /\ |__   __||  ____|
    #  | |     | |__) || |__      /  \   | |   | |__   
    #  | |     |  _  / |  __|    / /\ \  | |   |  __|  
    #  | |____ | | \ \ | |____  / ____ \ | |   | |____ 
    #   \_____||_|  \_\|______|/_/    \_\|_|   |______|
    #
    # ---------------------------------------------------------------------------------
    # Method create
    # Allow to create a new project
    public function create(classProject $project)
    {
        try {
            $sql = " INSERT INTO 
                        projects 
                        (
                            project, 
                            description, 
                            id_projectManager, 
                            id_customer, 
                            finish_date
                        ) 
                        VALUES 
                        ( 
                            :project, 
                            :description, 
                            :id_projectManager, 
                            :id_customer, 
                            :finish_date
                        )";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            // Link the parameters
            //-----------------------------------------------------------------------------------
            $pdoSt->bindParam(":project", $project->project, PDO::PARAM_STR, 8);
            $pdoSt->bindParam(":description", $project->description, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":id_projectManager", $project->id_projectManager, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":id_customer", $project->id_customer, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":finish_date", $project->finish_date, PDO::PARAM_STR, 20);

            // execute
            $pdoSt->execute();

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
    # Permit execute command DELETE at the table projects
    public function delete($id)
    {
        try {

            $sql = "DELETE FROM projects WHERE id = :id;";

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
    # Allows you to sort the proj$project table by any of the main columns
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
            $sql = "SELECT id, name FROM customer";
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
            $sql = "SELECT id, name, last_name FROM projectManager";
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