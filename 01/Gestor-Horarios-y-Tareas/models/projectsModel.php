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
                    concat_ws(', ', pm.last_name, pm.name) manager_name,
                    c.name customerName,
                    pr.finish_date
                FROM 
                    projects pr
                LEFT JOIN 
                    projectManager_project ppm ON pr.id = ppm.id_project
                LEFT JOIN
                    project_managers pm ON ppm.id_project_manager = pm.id
                LEFT JOIN 
                    customer_project cp ON pr.id = cp.id_project
                LEFT JOIN 
                    customers c ON cp.id_customer = c.id 
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
                    id,
                    project, 
                    description,
                    id_projectManager,
                    id_customer,
                    finish_date
                FROM 
                    projects
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
    # Update the project's data
    public function update(classProject $project_, $id)
    {
        try {
            $sql = " 
                    UPDATE projects
                    SET
                        project=:project,
                        description=:description,
                        id_projectManager=:id_projectManager,
                        id_customer=:id_customer,
                        finish_date=:finish_date,
                        update_at = now()
                    WHERE
                        id=:id
                    LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":project", $project_->project, PDO::PARAM_STR, 8);
            $pdoSt->bindParam(":description", $project_->description, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":id_projectManager", $project_->id_projectManager, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":id_customer", $project_->id_customer, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":finish_date", $project_->finish_date, PDO::PARAM_STR, 20);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_STR);

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
                LEFT JOIN 
                    projectManager prM ON pr.id_projectManager = prM.id
                LEFT JOIN 
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
            $sql = "SELECT id, name FROM customers";
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
            $sql = "SELECT id, name, last_name FROM project_managers";
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