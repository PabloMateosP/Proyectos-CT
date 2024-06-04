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
                    concat_ws(', ', pM.last_name, pM.name) pManager_name
                FROM 
                    project_managers pM
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

    # --------------------------------------------------------------------------------- 
    #     
    #    _____ ______ _______   _____  _____   ____       _ ______ _____ _______ _____   ______     __  __  __          _   _          _____ ______ _____  
    #   / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __/ ____| |  _ \ \   / / |  \/  |   /\   | \ | |   /\   / ____|  ____|  __ \ 
    #  | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | | | (___   | |_) \ \_/ /  | \  / |  /  \  |  \| |  /  \ | |  __| |__  | |__) |
    #  | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |  \___ \  |  _ < \   /   | |\/| | / /\ \ | . ` | / /\ \| | |_ |  __| |  _  / 
    #  | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |  ____) | | |_) | | |    | |  | |/ ____ \| |\  |/ ____ \ |__| | |____| | \ \ 
    #   \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_| |_____/  |____/  |_|    |_|  |_/_/    \_\_| \_/_/    \_\_____|______|_|  \_\
    #
    # --------------------------------------------------------------------------------- 
    # Get projects by manager
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
    #    _____ ______ _______   _____  _____   ____       _ ______ _____ _______   __  __          _   _          _____ ______ _____  
    #   / ____|  ____|__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __| |  \/  |   /\   | \ | |   /\   / ____|  ____|  __ \ 
    #  | |  __| |__     | |    | |__) | |__) | |  | |    | | |__ | |       | |    | \  / |  /  \  |  \| |  /  \ | |  __| |__  | |__) |
    #  | | |_ |  __|    | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |    | |\/| | / /\ \ | . ` | / /\ \| | |_ |  __| |  _  / 
    #  | |__| | |____   | |    | |    | | \ \| |__| | |__| | |___| |____   | |    | |  | |/ ____ \| |\  |/ ____ \ |__| | |____| | \ \ 
    #   \_____|______|  |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_|    |_|  |_/_/    \_\_| \_/_/    \_\_____|______|_|  \_\                                                                                                                              
    #                                                                                                                              
    # ---------------------------------------------------------------------------------  
    public function getProjectsManager($managerId)
    {
        try {
            $sql = "SELECT id_project FROM projectManager_project WHERE id_project_manager = :manager_id";

            $pdoSt = $this->db->connect()->prepare($sql);
            $pdoSt->bindParam(':manager_id', $managerId, PDO::PARAM_INT);
            $pdoSt->execute();
            $result = $pdoSt->fetchAll(PDO::FETCH_COLUMN);
            return $result;

        } catch (PDOException $e) {
            // Manejar la excepción de manera más específica si es necesario
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

    # --------------------------------------------------------------------------------- 
    #    
    #  _    _ _____  _____       _______ ______   _____  _____   ____       _ ______ _____ _______   _____ _____  
    #  | |  | |  __ \|  __ \   /\|__   __|  ____| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __| |_   _|  __ \ 
    #  | |  | | |__) | |  | | /  \  | |  | |__    | |__) | |__) | |  | |    | | |__ | |       | |      | | | |  | |
    #  | |  | |  ___/| |  | |/ /\ \ | |  |  __|   |  ___/|  _  /| |  | |_   | |  __|| |       | |      | | | |  | |
    #  | |__| | |    | |__| / ____ \| |  | |____  | |    | | \ \| |__| | |__| | |___| |____   | |     _| |_| |__| |
    #   \____/|_|    |_____/_/    \_\_|  |______| |_|    |_|  \_\\____/ \____/|______\_____|  |_|    |_____|_____/ 
    #
    # --------------------------------------------------------------------------------- 
    # Update project manager id from a project 
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

    # ---------------------------------------------------------------------------------     
    #   _____ _   _  _____ ______ _____ _______   _____  _____   ____       _ ______ _____ _______   __  __          _   _          _____ ______ _____    _____  ______ _            _______ _____ ____  _   _ 
    #  |_   _| \ | |/ ____|  ____|  __ \__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __| |  \/  |   /\   | \ | |   /\   / ____|  ____|  __ \  |  __ \|  ____| |        /\|__   __|_   _/ __ \| \ | |
    #    | | |  \| | (___ | |__  | |__) | | |    | |__) | |__) | |  | |    | | |__ | |       | |    | \  / |  /  \  |  \| |  /  \ | |  __| |__  | |__) | | |__) | |__  | |       /  \  | |    | || |  | |  \| |
    #    | | | . ` |\___ \|  __| |  _  /  | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |    | |\/| | / /\ \ | . ` | / /\ \| | |_ |  __| |  _  /  |  _  /|  __| | |      / /\ \ | |    | || |  | | . ` |
    #   _| |_| |\  |____) | |____| | \ \  | |    | |    | | \ \| |__| | |__| | |___| |____   | |    | |  | |/ ____ \| |\  |/ ____ \ |__| | |____| | \ \  | | \ \| |____| |____ / ____ \| |   _| || |__| | |\  |
    #  |_____|_| \_|_____/|______|_|  \_\ |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_|    |_|  |_/_/    \_\_| \_/_/    \_\_____|______|_|  \_\ |_|  \_\______|______/_/    \_\_|  |_____\____/|_| \_|
    #
    # --------------------------------------------------------------------------------- 
    # Insert the relation between project and a project manager
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
    #   _____ _   _  _____ ______ _____ _______   _____  _____   ____       _ ______ _____ _______   __  __          _   _          _____ ______ _____    _____ _____  _____  
    #  |_   _| \ | |/ ____|  ____|  __ \__   __| |  __ \|  __ \ / __ \     | |  ____/ ____|__   __| |  \/  |   /\   | \ | |   /\   / ____|  ____|  __ \  |_   _|  __ \|  __ \ 
    #    | | |  \| | (___ | |__  | |__) | | |    | |__) | |__) | |  | |    | | |__ | |       | |    | \  / |  /  \  |  \| |  /  \ | |  __| |__  | |__) |   | | | |  | | |__) |
    #    | | | . ` |\___ \|  __| |  _  /  | |    |  ___/|  _  /| |  | |_   | |  __|| |       | |    | |\/| | / /\ \ | . ` | / /\ \| | |_ |  __| |  _  /    | | | |  | |  ___/ 
    #   _| |_| |\  |____) | |____| | \ \  | |    | |    | | \ \| |__| | |__| | |___| |____   | |    | |  | |/ ____ \| |\  |/ ____ \ |__| | |____| | \ \   _| |_| |__| | |     
    #  |_____|_| \_|_____/|______|_|  \_\ |_|    |_|    |_|  \_\\____/ \____/|______\_____|  |_|    |_|  |_/_/    \_\_| \_/_/    \_\_____|______|_|  \_\ |_____|_____/|_|     
    #
    # --------------------------------------------------------------------------------- 
    # Insert the data in the field project of a project manager
    public function insertProjectManagerIdProject($projectId, $id)
    {
        try {
            $sql = "UPDATE projects
                    SET
                        id_projectManager=:id,
                        update_at = now()
                    WHERE
                        id=:projectId
                    LIMIT 1;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":projectId", $projectId, PDO::PARAM_INT);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_INT);
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
            $sql = "UPDATE project_managers
                    SET
                        last_name=:last_name,
                        name=:name,
                        update_at = now()
                    WHERE
                        id=:id
                    LIMIT 1";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindParam(":last_name", $projectManager->last_name, PDO::PARAM_STR, 8);
            $pdoSt->bindParam(":name", $projectManager->name, PDO::PARAM_STR, 50);
            $pdoSt->bindParam(":id", $id, PDO::PARAM_STR);

            $pdoSt->execute();

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # --------------------------------------------------------------------------------- 
    #    
    #   _    _ _____  _____       _______ ______   _____ _____    _____  _____   ____       _ ______ _____ _______ 
    #  | |  | |  __ \|  __ \   /\|__   __|  ____| |_   _|  __ \  |  __ \|  __ \ / __ \     | |  ____/ ____|__   __|
    #  | |  | | |__) | |  | | /  \  | |  | |__      | | | |  | | | |__) | |__) | |  | |    | | |__ | |       | |   
    #  | |  | |  ___/| |  | |/ /\ \ | |  |  __|     | | | |  | | |  ___/|  _  /| |  | |_   | |  __|| |       | |   
    #  | |__| | |    | |__| / ____ \| |  | |____   _| |_| |__| | | |    | | \ \| |__| | |__| | |___| |____   | |   
    #   \____/|_|    |_____/_/    \_\_|  |______| |_____|_____/  |_|    |_|  \_\\____/ \____/|______\_____|  |_|   
    #
    # --------------------------------------------------------------------------------- 
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

    # ---------------------------------------------------------------------------------   
    #
    #   _____  ______ _      ______ _______ ______   _____  ______ _            _______ _____ ____  _   _   _____  __  __ 
    #  |  __ \|  ____| |    |  ____|__   __|  ____| |  __ \|  ____| |        /\|__   __|_   _/ __ \| \ | | |  __ \|  \/  |
    #  | |  | | |__  | |    | |__     | |  | |__    | |__) | |__  | |       /  \  | |    | || |  | |  \| | | |__) | \  / |
    #  | |  | |  __| | |    |  __|    | |  |  __|   |  _  /|  __| | |      / /\ \ | |    | || |  | | . ` | |  ___/| |\/| |
    #  | |__| | |____| |____| |____   | |  | |____  | | \ \| |____| |____ / ____ \| |   _| || |__| | |\  | | |    | |  | |
    #  |_____/|______|______|______|  |_|  |______| |_|  \_\______|______/_/    \_\_|  |_____\____/|_| \_| |_|    |_|  |_|
    #
    # --------------------------------------------------------------------------------- 
    # Delete the relation between project manager and project where project manager = x
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

    # ---------------------------------------------------------------------------------  
    #  
    #   _____  ______ _      ______ _______ ______   _____  ______ _            _______ _____ ____  _   _   _____  __  __ _____  
    #  |  __ \|  ____| |    |  ____|__   __|  ____| |  __ \|  ____| |        /\|__   __|_   _/ __ \| \ | | |  __ \|  \/  |  __ \ 
    #  | |  | | |__  | |    | |__     | |  | |__    | |__) | |__  | |       /  \  | |    | || |  | |  \| | | |__) | \  / | |__) |
    #  | |  | |  __| | |    |  __|    | |  |  __|   |  _  /|  __| | |      / /\ \ | |    | || |  | | . ` | |  ___/| |\/| |  ___/ 
    #  | |__| | |____| |____| |____   | |  | |____  | | \ \| |____| |____ / ____ \| |   _| || |__| | |\  | | |    | |  | | |     
    #  |_____/|______|______|______|  |_|  |______| |_|  \_\______|______/_/    \_\_|  |_____\____/|_| \_| |_|    |_|  |_|_|     
    #
    # --------------------------------------------------------------------------------- 
    # We delete the relation between project manager and project where project = x and project manager = x
    public function deleteRelationPmP($id_projectManager, $id_project)
    {
        try {
            $sql = "DELETE FROM projectManager_project WHERE id_project = :id_project AND id_project_manager = :id_projectManager;";
            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT);
            $pdoSt->bindParam(":id_projectManager", $id_projectManager, PDO::PARAM_INT);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once ("template/partials/errorDB.php");
            exit();
        }
    }

    # ---------------------------------------------------------------------------------
    #     
    #   _____  ______ _      ______ _______ ______ _____ _____  _____  _____   ____       _ ______ _____ _______   _____  __  __ 
    #  |  __ \|  ____| |    |  ____|__   __|  ____|_   _|  __ \|  __ \|  __ \ / __ \     | |  ____/ ____|__   __| |  __ \|  \/  |
    #  | |  | | |__  | |    | |__     | |  | |__    | | | |  | | |__) | |__) | |  | |    | | |__ | |       | |    | |__) | \  / |
    #  | |  | |  __| | |    |  __|    | |  |  __|   | | | |  | |  ___/|  _  /| |  | |_   | |  __|| |       | |    |  ___/| |\/| |
    #  | |__| | |____| |____| |____   | |  | |____ _| |_| |__| | |    | | \ \| |__| | |__| | |___| |____   | |    | |    | |  | |
    #  |_____/|______|______|______|  |_|  |______|_____|_____/|_|    |_|  \_\\____/ \____/|______\_____|  |_|    |_|    |_|  |_|
    # 
    # ---------------------------------------------------------------------------------
    # We delete the id project in the project manager when we delete a project
    public function deleteIdProjectMProjec($id_project)
    {
        try {

            $sql = "UPDATE projects
                    SET
                        id_projectManager = null
                    WHERE
                        id=:id_project
                    LIMIT 1;";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(":id_project", $id_project, PDO::PARAM_INT);
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
    # take the info of an project Manager
    public function read($id)
    {
        try {
            $sql = " 
                SELECT
                    id,
                    last_name, 
                    name,
                    created_at,
                    update_at
                FROM 
                    project_managers
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