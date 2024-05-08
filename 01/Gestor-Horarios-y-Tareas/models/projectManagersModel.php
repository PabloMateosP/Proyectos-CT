<?php

class projectManagersModel extends Model
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
    # Select form table projectManager 
    public function get()
    {
        try {
            $sql = "
                SELECT 
                    pM.id,
                    concat_ws(', ', pM.last_name, pM.name) pManager_name,
                    p.project
                FROM 
                    project_managers pM
                LEFT JOIN projectManager_project pp ON pM.id = pp.id_project_manager
                LEFT JOIN projects p ON pp.id_project = p.id
                ORDER BY pM.id ASC;";

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


    public function getProjectsByManager($managerId)
    {
        try {

            $sql = "SELECT p.id_projectManager, p.project 
                FROM projects p 
                LEFT JOIN project_managers pm ON p.id_projectManager = pm.id
                WHERE pm.id = :manager_id OR pm.id IS NULL";

            $conexion = $this->db->connect();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':manager_id', $managerId);
            $stmt->execute();

            return $stmt->fetchAll();

        } catch (PDOException $e) {

            require_once ("template/partials/errorDB.php");
            exit();

        }
    }

    # ---------------------------------------------------------------------------------  
    #  
    #    _____ ______ _______   _____  _____   ____       _ ______ _____ _______ _____ 
    #   / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __/ ____|
    #  | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | | | (___  
    #  | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |  \___ \ 
    #  | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |  ____) |
    #   \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_| |_____/ 
    #
    # ---------------------------------------------------------------------------------
    # function get_projects 
    # function to get the information about all the projects
    public function get_projects()
    {
        try {
            $sql = "SELECT 
                        pr.id,
                        pr.project,
                        pr.description AS 'desc',
                        pr.id_projectManager
                    FROM 
                        projects pr
                    order by pr.id";

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
    #    _____ _____  ______       _______ ______ 
    #   / ____|  __ \|  ____|   /\|__   __|  ____|
    #  | |    | |__) | |__     /  \  | |  | |__   
    #  | |    |  _  /|  __|   / /\ \ | |  |  __|  
    #  | |____| | \ \| |____ / ____ \| |  | |____ 
    #   \_____|_|  \_\______/_/    \_\_|  |______|
    #
    # ---------------------------------------------------------------------------------
    # Method create
    # Allow to create a new working hour
    public function create(classProjectManagers $projectManager)
    {
        try {
            $sql = " INSERT INTO 
                        project_managers 
                        (
                            last_name, 
                            name
                        ) 
                        VALUES 
                        ( 
                            :last_name, 
                            :name
                        )";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            // Link the parameters
            //-----------------------------------------------------------------------------------
            $pdoSt->bindParam(":last_name", $projectManager->last_name, PDO::PARAM_STR, 45);
            $pdoSt->bindParam(":name", $projectManager->name, PDO::PARAM_STR, 20);

            // execute
            $pdoSt->execute();

            // Retrieve the ID of the last inserted row
            $lastInsertedId = $conexion->lastInsertId();

            return $lastInsertedId; // Return the ID of the last inserted row

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    public function updateProjectId($id_project, $id)
    {
        try {
            $sql = "UPDATE projects
                        SET
                            id_projectManager=:id_project,
                            update_at = now()
                        WHERE
                            id=:id
                        LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT, 10);

            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }

    }

    public function insertProjectManagerRelationship($projectId, $projectManagerId)
    {
        try {

            $sql = "INSERT INTO projectManager_project (id_project, id_project_manager) VALUES (:projectId, :projectManagerId)";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":projectId", $projectId, PDO::PARAM_INT);
            $pdoSt->bindParam(":projectManagerId", $projectManagerId, PDO::PARAM_INT);
            $pdoSt->execute();

        } catch (PDOException $e) {
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
    # Update the projectManager's data
    public function update(classProjectManagers $projectManager, $id)
    {
        try {
            $sql = " 
                    UPDATE projectManagers
                    SET
                        last_name=:last_name,
                        name=:name,
                        id_project=:id_project,
                        update_at = now()
                    WHERE
                        id=:id
                    LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":last_name", $projectManager->last_name, PDO::PARAM_STR, 8);
            $pdoSt->bindParam(":name", $projectManager->name, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":id_project", $projectManager->id_project, PDO::PARAM_INT, 10);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_STR);

            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }


    public function updateIdProject($id)
    {
        try {

            $sql = "UPDATE projects
                    SET
                        id_projectManager=null,
                        update_at=now()
                    WHERE
                        id=:id
                    LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

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
    # Permit execute command DELETE at the table project Manager
    public function delete($id)
    {
        try {

            $sql = "DELETE FROM project_managers WHERE id = :id;";

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

    public function deleteRelationPM($id_project_manager)
    {
        try {

            $sql = " DELETE FROM projectManager_project WHERE id_project_manager = :id_project_manager;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_project_manager", $id_project_manager, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

}